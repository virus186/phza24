<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Services\AuthService;
use App\Traits\ImageStore;
use App\Traits\Notification;
use App\Traits\Otp;
use App\Traits\SendMail;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Affiliate\Repositories\AffiliateRepository;
use Modules\FormBuilder\Repositories\FormBuilderRepositories;
use Modules\GeneralSetting\Entities\EmailTemplateType;
use Modules\GeneralSetting\Entities\SmsTemplate;
use Modules\GeneralSetting\Entities\UserNotificationSetting;
use Modules\Marketing\Entities\ReferralCodeSetup;
use Modules\Marketing\Entities\ReferralUse;
use Modules\Marketing\Entities\ReferralCode;
use Modules\Otp\Entities\Otp as EntitiesOtp;
use Modules\UserActivityLog\Traits\LogActivity;
use Nwidart\Modules\Facades\Module;
use Session;

use Exception;
use Modules\FrontendCMS\Entities\LoginPage;

class RegisterController extends Controller
{
    use Notification, Otp, SendMail;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    protected function redirectTo()
    {
        if (app('business_settings')->where('type', 'email_verification')->first()->status == 1) {
            return '/user-email-verify';
        }
        if(session()->has('from_checkout')){
            $next_url = session()->get('from_checkout');
            session()->forget('from_checkout');
            return $next_url;
        }
        return '/profile/dashboard';
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest', 'maintenance_mode']);
        $this->middleware(['prohibited_demo_mode'])->only('register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if (env('NOCAPTCHA_FOR_REG') == "true" && app('theme')->folder_path == 'amazy') {
            $g_recaptcha = 'required';
        }else{
            $g_recaptcha = 'nullable';
        }
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users,email', 'check_unique_phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' =>$g_recaptcha,
            'referral_code' => ['sometimes', 'nullable', Rule::exists('referral_codes', 'referral_code')->where('status', 1)]
        ],
        [
            'password.min' => 'The password field minimum 8 character.',
            'g-recaptcha-response.required' => 'The google recaptcha field is required.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function othersFieldValue($data)
    {
        return json_encode($data);
    }
    public function create($data)
    {
        
        $c_data = [];
        if($data->has('custom_field')){
            foreach (json_decode($data['custom_field']) as  $key => $f){
                if($data->hasFile($f)){
                    $file = ImageStore::saveImage($data[$f], 250, 250);
                    $c_data[$f] = $file;
                }else{
                    $c_data[$f] = $data[$f];
                }
            }
        }
        
        $field = $data['email'];
        $email = null;
        if (is_numeric($field)) {
            $phone = $data['email'];
        } elseif (filter_var($field, FILTER_VALIDATE_EMAIL)) {
            $email = $data['email'];
        }
        
        $user =  User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => isset($phone) ? $phone : NULL,
            'email' => isset($email) ? $email : NULL,
            'verify_code' => sha1(time()),
            'password' => Hash::make($data['password']),
            'role_id' => 4,
            'phone' => isset($phone) ? $phone : NULL,
            'others' => $this->othersFieldValue($c_data),
            'currency_id' => app('general_setting')->currency,
            'lang_code' => app('general_setting')->language_code,
            'currency_code' => app('general_setting')->currency_code,
        ]);

        //affiliate user
        if(isModuleActive('Affiliate')){
            $affiliateRepo = new AffiliateRepository();
            $affiliateRepo->affiliateUser($user->id);
        }

        // User Notification Setting Create
        (new UserNotificationSetting)->createForRegisterUser($user->id);
        $this->typeId = EmailTemplateType::where('type', 'register_email_template')->first()->id; //register email templete typeid
        $this->adminNotificationUrl = '/customer/active-customer-list';
        $this->routeCheck = 'cusotmer.list.get-data';
        $this->notificationSend("Register", $user->id);

        //for email verification
        if(!isModuleActive('Otp') && !otp_configuration('otp_activation_for_customer') && $email != null){
            if (app('business_settings')->where('type', 'email_verification')->first()->status == 1) {
                $code = '<a class="btn btn-success" href="' . url('/verify?code=') . $user['verify_code'] . '">Click Here To Verify Your Account</a>';
                $this->sendVerificationMail($user, $code);
            }
        }

        if (isset($data['referral_code'])) {
            $referralData = ReferralCodeSetup::first();
            $referralExist = ReferralCode::where('referral_code', $data['referral_code'])->first();
            if ($referralExist) {
                $referralExist->update(['total_used' => $referralExist->total_used + 1]);
                ReferralUse::create([
                    'user_id' => $user->id,
                    'referral_code' => $data['referral_code'],
                    'discount_amount' => $referralData->amount
                ]);
            }
        }
        return $user;
    }

    public function register(Request $request)
    {
        $request->validate( [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'referral_code' => ['sometimes', 'nullable', Rule::exists('referral_codes', 'referral_code')->where('status', 1)]
        ],
        [
            'password.min' => 'The password field minimum 8 character.',
            
        ]);
       
        if (isModuleActive('Otp') && otp_configuration('otp_activation_for_customer')) {
            try {
                if (!$this->sendOtp($request)) {
                    Toastr::error(__('otp.something_wrong_on_otp_send'), __('common.error'));
                    return back();
                }
                return view(theme('auth.otp'), compact('request'));
            } catch (Exception $e) {
                LogActivity::errorLog($e->getMessage());
                Toastr::error(__('otp.something_wrong_on_otp_send'), __('common.error'));
                return back();
            }
        }
        $authRepos = new AuthRepository();
        $user_exist = $authRepos->getRegister($request->all());
        if($user_exist){
            $prev_session_id = session()->getId();
            $buy_it_now = session()->get('buy_it_now');
            $this->guard()->login($user_exist);
            $this->dataUpdateWhenLogin($prev_session_id, $buy_it_now);

            Toastr::success(__('auth.successfully_registered'), __('common.success'));
            LogActivity::addLoginLog(Auth::user()->id, Auth::user()->first_name . ' - logged in at : ' . Carbon::now());
            return $this->registered($request, $user_exist)
                ?: redirect($this->redirectPath());

        }
        $this->validator($request->all())->validate();
        
        event(new Registered($user = $this->create($request)));
        $prev_session_id = session()->getId();
        $buy_it_now = session()->get('buy_it_now');
        $this->guard()->login($user);
        $this->dataUpdateWhenLogin($prev_session_id, $buy_it_now);

        Toastr::success(__('auth.successfully_registered'), __('common.success'));
        LogActivity::addLoginLog(Auth::user()->id, Auth::user()->first_name . ' - logged in at : ' . Carbon::now());
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    public function showRegistrationForm()
    {
        $row = '';
        $form_data = '';
        if(Module::has('FormBuilder')){
            if(Schema::hasTable('custom_forms')){
                $formBuilderRepo = new FormBuilderRepositories();
                $row = $formBuilderRepo->find(2);
                if($row->form_data){
                    $form_data = json_decode($row->form_data);
                }
            }
        }

        if(url()->previous() == url('/checkout') || url()->previous() == url('/checkout?checkout_type=YnV5X2l0X25vdw==')){
            session()->put('from_checkout',url()->previous());
        }

        $loginPageInfo = LoginPage::findOrFail(2);
        return view(theme('auth.register'),compact('row','form_data','loginPageInfo'));
    }

    private function dataUpdateWhenLogin($prev_session_id, $buy_it_now){
        if($buy_it_now == 'yes'){
            session()->put('but_it_now', 'yes');
        }
        $carts = Cart::where('session_id', $prev_session_id)->get();
        if ($carts->count()) {
            
            foreach ($carts as $key => $cartItem) {
                $cartData = Cart::where('product_id', $cartItem->product_id)->where('user_id', auth()->id())->where('seller_id', $cartItem->seller_id)->where('shipping_method_id', $cartItem->shipping_method_id)->where('product_type',$cartItem->product_type)->first();
                if ($cartData) {
                    $cartData->update([
                        'qty' => $cartItem->qty,
                        'total_price' => $cartItem->total_price,
                        'is_select' => 1
                    ]);
                    $cartItem->delete();
                } else {
                    $cartItem->update([
                        'user_id' => auth()->id(),
                        'session_id' => null
                    ]);
                }
            }
        }
    }
}
