<?php

namespace Modules\PaymentGateway\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\OrderRepository;
use Modules\Wallet\Repositories\WalletRepository;
use PaytmWallet;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Account\Repositories\TransactionRepository;
use Modules\Account\Entities\Transaction;
use Modules\FrontendCMS\Entities\SubsciptionPaymentInfo;
use App\Traits\Accounts;
use Carbon\Carbon;
use Modules\UserActivityLog\Traits\LogActivity;

class PayUmoneyController extends Controller
{
    use Accounts;

    public function __construct()
    {
        $this->middleware('maintenance_mode');
    } 

    public function payment($data)
    {
        $credential = $this->getCredential();
        if (@$credential->perameter_1 == "TEST_MODE") {
            $PAYU_BASE_URL = "https://test.payu.in/_payment";
        }
        else {
            $PAYU_BASE_URL = "https://secure.payu.in/_payment";
        }

        $MERCHANT_KEY = @$credential->perameter_2; // add your id
        $SALT = @$credential->perameter_3; // add your id

        $action = '';
        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        $posted = array();
        $posted = array(
            'key' => $MERCHANT_KEY,
            'txnid' => $txnid,
            'amount' => $data['amount'],
            'firstname' => $data['firstname'],
            'email' => $data['email'],
            'productinfo' => $data['productinfo'],
            'surl' => route('payumoney.success'),
            'furl' => route('payumoney.failed'),
            'service_provider' => 'payu_paisa',
        );

        $hash = '';
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

        if(empty($posted['hash']) && sizeof($posted) > 0) {
            $hashVarsSeq = explode('|', $hashSequence);
            $hash_string = '';
            foreach($hashVarsSeq as $hash_var) {
                $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                $hash_string .= '|';
            }
            $hash_string .= $SALT;

            $hash = strtolower(hash('sha512', $hash_string));
        }


        try {
            $client = new \GuzzleHttp\Client();

            $request = Http::post($PAYU_BASE_URL,[
                'key' => $MERCHANT_KEY,
                'txnid' => $txnid,
                'amount' => $data['amount'],
                'productinfo' => $data['productinfo'],
                'firstname' => $data['firstname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'surl' => route('payumoney.success'),
                'furl' => route('payumoney.failed'),
                'hash' => $hash,
                'service_provider' => 'payu_paisa',
            ]);
        } catch (\Exception $e) {

            LogActivity::errorLog($e->getMessage());
            
        }
    }

    public function success(Request $request)
    {
        $response = $request->input();
        if ($response['status'] == "success" && $response['txnid'] != null) {
            if ($response['productinfo'] == "Wallet_Recharge") {
                $amount = $response['amount'];
                $walletService = new WalletRepository;
                return $walletService->walletRecharge($amount, "11", $response['txnid']);
                
            }
            if ($response['productinfo'] == "Checkout") {
                $amount = $response['amount'];
                $orderPaymentService = new OrderRepository;
                $order_payment = $orderPaymentService->orderPaymentDone($amount, "11", $response['txnid'], (auth()->check())?auth()->user():null);
                if($order_payment == 'failed'){
                    Toastr::error('Invalid Payment');
                    return redirect(url('/checkout'));
                }
                $payment_id = $order_payment->id;
                $data['payment_id'] = encrypt($payment_id);
                $data['gateway_id'] = encrypt(5);
                $data['step'] = 'complete_order';
                Toastr::success(__('common.payment_successfully'),__('common.success'));
                LogActivity::successLog('checkout payment successful.');
                return redirect()->route('frontend.checkout', $data);
            }
            if ($response['productinfo'] == "subscription_payment") {
                $amount = $response['amount'];
                $defaultIncomeAccount = $this->defaultIncomeAccount();
                $transactionRepo = new TransactionRepository(new Transaction);
                $seller_subscription = getParentSeller()->SellerSubscriptions;
                $transaction = $transactionRepo->makeTransaction(getParentSeller()->first_name." - Subsriction Payment", "in", "PayUMoney", "subscription_payment", $defaultIncomeAccount, "Subscription Payment", $seller_subscription, $amount, Carbon::now()->format('Y-m-d'), getParentSellerId(), null, null);
                $seller_subscription->update(['last_payment_date' => Carbon::now()->format('Y-m-d')]);
                SubsciptionPaymentInfo::create([
                    'transaction_id' => $transaction->id,
                    'txn_id' => $response['txnid'],
                    'seller_id' => getParentSellerId(),
                    'subscription_type' => getParentSeller()->sellerAccount->subscription_type,
                    'commission_type' => @$seller_subscription->pricing->name
                ]);
                session()->forget('subscription_payment');

                Toastr::success(__('common.payment_successfully'),__('common.success'));
                LogActivity::successLog('Subscription payment successful.');
                return redirect()->route('seller.dashboard');
            }
        }else {
            Toastr::error(__('common.operation_failed'));
            return back();
        }
    }

    public function failed()
    {
        Toastr::error(__('common.operation_failed'));
        return redirect()->route('frontend.welcome');
    }

    private function getCredential(){
        $url = explode('?',url()->previous());
        if(isset($url[0]) && $url[0] == url('/checkout')){
            $is_checkout = true;
        }else{
            $is_checkout = false;
        }
        if(session()->has('order_payment') && app('general_setting')->seller_wise_payment && session()->has('seller_for_checkout') && $is_checkout){
            $credential = getPaymentInfoViaSellerId(session()->get('seller_for_checkout'), 11);
        }else{
            $credential = getPaymentInfoViaSellerId(1, 11);
        }
        return $credential;
    }
}
