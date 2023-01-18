@extends('frontend.amazy.layouts.app')

@section('title')
    {{ __('blog.blog') }}
@endsection

@section('content')
<div class="amazy_blog_section section_spacing6">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="row">
                        
                        @if($posts->count() > 0)
                            @foreach($posts as $post)
                                <div class="col-lg-4 col-md-6">
                                    <div class="amazy_blog_Widget mb_35 style2">
                                        <a href="{{route('blog.single.page',$post->slug)}}" class="thumb">
                                            <img src="{{isset($post->image_url)? showImage($post->image_url):showImage('backend/img/default.png')}}" alt="{{$post->title}}" title="{{$post->title}}">
                                        </a>
                                        <div class="blog_content">
                                            <span>{{date(app('general_setting')->dateFormat->format, strtotime($post->published_at))}}</span>
                                            <a href="{{route('blog.single.page',$post->slug)}}">
                                                <h4>{{$post->title}}</h4>
                                            </a>
                                            <p>{{$post->excerpt}}</p>
                                            <a href="{{route('blog.single.page',$post->slug)}}" class="amazy_readMore_link">+ {{__('blog.Read more')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if ($posts->lastPage() > 1)
                                <div class="col-12">
                                    <x-pagination-component :items="$posts" type=""/>
                                </div>
                            @endif
                        @else
                            <div class="col-lg-12 col-md-12">
                                <div class="card h-100">
                                    <div class="single-post post-style-1 p-2">
                                    <strong>{{ __('blog.no_post_found') }}</strong>
                                    </div><!-- single-post -->
                                </div><!-- card -->
                            </div><!-- col-lg-4 col-md-6 -->
                        @endif
                        
                    </div>
                </div>
                @include('frontend.amazy.pages.blog.partials._sidebar')
            </div>
        </div>
    </div>
@endsection