<div class="table_border_whiteBox mb_30">
    <div>
        <h3 class="font_20 f_w_700 mb-0 ">{{__('amazy.Follow seller History')}}</h3>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table amazy_table style4 mb-0">
            <thead>
                <tr> 
                    <th class="font_14 f_w_700 priamry_text text-nowrap" scope="col">{{__('common.sl')}}</th>
                    <th class="font_14 f_w_700 priamry_text text-nowrap" scope="col">{{__('common.name')}}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0 text-nowrap" scope="col">{{__('product.total_product')}}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0 text-nowrap" scope="col">{{__('customer_panel.total_followers')}}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0 text-nowrap" scope="col">{{__('common.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($followSellers as $followSeller)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td><img src="{{$followSeller->seller->photo?showImage($followSeller->seller->photo):showImage('frontend/default/img/avatar.jpg')}}" alt="{{$followSeller->seller->name}}" title="{{$followSeller->seller->name}}" height="75" wight="75"> <hr>{{$followSeller->seller->name}}</td>
                        <td>{{@$followSeller->seller->seller_products->count()??0}}</td>
                        <td>{{$followSeller->seller->countFollow()}}</td>
                        <td>
                            <button type="btn" class="amaz_primary_btn style3 text-uppercase unfollow_btn" data-seller="{{encrypt($followSeller->seller_id)}}">{{__('amazy.Unfollow')}}</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($followSellers->lastPage() > 1)
        <x-pagination-component :items="$followSellers" type=""/>
    @elseif(!$followSellers->count())
        <p class="empty_p">{{ __('common.empty_list') }}.</p>
    @endif
</div>


