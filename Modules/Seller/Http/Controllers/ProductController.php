<?php

namespace Modules\Seller\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Seller\Entities\SellerProductSKU;
use Modules\Seller\Services\ProductService;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\GST\Repositories\GstConfigureRepository;
use Modules\Product\Entities\Product;
use Modules\Product\Services\AttributeService;
use Modules\Product\Services\BrandService;
use Modules\Product\Services\CategoryService;
use Modules\Product\Services\UnitTypeService;
use Modules\Setup\Services\TagService;
use Modules\Shipping\Services\ShippingService;
use Modules\Product\Services\ProductService as mainProductService;
use Modules\MultiVendor\Entities\SellerBusinessInformation;
use Modules\UserActivityLog\Traits\LogActivity;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->middleware('maintenance_mode');
        $this->productService = $productService;
    }

    public function index()
    {
        try{
            return view('seller::products.index');
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }

    public function getData()
    {
        $status_slider = '_all_product_';
        if(isset($_GET['table'])){
            $products = $this->productService->getFilterdProduct(['table' => $_GET['table']]);
            $status_slider = '_'.$_GET['table'].'_';
        }else{
            $products = $this->productService->getAll();
        }

        return DataTables::of($products)
        ->addIndexColumn()
        ->addColumn('product_name', function($products){
            return view('seller::products.components.product._name_td',compact('products'));
        })
        ->addColumn('brand', function($products){
            return @$products->product->brand->name;
        })
        ->addColumn('logo', function($products){
            return view('seller::products.components.product._logo_td',compact('products'));
        })
        ->addColumn('stock', function($products){
            return view('seller::products.components.product._stock_td',compact('products'));
        })
        ->addColumn('status', function($products) use ($status_slider){
            return view('seller::products.components.product._status_td',compact('products','status_slider'));
        })
        ->addColumn('action',function($products){
            return view('seller::products.components.product._action_td',compact('products'));
        })
        ->rawColumns(['product_name','logo','stock','status','action'])
        ->toJson();
    }

    public function getProduct($id){
        try{
            $product = $this->productService->getProduct($id);
            if($product == 'product_exsist'){
                return 'product_exsist';
            }else{
                $gst_list = (string)view('seller::products.components._get_gst_list', compact('product'));
                $checkWholeSaleM = isModuleActive('WholeSale')? 1: null;
                return response()->json([
                    'product' => $product,
                    'gst_list' => $gst_list,
                    'checkWholeSaleM'=>$checkWholeSaleM
                ]);
            }
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }

    public function updateStatus(Request $request){

        try{
            $this->productService->statusChange($request->only('status'),$request->id);
            LogActivity::successLog('product status updated.');
            return $this->loadTableData();
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }

    }

    public function create(CategoryService $categoryService, UnitTypeService $unitTypeService, BrandService $brandService, TagService $tagService, AttributeService $attributeService, ShippingService $shippingService){


        $seller_id = getParentSellerId();
        $business_info = SellerBusinessInformation::with('country', 'state', 'city')->where('user_id', $seller_id)->first();
        $bankInfo = $this->productService->getSellerBankInfo();

        if($business_info ==null || $business_info->business_owner_name == null || $business_info->business_address1 == null
        || $business_info->business_address1 == null || $business_info->business_country == null
        || $business_info->business_state == null || $business_info->business_city == null || $bankInfo->bank_title == null
        || $bankInfo->bank_account_number == null || $bankInfo->bank_name == null || $bankInfo->bank_branch_name == null || $bankInfo->bank_routing_number == null){
            if(auth()->user()->role->type == 'seller'){
                Toastr::warning(__('seller.please_update_business_bank_information_from_profile_first'), __('common.warning'));
                return back();
            }else{
                $data['units'] = $unitTypeService->getActiveAll();
                $data['attributes'] = $attributeService->getActiveAll();
                $data['shippings'] = $shippingService->getActiveAll();
                $data['Products'] = $this->productService->getProductOfOtherSeller();
                $data['totalProducts'] = count($this->productService->getAllMyProduct());
                $gstGroup_repo = new GstConfigureRepository();
                $data['gst_groups'] = $gstGroup_repo->getGroup();
                if (auth()->user()->role->type == "seller") {
                    if (auth()->user()->SellerAccount->seller_commission_id == 3) {
                        if (auth()->user()->SellerSubscriptions->pricing->stock_limit > $data['totalProducts'] && auth()->user()->SellerSubscriptions->is_paid == 1) {
                            return view('seller::products.components.create', $data);
                        }
                        else {

                            Toastr::warning(__('product.uploading_product_is_disabled_according_to_your_package_right_now'),__('common.warning'));
                            return back();
                        }
                    }else {
                        return view('seller::products.components.create', $data);
                    }
                }else {
                    return view('seller::products.components.create', $data);
                }
            }

        }else{
            $data['units'] = $unitTypeService->getActiveAll();
            $data['attributes'] = $attributeService->getActiveAll();
            $data['shippings'] = $shippingService->getActiveAll();
            $data['Products'] = $this->productService->getProductOfOtherSeller();
            $data['totalProducts'] = count($this->productService->getAllMyProduct());
            $gstGroup_repo = new GstConfigureRepository();
            $data['gst_groups'] = $gstGroup_repo->getGroup();

            if (auth()->user()->role->type == "seller") {
                $seller = getParentSeller();
                if ($seller->SellerAccount->seller_commission_id == 3) {
                    if ($seller->SellerSubscriptions->pricing->stock_limit >= $data['totalProducts'] && $seller->SellerSubscriptions->is_paid == 1) {
                        return view('seller::products.components.create', $data);
                    }
                    else {
                        Toastr::warning(__('product.uploading_product_is_disabled_according_to_your_package_right_now'),__('common.warning'));
                        return back();
                    }
                }else {
                    return view('seller::products.components.create', $data);
                }
            }else {
                return view('seller::products.components.create', $data);
            }
        }


    }


    public function store(Request $request){

        $request->validate([
            'product_name' => ['required',Rule::unique('seller_products', 'product_name')->where(function($query){
                $seller_id = getParentSellerId();
                return $query->where('user_id', $seller_id);
            })],
            'subtitle_1' => ['nullable', 'max:190'],
            'subtitle_2' => ['nullable', 'max:190']
        ]);

        try{
             $business_info = $this->productService->getSellerBusinessInfo();
            $bankInfo = $this->productService->getSellerBankInfo();

            if($business_info->business_owner_name == null || $business_info->business_address1 == null
                || $business_info->business_address1 == null || $business_info->business_country == null
                || $business_info->business_state == null || $business_info->business_city == null || $bankInfo->bank_title == null
                || $bankInfo->bank_account_number == null || $bankInfo->bank_name == null || $bankInfo->bank_branch_name == null || $bankInfo->bank_routing_number == null){

                    if(auth()->user()->role->type == "seller"){
                        Toastr::warning(__('seller.please_update_business_bank_information_from_profile_first'), __('common.warning'));
                        return back();
                    }else{
                        $this->productService->store($request->except('_token'));
                        LogActivity::successLog('product store successful.');
                        Toastr::success(__('common.added_successfully'),__('common.success'));

                        if(auth()->user()->role->type == 'superadmin' || auth()->user()->role->type == 'admin' || auth()->user()->role->type == 'staff'){
                            return redirect()->route('admin.my-product.index');
                        }else{
                            return redirect()->route('seller.product.index');
                        }
                    }

            }else{
                $this->productService->store($request->except('_token'));
                LogActivity::successLog('product store successful.');
                Toastr::success(__('common.added_successfully'),__('common.success'));

                if(auth()->user()->role->type == 'superadmin' || auth()->user()->role->type == 'admin' || auth()->user()->role->type == 'staff'){
                    return redirect()->route('admin.my-product.index');
                }else{
                    return redirect()->route('seller.product.index');
                }
            }


        }catch(Exception $e){
            Toastr::error(__('common.error_message'),__('common.error'));
            LogActivity::errorLog($e->getMessage());
            return back();
        }
    }


    public function show(Request $request){
        try{
         
            $product = $this->productService->findById($request->id);
            return view('seller::products.components.my_product_details_view',compact('product'));
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }

    }

    public function admin_product_show(Request $request){
        try{
            $product = $this->productService->findBySellerProductId($request->id);
            return view('seller::products.components.view',compact('product'));
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }

    }

    public function edit($id){

        try{
            $data['product'] = $this->productService->findBySellerProductId($id);
            $data['skus'] = $this->productService->getThisSKUProduct($id);

            $totalWholesalePrice = '';
            if(isModuleActive('WholeSale')){
                if (@$data['product']->product->product_type ==1){
                    $totalWholesalePrice = \Modules\WholeSale\Entities\WholesalePrice::where('product_id', $id)->get();
                }
            }
            $data['totalWholesalePrice'] = $totalWholesalePrice;

            return view('seller::products.components.edit',$data);
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }

    }

    public function myProductEdit($id, CategoryService $categoryService, UnitTypeService $unitTypeService, BrandService $brandService, TagService $tagService, AttributeService $attributeService, ShippingService $shippingService, mainProductService $mainProductService){

        $product = Product::where('id',$id)->with('tags')->where('is_approved', 0)->where('created_by', auth()->id())->first();
        if($product){
            $data['product'] = $product;
            $data['units'] = $unitTypeService->getActiveAll();
            $data['attributes'] = $attributeService->getActiveAll();
            $data['shippings'] = $shippingService->getActiveAll();
            $data['products'] = $mainProductService->getAllForEdit($id);
            $gstGroup_repo = new GstConfigureRepository();
            $data['gst_groups'] = $gstGroup_repo->getGroup();
            return view('seller::products.components.my_product_edit', $data);
        }
        return abort(404);
    }

    public function myProductClone($id, CategoryService $categoryService, UnitTypeService $unitTypeService, BrandService $brandService, TagService $tagService, AttributeService $attributeService, ShippingService $shippingService, mainProductService $mainProductService){
        $data['product'] = Product::where('id',$id)->with('tags')->where('created_by', auth()->id())->firstOrFail();
        $data['units'] = $unitTypeService->getActiveAll();
        $data['attributes'] = $attributeService->getActiveAll();
        $data['shippings'] = $shippingService->getActiveAll();
        $data['products'] = $mainProductService->getAllForEdit($id);
        $gstGroup_repo = new GstConfigureRepository();
        $data['gst_groups'] = $gstGroup_repo->getGroup();
        return view('seller::products.components.my_product_clone', $data);
    }


    public function update(Request $request, $id)
    {

        $request->validate([
            'product_name' => ['required', Rule::unique('seller_products', 'product_name')->where(function($query) use ($id){
                $seller_id = getParentSellerId();
                return $query->where('user_id', $seller_id)->where('id', '!=', $id);
            })],
            'subtitle_1' => ['nullable', 'max:190'],
            'subtitle_2' => ['nullable', 'max:190']
        ]);

        try{
            $this->productService->update($request->except('_token'),$id);
            Toastr::success(__('common.updated_successfully'),__('common.success'));
            LogActivity::successLog('product updated.');
            return redirect()->route('seller.product.index');

        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.error_message'), __('common.error'));
            return back();
        }
    }

    public function variantDelete(Request $request){
        try{
            $del = $this->productService->variantDelete($request->id);

            LogActivity::successLog('Variant delete successful.');
            return $del;

        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }


    public function destroy(Request $request)
    {
        try {
            $result = $this->productService->deleteById($request->id);
            if ($result == "not_possible") {
                return response()->json([
                    'msg' => __('product.this_product_already_used_on_order_or_somewhere_so_delete_not_possible')
                ]);
            }

            LogActivity::successLog('product delete successful.');
            return $this->loadTableData();
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ],503);
        }

    }

    private function loadTableData()
    {

        try {
            $productList = $this->productService->getAll();

            return response()->json([
                'ProductList' =>  (string)view('seller::products.components.list', compact('productList')),
                'AlertList' =>  (string)view('seller::products.components.alert_list', compact('productList')),
                'StockList' =>  (string)view('seller::products.components.stock_list', compact('productList')),
                'DisabledList' =>  (string)view('seller::products.components.disabled_list', compact('productList'))
            ],200);
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));

            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }

    public function variant(Request $request)
    {
        $variants = $request->ids;
        if ($request->has('stock_manage')) {
            $stock_manage = $request->stock_manage;
        }
        else {
            $stock_manage = null;
        }

        return response()->json([
            'variants' =>  (string)view('seller::products.components.variant_product_table', compact('variants', 'stock_manage'))
        ]);
    }

    public function variantEdit(Request $request)
    {
        $variant = $this->productService->getVariantByProduct($request->except('_token'));
        if ($request->has('stock_manage')) {
            $stock_manage = $request->stock_manage;
        }
        else {
            $stock_manage = null;
        }

        $request->has('getIncKey')? ($incKey=$request->getIncKey+1) : $incKey=null;

        return response()->json([
            'variants' =>  (string)view('seller::products.components.variant_product_table_edit', compact('variant', 'stock_manage', 'incKey'))
        ]);
    }


    public function update_stock_manage_status(Request $request)
    {
        try{
            $this->productService->stockManageStatus($request->except('_token'));

            LogActivity::successLog('Update stock manage status successful.');
            return 1;
        }catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return 0;
        }
    }

    public function get_seller_product_sku_wise_price(Request $request)
    {
        return $this->productService->get_seller_product_sku_wise_price($request->except('_token'));
    }

    public function changeState(Request $request){
        if($request->has('type')){
            session()->put('seller_product_create_state', $request->type);
            return true;
        }
        return false;
    }

}
