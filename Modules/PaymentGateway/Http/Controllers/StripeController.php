<?php

namespace Modules\PaymentGateway\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\OrderRepository;
use \Modules\Wallet\Repositories\WalletRepository;
use Modules\Account\Repositories\TransactionRepository;
use Modules\Account\Entities\Transaction;
use Modules\FrontendCMS\Entities\SubsciptionPaymentInfo;
use App\Traits\Accounts;
use Carbon\Carbon;
use Exception;
use Modules\UserActivityLog\Traits\LogActivity;
use Stripe;

class StripeController extends Controller
{
    use Accounts;

    public function __construct()
    {
        $this->middleware('maintenance_mode');
    } 

    public function payment_page(Request $request)
    {
         return view('paymentgateway::stripe_payment.create');
    }

    public function stripePost($data)
    {
        $currency_code = getCurrencyCode();
        $credential = $this->getCredential();
        Stripe\Stripe::setApiKey(@$credential->perameter_3);
        try{
            $stripe = Stripe\Charge::create ([
                "amount" => round($data['amount'] * 100),
                "currency" => $currency_code,
                "source" => $data['stripeToken'],
                "description" => "Payment from ". url('/')
            ]);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), __('common.error'));
            return redirect()->back();
        }
        if ($stripe['status'] == "succeeded") {
            $return_data = $stripe['id'];
            if (session()->has('wallet_recharge')) {
                $walletService = new WalletRepository;
                return $walletService->walletRecharge($data['amount'], "4", $return_data);
            }
            if (session()->has('order_payment')) {
                $orderPaymentService = new OrderRepository;
                $order_payment = $orderPaymentService->orderPaymentDone($data['amount'], "4", $return_data, (auth()->check())?auth()->user():null);
                if($order_payment == 'failed'){
                    Toastr::error('Invalid Payment');
                    return redirect(url('/checkout'));
                }
                $payment_id = $order_payment->id;
                Session()->forget('order_payment');
                LogActivity::successLog('Order payment successful.');
                return $payment_id;
            }
            if (session()->has('subscription_payment')) {
                $defaultIncomeAccount = $this->defaultIncomeAccount();
                $seller_subscription = getParentSeller()->SellerSubscriptions;
                $transactionRepo = new TransactionRepository(new Transaction);
                $transaction = $transactionRepo->makeTransaction(getParentSeller()->first_name." - Subsriction Payment", "in", "Stripe", "subscription_payment", $defaultIncomeAccount, "Subscription Payment", $seller_subscription, $data['amount'], Carbon::now()->format('Y-m-d'), getParentSellerId(), null, null);
                $seller_subscription->update(['last_payment_date' => Carbon::now()->format('Y-m-d')]);
                SubsciptionPaymentInfo::create([
                    'transaction_id' => $transaction->id,
                    'txn_id' => $return_data,
                    'seller_id' => getParentSellerId(),
                    'subscription_type' => getParentSeller()->sellerAccount->subscription_type,
                    'commission_type' => @$seller_subscription->pricing->name
                ]);
                LogActivity::successLog('Subscription payment successful.');
                return true;
            }
        }else {
            return redirect()->route('frontend.welcome');
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
            $credential = getPaymentInfoViaSellerId(session()->get('seller_for_checkout'), 4);
        }else{
            $credential = getPaymentInfoViaSellerId(1, 4);
        }
        return $credential;
    }

}
