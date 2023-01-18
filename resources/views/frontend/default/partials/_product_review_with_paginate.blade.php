<div class="item_description">
    <h4>{{__('defaultTheme.product_reviews')}}</h4>

    @php
        $total_number_of_item_per_page = $reviews->perPage();
        $total_number_of_items = ($reviews->total() > 0) ? $reviews->total() : 0;
        $total_number_of_pages = $total_number_of_items / $total_number_of_item_per_page;
        $reminder = $total_number_of_items % $total_number_of_item_per_page;
        if ($reminder > 0) {
            $total_number_of_pages += 1;
        }
        $current_page = $reviews->currentPage();
        $previous_page = $reviews->currentPage() - 1;
        if($current_page == $reviews->lastPage()){
            $show_end = $total_number_of_items;
        }else{
            $show_end = $total_number_of_item_per_page * $current_page;
        }


        $show_start = 0;
        if($total_number_of_items > 0){
            $show_start = ($total_number_of_item_per_page * $previous_page) + 1;
        }


    @endphp

    @if(count($reviews) > 0)
        @foreach(@$reviews as $key => $review)

        <div class="client_review media_style">
            <div class="single_product_img">
                @if($review->is_anonymous == 1 || @$review->customer->avatar == null)
                <img src="{{showImage('frontend/default/img/avatar.jpg')}}" alt="#" />
                @else
                <img src="{{showImage(@$review->customer->avatar)}}" alt="#" />
                @endif
            </div>
            <div class="single_product_text">
                <div class="review_icon">
                    <i class="fas fa-star {{$review->rating >= 1?'':'text-gray'}}"></i>
                    <i class="fas fa-star {{$review->rating >= 2?'':'text-gray'}}"></i>
                    <i class="fas fa-star {{$review->rating >= 3?'':'text-gray'}}"></i>
                    <i class="fas fa-star {{$review->rating >= 4?'':'text-gray'}}"></i>
                    <i class="fas fa-star {{$review->rating == 5?'':'text-gray'}}"></i>
                </div>
                <h3>{{$review->is_anonymous==1?'Unknown Name':@$review->customer->first_name.' '.@$review->customer->last_name}} <span>{{date('dS M, Y',strtotime($review->created_at))}}</span></h3>
                <p>
                    {{$review->review}}
                </p>
                <div class="single_product_img">

                    @foreach($review->images as $key => $image)
                        <img class="review_img" src="{{showImage($image->image)}}" alt="">
                    @endforeach
                </div>
            </div>


        </div>
        @if(@$review->reply)
        <div class="replyDiv">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="client_review media_style">
                        <div class="single_product_img">
                            <img class="seller_avatar" src="{{showImage(@$review->seller->avatar?$review->seller->avatar:'frontend/default/img/avatar.jpg')}}" alt="" />
                        </div>
                        <div class="single_product_text seller_text_div">
                            <h3>{{@$review->seller->first_name}}<span>{{date('dS M, Y',strtotime($review->reply->created_at))}}</span></h3>
                            <p>
                                {{@$review->reply->review}}
                            </p>

                        </div>


                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    @else
        <p>{{ __('defaultTheme.no_review_found') }}</p>
    @endif

</div>

@if($reviews->lastPage() > 1)
    <div class="col-lg-12">
        <x-pagination-component :items="$reviews" type=""/>
    </div>
@endif
