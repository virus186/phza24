@php
    $headerSliderSection = $headers->where('type','slider')->first();
@endphp
<div class="home_banner bannerUi_active owl-carousel {{$headerSliderSection->is_enable == 0?'d-none':''}}">
    @php
        $sliders = $headerSliderSection->sliders();
    @endphp
    @if(count($sliders) > 0)
        @foreach($sliders as $key => $slider)
            <a class="banner_img" href="
                @if($slider->data_type == 'url')
                    {{$slider->url}}
                @elseif($slider->data_type == 'product')
                    {{singleProductURL(@$slider->product->seller->slug, @$slider->product->slug)}}
                @elseif($slider->data_type == 'category')
                    {{route('frontend.category-product',['slug' => @$slider->category->slug, 'item' =>'category'])}}
                @elseif($slider->data_type == 'brand')
                    {{route('frontend.category-product',['slug' => @$slider->brand->slug, 'item' =>'brand'])}}
                @elseif($slider->data_type == 'tag')
                    {{route('frontend.category-product',['slug' => @$slider->tag->name, 'item' =>'tag'])}}
                @else
                    {{url('/category')}}
                @endif
                " {{$slider->is_newtab == 1?'target="_blank"':''}}>
                {{-- <img class="img-fluid " data-src="{{showImage($slider->slider_image)}}" src="{{showImage(themeDefaultImg())}}" alt=""> --}}
                <img class="img-fluid" src="{{showImage($slider->slider_image)}}" alt="{{@$slider->name}}" title="{{@$slider->name}}">
            </a>
        @endforeach
    @endif
</div>