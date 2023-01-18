{{-- @isset ($brandList)
    @if (count($brandList) > 0)
        <div class="single_category">
            <div class="category_tittle">
                <h4>{{ __('common.brand') }}</h4>
            </div>
            <div class="single_category_option">
                <nav>
                    <ul>
                        @foreach($brandList as $key => $brand)
                        <li class='sub-menu'><a class="getProductByChoice" data-id="brand" data-value="{{ $brand->id }}" >{{$brand->name}}<div class='ti-plus right'></div></a></li>
                        @endforeach

                    </ul>
                </nav>
            </div>
        </div>
    @endif
@endisset --}}
@isset ($brandList)
    @if (count($brandList) > 0)
        <div class="single_pro_categry">
            <h4 class="font_18 f_w_700">
            {{__('common.filter_by_brands')}}
            </h4>
            <ul class="Check_sidebar mb_35">
                @foreach($brandList as $key => $brand)
                    <li>
                        <label class="primary_checkbox d-flex">
                            <input type="checkbox" class="getProductByChoice" data-id="brand" data-value="{{ $brand->id }}">
                            <span class="checkmark mr_10"></span>
                            <span class="label_name">{{$brand->name}}</span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endisset