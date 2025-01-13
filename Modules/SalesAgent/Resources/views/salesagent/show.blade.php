@extends('layouts.main')
@section('page-title')
    {{ __('Sales Agent - Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Sales Agent') }} , {{ __('Details') }}
@endsection
@push('css')
    <style>
        .cus-card {
            min-height: 204px;
        }
    </style>
@endpush
@section('page-action')
    <div>

    </div>
@endsection
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-4">
                </div>
                <div class="col-md-8 mt-4">
                    <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="vendor-details-tab" data-bs-toggle="pill"
                                data-bs-target="#vendor-details" type="button">{{ __('Details') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="vendor-bills-tab" data-bs-toggle="pill"
                                data-bs-target="#vendor-bills" type="button">{{ __('Programs') }}</button>
                        </li>
                        @stack('vendor_purchase_tab')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="vendor-payment-tab" data-bs-toggle="pill"
                                data-bs-target="#vendor-payment" type="button">{{ __('Purchase Orders') }}</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content" id="pills-tabContent">

                <div class="tab-pane fade active show" id="vendor-details" role="tabpanel"
                    aria-labelledby="pills-user-tab-1">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="card pb-0 customer-detail-box">
                                <div class="card-body cus-card">
                                    <h5 class="card-title">{{ __('Sales Agent Info') }}</h5>
                                    <p class="card-text mb-0">{{ $salesAgent->name }}</p>
                                    <p class="card-text mb-0">{{ $salesAgent->email }}</p>
                                    <p class="card-text mb-0">{{ $salesAgent->contact }}</p>
                                    @if (!empty($customFields) && $salesAgent->customField && count($salesAgent->customField) > 0)
                                        @foreach ($customFields as $field)
                                            <tr>
                                                <th>{{ $field->name }}</th>
                                                <td>
                                                    @if ($field->type == 'attachment' && isset($salesAgent->customField[$field->id]))
                                                        <a href="{{ get_file($salesAgent->customField[$field->id]) }}"
                                                            target="_blank">
                                                            <img src="{{ get_file($salesAgent->customField[$field->id]) }}"
                                                                class="wid-75 rounded me-3">
                                                        </a>
                                                    @else
                                                        {{ !empty($salesAgent->customField[$field->id]) ? $salesAgent->customField[$field->id] : '-' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="card pb-0 customer-detail-box">
                                <div class="card-body cus-card">
                                    <h3 class="card-title">{{ __('Billing Info') }}</h3>
                                    <p class="card-text mb-0">{{ $salesAgent->billing_name }}</p>
                                    <p class="card-text mb-0">{{ $salesAgent->billing_address }}</p>
                                    <p class="card-text mb-0">
                                        {{ $salesAgent->billing_city . ' ,' . $salesAgent->billing_state . ' ,' . $salesAgent->billing_zip }}
                                    </p>
                                    <p class="card-text mb-0">{{ $salesAgent->billing_country }}</p>
                                    <p class="card-text mb-0">{{ $salesAgent->billing_phone }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="card pb-0 customer-detail-box">
                                <div class="card-body cus-card">
                                    <h3 class="card-title">{{ __('Shipping Info') }}</h3>
                                    <p class="card-text mb-0">{{ $salesAgent->shipping_name }}</p>
                                    <p class="card-text mb-0">{{ $salesAgent->shipping_address }}</p>
                                    <p class="card-text mb-0">
                                        {{ $salesAgent->shipping_city . ' ,' . $salesAgent->shipping_state . ' ,' . $salesAgent->shipping_zip }}
                                    </p>
                                    <p class="card-text mb-0">{{ $salesAgent->shipping_country }}</p>
                                    <p class="card-text mb-0">{{ $salesAgent->shipping_phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card pb-0">
                                <div class="card-body">
                                    <h3 class="card-title">{{ __('Company Info') }}</h3>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="p-2">
                                                <p class="card-text mb-0">{{ __('Purchase Order Id') }}</p>
                                                <h6 class="report-text mb-3">
                                                    {{ Modules\SalesAgent\Entities\SalesAgent::purchaseOrderNumberFormat($salesAgent->vendor_id) }}
                                                </h6>
                                                <p class="card-text mb-0">{{ __('Total Purchase Orders') }}</p>
                                                <h6 class="report-text mb-0">{{ $totalPurchaseOrders->count() }}</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="p-2">
                                                <p class="card-text mb-0">{{ __('Date of Creation') }}</p>
                                                <h6 class="report-text mb-3">
                                                    {{ company_date_formate($salesAgent->created_at) }}</h6>
                                                <p class="card-text mb-0">{{ __('Total Purchase Orders value') }}</p>
                                                <h6 class="report-text mb-0">
                                                    {{ currency_format_with_sym($totalSalesOrdersValue) }}</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="p-2">
                                                <p class="card-text mb-0">{{ __('Total programs') }}</p>
                                                <h6 class="report-text mb-3">{{ $totalPrograms }}</h6>
                                                <p class="card-text mb-0">{{ __('Total Invoice Created') }}</p>
                                                <h6 class="report-text mb-0">{{ $totalInvoiceCreated }}</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="p-2">
                                                <p class="card-text mb-0">{{ __('Total Delivered Orders') }}</p>
                                                <h6 class="report-text mb-3">{{ $totalDeliveredOrders }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="vendor-bills" role="tabpanel" aria-labelledby="pills-user-tab-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-border-style">
                                    <div class="table-responsive">
                                        <table class="table mb-0 pc-dt-simple" id="assets">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('From') }}</th>
                                                    <th>{{ __('To') }}</th>
                                                    @if (Laratrust::hasPermission('salesagent programs show'))
                                                        <th>{{ __('Status') }}</th>
                                                    @endif
                                                    @if (Laratrust::hasPermission('salesagent programs show') ||
                                                            Laratrust::hasPermission('programs edit') ||
                                                            Laratrust::hasPermission('salesagent delete') ||
                                                            Laratrust::hasPermission('salesagent show'))
                                                        <th width="10%"> {{ __('Action') }}</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($programs as $k => $program)
                                                    <tr class="font-style">
                                                        <td><a href="{{ route('programs.show', \Crypt::encrypt($program['id'])) }}"
                                                                class="">{{ $program['name'] }}</a></td>
                                                        <td>{{ $program['from_date'] }}</td>
                                                        <td>{{ $program['to_date'] }}</td>
                                                        @if (Laratrust::hasPermission('salesagent programs show'))
                                                            <td>
                                                                @if (in_array(\Auth::user()->id, explode(',', $program->sales_agents_applicable)))
                                                                    <span
                                                                        class="badge fix_badges bg-primary  p-2 px-3 rounded bill_status">{{ __('Joined') }}</span>
                                                                @elseif(in_array(\Auth::user()->id, explode(',', $program->requests_to_join)))
                                                                    <span
                                                                        class="badge fix_badges bg-info p-2 px-3 rounded bill_status">{{ __('Requested') }}</span>
                                                                @else
                                                                    <span
                                                                        class="badge fix_badges bg-secondary p-2 px-3 rounded bill_status">{{ __('Not yet participated') }}</span>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        @if (Laratrust::hasPermission('salesagent programs show') ||
                                                                Laratrust::hasPermission('programs edit') ||
                                                                Laratrust::hasPermission('programs delete') ||
                                                                Laratrust::hasPermission('programs show'))
                                                            <td class="Action">
                                                                <span>
                                                                    @if (Laratrust::hasPermission('salesagent programs show') || Laratrust::hasPermission('programs show'))
                                                                        <div class="action-btn bg-warning ms-2">
                                                                            <a href="{{ route('programs.show', \Crypt::encrypt($program['id'])) }}"
                                                                                class="mx-3 btn btn-sm align-items-center"
                                                                                data-bs-toggle="tooltip"
                                                                                title="{{ __('View') }}">
                                                                                <i
                                                                                    class="ti ti-eye text-white text-white"></i>
                                                                            </a>
                                                                        </div>
                                                                        @if (Laratrust::hasPermission('salesagent programs show') &&
                                                                                !in_array(\Auth::user()->id, explode(',', $program->sales_agents_applicable)) &&
                                                                                !in_array(\Auth::user()->id, explode(',', $program->requests_to_join)))
                                                                            <div class="action-btn bg-primary ms-2">
                                                                                <a href="{{ route('salesagent.program.send.request', [$program['id']]) }}"
                                                                                    class="mx-3 btn btn-sm align-items-center"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="{{ __('Send Request') }}">
                                                                                    <i
                                                                                        class="ti ti-arrow-forward-up text-white text-white"></i>
                                                                                </a>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                    @permission('programs edit')
                                                                        <div class="action-btn bg-info ms-2">
                                                                            <a href="{{ route('programs.edit', $program['id']) }}"
                                                                                class="mx-3 btn btn-sm  align-items-center"
                                                                                data-size="lg" data-bs-toggle="tooltip"
                                                                                title=""
                                                                                data-title="{{ __('Edit Sales Agent') }}"
                                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                                <i class="ti ti-pencil text-white"></i>
                                                                            </a>
                                                                        </div>
                                                                    @endpermission
                                                                    @if (!empty($program['id']))
                                                                        @permission('programs delete')
                                                                            <div class="action-btn bg-danger ms-2">
                                                                                {{ Form::open(['route' => ['programs.destroy', $program['id']], 'class' => 'm-0']) }}
                                                                                @method('DELETE')
                                                                                <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                                    data-bs-toggle="tooltip" title=""
                                                                                    data-bs-original-title="Delete"
                                                                                    aria-label="Delete"
                                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                                    data-confirm-yes="delete-form-{{ $program['id'] }}"><i
                                                                                        class="ti ti-trash text-white text-white"></i></a>
                                                                                {{ Form::close() }}
                                                                            </div>
                                                                        @endpermission
                                                                    @endif
                                                                </span>
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
                </div>

                <div class="tab-pane fade" id="vendor-payment" role="tabpanel" aria-labelledby="pills-user-tab-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-border-style">
                                    <div class="table-responsive">
                                        <table class="table mb-0 pc-dt-simple" id="assets1">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Order Number') }}</th>
                                                    @if (\Auth::user()->type == 'company')
                                                        <th>{{ __('Agent') }}</th>
                                                    @endif
                                                    <th>{{ __('Order Date') }}</th>
                                                    <th>{{ __('Order Value') }}</th>
                                                    @if (\Auth::user()->type == 'company')
                                                        <th>{{ __('Invoice') }}</th>
                                                    @endif
                                                    <th>{{ __('Delivery Date') }}</th>
                                                    <th>{{ __('Delivery status') }}</th>
                                                    <th>{{ __('Order Status') }}</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($totalPurchaseOrders as $k => $order)
                                                    <tr class="font-style">
                                                        <td class="">
                                                            <a href="{{ route('salesagents.purchase.order.show', \Crypt::encrypt($order['id'])) }}"
                                                                class="btn btn-outline-primary">
                                                                {{ Modules\SalesAgent\Entities\SalesAgent::purchaseOrderNumberFormat($order['id']) }}
                                                            </a>
                                                        </td>
                                                        @if (\Auth::user()->type == 'company')
                                                            <td>{{ $order->user->name }}</td>
                                                        @endif
                                                        <td>{{ $order['order_date'] }}</td>
                                                        <td>{{ currency_format_with_sym($order->getTotal()) }}</td>
                                                        @if (\Auth::user()->type == 'company')
                                                            <td>
                                                                @if (empty($order['invoice_id']))
                                                                    <span
                                                                        class="badge fix_badges bg-secondary p-2 px-3 rounded bill_status">{{ __('Not Created Yet') }}</span>
                                                                @else
                                                                    @if (Laratrust::hasPermission('invoice show'))
                                                                        <a target="_blank"
                                                                            href="{{ route('invoice.show', \Crypt::encrypt($order['invoice_id'])) }}"
                                                                            class="text-primary">{{ App\Models\Invoice::invoiceNumberFormat($order['invoice_id']) }}</a>
                                                                    @else
                                                                        <a target="_blank" href="#"
                                                                            class="text-primary">{{ App\Models\Invoice::invoiceNumberFormat($order['invoice_id']) }}</a>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        @endif

                                                        <td>{{ $order['delivery_date'] }}</td>
                                                        <td>
                                                            @if ($order->order_status == 3)
                                                                <span
                                                                    class="badge fix_badges bg-primary  p-2 px-3 rounded bill_status">{{ __('Delivered') }}</span>
                                                            @else
                                                                <span
                                                                    class="badge fix_badges bg-secondary  p-2 px-3 rounded bill_status">{{ __('Undelivered') }}</span>
                                                            @endif

                                                        </td>
                                                        <td>
                                                            @if ($order->order_status == 0)
                                                                <span
                                                                    class="badge fix_badges bg-primary p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder[$order->order_status]) }}</span>
                                                            @elseif($order->order_status == 1)
                                                                <span
                                                                    class="badge fix_badges bg-info p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder[$order->order_status]) }}</span>
                                                            @elseif($order->order_status == 2)
                                                                <span
                                                                    class="badge fix_badges bg-secondary p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder[$order->order_status]) }}</span>
                                                            @elseif($order->order_status == 3)
                                                                <span
                                                                    class="badge fix_badges bg-primary p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder[$order->order_status]) }}</span>
                                                            @elseif($order->order_status == 4)
                                                                <span
                                                                    class="badge fix_badges bg-danger p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder[$order->order_status]) }}</span>
                                                            @endif
                                                        </td>

                                                        @if (Laratrust::hasPermission('salesagent purchase delete') || Laratrust::hasPermission('salesagent purchase show'))
                                                            <td class="Action">
                                                                <span>
                                                                    @permission('salesagent purchase show')
                                                                        <div class="action-btn bg-warning ms-2">
                                                                            <a href="{{ route('salesagents.purchase.order.show', \Crypt::encrypt($order['id'])) }}"
                                                                                class="mx-3 btn btn-sm align-items-center"
                                                                                data-bs-toggle="tooltip"
                                                                                title="{{ __('View') }}">
                                                                                <i class="ti ti-eye text-white text-white"></i>
                                                                            </a>
                                                                        </div>
                                                                    @endpermission
                                                                    @permission('salesagent purchase delete')
                                                                        <div class="action-btn bg-danger ms-2">
                                                                            {{ Form::open(['route' => ['salesagents.destroy', $order['id']], 'class' => 'm-0']) }}
                                                                            @method('DELETE')
                                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                                data-bs-toggle="tooltip" title=""
                                                                                data-bs-original-title="Delete"
                                                                                aria-label="Delete"
                                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                                data-confirm-yes="delete-form-{{ $order['id'] }}"><i
                                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                                            {{ Form::close() }}
                                                                        </div>
                                                                    @endpermission
                                                                </span>
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
                </div>
            </div>
        </div>
    </div>
@endsection
