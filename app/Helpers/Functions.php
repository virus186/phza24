<?php
use App\Mail\ContactMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Attendance\Entities\Attendance;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManagerStatic as Image;
use Modules\PaymentGateway\Entities\SellerWisePaymentGateway;
use Modules\SidebarManager\Entities\Sidebar;

if (!function_exists('theme')) {
    function theme($data)
    {
        $theme = app('theme');
        if ($theme) {
            return 'frontend.' . $theme->folder_path . '.' . $data;
        }
    }

}
if (!function_exists('themeWithSlash')) {
    function themeWithSlash($data)
    {
        $theme = app('theme');
        if ($theme) {
            return 'frontend/' . $theme->folder_path . '/' . $data;
        }
    }

}

if (!function_exists('themeDefaultImg')) {
    function themeDefaultImg()
    {
        $theme = app('theme');
        if ($theme) {
            if(app('theme')->folder_path == 'amazy'){
                $json = file_get_contents(storage_path('/app/amazy_img.json'));
            }else{
                $json = file_get_contents(storage_path('/app/default_img.json'));
            }
            $json = json_decode($json, true);
            return $json[$theme->folder_path];
        }
    }
}

 if(!function_exists('contactMail')){
    function contactMail($details){
        return Mail::to(env('MAIL_USERNAME'))->queue(new ContactMail($details));
    }
 }

if (!function_exists('attendanceCheck')) {
    function attendanceCheck($user_id, $type, $date)
    {
        $attendance = Attendance::where('user_id', $user_id)->whereDate('date', Carbon::parse($date)->format('Y-m-d'))->first();
        if ($attendance != null) {
            if ($attendance->attendance == $type) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}

if (!function_exists('attendanceNote')) {
    function attendanceNote($user_id)
    {
        $todayAttendance = Attendance::where('user_id', $user_id)->where('date', Carbon::today()->toDateString())->first();
        if ($todayAttendance != null) {
            return true;
        } else {
            return false;
        }
    }
}


if (!function_exists('Note')) {
    function Note($user_id)
    {
        $todayAttendance = Attendance::where('user_id', $user_id)->where('date', Carbon::today()->toDateString())->first();
        if ($todayAttendance != null && $todayAttendance->note != null) {
            return $todayAttendance->note;
        } else {
            return false;
        }
    }
}


 if(!function_exists('contactMail')){
    function contactMail($details){
        return MailSend::to('spn21@spondonit.com')->send(new ContactMail($details));
    }
 }



if(!function_exists('is_admin_user')){
    function is_admin_user($id){
        $authIdList=[1,2,3];
        if(in_array($id,$authIdList)){
            return true;
        }
        return false;
    }
 }

if(!function_exists('image_resize')){
    function image_resize(){
        $image=asset('public/uploads/blog/026649a94d244f70d1ce3b08a5a801dd.jpg');
        $img = Image::make('http://amazcart.com/public/uploads/blog/026649a94d244f70d1ce3b08a5a801dd.jpg');
        $resize=$img->resize(320, 200);
        return $img;

    }
 }
if(!function_exists('selling_price')){
    function selling_price ($amount = 0, $discount_type = 1, $discount_amount = 0){
        $discount = 0;
        if($discount_type == 0){
            $discount = ($amount/100) *$discount_amount;
        }if($discount_type == 1){
            $discount = $discount_amount;
        }

        $selling_price = $amount - $discount;
        return $selling_price;
     }
}

if(!function_exists('tax_count')){
    function tax_count($price=0 , $tax_amount=0, $tax_type=0){
        $tax = 0;
        if($tax_type == 0){
            $tax = ($price/100) * $tax_amount;
        }
        if($tax_type == 1){
            $tax = $tax_amount;
        }
        return $tax;
    }
}

if (!function_exists('sidebar_menus')) {
    function sidebar_menus()
    {
        if (!session()->has('menus')) {

            if (\Illuminate\Support\Facades\Auth::user()->sidebars()->exists()) {
                $PermissionList = Sidebar::where('user_id', auth()->id())->orderBy('position', 'asc')->get();
            } else {
                $PermissionList = Sidebar::where('user_id', 0)->orderBy('position', 'asc')->get();
            }
            $data['MainMenuList'] = $PermissionList->where('type', 1);
            $data['SubMenuList'] = $PermissionList->where('type', 2);
            $data['PermissionList'] = $PermissionList;
            $data['actions'] = $PermissionList->where('type', 3);
            session()->put('menus', $data);
            $data = session()->get('menus');
        } else {
            $data = session()->get('menus');
        }
        return $data;
    }
}

if (!function_exists('menuManagerCheck')) {
    function menuManagerCheck($type, $module_id, $route = null)   //type = 1 for main menu,2 for sub menu and 3 for action
    {
        $row = [
            'position' => '',
            'status' => ''
        ];

        $sidebar = sidebar_menus();

        if ($type == 1) {
            $mainMenu = $sidebar['MainMenuList']->where('module_id', $module_id)->first();

            $row = [
                'position' => $mainMenu->position ?? '',
                'status' => $mainMenu->status ?? ''
            ];
        } elseif ($type == 2) {
            $subMenu = $sidebar['SubMenuList']->where('module_id', $module_id)->where('route', $route)->first();

            $row = [
                'position' => $subMenu->position ?? '',

                'status' => $subMenu->status ?? ''


            ];
        } elseif ($type == 3) {
            $actions = $sidebar['actions']->where('module_id', $module_id)->where('route', $route)->first();

            $row = [
                'position' => $actions->position ?? '',

                'status' => $actions->status ?? ''

            ];
        }
        return json_decode(json_encode($row));
    }
}

if (!function_exists('asset_path')) {
    function asset_path($path = null){
        return 'public/'.$path;
    }
}


function setEnv($name, $value)
{
    $path = base_path('.env');
    if (file_exists($path)) {
        file_put_contents($path, str_replace(
            $name . '=' . env($name), $name . '=' . $value, file_get_contents($path)
        ));
    }
}

if (!function_exists('isRtl')) {
    function isRtl()
    {
        if (app('current_lang')->rtl == 1) {
            return true;
        }
        return false;
    }
}

if(!function_exists('getVar')){
    /*
     *  Used to get value-list json
     *  @return array
     */

    function getVar($list) {
        $file = resource_path('var/' . $list . '.json');

        return (File::exists($file)) ? json_decode(file_get_contents($file), true) : [];
    }
}
if (!function_exists('affiliateConfig')) {
    function affiliateConfig($key)
    {
        try {
            if($key){
                if (Cache::has('affiliate_config')) {
                    $affiliate_configs =  Cache::get('affiliate_config');
                    return $affiliate_configs[$key];

                } else {
                    Cache::forget('affiliate_config');
                    $datas = [];
                    foreach (\Modules\Affiliate\Entities\AffiliateConfiguration::get() as  $setting) {
                        $datas[$setting->key] = $setting->value;
                    }
                    Cache::rememberForever('affiliate_config', function () use($datas) {
                        return $datas;
                    });
                    $affiliate_configs =  Cache::get('affiliate_config');
                    return $affiliate_configs[$key];
                }
            }else{
                return false;
            }

        } catch (Exception $exception) {
            return false;
        }
    }
}
if (!function_exists('isAffiliateUser')) {
    function isAffiliateUser()
    {
        try {
            if(auth()->check()){
               if(auth()->user()->affiliate_request ==1){
                   return true;
               }
            }
            return false;

        } catch (Exception $exception) {
            return false;
        }
    }
}

if (!function_exists('hasAffiliateAccess')) {
    function hasAffiliateAccess()
    {
        try {
            if(auth()->check()){
                if(auth()->user()->role->type == 'superadmin' || auth()->user()->role->type == 'admin' || auth()->user()->role->type == 'staff'){
                    return true;
                }
                if(auth()->user()->affiliate_request ==1 && auth()->user()->accept_affiliate_request ==1){
                    return true;
                }
            }
            return false;

        } catch (Exception $exception) {
            return false;
        }
    }
}

if (!function_exists('getParentSellerId')) {
    function getParentSellerId()
    {
        $seller_id = 0;
        if(auth()->check()){
            if (auth()->user()->role->type == 'seller') {
                if(auth()->user()->sub_seller->seller_id){
                    $seller_id = auth()->user()->sub_seller->seller_id;
                }else{
                    $seller_id = auth()->id();
                }
            } elseif (auth()->user()->role->type == "superadmin") {
                $seller_id = auth()->id();
            } elseif (auth()->user()->role->type == "staff" || auth()->user()->role->type == "admin") {
                $seller_id = User::whereHas('role', function($q){
                    return $q->where('type', 'superadmin');
                })->first()->id;
            }
        }
        return $seller_id;
    }
}

if (!function_exists('getParentSeller')) {
    function getParentSeller()
    {
        $seller = null;
        if(auth()->check()){
            if (auth()->user()->role->type == 'seller') {
                if(auth()->user()->sub_seller->seller_id){
                    $seller = auth()->user()->sub_seller->seller;
                }else{
                    $seller = auth()->user();
                }
            }elseif(auth()->user()->role->type == 'superadmin' || auth()->user()->role->type == 'admin' || auth()->user()->role->type == 'staff'){
                $seller = User::with('role')->select(['role_id','id'])->find(1);
            }
        }
        return $seller;
    }
}


if (!function_exists('shippingConfig')) {
    function shippingConfig($key)
    {
        try {
            if($key){
                if (Cache::has('shipping_config')) {
                    $configs =  Cache::get('shipping_config');
                    return $configs[$key];

                } else {
                    Cache::forget('shipping_config');
                    $datas = [];
                    foreach (\Modules\Shipping\Entities\ShippingConfiguration::get() as  $setting) {
                        $datas[$setting->key] = $setting->value;
                    }
                    Cache::rememberForever('shipping_config', function () use($datas) {
                        return $datas;
                    });
                    $configs =  Cache::get('shipping_config');
                    return $configs[$key];
                }
            }else{
                return false;
            }

        } catch (Exception $exception) {
            return false;
        }
    }
}

if (!function_exists('sellerWiseShippingConfig')) {
    function sellerWiseShippingConfig($sellerId)
    {
        try {
            if($sellerId){
               $row =  \Modules\Shipping\Entities\ShippingConfiguration::where('seller_id',$sellerId)->first();
               if($row){
                   return collect($row);

               }else{
                   return null;
               }
            }else{
                return null;
            }

        } catch (Exception $exception) {
            return null;
        }
    }
}

if (!function_exists('singleProductURL')) {
    function singleProductURL($seller = null, $product=null){
        if(isModuleActive('MultiVendor')){
            return route('frontend.item.show',[$seller,$product]);
        }else{
            return route('frontend.item.show',$product);
        }
    }
}

if (!function_exists('pickupLocationData')) {
    function pickupLocationData($key)
    {

        try {
            $user_id = getParentSellerId();
            if($key){
                $row = \Modules\Shipping\Entities\PickupLocation::where('is_set',1)->where('created_by',$user_id)->first();
                if(!$row){
                    $row = \Modules\Shipping\Entities\PickupLocation::where('created_by',$user_id)->first();
                }
                $data = [
                    'id'=> $row->id,
                    'pickup_location'=> $row->pickup_location,
                    'name'=>$row->name,
                    'email'=>$row->email,
                    'phone'=>$row->phone,
                    'address'=>$row->address,
                    'address_2'=>$row->address_2,
                    'city'=>$row->city->name,
                    'state'=>$row->state->name,
                    'country'=>$row->country->name,
                    'pin_code'=>$row->pin_code,
                ];
                return $data[$key];
            }else{
                return false;
            }

        } catch (Exception $exception) {
            return false;
        }
    }
}

if (!function_exists('getCurrencyCode')) {
    function getCurrencyCode(){
        $currency_code = app('general_setting')->currency_code;

        if(Session::has('currency')){
            $currency = \Modules\GeneralSetting\Entities\Currency::where('id', session()->get('currency'))->first();
            $currency_code = $currency->code;
        }

        if(auth()->check()){
            $currency_code = auth()->user()->currency_code;
        }
        return $currency_code;
    }
}


if (!function_exists('getProductDiscountedPrice')) {
    function getProductDiscountedPrice($product){
        $price = single_price(0);
        if($product!=null){
            if($product->hasDeal){
                if($product->hasDeal->discount > 0){
                    if ($product->product->product_type == 1){
                        $price = single_price(selling_price(@$product->skus[0]->selling_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount));
                    }else{
                        if (selling_price(@$product->skus->min('selling_price'),@$product->hasDeal->discount_type,@$product->hasDeal->discount) === selling_price(@$product->skus->max('selling_price'),@$product->hasDeal->discount_type,@$product->hasDeal->discount)){
                            $price = single_price(selling_price(@$product->skus->min('selling_price'),@$product->hasDeal->discount_type,@$product->hasDeal->discount));
                        }else{
                            $price = single_price(selling_price(@$product->skus->min('selling_price'),@$product->hasDeal->discount_type,@$product->hasDeal->discount)).' - '.single_price(selling_price($product->skus->max('selling_price'),@$product->hasDeal->discount_type,@$product->hasDeal->discount));
                        }
                    }
                }
            }else{
                if(@$product->product->product_type == 1){
                    if(@$product->hasDiscount == 'yes'){
                        $price = single_price(selling_price(@$product->skus[0]->selling_price,@$product->discount_type,@$product->discount));
                    }else{
                        $price = single_price(@$product->skus[0]->selling_price);
                    }
                }else{
                    if(@$product->hasDiscount == 'yes'){
                        if(selling_price(@$product->skus->min('selling_price'),@$product->discount_type,@$product->discount) === selling_price(@$product->skus->max('selling_price'),@$product->discount_type,@$product->discount)){
                            $price = single_price(selling_price(@$product->skus->min('selling_price'),@$product->discount_type,$product->discount));
                        }else{
                            $price = single_price(selling_price(@$product->skus->min('selling_price'),@$product->discount_type,$product->discount)).' - '.single_price(selling_price(@$product->skus->max('selling_price'),@$product->discount_type,@$product->discount));
                        }
                    }else{
                        if (@$product->skus->min('selling_price') === @$product->skus->max('selling_price')){
                            $price = single_price(@$product->skus->min('selling_price'));
                        }else{
                            $price = single_price(@$product->skus->min('selling_price')).' - '.single_price(@$product->skus->max('selling_price'));
                        }
                    }
                }
            }
        }
        return $price;
    }
}

if (!function_exists('getProductwitoutDiscountPrice')) {
    function getProductwitoutDiscountPrice($product){
        $price = single_price(0);
        if($product != null){
            if($product->hasDeal){
                if($product->hasDeal->discount > 0){
                    if ($product->product->product_type == 1){
                        $price = single_price($product->skus[0]->selling_price);
                    }else{
                        if($product->skus->min('selling_price') === $product->skus->max('selling_price')){
                            $price = single_price($product->skus->min('selling_price'));
                        }else{
                            $price = single_price($product->skus->min('selling_price')) . ' - ' . single_price($product->skus->max('selling_price'));
                        }
                    }
                }
            }else{
                if(@$product->hasDiscount == 'yes'){
                    if($product->product->product_type == 1){
                        $price = single_price($product->skus[0]->selling_price);
                    }else{
                        if ($product->skus->min('selling_price') === $product->skus->max('selling_price')){
                            $price = single_price($product->skus->min('selling_price'));
                        }else{
                            $price = single_price($product->skus->min('selling_price')) . ' - ' . single_price($product->skus->max('selling_price'));
                        }
                    }
                }
            }
        }
        return $price;
    }
}

if (!function_exists('getGiftcardwithDiscountPrice')) {
    function getGiftcardwithDiscountPrice($giftcard){
        if(@$giftcard->hasDiscount()){
            $price = single_price(selling_price(@$giftcard->selling_price, @$giftcard->discount_type, @$giftcard->discount));
        }
        else{
            $price = single_price(@$giftcard->selling_price);
        }
        return $price;
    }
}
if (!function_exists('getGiftcardwithoutDiscountPrice')) {
    function getGiftcardwithoutDiscountPrice($giftcard){
        $price = single_price(0);
        if(@$giftcard){
            if(@$giftcard->hasDiscount()){
                $price = single_price(selling_price(@$giftcard->selling_price));
            }
        }

        return $price;
    }
}

if (!function_exists('textLimit')) {
    function textLimit($text, $limit = 20){
        if($text){
            return \Illuminate\Support\Str::limit($text, $limit, $end='...');
        }
        return null;
    }
}

if (!function_exists('checkTableJoin')) {
    function checkTableJoin($query, $table) {
        $joins = $query->getQuery()->joins;
        if($joins == null) {
            return false;
        }
        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }
        return false;
    }
}

function spn_active_link($route_or_path, $class = 'mm-active')
{
    if (is_array($route_or_path)) {
        foreach ($route_or_path as $route) {
            if (request()->is($route)) {
                return $class;
            }
        }
        return in_array(request()->route()->getName(), $route_or_path) ? $class : false;
    } else {
        if (request()->route()->getName() == $route_or_path) {
            return $class;
        }

        if (request()->is($route_or_path)) {
            return $class;
        }
    }

    return false;
}

function childrenRoute($menu, $routes = []){
    if(@$menu->backendMenu->route){
        $routes[] = $menu->backendMenu->route;
    }
    if($menu->children->count()){
        foreach($menu->children as $child){
            // $routes[] = $child->route;
            $routes = childrenRoute($child, $routes);
        }
        return $routes;
    }
    return $routes;
}
if (!function_exists('getLanguageList')) {
    function getLanguageList()
    {
        return Cache::rememberForever('LanguageList', function () {
            return DB::table('languages')
                ->where('status', 1)
                ->select('code', 'name', 'native')
                ->orderBy('id', 'DESC')
                ->get();
        });
    }
}


if (!function_exists('getPaymentInfoViaSellerId')) {
    function getPaymentInfoViaSellerId($seller_id=1, $gateway_id=null)
    {
        if(app('general_setting')->seller_wise_payment){
            $payment_credential = SellerWisePaymentGateway::with('method')->where('user_id', $seller_id)->whereHas('method', function($q) use($gateway_id){
                $q->where('id', $gateway_id);
            })->first();
        }else{
            $payment_credential = SellerWisePaymentGateway::with('method')->where('user_id', 1)->whereHas('method', function($q) use($gateway_id){
                $q->where('id', $gateway_id);
            })->first();
        }
        return $payment_credential;    
    }
}
if (! function_exists('getNumberTranslate')) {
    function getNumberTranslate($number){
        if (auth()->check()) {
            $lang_file = resource_path() . '/lang/'.auth()->user()->lang_code.'/numbers.php';
        }else {
            $lang_file = resource_path() . '/lang/'.\Session::get('locale').'/numbers.php';
               
        }
       if (!file_exists($lang_file)) {
           $lang_file = resource_path() . '/lang/default/numbers.php';
       }
       $languages = include  "{$lang_file}";
        $number_set_tr = strtr($number,$languages);
        return $number_set_tr;
    }
}
