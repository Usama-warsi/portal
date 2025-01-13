<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <table class="table modal-table">
                <tbody>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <td>{{ $salesaccount->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Website') }}</th>
                        <td>{{ $salesaccount->website }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Email') }}</th>
                        <td>{{ $salesaccount->email }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Phone') }}</th>
                        <td>{{ $salesaccount->phone }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Billing Address') }}</th>
                        <td>{{ $salesaccount->billing_address }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('City') }}</th>
                        <td>{{ $salesaccount->billing_city }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Country') }}</th>
                        <td>{{ $salesaccount->billing_country }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Type') }}</th>
                        <td>{{ !empty($salesaccount->accountType) ? $salesaccount->accountType->name : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Industry') }}</th>
                        <td>{{ !empty($salesaccount->accountIndustry) ? $salesaccount->accountIndustry->name : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Assigned User') }}</th>
                        <td>{{ !empty($salesaccount->assign_user) ? $salesaccount->assign_user->name : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Created') }}</th>
                        <td>{{ company_date_formate($salesaccount->created_at) }}</td>
                    </tr>
                    @if (!empty($customFields) && count($salesaccount->customField) > 0)
                        @foreach ($customFields as $field)
                            <tr>
                                <th>{{ $field->name }}</th>
                                <td>
                                    @if ($field->type == 'attachment')
                                        <a href="{{ get_file($salesaccount->customField[$field->id]) }}" target="_blank">
                                            <img src="{{ get_file($salesaccount->customField[$field->id]) }}"
                                                class="wid-75 rounded me-3">
                                        </a>
                                    @else
                                        {{ !empty($salesaccount->customField[$field->id]) ? $salesaccount->customField[$field->id] : '-' }}
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
        @permission('salesaccount edit')
            <div class="action-btn bg-info ms-2">
                <a href="{{ route('salesaccount.edit', $salesaccount->id) }}"
                    data-bs-toggle="tooltip"class="mx-3 btn btn-sm d-inline-flex align-items-center text-white "
                    data-title="{{ __('Account Edit') }}" title="{{ __('Edit') }}"><i class="ti ti-pencil"></i>
                </a>
            </div>
        @endpermission
    </div>
</div>
