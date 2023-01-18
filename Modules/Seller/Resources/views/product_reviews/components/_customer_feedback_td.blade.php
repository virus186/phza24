<p><strong>{{ $review->review }}</strong></p>
<div class="d-flex mt-10">
    <div class="product_img_div">
        <img class="product_img mr-0" src="{{ showImage($review->product->product->thumbnail_image_source) }}" alt="">
    </div>
    <p class="ml-3">{{ $review->product->product->product_name }}</p>
</div>
