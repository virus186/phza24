<?php

namespace Modules\PaymentGateway\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\OrderRepository;
use \Modules\Wallet\Repositories\WalletRepository;
use Omnipay\Omnipay;
use Modules\Account\Repositories\TransactionRepository;
use Modules\Account\Entities\Transaction;
use Modules\FrontendCMS\Entities\SubsciptionPaymentInfo;
use App\Traits\Accounts;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use Modules\UserActivityLog\Traits\LogActivity;

class PayPalController extends Controller
{
    use Accounts;
    public $payPalGateway;

    public function __construct()
    {
        $this->middleware('maintenance_mode');

        $this->payPalGateway = Omnipay::create('PayPal_Rest');
        $credential = $this->getCredential();
        $this->payPalGateway->setClientId(@$credential->perameter_2);
        $this->payPalGateway->setSecret(@$credential->perameter_3);
        if(@$credential->perameter_1 == 'sandbox'){
            $this->payPalGateway->setTestMode(true);
        }elseif(@$credential->perameter_1 == 'live'){
            $this->payPalGateway->setTestMode(false);
        }
    }


    public function payment($data)
    {
        $data['amount'] = round($data['amount'],2);
        $response = $this->payPalGateway->purchase(array(
            'amount' => $data['amount'],
            'currency' => app('general_setting')->currency_code,
            'returnUrl' => route('paypal.paypalSuccess'),
            'cancelUrl' => route('paypal.paypalFailed'),

        ))->send();

        if ($response->isRedirect()) {
            $response->redirect(); // this will automatically forward the customer
        } else {
            Toastr::error($response->getMessage(), 'Failed');
            return \redirect()->back();
        }
    }

    public function paypalSuccess(Request $request)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->payPalGateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));
            $response = $transaction->send();

            if ($response->isSuccessful()) {
                $arr_body = $response->getData();
                $paymentAmount = $arr_body['transactions'][0]['amount'];
                if (session()->has('wallet_recharge')) {
                    $return_data = $arr_body['id'];
                    $walletService = new WalletRepository;
                    return $walletService->walletRecharge($paymentAmount['total'], "3", $return_data);
                }
                if (session()->has('order_payment')) {
                    $return_data = $arr_body['id'];
                    $orderPaymentService = new OrderRepository;
                    $order_payment = $orderPaymentService->orderPaymentDone($paymentAmount['total'], "3", $return_data, (auth()->check())?auth()->user():null);
                    if($order_payment == 'failed'){
                        Toastr::error('Invalid Payment');
                        return redirect(url('/checkout'));
                    }
                    $payment_id = $order_payment->id;
                    Session()->forget('order_payment');
                    $data['payment_id'] = encrypt($payment_id);
                    $data['gateway_id'] = encrypt(3);
                    $data['step'] = 'complete_order';
                    LogActivity::successLog('Order payment successful.');
                    return redirect()->route('frontend.checkout', $data);
                }
                if (session()->has('subscription_payment')) {
                    $return_data = $arr_body['id'];
                    $tnx_check = SubsciptionPaymentInfo::where('txn_id', $return_data)->first();
                    if($tnx_check){
                        Toastr::error('Invalid Payment');

                    }else{
                        $defaultIncomeAccount = $this->defaultIncomeAccount();
                        $seller_subscription = getParentSeller()->SellerSubscriptions;
                        $transactionRepo = new TransactionRepository(new Transaction);
                        $transaction = $transactionRepo->makeTransaction(getParentSeller()->first_name." - Subsriction Payment", "in", "Paypal", "subscription_payment", $defaultIncomeAccount, "Subscription Payment", $seller_subscription, $paymentAmount['total'], Carbon::now()->format('Y-m-d'), getParentSellerId(), null, null);
                        $seller_subscription->update(['last_payment_date' => Carbon::now()->format('Y-m-d')]);
                        SubsciptionPaymentInfo::create([
                            'transaction_id' => $transaction->id,
                            'txn_id' => $return_data,
                            'seller_id' => getParentSellerId(),
                            'subscription_type' => getParentSeller()->sellerAccount->subscription_type,
                            'commission_type' => @$seller_subscription->pricing->name
                        ]);
                        session()->forget('subscription_payment');
                        Toastr::success(__('common.payment_successfully'),__('common.success'));
                        LogActivity::successLog('Subscription payment successful.');
                    }
                    return redirect()->route('seller.dashboard');
                }
                return redirect()->back();

            } else {
                $msg = str_replace("'", " ", $response->getMessage());
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        }
    }

    public function paypalFailed()
    {
        if (session()->has('wallet_recharge')) {
            if (auth()->user()->role->type == 'customer') {
                return redirect(url('wallet/customer/my-wallet-index'));
            } elseif (auth()->user()->role->type == 'seller') {
                return redirect(url('wallet/seller/my-wallet-index'));
            }elseif (auth()->user()->role->type == 'admin') {
                return redirect(url('wallet/admin/my-wallet-index'));
            }
            return redirect(url('/'));
        }elseif (session()->has('order_payment')) {
            return redirect(url('/checkout'));
        }elseif (session()->has('subscription_payment')) {
            return redirect()->route('seller.dashboard');
        }
        return redirect(url('/'));
    }

    private function getCredential(){
        $url = explode('?',url()->previous());
        if(isset($url[0]) && $url[0] == url('/checkout')){
            $is_checkout = true;
        }else{
            $is_checkout = false;
        }
        if(session()->has('order_payment') && app('general_setting')->seller_wise_payment && session()->has('seller_for_checkout') && $is_checkout){
            $credential = getPaymentInfoViaSellerId(session()->get('seller_for_checkout'), 3);
        }else{
            $credential = getPaymentInfoViaSellerId(1, 3);
        }
        return $credential;
    }

}
