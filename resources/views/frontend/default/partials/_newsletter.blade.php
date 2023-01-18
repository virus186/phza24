<section class="project_estimate section_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="estimate_part">
                    @foreach ($FeatureList as $key => $item)
                        <div class="single_estimate_part">
                            <i class="{{ $item->icon }}"></i>
                            <p>{{ $item->title }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <x-subscribe-component :subscribeContent="$subscribeContent"/>
        </div>
    </div>
</section>
