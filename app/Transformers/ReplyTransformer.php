<?php
/**
 * 回复数据处理
 */

namespace App\Transformers;

use App\Models\Reply;
use League\Fractal\TransformerAbstract;

class ReplyTransformer extends TransformerAbstract
{

    /**
     * 可用包括
     *
     * @var array
     */
    protected $availableIncludes = ['user', 'topic'];

    /**
     * 回复数据
     *
     * @param Reply $reply
     * @return array
     */
    public function transform(Reply $reply)
    {

        return [
            'id' => $reply->id,
            'user_id' => (int) $reply->user_id,
            'topic_id' => (int) $reply->topic_id,
            'content' => $reply->content,
            'created_at' => $reply->created_at->toDateTimeString(),
            'updated_at' => $reply->updated_at->toDateTimeString(),
        ];
    }

    /**
     * 导入用户数据
     *
     * @param Reply $reply
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Reply $reply)
    {
        return $this->item($reply->user, new UserTransformer());
    }

    /**
     * 导入话题数据
     *
     * @param Reply $reply
     * @return \League\Fractal\Resource\Item
     */
    public function includeTopic(Reply $reply)
    {
        return $this->item($reply->topic, new TopicTransformer());
    }
}