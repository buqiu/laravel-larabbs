<?php
/**
 * 资源推荐类
 */

namespace App\Http\Controllers\Api\V1;

use App\Models\Link;
use App\Transformers\LinkTransformer;
use Illuminate\Http\Request;

class LinkController extends Controller
{

    /**
     * 资源推荐列表
     *
     * @param Link $link
     * @return \Dingo\Api\Http\Response
     */
    public function index(Link $link)
    {
        $links = $link->getAllCached();

        return $this->response->collection($links, new  LinkTransformer());
    }
}
