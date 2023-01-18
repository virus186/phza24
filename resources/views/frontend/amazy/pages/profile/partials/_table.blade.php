<thead>
    <tr>
    <th class="font_14 f_w_700 text-nowrap" scope="col">{{__('common.full_name')}}</th>
    <th class="font_14 f_w_700 border-start-0 border-end-0" scope="col">{{__('common.address')}}</th>
    <th class="font_14 f_w_700 border-start-0 border-end-0" scope="col">{{__('common.region')}}</th>
    <th class="font_14 f_w_700 border-start-0 border-end-0" scope="col">{{__('common.e-mail')}}</th>
    <th class="font_14 f_w_700 text-nowrap" scope="col">{{__('common.phone_number')}}</th>
    <th class="font_14 f_w_700 text-nowrap" scope="col"></th>
    </tr>
</thead>
<tbody>
    @foreach ($addressList as $address)
    <tr>
        <td>
            <span class="font_14 f_w_400 mute_text text-nowrap">{{ $address->name }}</span>
        </td>
        <td>
            <span class="font_14 f_w_500 mute_text">{{ $address->address }}</span>
        </td>
        <td>
            <span class="font_14 f_w_400 mute_text text-nowrap">{{@$address->getCity->name.', '.@$address->getState->name.', '.@$address->getCountry->name}}</span>
        </td>
        <td>
            <span class="font_14 f_w_400 mute_text text-nowrap">{{ $address->email }}</span>
        </td>
        <td>
            <span class="font_14 f_w_400 mute_text text-nowrap" >{{ $address->phone }}</span>
        </td>
        <td>
            <button class="amazy_status_btn edit_address" data-id="{{ $address->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15">
                    <g  data-name="edit (1)" transform="translate(0 -0.004)">
                        <g  data-name="Group 1742" transform="translate(0 1.074)">
                        <g  data-name="Group 1741">
                            <path  data-name="Path 3050" d="M12.324,40.566a.536.536,0,0,0-.536.536V46.46a.536.536,0,0,1-.536.536H1.607a.536.536,0,0,1-.536-.536V35.744a.536.536,0,0,1,.536-.536h6.43a.536.536,0,1,0,0-1.072H1.607A1.607,1.607,0,0,0,0,35.744V46.46a1.607,1.607,0,0,0,1.607,1.607h9.645A1.607,1.607,0,0,0,12.86,46.46V41.1A.536.536,0,0,0,12.324,40.566Z" transform="translate(0 -34.137)" fill="#fd4949"/>
                        </g>
                        </g>
                        <g  data-name="Group 1744" transform="translate(3.229 0.004)">
                        <g  data-name="Group 1743" transform="translate(0 0)">
                            <path  data-name="Path 3051" d="M113.58.6a2.048,2.048,0,0,0-2.9,0l-7.048,7.047a.541.541,0,0,0-.129.209l-1.07,3.21a.535.535,0,0,0,.507.7.544.544,0,0,0,.169-.027l3.21-1.07a.535.535,0,0,0,.209-.129L113.58,3.5A2.048,2.048,0,0,0,113.58.6Zm-.757,2.141L105.868,9.7l-2.078.694.692-2.076,6.959-6.956a.978.978,0,1,1,1.384,1.382Z" transform="translate(-102.409 -0.004)" fill="#fd4949"/>
                        </g>
                        </g>
                    </g>
                </svg>
            </button>
            @if(!$address->is_billing_default && !$address->is_shipping_default)
                <button class="amazy_status_btn delete_address_btn mt_10" data-id="{{ $address->id }}">
                    <i class="ti-trash"></i>
                </button>
            @endif
        </td>
    </tr>
    @endforeach
</tbody>