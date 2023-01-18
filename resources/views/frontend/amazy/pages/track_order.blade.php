@extends('frontend.amazy.layouts.app')

@section('title')
    {{__('defaultTheme.track_order')}}
@endsection
<style>
    .order_tracking_area{
        padding: 50px 0px;
    }
</style>
@section('content')
    <div class="order_tracking_area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-8 col-md-10">
                    <div class="tracking_form">

                        <h3 class="font_30 f_w_700 mb_5">{{__('defaultTheme.track_your_order')}}</h3>
                        <p class="mb-4">{{__('defaultTheme.enter_your_order_id_in_the_box_below_and_press_the_track_button')}}</p>

                        <form action="{{ route('frontend.order.track_find') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb_20">
                                    <label class="primary_label2 style2">{{ __('defaultTheme.order_tracking_number') }} <span>*</span></label>
                                    <input id="order_number" name="order_number"
                                    value="{{old('order_number')}}"
                                    placeholder="{{ __('defaultTheme.order_tracking_number') }}" value="{{old('order_number')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('defaultTheme.order_tracking_number') }}'" class="primary_input3 rounded-0 style2" type="text">
                                    @error('order_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @guest
                                    @if(app('general_setting')->track_order_by_secret_id)
                                        <div class="col-12 mb_20">
                                            <label class="primary_label2 style2">{{ __('defaultTheme.secret_id_only_for_guest_user') }} <span>*</span></label>
                                            <input id="guest_id" name="secret_id"
                                            placeholder="{{ __('defaultTheme.secret_id_only_for_guest_user') }}"
                                            value="{{old('secret_id')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('defaultTheme.secret_id_only_for_guest_user') }}'" class="primary_input3 rounded-0 style2" required type="text">
                                            @error('secret_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                @endguest
                                <div class="col-12">
                                    <button class="amaz_primary_btn  rounded-0  w-100 text-uppercase  text-center">{{ __('defaultTheme.track_now') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="order_shiping_box_area section_spacing6 mb_30">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-xl-4 col-lg-4 col-md-6  p-0">
                    <div class="single_order_shiping_box d-flex flex-wrap">
                        <div class="icon">
                        <svg id="_return_icon" data-name=" return_icon" xmlns="http://www.w3.org/2000/svg" width="32.947" height="40" viewBox="0 0 32.947 40">
                        <path id="_return_icon-2" data-name=" return_icon" d="M21.474,10.563H13.029l3.058-3.035a2.362,2.362,0,0,0-3.34-3.34L5.69,11.245a2.352,2.352,0,0,0,0,3.34l7.057,7.057a2.362,2.362,0,1,0,3.34-3.34l-3.058-3.035h8.445A11.762,11.762,0,1,1,9.713,27.029a2.352,2.352,0,1,0-4.7,0A16.467,16.467,0,1,0,21.474,10.563Z" transform="translate(-4.994 -3.496)" fill="#fd4949"/>
                        </svg>

                        </div>
                        <div class="traking_text">
                            <h4>30 Days Returns</h4>
                            <p>For all oders over $100, consectetur
                                adipim scing elit.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6  p-0">
                    <div class="single_order_shiping_box d-flex flex-wrap">
                        <div class="icon">
                            <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                                <g id="Group_1687" data-name="Group 1687">
                                    <path id="Path_1996" data-name="Path 1996" d="M36.8,1H5.2A4.21,4.21,0,0,0,1,5.2V36.8A4.21,4.21,0,0,0,5.2,41H36.8A4.21,4.21,0,0,0,41,36.8V5.2A4.21,4.21,0,0,0,36.8,1ZM25,3.667v9.842l-3.4-1.7a1.335,1.335,0,0,0-1.193,0l-3.4,1.7V3.667ZM38.333,36.8A1.54,1.54,0,0,1,36.8,38.333H5.2A1.54,1.54,0,0,1,3.667,36.8V5.2A1.54,1.54,0,0,1,5.2,3.667h9.129v12a1.334,1.334,0,0,0,1.93,1.193L21,14.491l4.737,2.369a1.334,1.334,0,0,0,1.93-1.193v-12H36.8A1.54,1.54,0,0,1,38.333,5.2Z" transform="translate(-1 -1)" fill="#fd4949"/>
                                    <path id="Path_1997" data-name="Path 1997" d="M17,21H6.333a1.333,1.333,0,0,0,0,2.666H17A1.333,1.333,0,0,0,17,21Z" transform="translate(0.332 5.667)" fill="#fd4949"/>
                                    <path id="Path_1998" data-name="Path 1998" d="M13,25H6.333a1.333,1.333,0,0,0,0,2.666H13A1.333,1.333,0,0,0,13,25Z" transform="translate(0.332 7.003)" fill="#fd4949"/>
                                </g>
                            </svg>
                        </div>
                        <div class="traking_text">
                            <h4>Free Shipping</h4>
                            <p>For all oders over $100, consectetur
                                adipim scing elit.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6  p-0">
                    <div class="single_order_shiping_box d-flex flex-wrap">
                        <div class="icon">
                        <svg id="_003-ribbon" data-name="003-ribbon" xmlns="http://www.w3.org/2000/svg" width="39.999" height="40" viewBox="0 0 39.999 40">
                            <g id="Group_1689" data-name="Group 1689">
                                <g id="Group_1688" data-name="Group 1688">
                                <path id="Path_1999" data-name="Path 1999" d="M39.839,31.88,32.584,19.285A13.746,13.746,0,1,0,6.256,13.75a13.637,13.637,0,0,0,1.173,5.532L.174,31.875a1.251,1.251,0,0,0,1.408,1.833l7.34-1.975,1.953,7.338a1.247,1.247,0,0,0,1.042.917.953.953,0,0,0,.165.013,1.25,1.25,0,0,0,1.083-.625L20.006,27.53l6.843,11.845a1.249,1.249,0,0,0,2.29-.3l1.953-7.338,7.34,1.975a1.25,1.25,0,0,0,1.408-1.83ZM12.511,35.507l-1.5-5.628a1.258,1.258,0,0,0-.582-.762,1.274,1.274,0,0,0-.95-.125L3.846,30.507l5.025-8.723a13.763,13.763,0,0,0,8.422,5.445ZM8.756,13.75A11.25,11.25,0,1,1,20.006,25,11.264,11.264,0,0,1,8.756,13.75ZM30.531,28.995a1.261,1.261,0,0,0-.95.125,1.244,1.244,0,0,0-.583.763L27.5,35.51l-4.782-8.278a13.76,13.76,0,0,0,8.423-5.445l5.025,8.723Z" transform="translate(-0.006)" fill="#fd4949"/>
                                </g>
                            </g>
                            <g id="Group_1691" data-name="Group 1691" transform="translate(12.493 5.046)">
                                <g id="Group_1690" data-name="Group 1690">
                                <path id="Path_2000" data-name="Path 2000" d="M174.925,70.135a1.246,1.246,0,0,0-.871-.816l-3.547-.928-1.946-3.277a1.3,1.3,0,0,0-2.149,0l-1.946,3.277-3.55.928a1.25,1.25,0,0,0-.663,1.991l2.379,2.977-.26,3.89a1.251,1.251,0,0,0,1.739,1.233l3.375-1.433,3.375,1.433a1.25,1.25,0,0,0,1.739-1.233l-.26-3.89,2.377-2.977A1.252,1.252,0,0,0,174.925,70.135Zm-4.843,2.972a1.243,1.243,0,0,0-.27.863l.158,2.344-1.994-.848a1.251,1.251,0,0,0-.981,0L165,76.314l.155-2.344a1.243,1.243,0,0,0-.27-.863l-1.478-1.854,2.184-.573a1.255,1.255,0,0,0,.758-.57l1.133-1.909,1.133,1.909a1.255,1.255,0,0,0,.758.57l2.184.573Z" transform="translate(-159.979 -64.544)" fill="#fd4949"/>
                                </g>
                            </g>
                            </svg>
                        </div>
                        <div class="traking_text">
                            <h4>International Warranty</h4>
                            <p>For all oders over $100, consectetur
                                adipim scing elit.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
