<div class="col-xl-3 col-lg-3">
    <div class="blog_sidebar_wrap mb_30">
        <form action="{{url('/blog')}}" name="sidebar_search">
            <div class="input-group  theme_search_field4 w-100 mb_20 style2">
                <div class="input-group-prepend">
                    <button class="btn" type="button"> <i class="ti-search"></i> </button>
                </div>
                <input type="text" class="form-control search_input" id="inlineFormInputGroup" placeholder="{{ __('blog.search_posts') }}" value="{{request()->get('query')}}" name="query" required>
            </div>
        </form>
        <div class="blog_sidebar_box mb_20">
            <h4 class="font_18 f_w_700 mb_10">
                {{ __('common.category') }}
            </h4>
            <div class="home6_border w-100 mb_20"></div>
            <ul class="Check_sidebar mb-0">
                @foreach($categoryPost as $post)
                    <li>
                        <label class="primary_checkbox d-flex">
                            <a href="{{route('blog.category.posts',$post->slug)}}" class="label_name f_w_400">{{$post->name}} <span>({{$post->active_post_count}})</span></a>
                            
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="blog_sidebar_box mb_15">
            <h4 class="font_18 f_w_700 mb_10">
                {{ __('blog.popular_posts') }}
            </h4>
            <div class="home6_border w-100 mb_20"></div>
            <div class="news_lists">
                @foreach($popularPost as $post)
                    <div class="single_newslist">
                        <a href="{{route('blog.single.page',$post->slug)}}">
                            <h4>{{textLimit($post->title,50)}}</h4>
                        </a>
                        <p>{{date(app('general_setting')->dateFormat->format, strtotime($post->published_at))}}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="blog_sidebar_box mb_30 p-0 border-0">
            <h4 class="font_18 f_w_700 mb_10">
                {{__('blog.Keywords')}}
            </h4>
            <div class="home6_border w-100 mb_20"></div>
            <div class="keyword_lists d-flex align-items-center flex-wrap gap_10">
                @foreach($keywords as $tag)
                    <a href="{{url('/blog').'?tag='.$tag->name}}">{{$tag->name}}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>