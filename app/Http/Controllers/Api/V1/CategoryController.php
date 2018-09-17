<?php
/**
 * 分类类
 */

namespace App\Http\Controllers\Api\V1;

use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * 分类列表
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        return $this->response->collection(Category::all(), new CategoryTransformer());
    }
}
