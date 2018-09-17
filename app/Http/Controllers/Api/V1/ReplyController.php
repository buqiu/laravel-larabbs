<?php
/**
 * 回复类
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ReplyRequest;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    /**
     * 某个用户的回复列表
     *
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    public function userIndex(User $user)
    {
        $replies = $user->replies()->paginate(20);

        return $this->response->paginator($replies, new ReplyTransformer());
    }

    /**
     * 回复列表
     *
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function index(Topic $topic)
    {
        $replies = $topic->replies()->paginate(20);

        return $this->response->paginator($replies, new ReplyTransformer());
    }

    /**
     * 发布回复
     *
     * @param ReplyRequest $request
     * @param Topic $topic
     * @param Reply $reply
     * @return \Dingo\Api\Http\Response
     */
    public function store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
        $reply->fill($request->all());
        $reply->topic_id = $topic->id;
        $reply->user_id = $this->user()->id;
        $reply->save();

        return $this->response->item($reply, new ReplyTransformer())
            ->setStatusCode(201);
    }

    /**
     * 删除回复
     *
     * @param Topic $topic
     * @param Reply $reply
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Topic $topic, Reply $reply)
    {
        if ($reply->topic_id != $topic->id){
            return $this->response->errorBadRequest();
        }
        $this->authorize('destroy', $reply);
        $reply->delete();

        return $this->response->noContent();
    }
}
