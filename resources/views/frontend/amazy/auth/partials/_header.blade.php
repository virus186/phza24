<!doctype html>
<html @if(isRtl()) dir="rtl" class="rtl no-js" @else class="no-js" @endif lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{app('general_setting')->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="{{showImage(app('general_setting')->favicon)}}">

    @php
        $themeColor = Modules\Appearance\Entities\ThemeColor::where('status',1)->first();
    @endphp
    <style>

        :root {
            --background_color : {{ $themeColor->background_color }};
            --base_color : {{ $themeColor->base_color }};
            --text_color : {{ $themeColor->text_color }};
            --feature_color : {{ $themeColor->feature_color }};
            --footer_color : {{ $themeColor->footer_color }};
            --navbar_color : {{ $themeColor->navbar_color }};
            --menu_color : {{ $themeColor->menu_color }};
            --border_color : {{ $themeColor->border_color }};
            --success_color : {{ $themeColor->success_color }};
            --warning_color : {{ $themeColor->warning_color }};
            --danger_color : {{ $themeColor->danger_color }};
        }
    </style>
    <!-- CSS here -->
    @if(isRtl())
    <link rel="stylesheet" href="{{asset(asset_path('frontend/amazy/compile_css/rtl_app.css'))}}" />
    @else
    <link rel="stylesheet"  href="{{asset(asset_path('frontend/amazy/compile_css/app.css'))}}" />
    @endif

    @stack('styles')
    <!-- CSS here -->
</head>