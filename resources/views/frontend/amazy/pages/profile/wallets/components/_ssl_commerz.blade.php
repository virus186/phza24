<form action="{{route('my-wallet.store')}}" method="post" id="ssl_commerz_form">
    @csrf
    <input type="hidden" name="method" value="SslCommerz">
    <input type="hidden" name="type" value="wallet_recharge">
    <input type="hidden" name="amount" value="{{$recharge_amount}}">
    
    <button class="wallet_elemnt" type="submit" id="sslczPayBtn">
        <img class="img-fluid" src="{{showImage($gateway_activations->where('method', 'SslCommerz')->first()->logo)}}" alt="">
    </button>

</form>