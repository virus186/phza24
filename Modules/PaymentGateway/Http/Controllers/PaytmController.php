<?php

namespace Modules\PaymentGateway\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\OrderRepository;
use Modules\Wallet\Repositories\WalletRepository;
use Modules\Account\Repositories\TransactionRepository;
use Modules\Account\Entities\Transaction;
use Modules\FrontendCMS\Entities\SubsciptionPaymentInfo;
use App\Traits\Accounts;
use Carbon\Carbon;
use PaytmWallet;
use Brian2694\Toastr\Facades\Toastr;
use Modules\UserActivityLog\Traits\LogActivity;

class PaytmController extends Controller
{
    use Accounts;

    public function __construct()
    {
        $this->middleware('maintenance_mode');
    } 

    public function payment($data)
    {
        // config dynamic for seller to seller payment
        $this->getCredential();
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => date('y-m-d').'-'.rand(1,9999),
          'user' => $data['name'],
          'mobile_number' => $data['mobile'],
          'email' => $data['email'],
          'amount' => round($data['amount'],2),
          'callback_url' => route('paytm.payment_redirect_callback')
        ]);
        return $payment->receive();
    }

    public function paymentCallback()
    {
        try {
            // config dynamic for seller to seller payment
            $this->getCredential();
            
            $transaction = PaytmWallet::with('receive');

            $response = $transaction->response();

            if ($transaction->isSuccessful()) {
                if (session()->has('wallet_recharge')) {
                    $amount = $response['TXNAMOUNT'];
                    $response = $transaction->getTransactionId();
                    $walletService = new WalletRepository;
                    session()->forget('wallet_recharge');
                    return $walletService->walletRecharge($amount, "9", $response);
                }
                if (session()->has('order_payment')) {
                    $amount = $response['TXNAMOUNT'];
                    $response = $transaction->getTransactionId();
                    $orderPaymentService = new OrderRepository;
                    $order_payment = $orderPaymentService->orderPaymentDone($amount, "9", $response, (auth()->check())?auth()->user():null);
                    if($order_payment == 'failed'){
                        Toastr::error('Invalid Payment');
                        return redirect(url('/checkout'));
                    }
                    $payment_id = $order_payment->id;
                    session()->forget('order_payment');
                    $data['payment_id'] = encrypt($payment_id);
                    $data['gateway_id'] = encrypt(9);
                    $data['step'] = 'complete_order';
                    LogActivity::successLog('Order payment successful.');
                    return redirect()->route('frontend.checkout', $data);
                }
                if (session()->has('subscription_payment')) {
                    $amount = $response['TXNAMOUNT'];
                    $defaultIncomeAccount = $this->defaultIncomeAccount();
                    $transactionRepo = new TransactionRepository(new Transaction);
                    $seller_subscription = getParentSeller()->SellerSubscriptions;
                    $transaction = $transactionRepo->makeTransaction(getParentSeller()->first_name." - Subsriction Payment", "in", "PayTM", "subscription_payment", $defaultIncomeAccount, "Subscription Payment", $seller_subscription, $amount, Carbon::now()->format('Y-m-d'), getParentSellerId(), null, null);
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
                    LogActivity::successLog('Subscription payment successful.');
                    return redirect()->route('seller.dashboard');
                }
            }else {
                Toastr::error(__('common.operation_failed'));
                return redirect(url('/'));
            }
        } catch (\Exception $e) {

            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
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
            $credential = getPaymentInfoViaSellerId(session()->get('seller_for_checkout'), 9);
        }else{
            $credential = getPaymentInfoViaSellerId(1, 9);
        }
        config(['services.paytm-wallet.env'=> $credential->perameter_1]);
        config(['services.paytm-wallet.merchant_id'=> $credential->perameter_2]);
        config(['services.paytm-wallet.merchant_website'=> $credential->perameter_3]);
        config(['services.paytm-wallet.merchant_key'=> $credential->perameter_4]);
        config(['services.paytm-wallet.channel'=> $credential->perameter_5]);
        config(['services.paytm-wallet.industry_type'=> $credential->perameter_6]);

        return $credential;
    }
}
