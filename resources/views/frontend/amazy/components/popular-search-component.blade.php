<div class="amaz_popular_search section_spacing">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section__title d-flex align-items-center gap-3 mb_30">
                    <h3 class="m-0 flex-fill">{{__('common.popular_searches')}}</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if($search_items->count())
                    <div class="popular_search_lists mb_30">
                        @foreach($search_items as $item)
                            <a class="popular_search_list" href="{{url('/').'/category'.'/'.$item->keyword.'?item=search&category=0'}}">{{$item->keyword}}</a>
                        @endforeach
                    </div>
                @else
                    <p>{{__('amazy.no_search_keyword_found')}}</p>
                @endif
            </div>
        </div>
    </div>
</div>