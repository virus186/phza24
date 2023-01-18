<?php

namespace Modules\PaymentGateway\Http\Controllers;

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

class InstamojoController extends Controller
{
    use Accounts;

    public function __construct()
    {
        $this->middleware('maintenance_mode');
    }  

    public function paymentProcess($data)
    {
        try {
            $credential = $this->getCredential();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, @$credential->perameter_3.'payment-requests/');
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    "X-Api-Key:" . @$credential->perameter_1,
                    "X-Auth-Token:" . @$credential->perameter_2
                )
            );
            $payload = array(
                'purpose' => 'NiceSnippets',
                'amount' => $data['amount'],
                'phone' => $data['mobile'],
                'buyer_name' => $data['name'],
                'redirect_url' => route('instamojo.payment_success'),
                'send_email' => true,
                'webhook' => '',
                'send_sms' => true,
                'email' => $data['email'],
                'allow_repeated_payments' => false
            );
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            if($response && !$response->success && $response->message){
                if(gettype($response->message) == 'string'){
                    Toastr::error($response->message);
                }else{
                    foreach($response->message as $msg){
                        Toastr::error($msg[0]);
                    }
                }
                return redirect()->back();
            }elseif(!$response){
                Toastr::error('Invalid System credential.');
                return redirect()->back();
            }
            else{
                return redirect($response->payment_request->longurl);
            }
            
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return redirect()->back();
        }
    }

    public function paymentSuccess(Request $request)
    {
        $input = $request->all();
        $credential = $this->getCredential();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,@$credential->perameter_3 .'payments/' . $request->get('payment_id'));
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                "X-Api-Key:" . @$credential->perameter_1,
                "X-Auth-Token:" . @$credential->perameter_2
            )
        );

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            Toastr::error(__('common.operation_failed'));
            return redirect()->back();
        } else {
            $data = json_decode($response);
        }

        if ($data->success == true) {
            if ($data->payment->status == "Credit") {
                if (session()->has('wallet_recharge')) {
                    $amount =  $data->payment->amount;
                    $response = $data->payment->payment_id;
                    $walletService = new WalletRepository;
                    session()->forget('wallet_recharge');
                    return $walletService->walletRecharge($amount, "8", $response);
                }
                if (session()->has('order_payment')) {
                    $amount =  $data->payment->amount;
                    $response = $data->payment->payment_id;
                    $orderPaymentService = new OrderRepository;
                    $order_payment = $orderPaymentService->orderPaymentDone($amount, "8", $response, (auth()->check()) ? auth()->user() : null);
                    if($order_payment == 'failed'){
                        Toastr::error('Invalid Payment');
                        return redirect(url('/checkout'));
                    }
                    $payment_id = $order_payment->id;
                    Session()->forget('order_payment');
                    $datas['payment_id'] = encrypt($payment_id);
                    $datas['gateway_id'] = encrypt(8);
                    $datas['step'] = 'complete_order';
                    LogActivity::successLog('Order payment successful.');
                    return redirect()->route('frontend.checkout', $datas);
                }
                if (session()->has('subscription_payment')) {
                    $amount =  $data->payment->amount;
                    $response = $data->payment->payment_id;
                    $defaultIncomeAccount = $this->defaultIncomeAccount();
                    $seller_subscription = getParentSeller()->SellerSubscriptions;
                    $transactionRepo = new TransactionRepository(new Transaction);
                    $transaction = $transactionRepo->makeTransaction(getParentSeller()->first_name . " - Subsriction Payment", "in", "InstaMojo", "subscription_payment", $defaultIncomeAccount, "Subscription Payment", $seller_subscription, $amount, Carbon::now()->format('Y-m-d'), getParentSellerId(), null, null);
                    $seller_subscription->update(['last_payment_date' => Carbon::now()->format('Y-m-d')]);
                    SubsciptionPaymentInfo::create([
                        'transaction_id' => $transaction->id,
                        'txn_id' => $response,
                        'seller_id' => getParentSellerId(),
                        'subscription_type' => getParentSeller()->sellerAccount->subscription_type,
                        'commission_type' => @$seller_subscription->pricing->name
                    ]);
                    session()->forget('subscription_payment');
                    Toastr::success(__('common.paymeny_successfully'), __('common.success'));
                    LogActivity::successLog('Subscription payment successful.');
                    return redirect()->route('seller.dashboard');
                }
            }
        } else {
            if (session()->has('subscription_payment')) {
                session()->forget('subscription_payment');
                Toastr::error(__('common.operation_failed'));
                return redirect()->route('seller.dashboard');
            }
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
            $credential = getPaymentInfoViaSellerId(session()->get('seller_for_checkout'), 8);
        }else{
            $credential = getPaymentInfoViaSellerId(1, 8);
        }
        return $credential;
    }
}
