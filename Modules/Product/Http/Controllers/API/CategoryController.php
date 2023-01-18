<?php

namespace Modules\Product\Http\Controllers\API;

use App\Repositories\FilterRepository;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Repositories\AttributeRepository;
use \Modules\Product\Services\CategoryService;
use Modules\Product\Transformers\CategoryResource;
use Modules\Seller\Entities\SellerProduct;

/**
* @group Categories
*
* APIs for Categories
*/
class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Category List
     * @response{
     *      "data": [
     *           {
     *               "id": 6,
     *               "name": "Mobile",
     *               "slug": "mobile",
     *               "parent_id": 3,
     *               "depth_level": 2,
     *               "icon": "fas fa-mobile-alt",
     *               "searchable": 1,
     *               "status": 1,
     *               "total_sale": 3,
     *               "avg_rating": 0,
     *               "commission_rate": 0,
     *               "created_at": "2021-05-29T07:27:11.000000Z",
     *               "updated_at": "2021-06-07T13:18:43.000000Z",
     *               "AllProducts": {
     *                   "current_page": 1,
     *                   "data": [
     *                       product list ...
     *                   ],
     *                   "first_page_url": "http://ecommerce.test/api/product/category?page=1",
     *                   "from": 1,
     *                   "last_page": 1,
     *                   "last_page_url": "http://ecommerce.test/api/product/category?page=1",
     *                   "links": [
     *                       {
     *                           "url": null,
     *                           "label": "&laquo; Previous",
     *                           "active": false
     *                       },
     *                       {
     *                           "url": "http://ecommerce.test/api/product/category?page=1",
     *                           "label": "1",
     *                           "active": true
     *                       },
     *                       {
     *                           "url": null,
     *                           "label": "Next &raquo;",
     *                           "active": false
     *                       }
     *                   ],
     *                   "next_page_url": null,
     *                   "path": "http://ecommerce.test/api/product/category",
     *                   "per_page": 10,
     *                   "prev_page_url": null,
     *                   "to": 1,
     *                   "total": 1
     *               },
     *               "category_image": {
     *                   "category_id": 6
     *               },
     *               "parent_category": {
     *                   parent category ....
     *               },
     *               "sub_categories": [
     *                   sub category list ...
     *               ]
     *           }
     *       ]
     * }
     */

    public function index(){

        $categories = $this->categoryService->getActiveAll();
        if(count($categories) > 0){
            return CategoryResource::collection($categories);
        }else{
            return response()->json([
                'message' => 'category not found'
            ],404);
        }
        
    }

    /**
     * Single Category
     * @urlParam id integer required category id
     * @response{
     *      "data": {
    *            "id": 6,
    *            "name": "Mobile",
    *            "slug": "mobile",
    *            "parent_id": 3,
    *            "depth_level": 2,
    *            "icon": "fas fa-mobile-alt",
    *            "searchable": 1,
    *            "status": 1,
    *            "total_sale": 3,
    *            "avg_rating": 0,
    *            "commission_rate": 0,
    *            "created_at": "2021-05-29T07:27:11.000000Z",
    *            "updated_at": "2021-06-07T13:18:43.000000Z",
    *            "AllProducts": {
    *                "current_page": 1,
    *                "data": [
    *                    product lists
    *                ],
    *                "first_page_url": "http://ecommerce.test/api/product/category/6?page=1",
    *                "from": 1,
    *                "last_page": 1,
    *                "last_page_url": "http://ecommerce.test/api/product/category/6?page=1",
    *                "links": [
    *                    {
    *                        "url": null,
    *                        "label": "&laquo; Previous",
    *                        "active": false
    *                    },
    *                    {
    *                        "url": "http://ecommerce.test/api/product/category/6?page=1",
    *                        "label": "1",
    *                        "active": true
    *                    },
    *                    {
    *                        "url": null,
    *                        "label": "Next &raquo;",
    *                        "active": false
    *                    }
    *                ],
    *                "next_page_url": null,
    *                "path": "http://ecommerce.test/api/product/category/6",
    *                "per_page": 10,
    *                "prev_page_url": null,
    *                "to": 1,
    *                "total": 1
    *            },
    *            "category_image": {
    *                "category_id": 6
    *            },
    *            "parent_category": {
    *                parent category ..
    *            },
    *            "sub_categories": [
    *                sub categories ...
    *            ]
    *        }
     *       ,
     *       "attributes": [
     *       {
     *           "id": 2,
     *           "name": "Storage",
     *           "display_type": null,
     *           "description": null,
     *           "status": 1,
     *           "created_by": null,
     *           "updated_by": null,
     *           "created_at": "2021-07-01T10:05:16.000000Z",
     *           "updated_at": "2021-07-01T10:05:16.000000Z",
     *          "values": [
     *           singl
     *          ]
     *       }
     *   ],
     *   "color": {
     *       "id": 1,
     *       "name": "Color",
     *       "display_type": "radio_button",
     *       "description": "null",
     *       "status": 1,
     *       "created_by": null,
     *       "updated_by": null,
     *       "created_at": "2018-11-04T20:12:26.000000Z",
     *       "updated_at": "2018-11-04T20:12:26.000000Z",
     *       "values": [
     *           {
     *               "id": 1,
     *               "value": "black",
     *               "attribute_id": 1,
     *               "created_at": "2021-07-01T09:51:50.000000Z",
     *               "updated_at": "2021-07-01T09:51:50.000000Z"
     *           },
     *           
     *       ]
     *   },
     *   "brands": [
     *       {
     *           "id": 1,
     *           "name": "Xiaomi",
     *           "logo": "uploads/images/01-07-2021/60dd926b30fde.png",
     *           "description": null,
     *           "link": null,
     *           "status": 1,
     *           "featured": 1,
     *           "meta_title": null,
     *           "meta_description": null,
     *           "sort_id": null,
     *           "total_sale": 0,
     *           "avg_rating": 0,
     *           "slug": "xiaomi",
     *           "created_by": null,
     *           "updated_by": null,
     *           "created_at": "2021-07-01T10:01:15.000000Z",
     *           "updated_at": "2021-07-01T10:01:15.000000Z",
     *       }
     *   ],
     *   "lowest_price": 190,
     *   "height_price": 22
     * }
     */

    public function show($id){

        $category = $this->categoryService->showById($id);
        $category_ids = $this->categoryService->getAllSubSubCategoryID($id);

        $attributeRepo = new AttributeRepository;
        $attributes = $attributeRepo->getAttributeForSpecificCategory($id, $category_ids);
        $color = $attributeRepo->getColorAttributeForSpecificCategory($id, $category_ids);
        $filterRepo = new FilterRepository();
        $brands = $filterRepo->filterBrandCategoryWise($id, $category_ids);
        $category_ids = array_merge($category_ids,[intval($id)]);
        $catProducts = SellerProduct::where('status', 1)->whereHas('product', function($query) use ($id, $category_ids){
            return $query->Wherehas('categories',function($q)use($category_ids){
                return $q->whereIn('category_id', $category_ids);
            });
        })->pluck('id')->toArray();
        $lowest_price = $filterRepo->filterProductMinPrice($catProducts);
        $height_price = $filterRepo->filterProductMaxPrice($catProducts);
        if($category){
            $category = new CategoryResource($category);
            return response()->json([
                'data' => $category,
                'attributes' => $attributes,
                'color' => $color,
                'brands' => $brands,
                'lowest_price' => $lowest_price,
                'height_price' => $height_price

            ],200);
        }else{
            return response()->json([
                'message' => 'category not found'
            ],404);
        }
    }

    /**
     * Top Categories
     * @response{
     *       "data": [
     *           {
     *               "id": 6,
     *               "name": "Mobile",
     *               "slug": "mobile",
     *               "parent_id": 3,
     *               "depth_level": 2,
     *               "icon": "fas fa-mobile-alt",
     *               "searchable": 1,
     *               "status": 1,
     *               "total_sale": 3,
     *               "avg_rating": 0,
     *               "commission_rate": 0,
     *               "created_at": "2021-05-29T07:27:11.000000Z",
     *               "updated_at": "2021-06-07T13:18:43.000000Z",
     *               "AllProducts": {
     *                   "current_page": 1,
     *                   "data": [
     *                       product list
     *                   ],
     *                   "first_page_url": "http://ecommerce.test/api/product/category/filter/top?page=1",
     *                   "from": 1,
     *                   "last_page": 1,
     *                   "last_page_url": "http://ecommerce.test/api/product/category/filter/top?page=1",
     *                   "links": [
     *                       {
     *                           "url": null,
     *                           "label": "&laquo; Previous",
     *                           "active": false
     *                       },
     *                       {
     *                           "url": "http://ecommerce.test/api/product/category/filter/top?page=1",
     *                           "label": "1",
     *                           "active": true
     *                       },
     *                       {
     *                           "url": null,
     *                           "label": "Next &raquo;",
     *                           "active": false
     *                       }
     *                   ],
     *                   "next_page_url": null,
     *                   "path": "http://ecommerce.test/api/product/category/filter/top",
     *                   "per_page": 10,
     *                   "prev_page_url": null,
     *                   "to": 1,
     *                   "total": 1
     *               },
     *               "category_image": {
     *                   "category_id": 6
     *               },
     *               "parent_category": {
     *                   parent category ...
     *               },
     *               "sub_categories": [
     *                   subcategories ...
     *               ]
     *           }
     *       ]
     * }
     */

    public function topCategory(){
        $categories =  $this->categoryService->getCategoryByTop();
        
        if(count($categories) > 0){
            return CategoryResource::collection($categories,200);
        }else{
            return response()->json([
                'message' => 'category not found'
            ],404);
        }
    }

}
