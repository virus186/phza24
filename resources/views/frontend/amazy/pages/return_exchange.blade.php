@extends('frontend.amazy.layouts.app')

@section('title')
    {{$data->mainTitle}}
@endsection

@section('content')
<section class="return_part padding_top bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="single_return_part">
                    <h5 class="font_18 f_w_700">{{$data->returnTitle}}</h5>
                    @php echo $data->returnDescription; @endphp
                    <a href="{{url('/contact-us')}}" class="amaz_primary_btn style2 mb_20  add_to_cart flex-fill text-center">{{ __('common.contact_us') }}</a>
                </div>
                <div class="exchange_part">
                    <h5 class="font_18 f_w_700 m-0">{{$data->exchangeTitle}}</h5>
                    @php echo $data->exchangeDescription; @endphp
                    <a href="{{url('/contact-us')}}" class="amaz_primary_btn3 mb_20 text-center justify-content-center">{{ __('common.contact_us') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
