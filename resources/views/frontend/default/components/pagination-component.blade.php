@php
    $total_number_of_item_per_page = $items->perPage();
    $total_number_of_items = ($items->total() > 0) ? $items->total() : 0;
    $total_number_of_pages = $total_number_of_items / $total_number_of_item_per_page;
    $reminder = $total_number_of_items % $total_number_of_item_per_page;
    if ($reminder > 0) {
        $total_number_of_pages += 1;
    }

    $current_page = $items->currentPage();
    $previous_page = $items->currentPage() - 1;
    if($current_page == $items->lastPage()){
    $show_end = $total_number_of_items;
    }else{
    $show_end = $total_number_of_item_per_page * $current_page;
    }

    $show_start = 0;
    if($total_number_of_items > 0){
        $show_start = ($total_number_of_item_per_page * $previous_page) + 1;
    }
    if(!isset($request_type)){
        $request_type = request()->toRecievedList;
    }
@endphp
<div class="pagination_part">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item"><a class="page-link" href="{{ $items->previousPageUrl() }}"> <i class="ti-arrow-left"></i> </a></li>
            @for ($i=1; $i <= $total_number_of_pages; $i++)
                @if (($items->currentPage() + 2) == $i)
                    <li class="page-item"><a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a></li>
                @endif
                @if (($items->currentPage() + 1) == $i)
                    <li class="page-item"><a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a></li>
                @endif
                @if ($items->currentPage() == $i)
                    <li class="page-item @if ($request_type == $i || $request_type == null) active @endif"><a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a></li>
                @endif
                @if (($items->currentPage() - 1) == $i)
                    <li class="page-item"><a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a></li>
                @endif
                @if (($items->currentPage() - 2) == $i)
                    <li class="page-item"><a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a></li>
                @endif
            @endfor
            <li class="page-item"><a class="page-link" href="{{ $items->nextPageUrl() }}"> <i class="ti-arrow-right"></i> </a></li>
        </ul>
    </nav>
</div>