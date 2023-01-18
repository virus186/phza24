<?php

namespace Modules\PaymentGateway\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\OrderRepository;
use Modules\Wallet\Repositories\WalletRepository;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Account\Repositories\TransactionRepository;
use Modules\Account\Entities\Transaction;
use Modules\FrontendCMS\Entities\SubsciptionPaymentInfo;
use App\Traits\Accounts;
use Carbon\Carbon;
use Modules\UserActivityLog\Traits\LogActivity;

class JazzCashController extends Controller
{
    use Accounts;

    public function __construct()
    {
        $this->middleware('maintenance_mode');
    }  

    public function paymentStatus(Request $request)
	{
		$response = $request->input();
		if($response['pp_ResponseCode'] == '000')
		{
            if ($response['pp_BillReference'] == "walletRecharge") {
                $amount = $response['pp_Amount'] / 100;
                $walletService = new WalletRepository;
                return $walletService->walletRecharge($amount, "12", $response['pp_TxnRefNo']);
            }
            if ($response['pp_BillReference'] == "checkoutPay") {
                $amount = $response['pp_Amount'] / 100;
                $orderPaymentService = new OrderRepository;
                $order_payment = $orderPaymentService->orderPaymentDone($amount, "12", $response['pp_TxnRefNo'], (auth()->check())?auth()->user():null);
                if($order_payment == 'failed'){
                    Toastr::error('Invalid Payment');
                    return redirect(url('/checkout'));
                }
                $payment_id = $order_payment->id;
                $data['payment_id'] = encrypt($payment_id);
                $data['gateway_id'] = encrypt(5);
                $data['step'] = 'complete_order';
                Toastr::success($response['pp_ResponseMessage']);
                LogActivity::successLog('checkout payment successful.');
                return redirect()->route('frontend.checkout', $data);
            }
            if ($response['pp_BillReference'] == "SubscriptionPayment") {
                $amount = $response['pp_Amount'] / 100;
                $defaultIncomeAccount = $this->defaultIncomeAccount();
                $seller_subscription = getParentSeller()->SellerSubscriptions;
                $transactionRepo = new TransactionRepository(new Transaction);
                $transaction = $transactionRepo->makeTransaction(getParentSeller()->first_name." - Subsriction Payment", "in", "JazzCash", "subscription_payment", $defaultIncomeAccount, "Subscription Payment", $seller_subscription, $amount, Carbon::now()->format('Y-m-d'), getParentSellerId(), null, null);
                $seller_subscription->update(['last_payment_date' => Carbon::now()->format('Y-m-d')]);
                SubsciptionPaymentInfo::create([
                    'transaction_id' => $transaction->id,
                    'txn_id' => $response['pp_TxnRefNo'],
                    'seller_id' => getParentSellerId(),
                    'subscription_type' => getParentSeller()->sellerAccount->subscription_type,
                    'commission_type' => @$seller_subscription->pricing->name
                ]);
                session()->forget('subscription_payment');
                Toastr::success(__('common.payment_successfully'),__('common.success'));
                LogActivity::successLog('Subscription payment successful.');
                return redirect()->route('seller.dashboard');
            }
		}
        else {
            if (session()->has('subscription_payment')) {
                session()->forget('subscription_payment');
                Toastr::error(__('common.operation_failed'));
                return redirect()->route('seller.dashboard');
            }
            Toastr::error(__('common.operation_failed'));
            return redirect()->route('frontend.welcome');
        }
	}
}
