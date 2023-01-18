
<div class="amaz_cta_box {{$ads->status == 0?'d-none':''}}">
    <div class="row justify-content-center ">
        <a href="{{@$ads->description}}" class="col-xl-12 random_ads_div">
            <img class="img-fluid" src="{{showImage(@$ads->image)}}" alt="{{@$ads->description}}" title="{{@$ads->description}}">
        </a>
    </div>
</div>
        