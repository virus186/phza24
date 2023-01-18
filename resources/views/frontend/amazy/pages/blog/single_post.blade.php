@extends('frontend.amazy.layouts.app')

@section('title')
    {{ __('blog.blog') }}
@endsection

@section('content')
    <!-- blog_details_area::start  -->
    <div class="blog_details_area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-7">
                    <div class="blog_details_inner m-0">
                        <div class="blog_details_banner">
                            @if(isset($post->image_url))
                                <img class="img-fluid w-100" src="{{showImage($post->image_url)}}" alt="{{$post->title}}" title="{{$post->title}}">
                            @endif
                        </div>
                        <div class="blog_post_date d-flex align-items-center"> 
                            @foreach($post->categories as $category)
                                <span>{{$category->name}}</span> 
                            @endforeach
                            <p>{{date(app('general_setting')->dateFormat->format, strtotime($post->published_at))}}</p> 
                        </div>
                        <h3>{{$post->title}}</h3>
                        <div class="details_info">
                            <p id="laraberg">@php echo $post->content; @endphp</p>
                        </div>
                    </div>
                    <div class="blog_details_tags d-flex align-items-center gap_10">
                        <h4 class="font_16 f_w_700 m-0">{{ __('appearance.tags') }}:</h4>
                        <p class="font_14 f_w_500 m-0">
                            @foreach($post->tags as $tag)
                                {{$tag->name}}
                                @if(!$loop->last), @endif
                            @endforeach
                        </p>
                    </div>
                    <div class="blog_details_tags d-flex align-items-center gap_10">
                        @guest
                            <div class="float-left">
                                <button type="button" class="btn btn-sm  btn-info guest_btn_class">

                                    <span class="glyphicon glyphicon-thumbs-up"></span> {{__('blog.Like')}}
                                    <div class="d-inline-block">{{ $likePost->like_count }}</div>
                                </button>
                            </div>
                        @else
                            <div class="float-left">
                                <button type="button" class="btn btn-sm likebtn {{ $likecheck ? '' : 'btn-info' }}"
                                    pid="{{ $post->id }}">

                                    <span class="glyphicon glyphicon-thumbs-up"></span> {{__('blog.Like')}}
                                    <div id="like-bs3" class="d-inline-block">{{ $likePost->like_count }}</div>
                                </button>
                            </div>
                        @endguest
                    </div>

                    <div class="blog_reviews">
                        <h3 class="font_30 f_w_700 mb_35 lh-1">{{count($post->comments)}} {{__('blog.comments')}}</h3>
                        <div class="blog_reviews_inner">
                            @foreach($post->comments as $comment)
                                <div class="single_reviews flex-column">
                                    <div class="single_reviews">
                                        <div class="thumb">
                                            @if(@$comment->commentUser->avatar == null)
                                                {{\Illuminate\Support\Str::limit(@$comment->commentUser->first_name,1,$end='')}}{{\Illuminate\Support\Str::limit(@$comment->commentUser->last_name,1,$end='')}}
                                            @else
                                                <img src="{{showImage(@$comment->commentUser->avatar)}}" alt="{{$comment->commentUser->fullname}}" title="{{$comment->commentUser->fullname}}">
                                            @endif
                                        </div>
                                        <div class="review_content">
                                            <div class="review_content_head d-flex justify-content-between align-items-start flex-wrap">
                                                <div class="review_content_head_left">
                                                    <h4 class="f_w_700 font_20" >{{$comment->commentUser->fullname}}</h4>
                                                    <div class="rated_customer d-flex align-items-center">
                                                        <span>{{$comment->created_at->diffForHumans()}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p>{{$comment->comment}}</p>
                                            @auth
                                                <button class="amaz_primary_btn style3 text-nowrap mt-2 mb-2 reply" cid="{{ $comment->id }}" post_id="{{ $post->id }}" token="{{ csrf_token() }}">{{__('blog.Reply')}}</button>
                                                <div class="reply-form d-none">
                                                    
                                                </div>
                                            @endauth
                                        </div>
                                    </div>
                                    @if(count($comment->replay) > 0)
                                        @foreach ($comment->replay as $replay)
                                            <div class="single_reviews">
                                                <div class="thumb">
                                                    @if(@$replay->replayUser->avatar == null)
                                                        {{\Illuminate\Support\Str::limit(@$replay->replayUser->first_name,1,$end='')}}{{\Illuminate\Support\Str::limit(@$replay->replayUser->last_name,1,$end='')}}
                                                    @else
                                                        <img src="{{showImage(@$replay->replayUser->avatar)}}" alt="{{ $replay->replayUser->fullname }}" title="{{ $replay->replayUser->fullname }}">
                                                    @endif
                                                </div>
                                                <div class="review_content">
                                                    <div class="review_content_head d-flex justify-content-between align-items-start flex-wrap">
                                                        <div class="review_content_head_left">
                                                            <h4 class="f_w_700 font_20" >{{ $replay->replayUser->fullname }}</h4>
                                                            <div class="rated_customer d-flex align-items-center">
                                                                <span>{{$replay->created_at->diffForHumans()}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p>@php echo $replay->replay; @endphp</p>
                                                    @auth
                                                        <button type="button" class="amaz_primary_btn style3 text-nowrap mt-2 mb-2 rreply" cid="{{ $comment->id }}"
                                                            post_id="{{ $post->id }}"
                                                            replay_id="{{ $replay->id }}"
                                                            token="{{ csrf_token() }}">{{__('blog.Reply')}}</button>
                                                        <div class="rreply-form d-none"></div>
                                                    @endauth
                                                </div>
                                            </div>
                                            @if (count($replay->replayReplay) > 0)
                                                @foreach ($replay->replayReplay as $rreplay)
                                                    <div class="single_reviews">
                                                        <div class="single_reviews">
                                                            <div class="thumb">
                                                                @if(@$rreplay->replayUser->avatar == null)
                                                                    {{\Illuminate\Support\Str::limit(@$rreplay->replayUser->first_name,1,$end='')}}{{\Illuminate\Support\Str::limit(@$rreplay->replayUser->last_name,1,$end='')}}
                                                                @else
                                                                    <img src="{{showImage(@$rreplay->replayUser->avatar)}}" alt="{{ $rreplay->replayUser->fullname }}" title="{{ $rreplay->replayUser->fullname }}">
                                                                @endif
                                                            </div>
                                                            <div class="review_content">
                                                                <div class="review_content_head d-flex justify-content-between align-items-start flex-wrap">
                                                                    <div class="review_content_head_left">
                                                                        <h4 class="f_w_700 font_20" >{{ $rreplay->replayUser->fullname }}</h4>
                                                                        <div class="rated_customer d-flex align-items-center">
                                                                            <span>{{$rreplay->created_at->diffForHumans()}}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p>@php echo $rreplay->replay; @endphp</p>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if ($post->is_commentable == true)
                        @guest
                            <div class="blog_login_required">
                                <h4>{{ __('blog.for_post_a_new_comment_you_need_to_login_first') }} 
                                    <a href="{{ route('login') }}">{{ __('defaultTheme.login') }}</a>
                                </h4>
                            </div>
                        @else
                            <div class="blog_reply_box">
                                <h3 class="font_30 f_w_700 mb_40 lh-1">{{__('blog.Leave a Reply')}}</h3>
                                <form action="{{ route('blog.comment.store', $post->id) }}" name="comment_form"
                                    method="POST" id="comment_form">
                                    @csrf
                                    <div class="row">
                                        @if ($errors->has('comment'))
                                            <span class="alert alert-danger" role="alert">
                                                <strong>{{ $errors->first('comment') }}</strong>
                                            </span>
                                        @endif
                                        <div class="col-12">
                                            <label class="primary_label2">{{__('blog.Comments')}}<span>*</span></label>
                                            <textarea  name="comment" id="comment" placeholder="{{ __('common.write_some_messages') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.write_some_messages') }}'" class="primary_textarea3 radius_5px mb_15" required=""></textarea>
                                        </div>
                                        <div class="col-12">
                                            <button class="amaz_primary_btn min_220 style2 text-center   text-uppercase  text-center">{{ __('blog.post_comment') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endguest
                    @endif
                </div>
                @include('frontend.amazy.pages.blog.partials._sidebar')
            </div>
        </div>
    </div>
    <!-- blog_details_area::end  -->
@endsection

@push('scripts')
    <script type="text/javascript">

        (function($){
            "use strict";
            $(document).ready(function(){
                $(document).on("click", ".reply",function(){

                    var well = $(this).closest('.review_content');
                    var cid = $(this).attr("cid");
                    var pid = $(this).attr('post_id');
                    var token = $(this).attr('token');
                    var form = `
                        <div class="card card-body rounded-0">
                            <div class="row">
                                <form action="{{route('blog.replay')}}">
                                    <div class="col-12">
                                        <textarea  name="reply" placeholder="Write description here…" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Write description here… here…'" class="primary_textarea4 rounded-0 mb_15" required=""></textarea>
                                    </div>
                                    <input type="hidden" name="_token" value="${token}">
                                    <input type="hidden" name="comment_id" value="${cid}">
                                    <input type="hidden" name="post_id" value="${pid}">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="amaz_primary_btn style2 rounded-0  text-uppercase  text-center min_200">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    `;
                    well.find(".reply-form").html(form);
                    well.find(".reply-form").toggleClass('d-none');

                });


                //replay replay
                $(document).on("click", ".rreply",function(){
                    var well = $(this).closest('.review_content');
                    var cid = $(this).attr("cid");
                    var pid = $(this).attr('post_id');
                    var token = $(this).attr('token');
                    var replay_id =$(this).attr('replay_id');
                    var form = `
                        <div class="card card-body rounded-0">
                            <div class="row">
                                <form action="{{route('blog.replay')}}">
                                    <div class="col-12">
                                        <textarea  name="reply" placeholder="Write description here…" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Write description here… here…'" class="primary_textarea4 rounded-0 mb_15" required=""></textarea>
                                    </div>
                                    <input type="hidden" name="_token" value="${token}">
                                    <input type="hidden" name="comment_id" value="${cid}">
                                    <input type="hidden" name="post_id" value="${pid}">
                                    <input type="hidden" name="replay_id" value="${replay_id}">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="amaz_primary_btn style2 rounded-0  text-uppercase  text-center min_200">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    `;
                    well.find(".rreply-form").html(form);
                    well.find(".rreply-form").toggleClass('d-none');

                });


                $(document).on('click','.likebtn',function(){

                    var formData= new FormData();
                    var pid = $(this).attr('pid');
                    var c = $('#like-bs3').html();


                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('pid', pid);
                    $.ajax({
                        url: "{{ route('blog.post.like') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            if (response.dislike) {
                                toastr.success(response.dislike)
                                $('#like-bs3').html(parseInt(c)-1);
                                $('.likebtn').addClass("btn-info");
                            }
                            else if (response.like) {
                                toastr.success(response.like)
                                $('#like-bs3').html(parseInt(c)+1);
                                $('.likebtn').removeClass("btn-info");
                            }


                        },
                        error: function(response) {
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                        }
                    });

                });

                $(document).on('click', '.guest_btn_class', function(event){
                    event.preventDefault();
                    toastr.info('To add favorite list. You need to login first.','Info',{closeButton: true,progressBar: true,});
                });

            });
        })(jQuery);

    </script>
@endpush