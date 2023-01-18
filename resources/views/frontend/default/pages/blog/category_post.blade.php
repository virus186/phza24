@extends('frontend.default.layouts.app')

@section('breadcrumb')
    {{ __('blog.blog') }}
@endsection
@section('title')
    {{ __('blog.blog') }}
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset(asset_path('frontend/default/css/page_css/blog/category_post.css')) }}" />
@endsection
@section('content')

    @include('frontend.default.partials._breadcrumb')

    <!-- blog part here -->
    <section class="blog_part bg-white padding_top">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="blog_post">
                        @if ($posts->count() > 0)
                            @foreach ($posts as $post)
                                <div class="single_blog_post d-flex align-items-center">
                                    <div class="single_blog_post_img">
                                        <div class="blog_img_main_div">
                                            <a class="blog_img_div" href="{{ route('blog.single.page', $post->slug) }}">
                                                <img src="{{ isset($post->image_url) ? showImage($post->image_url) : showImage('backend/img/default.png') }}"
                                                    alt="#">
                                            </a>
                                        </div>

                                    </div>
                                    <div class="single_blog_post_content">
                                        <h4><a href="{{ route('blog.single.page', $post->slug) }}">{{ $post->title }}</a>
                                        </h4>
                                        <p>{{ $post->excerpt }}</p>
                                        <div class="blog_post_details">
                                            <a href="javascript:void(0);"> <i class="ti-user"></i>
                                                {{ $post->user->getFullNameAttribute() }} </a>
                                            <a href="javascript:void(0);"> <i class="ti-calendar"></i>
                                                {{ \Carbon\Carbon::parse($post->published_at)->toDayDateTimeString() }}</a>
                                            <a href="{{ route('blog.single.page', $post->slug) }}"> <i
                                                    class="ti-comment"></i> {{ count($post->comments) }} </a>
                                            <a href="{{ route('blog.single.page', $post->slug) }}"><i
                                                    class="ti-eye"></i>{{ $post->view_count }}</a>
                                            <a href="{{ route('blog.single.page', $post->slug) }}"><i
                                                    class="ti-thumb-up"></i>{{ $post->like->count() }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-lg-12 col-md-12">
                                <div class="card h-100">
                                    <div class="single-post post-style-1 p-2">
                                        <strong>{{ __('blog.no_post_found') }}</strong>
                                    </div><!-- single-post -->
                                </div><!-- card -->
                            </div><!-- col-lg-4 col-md-6 -->
                        @endif
                        <!--  -->
                    </div>
                    <!-- pagination -->
                    @if ($posts->lastPage() > 1)
                        <x-pagination-component :items="$posts" type=""/>
                    @endif
                    <!-- pagination -->
                </div>

                @include('frontend.default.pages.blog.partials._sidebar')


            </div>
        </div>
    </section>
    <!-- blog part end -->


@endsection
