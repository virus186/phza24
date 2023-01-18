@extends(theme('layouts.app'))
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('Modules/PageBuilder/Resources/assets/css/affiliate.css')}}">
    <style>
        .row{
            margin: 0!important;
        }
    </style>
@endsection

@section('content')


<div class="row">
    <div class="container mt_30 mb_30">
        @php
            echo $row->description;
        @endphp
    </div>
</div>
@endsection


