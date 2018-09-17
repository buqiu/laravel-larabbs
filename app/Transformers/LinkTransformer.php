<?php
/**
 * 资源推荐数据处理
 */

namespace App\Transformers;

use App\Models\Link;
use League\Fractal\TransformerAbstract;

class LinkTransformer extends TransformerAbstract
{

    /**
     * 资源推荐数据
     *
     * @param Link $link
     * @return array
     */
    public function transform(Link $link)
    {
        return [
            'id' => $link->id,
            'title' => $link->title,
            'link' => $link->link,
        ];
    }
}