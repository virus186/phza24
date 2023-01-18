@extends(theme('layouts.app'))
@if($pageData->is_page_builder == 1)

    @push('styles')
        @if($pageData->module == 'Affiliate')
            <link rel="stylesheet" type="text/css" href="{{asset('Modules/PageBuilder/Resources/assets/css/affiliate.css')}}">
        @endif
        <style>
            .row{
                margin: 0!important;
            }
            a:hover {
                color: var(--background_color) !important;
            }
        </style>
    @endpush


    @section('content')
        <div class="row">
            <div class="container mt_30 mb_30">
                @php echo $pageData->description; @endphp
            </div>
        </div>
    @endsection

@else

    @section('title')
        @php

            $arr = explode(' ',trim($pageData->title));
        @endphp
        {{$pageData->title}}
    @endsection

    @section('content')

        <!-- policy part here -->
        <section class="policy_part return_part padding_top bg-white">
            <div class="container">
                <div class="row justify-content-center mb_60 mt_60">
                    <div class="col-lg-10">
                        <div class="policy_part_iner">
                            @php echo $pageData->description; @endphp
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- policy part end -->

    @endsection

@endif
