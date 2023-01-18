<?php
namespace Modules\Seller\Repositories;

use App\Models\Cart;
use App\Models\UsedMedia;
use App\Models\User;
use App\Traits\GenerateSlug;
use App\Traits\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\MultiVendor\Entities\SellerAccount;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductSku;
use Modules\Product\Entities\ProductVariations;
use Modules\Seller\Entities\SellerProduct;
use Modules\Seller\Entities\SellerProductSKU;

use Modules\FrontendCMS\Entities\HomePageSection;
use Modules\GeneralSetting\Entities\EmailTemplateType;
use Modules\MultiVendor\Entities\SellerBankAccount;
use Modules\MultiVendor\Entities\SellerBusinessInformation;
use App\Traits\ImageStore;
use Modules\WholeSale\Entities\WholesalePrice;

class ProductRepository {

    use Notification;
    use ImageStore;
    use GenerateSlug;

    protected $seller;
    protected $productSku;

    public function __construct(User $seller, SellerProduct $product){
        $this->seller = $seller;
        $this->product = $product;
    }

    public function getAll(){
        $seller_id = getParentSellerId();
        if($seller_id){
            return $this->product::with(['product' => function($q1){
                $q1->select('id','product_name','thumbnail_image_source','brand_id','subtitle_1','subtitle_2');
            },'product.brand' => function($q2){
                $q2->select('id','name');
            },'skus'])->where('user_id',$seller_id);
        }else{
            return abort(404);
        }
    }

    public function getRecomandedProduct(){
        $section = HomePageSection::where('section_name', 'more_products')->first();
        return $section->getApiProductByQuery();
    }

    public function getTopPicks(){
        $section = HomePageSection::where('section_name', 'top_picks')->first();
        return $section->getApiProductByQuery();
    }

    public function getAllSellerProduct(){

        return SellerProduct::with('product', 'seller', 'reviews.customer', 'reviews.images', 'product.brand','product.categories','product.unit_type',
        'product.variations','product.skus', 'product.tags','product.gallary_images','product.relatedProducts.related_seller_products',
        'product.upSales.up_seller_products', 'product.crossSales.cross_seller_products','product.shippingMethods.shippingMethod')->where('status', 1)->paginate(10);
    }

    public function getSellerProductById($id){
        return SellerProduct::with('product','seller', 'reviews.customer', 'reviews.images', 'product.brand','product.categories','product.unit_type',
        'product.variations','product.skus', 'product.tags','product.gallary_images','product.relatedProducts.related_seller_products.product',
        'product.upSales.up_seller_products.product', 'product.crossSales.cross_seller_products.product','product.shippingMethods.shippingMethod')->where('id', $id)->first();
    }

    public function getMyProducts(){
        if(auth()->check() && auth()->user()->role->type == 'seller'){
            $seller_id = getParentSellerId();
            return Product::with('product','skus')->where('created_by',$seller_id)->latest()->get();
        }else{
            return abort(404);
        }
    }
    public function getFilterdProduct($data){
        $seller_id = getParentSellerId();
        if($data['table'] == 'alert'){
            return $this->product::where('stock_manage',1)->where('user_id',$seller_id)->whereHas('skus', function($query){
                return $query->select(DB::raw('SUM(product_stock) as sum_colum'))->having('sum_colum', '<=', 10);
            })->with(['product' => function($q1){
                $q1->select('id','product_name','brand_id','thumbnail_image_source');
            },'product.brand' => function($q2){
                $q2->select('id','name');
            }]);
        }
        if($data['table'] == 'stockout'){
            return $this->product::where('stock_manage',1)->where('user_id',$seller_id)->whereHas('skus', function($query){
                return $query->select(DB::raw('SUM(product_stock) as sum_colum'))->having('sum_colum', '<', 1);
            })->with(['product' => function($q1){
                $q1->select('id','product_name','brand_id','thumbnail_image_source');
            },'product.brand' => function($q2){
                $q2->select('id','name');
            }]);
        }
        if($data['table'] == 'disable'){
            return $this->product::where('status',0)->where('user_id',$seller_id)->with(['product' => function($q1){
                $q1->select('id','product_name','brand_id','thumbnail_image_source');
            },'product.brand' => function($q2){
                $q2->select('id','name');
            }]);
        }

    }

    public function getAllProduct(){
        return Product::where('is_approved',1)->where('status', 1)->get();
    }

    public function getAllMyProduct(){
        $seller_id = getParentSellerId();
        return SellerProduct::where('user_id', $seller_id)->get();
    }

    public function getProductOfOtherSeller(){
        $seller_id = getParentSellerId();
        $sellerProductIds = SellerProduct::where('user_id',$seller_id)->pluck('product_id');
        return Product::whereNotIn('id',$sellerProductIds)->where('is_approved',1)->where('status', 1)->get();
    }

    public function getProduct($id){

        $seller_id = getParentSellerId();
        $is_exsists = SellerProduct::where('user_id', $seller_id)->where('product_id', $id)->first();
        if($is_exsists){
            return 'product_exsist';
        }else{
            return Product::with('skus', 'activeSkus')->where('id',$id)->firstOrFail();
        }
    }
    public function statusChange($data, $id){
        return $this->product->findOrFail($id)->update([
            'status' => $data['status']
        ]);
    }
    public function store($data){
        $product = Product::where('id',$data['product_id'])->firstOrFail();
        $seller_id = getParentSellerId();
        $sellerProduct =  $this->product;
        if (isModuleActive('FrontendMultiLang')) {
            $productName = $this->productSlug((!empty($data['product_name'][auth()->user()->lang_code])) ? $data['product_name'][auth()->user()->lang_code] : $product->product_name);    
        }else{
            $productName = $this->productSlug((!empty($data['product_name'])) ? $data['product_name'] : $product->product_name);
        }
        $sellerProduct->product_id = $data['product_id'];
        $sellerProduct->product_name = (!empty($data['product_name'])) ? $data['product_name'] : $product->product_name;
        $sellerProduct->stock_manage = (!empty($data['stock_manage'])) ? $data['stock_manage'] : 0;
        $sellerProduct->tax = isset($data['tax'])?$data['tax']:0;
        $sellerProduct->user_id = $seller_id;
        $sellerProduct->tax_type = 0;
        $sellerProduct->discount = isset($data['discount'])?$data['discount']:0;
        $sellerProduct->discount_type = $data['discount_type'];
        $sellerProduct->discount_start_date = $data['discount_start_date']?date('Y-m-d',strtotime($data['discount_start_date'])):null;
        $sellerProduct->discount_end_date = $data['discount_end_date']?date('Y-m-d',strtotime($data['discount_end_date'])):null;
        $sellerProduct->thum_img = (!empty($data['thum_img_src'])) ? $data['thum_img_src'] : null;
        $sellerProduct->slug = $productName;
        $sellerProduct->subtitle_1 = $data['subtitle_1'];
        $sellerProduct->subtitle_2 = $data['subtitle_2'];
        $sellerProduct->save();

        if(isset($data['thum_img_src'])){
            UsedMedia::create([
                'media_id' => $data['thumb_image_id'],
                'usable_id' => $sellerProduct->id,
                'usable_type' => get_class($sellerProduct),
                'used_for' => 'thumb_image'
            ]);
        }

        if($product->product_type == 1){
            $sellerProductSKU = SellerProductSKU::create([
                'product_id' => $sellerProduct->id,
                'product_sku_id' => $product->skus->first()->id,
                'product_stock' => ($data['stock_manage'] == 1) ? $data['product_stock'] : 0,
                'selling_price' => $data['selling_price'],
                'status' => 1,
                'user_id' => $seller_id
            ]);

            //add Whole-sale price
            if (isModuleActive('WholeSale')){
                $wholeSaleMinQty = $data['wholesale_min_qty_0'];
                $wholeSaleMaxQty = $data['wholesale_max_qty_0'];
                $wholeSalePrice  = $data['wholesale_price_0'];

                $wholeSaleArrValue = [];

                if ( $wholeSaleMinQty[0]!=null ){
                    foreach ($wholeSaleMinQty as $keyMinQty=>$minVal){
                        $wholeSaleArrValue['min_qty'] = $wholeSaleMinQty[$keyMinQty];
                        $wholeSaleArrValue['max_qty'] = $wholeSaleMaxQty[$keyMinQty];
                        $wholeSaleArrValue['selling_price'] = $wholeSalePrice[$keyMinQty];
                        $wholeSaleArrValue['product_id'] = $sellerProduct->id;
                        $wholeSaleArrValue['sku_id'] = $sellerProductSKU->id;
                        $wholeSaleArrValue['created_at'] = date('Y-m-d');
                        $wholeSaleArrValue['updated_at'] = date('Y-m-d');

                        WholesalePrice::insert($wholeSaleArrValue);
                    }
                }
            }

        }

        if($product->product_type == 2){

            foreach($data['selling_price_sku'] as $key => $item){
                $sellerProductSKU = SellerProductSKU::create([
                    'product_id' => $sellerProduct->id,
                    'product_sku_id' => $data['product_skus'][$key],
                    'product_stock' => ($data['stock_manage'] == 1) ? $data['stock'][$key] : 0,
                    'selling_price' => $data['selling_price_sku'][$key],
                    'status' => 1,
                    'user_id' => $seller_id
                ]);

                //add Whole-sale price
                if (isModuleActive('WholeSale')){
                    $wholeSaleMinQty = $data['wholesale_min_qty_v_'.$key];
                    $wholeSaleMaxQty = $data['wholesale_max_qty_v_'.$key];
                    $wholeSalePrice  = $data['wholesale_price_v_'.$key];

                    $wholeSaleArrValue = [];

                    if ( $wholeSaleMinQty[0]!=null ){
                        foreach ($wholeSaleMinQty as $keyMinQty=>$minVal){
                            $wholeSaleArrValue['min_qty'] = $wholeSaleMinQty[$keyMinQty];
                            $wholeSaleArrValue['max_qty'] = $wholeSaleMaxQty[$keyMinQty];
                            $wholeSaleArrValue['selling_price'] = $wholeSalePrice[$keyMinQty];
                            $wholeSaleArrValue['product_id'] = $sellerProduct->id;
                            $wholeSaleArrValue['sku_id'] = $sellerProductSKU->id;
                            $wholeSaleArrValue['created_at'] = date('Y-m-d');
                            $wholeSaleArrValue['updated_at'] = date('Y-m-d');

                            WholesalePrice::insert($wholeSaleArrValue);
                        }
                    }
                }
            }
        }

        $sellerProduct->update([
            'min_sell_price' => $sellerProduct->skus->min('selling_price'),
            'max_sell_price' => $sellerProduct->skus->max('selling_price')
        ]);
        return 1;
    }

    public function findById($id){
        return $this->product::with('skus')->where('product_id',$id)->firstOrFail();
    }

    public function findBySellerProductId($id){
        return SellerProduct::with('skus')->findOrFail($id);
    }

    public function deleteById($id){

        $product =  $this->product->findOrFail($id);
        if(count($product->flashDealProducts) > 0 || count($product->newUserZoneProducts) > 0 ||
            count($product->MenuElements) > 0 || count($product->Silders) > 0 ||
            count($product->homepageCustomProducts) > 0 || count($product->Orders) > 0){
            return 'not_possible';
        }else{
            $skus = $product->skus->pluck('id')->toArray();
            $cart_list = Cart::where('product_type','product')->whereIn('product_id', $skus)->pluck('id')->toArray();
            if($product->headerProductPanel != null){
                $product->headerProductPanel->delete();
            }
            Cart::destroy($cart_list);

            ImageStore::deleteImage($product->thum_img);
            $product->delete();
            return 'possible';
        }
    }

    public function update($data, $id){

        $product =  $this->product::findOrFail($id);
        if (isModuleActive('FrontendMultiLang')) {
            $productName = $this->productSlug((!empty($data['product_name'][auth()->user()->lang_code])) ? $data['product_name'][auth()->user()->lang_code] : $product->product->product_name);    
        }else{
            $productName = $this->productSlug((!empty($data['product_name'])) ? $data['product_name'] : $product->product->product_name);
        }
        $product->product_name = (!empty($data['product_name'])) ? $data['product_name'] : $product->product->product_name;
        $product->stock_manage = (!empty($data['stock_manage'])) ? $data['stock_manage'] : 0;
        $product->tax = isset($data['tax'])?$data['tax']:0;
        $product->tax_type = 0;
        $product->discount = isset($data['discount'])?$data['discount']:0;
        $product->discount_type = $data['discount_type'];
        $product->discount_start_date = $data['discount_start_date']?date('Y-m-d',strtotime($data['discount_start_date'])):null;
        $product->discount_end_date = $data['discount_end_date']?date('Y-m-d',strtotime($data['discount_end_date'])):null;
        $product->thum_img =  isset($data['thum_img_src'])?$data['thum_img_src']: $product->thum_img;
        $product->slug = $productName;
        $product->subtitle_1 = $data['subtitle_1'];
        $product->subtitle_2 = $data['subtitle_2'];
        $product->save();

        if($product->seller->role->type == 'superadmin'){
            $product->product->update([
                'stock_manage' => (!empty($data['stock_manage'])) ? $data['stock_manage'] : 0
            ]);
        }

        if($product->product->product_type == 1){
            $product->skus->first()->update([
                'product_stock' => ($product->stock_manage == 1) ? $data['product_stock'] : 0,
                'selling_price' => $data['selling_price'],
            ]);
            $product->update([
                'min_sell_price' => $data['selling_price'],
                'max_sell_price' => $data['selling_price']
            ]);

            if($product->seller->role->type == 'superadmin'){
                $product->product->skus->first()->update([
                    'product_stock' => ($product->stock_manage == 1) ? $data['product_stock'] : 0
                ]);
            }

            //add/update Whole-sale price
            $sellerProductSKU = $product->skus->first();

            if (isModuleActive('WholeSale')){
                $allOldWholesalePrice = $sellerProductSKU->wholeSalePrices;
                $wholeSaleMinQty = $data['wholesale_min_qty_0'];
                $wholeSaleMaxQty = $data['wholesale_max_qty_0'];
                $wholeSalePrice  = $data['wholesale_price_0'];

                $wholeSaleArrValue = [];
                $updatedIds = [];

                if ( $wholeSaleMinQty[0]!=null ){
                    foreach ($wholeSaleMinQty as $keyMinQty=>$minVal){
                        if ($keyMinQty==0){
                            $wholesaleRow = $allOldWholesalePrice->first();
                        }else{
                            $wholesaleRow = $allOldWholesalePrice->skip($keyMinQty)->first();
                        }

                        $wholeSaleArrValue['min_qty'] = $wholeSaleMinQty[$keyMinQty];
                        $wholeSaleArrValue['max_qty'] = $wholeSaleMaxQty[$keyMinQty];
                        $wholeSaleArrValue['selling_price'] = $wholeSalePrice[$keyMinQty];
                        $wholeSaleArrValue['product_id'] = $sellerProductSKU->product_id;
                        $wholeSaleArrValue['sku_id'] = $sellerProductSKU->id;
                        $wholeSaleArrValue['created_at'] = date('Y-m-d');
                        $wholeSaleArrValue['updated_at'] = date('Y-m-d');

                        if ($wholesaleRow){
                            $wholesaleRow->update($wholeSaleArrValue);
                            $updatedIds[] = $wholesaleRow->id;
                        }else{
                            WholesalePrice::insert($wholeSaleArrValue);
                        }
                    }
                    $deletedIds = $allOldWholesalePrice->whereNotIn('id', $updatedIds)->pluck('id')->toArray();
                    WholesalePrice::destroy($deletedIds);
                }
            }
        }
        if($product->product->product_type == 2){
            foreach($data['product_skus'] as $key => $item){
                $variant = SellerProductSKU::where('product_sku_id',$item)->where('user_id', auth()->user()->id)->first();
                if(isset($variant)){
                    $variant->update([
                        'product_stock' => ($product->stock_manage == 1) ? $data['stock'][$key]??0 : 0,
                        'selling_price' => $data['selling_price_sku'][$key],
                        'status' => isset($data['status_'.$item])?1:0
                    ]);
                }
                else{
                    SellerProductSKU::create([
                        'product_id' => $product->id,
                        'product_sku_id' => $data['product_skus'][$key],
                        'product_stock' => ($product->stock_manage == 1) ? $data['stock'][$key]??0 : 0,
                        'selling_price' => $data['selling_price_sku'][$key],
                        'status' => isset($data['status_'.$item])?1:0,
                        'user_id' => getParentSellerId()
                    ]);
                }
                $min_sell_price = $product->skus->min('selling_price');
                $max_sell_price = $product->skus->max('selling_price');
                $product->update([
                    'min_sell_price' => $min_sell_price,
                    'max_sell_price' => $max_sell_price
                ]);

                if($product->seller->role->type == 'superadmin'){
                    ProductSku::find($item)->update([
                        'product_stock' => ($product->stock_manage == 1) ? $data['stock'][$key]??0 : 0
                    ]);
                }

                if (isModuleActive('WholeSale') && isset($data['wholesale_min_qty_v_'.$key])){

                    //add/update Whole-sale price
                    $sellerProductSKU = $variant;
                    $allOldWholesalePrice = $sellerProductSKU->wholeSalePrices;

                    $wholeSaleMinQty = $data['wholesale_min_qty_v_'.$key];
                    $wholeSaleMaxQty = $data['wholesale_max_qty_v_'.$key];
                    $wholeSalePrice  = $data['wholesale_price_v_'.$key];

                    $wholeSaleArrValue = [];
                    $updatedIds = [];

                    if ( $wholeSaleMinQty[0]!=null ){
                        foreach ($wholeSaleMinQty as $keyMinQty=>$minVal){
                            if ($keyMinQty==0){
                                $wholesaleRow = $allOldWholesalePrice->first();
                            }else{
                                $wholesaleRow = $allOldWholesalePrice->skip($keyMinQty)->first();
                            }

                            $wholeSaleArrValue['min_qty'] = $wholeSaleMinQty[$keyMinQty];
                            $wholeSaleArrValue['max_qty'] = $wholeSaleMaxQty[$keyMinQty];
                            $wholeSaleArrValue['selling_price'] = $wholeSalePrice[$keyMinQty];
                            $wholeSaleArrValue['product_id'] = $sellerProductSKU->product_id;
                            $wholeSaleArrValue['sku_id'] = $sellerProductSKU->id;
                            $wholeSaleArrValue['created_at'] = date('Y-m-d');
                            $wholeSaleArrValue['updated_at'] = date('Y-m-d');

                            if ($wholesaleRow){
                                $wholesaleRow->update($wholeSaleArrValue);
                                $updatedIds[] = $wholesaleRow->id;
                            }else{
                                WholesalePrice::insert($wholeSaleArrValue);
                            }
                        }

                        $deletedIds = $allOldWholesalePrice->whereNotIn('id', $updatedIds)->pluck('id')->toArray();
                        WholesalePrice::destroy($deletedIds);
                    }

                }

            }
        }

         // Send Notification
         $this->notificationUrl = route('seller.product.index');
         $this->typeId = EmailTemplateType::where('type', 'product_update_email_template')->first()->id;
         $users = User::whereHas('role', function($query){
             return $query->where('type', 'superadmin');
         })->pluck('id');
         foreach($users as $user){
             $this->notificationSend("Seller product update", $user);
         }

        return 1;
    }
    public function variantDelete($id){
        return SellerProductSKU::findOrFail($id)->delete();
    }

    public function getVariantByProduct($data){
        return ProductSku::where('id',$data['id'])->firstOrFail();
    }
    public function getThisSKUProduct($id){
        $sellerProduct = SellerProduct::findOrFail($id);
        $skunotin = SellerProductSKU::where('product_id',$id)->pluck('product_sku_id');

        return ProductSku::where('product_id',$sellerProduct->product->id)->where('status', 1)->whereNotIn('id',$skunotin)->get();
    }

    public function stockManageStatus($data){
        return SellerProduct::findOrFail($data['id'])->update([
            'stock_manage' => $data['status']
        ]);
    }

    public function getSellerBusinessInfo(){
        $seller_id = getParentSellerId();
        return SellerBusinessInformation::with('country', 'state', 'city')->where('user_id', $seller_id)->first();
    }

    public function getSellerBankInfo(){
        $seller_id = getParentSellerId();
        return SellerBankAccount::where('user_id', $seller_id)->first();
    }

    public function get_seller_product_sku_wise_price($data){
        $array = [];
        if (count($data) > 0) {
            foreach ($data['id'] as $key => $id) {
                array_push($array,explode('-',$id));
            }
        }

        $a = SellerProductSKU::query()->with('product.product');
        foreach ($array as $key => $value) {
            $a->where('user_id', $data['user_id'])->where('product_id', $data['product_id'])->whereHas('product_variations', function($query) use ($value){
                $query->where('attribute_id', $value[1])->where('attribute_value_id', $value[0]);
            });
        }
        if ($a->first()) {
            if(isModuleActive('WholeSale')){
                return response()->json([
                    'data' => $a->with(['sku','product','wholeSalePrices'])->first()
                ]);
            }
            return response()->json([
                'data' => $a->with(['sku','product'])->first()
            ]);
        }else {
            return 0;
        }
    }
}
