<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <table class="table modal-table">
                <tbody>
                <tr>
                    <th>{{__('Name')}}</th>
                    <td>{{ $contact->name }}</td>
                </tr>
                <tr>
                    <th>{{__('Account')}}</th>
                    <td>{{ !empty($contact->assign_account)?$contact->assign_account->name:'-'}}</td>
                </tr>
                <tr>
                    <th>{{__('Email')}}</th>
                    <td>{{ $contact->email }}</td>
                </tr>
                <tr>
                    <th>{{__('Phone')}}</th>
                    <td>{{ $contact->phone }}</td>
                </tr>
                <tr>
                    <th>{{__('Billing Address')}}</th>
                    <td>{{ $contact->contact_address }}</td>
                </tr>
                <tr>
                    <th>{{__('City')}}</th>
                    <td>{{ $contact->contact_city }}</td>
                </tr>
                <tr>
                    <th>{{__('State')}}</th>
                    <td>{{ $contact->contact_state }}</td>
                </tr>
                <tr>
                    <th>{{__('Country')}}</th>
                    <td>{{ $contact->contact_country }}</td>
                </tr>
                <tr>
                    <th>{{__('Assigned User')}}</th>
                    <td>{{ !empty($contact->assign_user)?$contact->assign_user->name:'-'}}</td>
                </tr>
                <tr>
                    <th>{{__('Created')}}</th>
                    <td>{{company_date_formate($contact->created_at)}}</td>
                </tr>
                @if (!empty($customFields) && count($contact->customField) > 0)
                        @foreach ($customFields as $field)
                            <tr>
                                <th>{{ $field->name }}</th>
                                <td>
                                    @if ($field->type == 'attachment')
                                        <a href="{{ get_file($contact->customField[$field->id]) }}" target="_blank">
                                            <img src="{{ get_file($contact->customField[$field->id]) }}"
                                                class="wid-75 rounded me-3">
                                        </a>
                                    @else
                                        {{ !empty($contact->customField[$field->id]) ? $contact->customField[$field->id] : '-' }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
        <div class="w-100 text-end pr-2">
        @permission('contact edit')
        <div class="action-btn bg-info ms-2">
            <a href="{{ route('contact.edit',$contact->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center text-white " data-bs-toggle="tooltip"data-title="{{__('Contact Edit')}}" title="{{__('Edit')}}"><i class="ti ti-pencil"></i>
            </a>
        </div>
        @endpermission
    </div>
</div>
