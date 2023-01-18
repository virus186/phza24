@php
    $credential = getPaymentInfoViaSellerId(1, 12);
    if (@$credential->perameter_1 == "sandbox") {
        $PAYU_BASE_URL = "https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/";
    }
    else {
        $PAYU_BASE_URL = @$credential->perameter_5;
    }
    $pp_Amount 	= $recharge_amount * 100;
    $DateTime 		= new \DateTime();
	$pp_TxnDateTime = $DateTime->format('YmdHis');
    $ExpiryDateTime = $DateTime;
	$ExpiryDateTime->modify('+' . 1 . ' hours');
	$pp_TxnExpiryDateTime = $ExpiryDateTime->format('YmdHis');
    $pp_TxnRefNo = 'T'.$pp_TxnDateTime;
    $post_data =  array(
			"pp_Version" 			=> "2.0",
            "pp_IsRegisteredCustomer"=>"No",
			"pp_TxnType" 			=> "MPAY",
			"pp_Language" 			=> "EN",
			"pp_MerchantID" 		=> @$credential->perameter_2,
			"pp_SubMerchantID" 		=> "",
			"pp_Password" 			=> @$credential->perameter_3,
			"pp_BankID" 			=> "",
			"pp_ProductID" 			=> "",
			"pp_TxnRefNo" 			=> $pp_TxnRefNo,
			"pp_Amount" 			=> $pp_Amount,
			"pp_TxnCurrency" 		=> "PKR",
			"pp_TxnDateTime" 		=> $pp_TxnDateTime,
			"pp_BillReference" 		=> "walletRecharge",
			"pp_Description" 		=> "wallet recharge purpose payment",
			"pp_TxnExpiryDateTime" 	=> $pp_TxnExpiryDateTime,
			"pp_ReturnURL" 			=> route('jazzcash.payment_status'),
			"pp_SecureHash" 		=> "",
			"ppmpf_1" 				=> "1",
			"ppmpf_2" 				=> "2",
			"ppmpf_3" 				=> "3",
			"ppmpf_4" 				=> "4",
			"ppmpf_5" 				=> "5",
		);

        $str = '';
		foreach($post_data as $key => $value){
			if(!empty($value)){
				$str = $str . '&' . $value;
			}
		}

		$str = @$credential->perameter_4.$str;

		$pp_SecureHash = hash_hmac('sha256', $str, @$credential->perameter_4);

        $post_data['pp_SecureHash'] = $pp_SecureHash;
@endphp

<div class="modal fade" id="JazzCashModal" tabindex="-1" role="dialog" aria-labelledby="JazzCashModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('wallet.jazz_cash_payment') }}</h5>
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>
            <div class="modal-body">
                <section class="send_query bg-white contact_form">
                    <form id="contactForm" action="{{ $PAYU_BASE_URL }}" class="p-0" method="POST">
                        <div class="row">
                            <div class="col-xl-12 text-center">
                                <h5>{{ __('wallet.are_you_sure_to_go_through_this_payment_gateway') }}</h5>
                            </div>
                        </div>
                        @foreach($post_data as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <div class="send_query_btn d-flex justify-content-between mt-4">
                            <button type="button" class="btn_1" data-dismiss="modal">{{ __('common.cancel') }}</button>
                            <button class="btn_1" type="submit">{{ __('wallet.continue_to_recharge') }}</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
