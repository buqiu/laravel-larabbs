<?php
/**
 * 话题类
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\TopicRequest;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * 某个用户话题列表
     *
     * @param User $user
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function userIndex(User $user, Topic $topic)
    {
        $topics = $user->topics()->recent()->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    /**
     * 话题列表
     *
     * @param Request $request
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, Topic $topic)
    {
        $query = $topic->query();

        if ($category_id = $request->category_id){
            $query->where('category_id', $category_id);
        }

        // 为了说明 N+1 问题，不使用 scopeWithOrder
        switch ($request->order){
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }
        $topics = $query->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    /**
     * 发布话题
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->response->item($topic, new TopicTransformer())
            ->setStatusCode(201);
    }

    /**
     * 话题详情
     *
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function show(Topic $topic)
    {
        return $this->response->item($topic, new TopicTransformer());
    }

    /**
     * 修改话题
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return $this->response->item($topic, new TopicTransformer());
    }

    /**
     * 删除话题
     *
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return $this->response->noContent();
    }
}
