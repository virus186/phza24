<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Modules\Product\Entities\Category;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::with('categoryImage', 'parentCategory', 'subCategories')->where('status', 1)->where('parent_id',0)->paginate(10);
        
        return response()->json([
            'data' => $categories,
            'msg' => 'success'
        ],200);
    }
}
