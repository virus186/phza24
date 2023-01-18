<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-4">
                <h5>{{ __('ticket.all_submmited_ticket') }}</h5>
            </div>
            <div class="col-lg-8 d-flex justify-content-end">
                <select name="status" id="status_by">
                    <option value="0" @if (isset($status) && $status == "0") selected @endif>{{ __('ticket.all_ticket') }}</option>
                    @foreach($statuses as $key => $item)
                    <option value="{{$item->id}}" @if (isset($status) && $status == $item->id) selected @endif>{{$item->name}}</option>
                    @endforeach

                </select>
                <a href="{{ route('frontend.support-ticket.create') }}" class="add_new_btn text-nowrap"> {{ __('common.add_new') }}</a>
            </div>
        </div>

    </div>
    <div class="col-lg-12 user_list_div">
        <div class="user-list table-responsive">
            <table class="table table-hover tablesaw">
                <thead>
                    <tr>
                        <th>{{ __('common.sl') }}</th>
                        <th>{{ __('ticket.ticket_id') }}</th>
                        <th>{{ __('ticket.subject') }}</th>
                        <th>{{ __('ticket.priority') }}</th>
                        <th>{{ __('ticket.last_update') }}</th>
                        <th>{{ __('common.action') }}</th>
                    </tr>
                </thead>
                <tbody class="cart_table_body">
                    @foreach ($tickets as $key => $ticket)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $ticket->reference_no }}</td>
                            <td>
                                {{ $ticket->subject }}
                            </td>
                            <td>{{ @$ticket->priority->name }}</td>
                            
                            <td>{{ date_format($ticket->updated_at, 'F j, Y ') }} at
                                {{ date_format($ticket->updated_at, 'g:i a') }}</td>

                            <td><a target="_blank" class="add_new_btn"
                                    href="{{ route('frontend.support-ticket.show', $ticket->reference_no) }}">{{__('common.view')}}</a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            @if(count($tickets) < 1)
                <p class="empty_p">{{ __('common.empty_list') }}.</p>
            @endif
        </div>
    </div>
    @if(count($tickets) > 0)
    <div class="col-lg-12">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <x-pagination-component :items="$tickets" type=""/>
            </div>
        </div>
    </div>
    @endif
</div>




