@extends('layouts.main')

@section('page-title')
    {{__('Sales Agent')}}
@endsection

@section('page-breadcrumb')
{{ __('Sales Agent')}} , {{ __('Purchase Order')}}
@endsection

@section('page-action')
    <div>
        @permission('salesagent purchase create')
            <a  href="{{ route('salesagents.purchase.order.create') }}" class="btn btn-sm btn-primary"  
                data-title="{{ __('Create New Purchase Order') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
            <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Setup') }}" data-url="{{ route('salesagent.purchase.setting.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Setup') }}">
                <i class="ti ti-settings"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{ __('Order Number') }}</th>
                                    @if(\Auth::user()->type == 'company')
                                        <th>{{ __('Agent') }}</th>
                                    @endif
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Order Value') }}</th>
                                    @if(\Auth::user()->type == 'company')
                                        <th>{{ __('Invoice') }}</th>
                                    @endif
                                    <th>{{ __('Delivery Date') }}</th>
                                    <th>{{ __('Delivery status') }}</th>
                                    {{-- @if(\Auth::user()->type !== 'company')
                                        <th>{{ __('Approval Status') }}</th>
                                    @endif --}}
                                    <th>{{ __('Order Status') }}</th>
                                    @if (Laratrust::hasPermission('salesagent purchase delete') || Laratrust::hasPermission('salesagent purchase show'))
                                        <th width="10%"> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrders as $k => $order)
                                    <tr class="font-style">
                                        <td class="">
                                            <a href="{{ route('salesagents.purchase.order.show', \Crypt::encrypt($order['id'])) }}"
                                                class="btn btn-outline-primary">
                                                {{ Modules\SalesAgent\Entities\SalesAgent::purchaseOrderNumberFormat($order['id']) }}
                                            </a>
                                        </td>
                                        @if(\Auth::user()->type == 'company')
                                            <td>{{ $order->user->name }}</td>
                                        @endif
                                        <td>{{ company_date_formate($order['order_date']) }}</td>
                                        <td>{{ currency_format_with_sym($order->getTotal())}}</td>
                                        @if(\Auth::user()->type == 'company')
                                        <td>
                                            @if (empty($order['invoice_id']))
                                                <span class="badge fix_badges bg-secondary p-2 px-3 rounded bill_status">{{ __('Not Created Yet') }}</span>
                                            @else
                                                @if (Laratrust::hasPermission('invoice show'))
                                                    <a target="_blank" href="{{ route('invoice.show', \Crypt::encrypt($order['invoice_id'])) }}"
                                                        class="text-primary">{{ App\Models\Invoice::invoiceNumberFormat($order['invoice_id']) }}</a>
                                                @else
                                                    <a target="_blank" href="#"
                                                        class="text-primary">{{ App\Models\Invoice::invoiceNumberFormat($order['invoice_id']) }}</a>
                                                @endif
                                            @endif
                                        </td>
                                        @endif

                                        <td>
                                            @if ($order['delivery_date'] < date('Y-m-d'))
                                                <p class="text-danger">
                                                    {{ company_date_formate($order['delivery_date']) }}</p>
                                            @else
                                                {{ company_date_formate($order['delivery_date']) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->order_status == 3)
                                                <span class="badge fix_badges bg-primary  p-2 px-3 rounded bill_status">{{ __('Delivered') }}</span>
                                            @else    
                                                <span class="badge fix_badges bg-secondary  p-2 px-3 rounded bill_status">{{ __('Undelivered') }}</span>
                                            @endif

                                        </td>
                                        {{-- @if(\Auth::user()->type !== 'company')
                                            <td>
                                                @if ($order->approval_status == 0)
                                                    <span
                                                        class="badge fix_badges bg-secondary  p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$approvalStatus[$order->approval_status]) }}</span>
                                                @elseif($order->approval_status == 1)
                                                    <span
                                                        class="badge fix_badges bg-primary p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$approvalStatus[$order->approval_status]) }}</span>
                                                @elseif($order->approval_status == 2)
                                                    <span
                                                        class="badge fix_badges bg-warning p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$approvalStatus[$order->approval_status]) }}</span>
                                                @elseif($order->approval_status == 3)
                                                    <span
                                                        class="badge fix_badges bg-danger p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$approvalStatus[$order->approval_status]) }}</span>
                                                @endif
                                            </td>
                                        @endif --}}
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
                                                                data-bs-toggle="tooltip" title="{{ __('View') }}">
                                                                <i class="ti ti-eye text-white text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @permission('salesagent purchase delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {{ Form::open(['route' => ['salesagents.purchase.order.destroy', $order['id']], 'class' => 'm-0']) }}
                                                            @method('DELETE')
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"
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
@endsection
