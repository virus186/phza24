<?php

namespace Modules\Product\Http\Controllers\API;

use App\Repositories\FilterRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Repositories\AttributeRepository;
use Modules\Product\Services\BrandService;
use Modules\Product\Transformers\BrandResource;

/**
* @group Brands
*
* APIs for Brands
*/
class BrandController extends Controller
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }
    
    /**
     * Brand List
     * @response{
     *      "data": [
     *           {
     *               "id": 2,
     *               "name": "KTM",
     *               "logo": "uploads/images/29-05-2021/60b1e7e14caf0.png",
     *               "description": null,
     *               "link": null,
     *               "status": 1,
     *               "featured": 1,
     *               "meta_title": null,
     *               "meta_description": null,
     *               "sort_id": null,
     *               "total_sale": 1,
     *               "avg_rating": 0,
     *               "slug": "ktm",
     *               "created_by": null,
     *               "updated_by": 5,
     *               "created_at": "2021-05-29T07:06:09.000000Z",
     *               "updated_at": "2021-05-29T12:11:27.000000Z",
     *               "AllProducts": {
     *                   "current_page": 1,
     *                   "data": [
     *                       product list ....
     *                   ],
     *                   "first_page_url": "http://ecommerce.test/api/product/brand?page=1",
     *                   "from": 1,
     *                   "last_page": 1,
     *                   "last_page_url": "http://ecommerce.test/api/product/brand?page=1",
     *                   "links": [
     *                       {
     *                           "url": null,
     *                           "label": "&laquo; Previous",
     *                           "active": false
     *                       },
     *                       {
     *                           "url": "http://ecommerce.test/api/product/brand?page=1",
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
     *                   "path": "http://ecommerce.test/api/product/brand",
     *                   "per_page": 10,
     *                   "prev_page_url": null,
     *                   "to": 1,
     *                   "total": 1
     *               }
     *           }
     *       ]
     * }
     */
    public function index()
    {
        $brands = $this->brandService->getActiveAll();
        
        if(count($brands) > 0){
            return BrandResource::collection($brands,200);
        }else{
            return response()->json([
                'message' => 'brnad not found'
            ],404);
        }
    }

    /**
     *  Single Brand
     * @urlParam id integer required brand id
     * @response{
     *      "data": {
     *           "id": 2,
     *           "name": "KTM",
     *           "logo": "uploads/images/29-05-2021/60b1e7e14caf0.png",
     *           "description": null,
     *           "link": null,
     *           "status": 1,
     *           "featured": 1,
     *           "meta_title": null,
     *           "meta_description": null,
     *           "sort_id": null,
     *           "total_sale": 1,
     *           "avg_rating": 0,
     *           "slug": "ktm",
     *           "created_by": null,
     *           "updated_by": 5,
     *           "created_at": "2021-05-29T07:06:09.000000Z",
     *           "updated_at": "2021-05-29T12:11:27.000000Z",
     *           "AllProducts": {
     *               "current_page": 1,
     *               "data": [
     *                   product list ....
     *               ],
     *               "first_page_url": "http://ecommerce.test/api/product/brand/2?page=1",
     *               "from": 1,
     *               "last_page": 1,
     *               "last_page_url": "http://ecommerce.test/api/product/brand/2?page=1",
     *               "links": [
     *                   {
     *                       "url": null,
     *                       "label": "&laquo; Previous",
     *                       "active": false
     *                   },
     *                   {
     *                       "url": "http://ecommerce.test/api/product/brand/2?page=1",
     *                       "label": "1",
     *                       "active": true
     *                   },
     *                   {
     *                       "url": null,
     *                       "label": "Next &raquo;",
     *                       "active": false
     *                   }
     *               ],
     *               "next_page_url": null,
     *               "path": "http://ecommerce.test/api/product/brand/2",
     *               "per_page": 10,
     *               "prev_page_url": null,
     *               "to": 1,
     *               "total": 1
     *           }
     *       }
     *   }
     * }
     */
    
    public function show($id)
    {
        $brand = $this->brandService->findById($id);
        $attributeRepo = new AttributeRepository;
        $attributes = $attributeRepo->getAttributeForSpecificBrand($id);
        $color = $attributeRepo->getColorAttributeForSpecificBrand($id);
        $filterRepo = new FilterRepository();
        $categories = $filterRepo->filterCategoryBrandWise($id);
        $products = $brand->sellerProductsAll()->pluck('id')->toArray();
        $lowest_price = $filterRepo->filterProductMinPrice($products);
        $height_price = $filterRepo->filterProductMaxPrice($products);
        if($brand){
            $brand = new BrandResource($brand);
            return response()->json([
                'data' => $brand,
                'attributes' => $attributes,
                'color' => $color,
                'categories' => $categories,
                'lowest_price' => $lowest_price,
                'height_price' => $height_price
            ]);
        }else{
            return response()->json([
                'message' => 'brnad not found'
            ],404);
        }
    }


    
}
