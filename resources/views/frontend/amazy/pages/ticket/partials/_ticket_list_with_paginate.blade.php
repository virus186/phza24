<div class="dashboard_white_box_header d-flex align-items-center gap_20 flex-wrap mb_20">
    <h4 class="font_24 f_w_700 flex-fill m-0">{{ __('ticket.all_submmited_ticket') }} </h4>
    <div class="wish_selects d-flex align-items-center gap_10 flex-wrap">
        <select class="amaz_select4 style2" name="status" id="status_by">
            <option value="0" @if (isset($status) && $status == "0") selected @endif>{{ __('ticket.all_ticket') }}</option>
            @foreach($statuses as $key => $item)
            <option value="{{$item->id}}" @if (isset($status) && $status == $item->id) selected @endif>{{$item->name}}</option>
            @endforeach
        </select>
        <a href="{{ route('frontend.support-ticket.create') }}" class="amaz_primary_btn style7 text-nowrap radius_3px">+ {{ __('common.add_new') }}</a>
    </div>
</div>
<div class="dashboard_white_box_body">
    <div class="table-responsive mb_30">
        <table class="table amazy_table style5 mb-0">
            <thead>
                <tr>
                    <th class="font_14 f_w_700 priamry_text" scope="col">{{ __('common.sl') }}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('ticket.ticket_id') }}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('ticket.subject') }}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('ticket.priority') }}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('ticket.last_update') }}</th>
                    <th class="font_14 f_w_700 priamry_text border-start-0 border-end-0" scope="col">{{ __('common.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $key => $ticket)
                    <tr>
                        <td>
                            <span class="font_14 f_w_500 mute_text">{{ $key + 1 }}</span>
                        </td>
                        <td>
                            <span class="font_14 f_w_500 mute_text">{{ $ticket->reference_no }}</span>
                        </td>
                        <td>
                            <span class="font_14 f_w_500 mute_text">{{ $ticket->subject }}</span>
                        </td>
                        <td>
                        <a href="#" class="table_badge_btn style4 text-nowrap">{{ @$ticket->priority->name }}</a>
                        </td>
                        <td>
                            <span class="font_14 f_w_500 mute_text text-nowrap">{{ date_format($ticket->updated_at, 'F j, Y ') }} {{ __('ticket.at') }}
                                {{ date_format($ticket->updated_at, 'g:i a') }}</span>
                        </td>
                        <td>
                        <a href="{{ route('frontend.support-ticket.show', $ticket->reference_no) }}" class="amaz_badge_btn4 text-nowrap text-capitalize text-center">{{__('common.view')}}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($tickets->lastPage() > 1)
        <x-pagination-component :items="$tickets" type=""/>
    @elseif(!$tickets->count())
        <p class="empty_p">{{ __('common.empty_list') }}.</p>
    @endif
</div>