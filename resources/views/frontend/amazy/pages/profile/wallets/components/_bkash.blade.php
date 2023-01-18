<form action="{{route('my-wallet.store')}}" method="post" id="bkash_form" class="bkash_form">
    @csrf
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    @if(@$credential->perameter_1 === "1")
        <script id="myScript"
                src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js"></script>
    @else
        <script id="myScript"
                src="https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js"></script>
    @endif

    <input type="hidden" name="method" value="Bkash">
    <input type="hidden" name="type" value="wallet_recharge">
    <input type="hidden" name="amount" value="{{$recharge_amount}}">
    <input type="hidden" name="trxID" id="trxID" value="">

    <button class="wallet_elemnt" type="button" id="bKash_button" onclick="BkashPayment()">
        <img class="img-fluid" src="{{showImage($gateway_activations->where('method', 'Bkash')->first()->logo)}}" alt="Bkash" title="Bkash">
    </button>

    @php
        $type = 'wallet_recharge';
        $amount = $recharge_amount;
    @endphp
    @include('bkash::bkash-script',compact('type','amount'))

</form>