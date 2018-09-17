<?php
/**
 * 通知类
 */

namespace App\Http\Controllers\Api\V1;

use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * 消息通知列表
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $notifications = $this->user->notifications()->paginate(20);

        return $this->response->paginator($notifications, new NotificationTransformer());
    }

    /**
     * 消息通知统计
     *
     * @return mixed
     */
    public function stats()
    {
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
        ]);
    }

    /**
     * 标记消息通知为已读
     *
     * @return \Dingo\Api\Http\Response
     */
    public function read()
    {
        $this->user()->markAsRead();

        return $this->response->noContent();
    }
}
