<?php

namespace Modules\PaymentGateway\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\OrderRepository;
use Unicodeveloper\Paystack\Paystack;
use Brian2694\Toastr\Facades\Toastr;
use \Modules\Wallet\Repositories\WalletRepository;
use Modules\Account\Repositories\TransactionRepository;
use Modules\Account\Entities\Transaction;
use Modules\FrontendCMS\Entities\SubsciptionPaymentInfo;
use App\Traits\Accounts;
use Carbon\Carbon;
use Modules\UserActivityLog\Traits\LogActivity;

class PaystackController extends Controller
{
    use Accounts;

    public function __construct()
    {
        $this->middleware('maintenance_mode');
    } 

    public function redirectToGateway()
    {
        $credential = $this->getCredential();
        config(['paystack.merchantEmail'=> @$credential->perameter_1]);
        config(['paystack.publicKey'=> @$credential->perameter_2]);
        config(['paystack.secretKey'=> @$credential->perameter_3]);
        config(['paystack.paymentUrl'=> @$credential->perameter_4]);
        $paystack = new Paystack(@$credential->perameter_1, @$credential->perameter_4);
        return $paystack->getAuthorizationUrl()->redirectNow();
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        $credential = $this->getCredential();
        config(['paystack.merchantEmail'=> @$credential->perameter_1]);
        config(['paystack.publicKey'=> @$credential->perameter_2]);
        config(['paystack.secretKey'=> @$credential->perameter_3]);
        config(['paystack.paymentUrl'=> @$credential->perameter_4]);
        $paystack = new Paystack(@$credential->perameter_1, @$credential->perameter_4);
        $payment = $paystack->getPaymentData();
        if ($payment['status'] == "true") {
            if (session()->has('wallet_recharge')) {
                $amount = $payment['data']['amount'] / 100;
                $response = $payment['data']['reference'];
                $walletService = new WalletRepository;
                session()->forget('wallet_recharge');
                return $walletService->walletRecharge($amount, "5", $response);

            }
            if (session()->has('order_payment')) {
                $amount = $payment['data']['amount'] / 100;
                $response = $payment['data']['reference'];
                $orderPaymentService = new OrderRepository;
                $order_payment = $orderPaymentService->orderPaymentDone($amount, "5", $response, (auth()->check())?auth()->user():null);
                if($order_payment == 'failed'){
                    Toastr::error('Invalid Payment');
                    return redirect(url('/checkout'));
                }
                $payment_id = $order_payment->id;
                Session()->forget('order_payment');
                $data['payment_id'] = encrypt($payment_id);
                $data['gateway_id'] = encrypt(5);
                $data['step'] = 'complete_order';
                LogActivity::successLog('Order payment successful by paystack.');
                return redirect()->route('frontend.checkout', $data);
            }
            if (session()->has('subscription_payment')) {
                $amount = $payment['data']['amount'] / 100;
                $response = $payment['data']['reference'];
                $defaultIncomeAccount = $this->defaultIncomeAccount();
                $transactionRepo = new TransactionRepository(new Transaction);
                $seller_subscription = getParentSeller()->SellerSubscriptions;
                $transaction = $transactionRepo->makeTransaction(getParentSeller()->first_name." - Subsriction Payment", "in", "PayStack", "subscription_payment", $defaultIncomeAccount, "Subscription Payment", $seller_subscription, $amount, Carbon::now()->format('Y-m-d'), getParentSellerId(), null, null);
                $seller_subscription->update(['last_payment_date' => Carbon::now()->format('Y-m-d')]);
                SubsciptionPaymentInfo::create([
                    'transaction_id' => $transaction->id,
                    'txn_id' => $response,
                    'seller_id' => getParentSellerId(),
                    'subscription_type' => getParentSeller()->sellerAccount->subscription_type,
                    'commission_type' => @$seller_subscription->pricing->name
                ]);
                session()->forget('subscription_payment');
                Toastr::success(__('common.payment_successfully'),__('common.success'));
                LogActivity::successLog('Subscription payment successful by paystack.');
                return redirect()->route('seller.dashboard');
            }
        }else {
            Toastr::error(__('common.operation_failed'));
            return back();
        }
    }

    private function getCredential(){
        $url = explode('?',url()->previous());
        if(isset($url[0]) && $url[0] == url('/checkout')){
            $is_checkout = true;
        }else{
            $is_checkout = false;
        }

        if(session()->has('order_payment') && app('general_setting')->seller_wise_payment && session()->has('seller_for_checkout') && $is_checkout){
            $credential = getPaymentInfoViaSellerId(session()->get('seller_for_checkout'), 5);
        }else{
            $credential = getPaymentInfoViaSellerId(1, 5);
        }
        return $credential;
    }

}
