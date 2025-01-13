@extends('layouts.main')
@section('page-title')
    {{ __('Manage Sales Invoice') }}
@endsection
@section('title')
    {{ __('Invoice') }}
@endsection
@section('page-breadcrumb')
    {{ __('Sales Invoice') }}
@endsection
@section('page-action')
    <div>
        @stack('addButtonHook')

        <a href="{{ route('salesinvoice.grid') }}" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
        @permission('salesinvoice create')
            <a data-size="lg" data-url="{{ route('salesinvoice.create', ['invoice', 0]) }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" data-title="{{ __('Create New Sales Invoice') }}" title=" {{ __('Create') }}"
                class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th scope="col" class="sort" data-sort="id">{{ __('ID') }}</th>
                                    <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                    <th scope="col" class="sort" data-sort="budget">{{ __('Account') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Created At') }}
                                    </th>
                                    
                                    @if (Laratrust::hasPermission('salesinvoice edit') || Laratrust::hasPermission('salesinvoice delete'))
                                        <th scope="col" class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>
                                            @permission('salesinvoice edit')
                                                <a href="{{ route('salesinvoice.edit', $invoice->id) }}"
                                                    class="btn btn-outline-primary" data-title="{{ __('Quote Details') }}">
                                                    {{ Modules\Sales\Entities\SalesInvoice::invoiceNumberFormat($invoice->invoice_id) }}
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-outline-primary"
                                                    data-title="{{ __('Quote Details') }}">
                                                    {{ Modules\Sales\Entities\SalesInvoice::invoiceNumberFormat($invoice->invoice_id) }}
                                                </a>
                                            @endpermission

                                        </td>
                                        <td>
                                            <span class="budget">
                                                {{ ucfirst($invoice->name) }}

                                            </span>
                                        </td>
                                        <td>
                                            <span class="budget">
                                                {{ ucfirst(!empty($invoice->accounts) ? $invoice->accounts->name : '--') }}</span>
                                        </td>
                                        <td>
                                            <span class="budget">{{ company_date_formate($invoice->created_at) }}</span>
                                        </td>
                                      
                                        @if (Laratrust::hasPermission('salesinvoice edit') || Laratrust::hasPermission('salesinvoice delete'))
                                            <td class="text-end">
                                                @permission('salesinvoice create')
                                                    <div class="action-btn bg-secondary ms-2">
                                                        {!! Form::open([
                                                            'method' => 'get',
                                                            'route' => ['salesinvoice.duplicate', $invoice->id],
                                                            'id' => 'duplicate-form-' . $invoice->id,
                                                        ]) !!}

                                                        <a href="#"
                                                            class="mx-3 btn btn-sm align-items-center text-white show_confirm"
                                                            data-bs-toggle="tooltip" title="{{ __('Duplicate') }}"
                                                            data-toggle="tooltip" data-original-title="{{ __('Delete') }}"
                                                            data-confirm="{{ __('You want to confirm this action') }}"
                                                            data-text="{{ __('Press Yes to continue or No to go back') }}"
                                                            data-confirm-yes="document.getElementById('duplicate-form-{{ $invoice->id }}').submit();">
                                                            <i class="ti ti-copy"></i>
                                                            {!! Form::close() !!}
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('salesinvoice edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('salesinvoice.edit', $invoice->id) }}"
                                                            data-bs-toggle="tooltip" title="{{ __('Details') }}"
                                                            class="mx-3 btn btn-sm align-items-center text-white "
                                                            data-title="{{ __('Edit Invoice') }}"><i
                                                                class="ti ti-pencil"></i></a>
                                                    </div>
                                                @endpermission
                                                @permission('salesinvoice delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['salesinvoice.destroy', $invoice->id]]) !!}
                                                        <a href="#!"
                                                            class="mx-3 btn btn-sm   align-items-center text-white show_confirm"
                                                            data-bs-toggle="tooltip" title='Delete'>
                                                            <i class="ti ti-trash"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endpermission

                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#billing_data', function() {
            $("[name='shipping_address']").val($("[name='billing_address']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
        })

        $(document).on('change', 'select[name=opportunity]', function() {

            var opportunities = $(this).val();
            getaccount(opportunities);
        });

        function getaccount(opportunities_id) {
            $.ajax({
                url: '{{ route('salesinvoice.getaccount') }}',
                type: 'POST',
                data: {
                    "opportunities_id": opportunities_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#amount').val(data.opportunitie.amount);
                    $('#account_name').val(data.account.name);
                    $('#account_id').val(data.account.id);
                    $('#billing_address').val(data.account.billing_address);
                    $('#shipping_address').val(data.account.shipping_address);
                    $('#billing_city').val(data.account.billing_city);
                    $('#billing_state').val(data.account.billing_state);
                    $('#shipping_city').val(data.account.shipping_city);
                    $('#shipping_state').val(data.account.shipping_state);
                    $('#billing_country').val(data.account.billing_country);
                    $('#billing_postalcode').val(data.account.billing_postalcode);
                    $('#shipping_country').val(data.account.shipping_country);
                    $('#shipping_postalcode').val(data.account.shipping_postalcode);

                }
            });
        }
    </script>
@endpush
