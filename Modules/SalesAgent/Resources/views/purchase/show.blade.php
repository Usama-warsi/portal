@extends('layouts.main')
@section('page-title')
    {{ __('Purchase Order Details') }}
@endsection
@section('page-breadcrumb')
    {{ __('Purchase Order Details') }}
@endsection

@section('page-action')
    <div class="">
        @if(\Auth::user()->type == 'company')
            <a class="dash-head-link dropdown-toggle border  arrow-none py-2 px-3 mx-3" data-bs-toggle="dropdown"
            href="#" role="button" aria-haspopup="false" aria-expanded="false"
            id="dropdownLanguage">
                <span
                    class="drp-text hide-mob format text-primary">{{ __('Status') }}</span>
                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                @foreach (Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder as $key => $status)
                    <a href={{ route('salesagents.update.purchase.order.status', [$purchaseOrder->id , $key])  }} class="dropdown-item {{$purchaseOrder->order_status == $key ? 'text-primary' : '' }}">{{ $status }}</a>
                @endforeach
            </div>
            @if (empty($purchaseOrder['invoice_id']))
                <a  class="btn btn-md text-white btn-primary" data-ajax-popup="true" data-size="md"
                    data-title="{{ __('Create Invoice') }}" data-url="{{ route('salesagents.purchase.invoice.model', $purchaseOrder->id) }}" data-bs-toggle="tooltip"
                    data-bs-original-title="{{ __('Create') }}">
                    {{__('Create Invoice')}}
                </a>
            @endif
        @endif
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                    <h2>{{__('Purchase Order')}}</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                    <h3 class="invoice-number float-end">{{ Modules\SalesAgent\Entities\SalesAgent::purchaseOrderNumberFormat($purchaseOrder->id) }}</h3>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-end">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="me-4">
                                            <small>
                                                <strong>{{__('Order Date')}} :</strong><br>
                                                {{ company_date_formate($purchaseOrder->order_date)}}<br><br>
                                            </small>
                                        </div>
                                        <div>
                                            <small>
                                                <strong>{{__('Delivery Date')}} :</strong><br>
                                                {{ company_date_formate($purchaseOrder->delivery_date)}}<br><br>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if(!empty($salesagent->billing_name))
                                    <div class="col">
                                        <small class="font-style">
                                            <strong>{{__('Billed To')}} :</strong><br>
                                            {{ !empty($salesagent->billing_name) ? $salesagent->billing_name : '' }}<br>
                                            {{ !empty($salesagent->billing_address) ? $salesagent->billing_address : '' }}<br>
                                            {{ !empty($salesagent->billing_city) ? $salesagent->billing_city . ' ,' : '' }}
                                            {{ !empty($salesagent->billing_state) ? $salesagent->billing_state . ' ,' : '' }}
                                            {{ !empty($salesagent->billing_zip) ? $salesagent->billing_zip : '' }}<br>
                                            {{ !empty($salesagent->billing_country) ? $salesagent->billing_country : '' }}<br>
                                            {{ !empty($salesagent->billing_phone) ? $salesagent->billing_phone : '' }}<br>
                                            <strong>{{__('Tax Number')}} : </strong>{{!empty($salesagent->tax_number)?$salesagent->tax_number:''}}
                                        </small>
                                    </div>
                                @endif
                                <div class="col">
                                    <small>
                                        <strong>{{__('Shipped To')}} :</strong><br>
                                        {{ !empty($salesagent->shipping_name) ? $salesagent->shipping_name : '' }}<br>
                                        {{ !empty($salesagent->shipping_address) ? $salesagent->shipping_address : '' }}<br>
                                        {{ !empty($salesagent->shipping_city) ? $salesagent->shipping_city .' ,': '' }}
                                        {{ !empty($salesagent->shipping_state) ? $salesagent->shipping_state .' ,': '' }}
                                        {{ !empty($salesagent->shipping_zip) ? $salesagent->shipping_zip : '' }}<br>
                                        {{ !empty($salesagent->shipping_country) ? $salesagent->shipping_country : '' }}<br>
                                        {{ !empty($salesagent->shipping_phone) ? $salesagent->shipping_phone : '' }}<br>
                                        <strong>{{__('Tax Number ')}} : </strong>{{!empty($salesagent->tax_number)?$salesagent->tax_number:''}}

                                    </small>
                                </div>
                                    {{-- <div class="col">
                                        <div class="float-end mt-3 ">
                                         {!! DNS2D::getBarcodeHTML(route('pay.billpay',\Illuminate\Support\Facades\Crypt::encrypt($purchaseOrder->id)), "QRCODE",2,2) !!}
                                        </div>
                                    </div> --}}
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong>{{__('Status')}} :</strong><br>
                                        @if ($purchaseOrder->order_status == 0)
                                            <span
                                                class="badge fix_badges bg-primary p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder[$purchaseOrder->order_status]) }}</span>
                                        @elseif($purchaseOrder->order_status == 1)
                                            <span
                                                class="badge fix_badges bg-info p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder[$purchaseOrder->order_status]) }}</span>
                                        @elseif($purchaseOrder->order_status == 2)
                                            <span
                                                class="badge fix_badges bg-secondary p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder[$purchaseOrder->order_status]) }}</span>
                                        @elseif($purchaseOrder->order_status == 3)
                                            <span
                                                class="badge fix_badges bg-warning p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder[$purchaseOrder->order_status  ]) }}</span>
                                        @elseif($purchaseOrder->order_status == 4)
                                            <span
                                                class="badge fix_badges bg-danger p-2 px-3 rounded bill_status">{{ __(Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder[$purchaseOrder->order_status]) }}</span>
                                        @endif
                                    </small>
                                </div>
                                @if(!empty($customFields) && count($purchaseOrder->customField)>0)
                                    @foreach($customFields as $field)
                                        <div class="col text-md-end">
                                            <small>
                                                <strong>{{$field->name}} :</strong><br>
                                                {{!empty($purchaseOrder->customField[$field->id])?$purchaseOrder->customField[$field->id]:'-'}}
                                                <br><br>
                                            </small>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-bold mb-2">{{__('Item Summary')}}</div>
                                    <small class="mb-2">{{__('All items here cannot be deleted.')}}</small>
                                    <div class="table-responsive mt-2">
                                        <table class="table mb-0 table-striped">
                                            <tr>
                                                <th class="text-dark" data-width="40">#</th>
                                                <th class="text-dark">{{__('Programs')}}</th>
                                                <th class="text-dark">{{__('Item')}}</th>
                                                <th class="text-dark">{{__('Quantity')}}</th>
                                                <th class="text-dark">{{__('Rate')}}</th>
                                                <th class="text-dark">{{__('Discount')}}</th>
                                                <th class="text-dark">{{__('Tax')}}</th>
                                                <th class="text-dark">{{__('Description')}}</th>
                                                <th class="text-right text-dark" width="12%">{{__('Price')}}<br>
                                                    <small class="text-danger font-weight-bold">{{__('After discount & tax')}}</small>
                                                </th>
                                            </tr>
                                            @php
                                                $totalQuantity=0;
                                                $totalRate=0;
                                                $totalTaxPrice=0;
                                                $totalDiscount=0;
                                                $taxesData=[];
                                                $TaxPrice_array = [];
                                            @endphp
                                            @foreach($purchaseOrder->items as $key =>$iteam)
                                                @php
                                                    $totalQuantity+=$iteam->quantity;
                                                    $totalRate+=$iteam->price;
                                                    $totalDiscount+=$iteam->discount;
                                                @endphp
                                                @if(!empty($iteam->tax))
                                                    @php
                                                        $taxes= Modules\SalesAgent\Entities\SalesAgentUtility::tax($iteam->tax);
                                                        foreach($taxes as $taxe){
                                                            $taxDataPrice= Modules\SalesAgent\Entities\SalesAgentUtility::taxRate($taxe->rate,$iteam->price,$iteam->quantity,$iteam->discount);
                                                            if (array_key_exists($taxe->name,$taxesData))
                                                            {
                                                                $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                            }
                                                            else
                                                            {
                                                                $taxesData[$taxe->name] = $taxDataPrice;
                                                            }
                                                        }
                                                    @endphp
                                                @endif
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{!empty($iteam->program) ? Str::ucfirst($iteam->program->name) : '--'}}</td>
                                                    <td>{{!empty($iteam->product)?$iteam->product->name:''}}</td>
                                                    <td>{{$iteam->quantity}}</td>
                                                    <td>{{ currency_format_with_sym($iteam->price)}}</td>
                                                    <td>{{currency_format_with_sym($iteam->discount)}}</td>

                                                    <td>
                                                        @if(!empty($iteam->tax))
                                                            <table>
                                                                @php
                                                                    $totalTaxRate = 0;
                                                                    $data=0;
                                                                @endphp
                                                                @foreach($taxes as $tax)
                                                                    @php
                                                                        $taxPrice= Modules\SalesAgent\Entities\SalesAgentUtility::taxRate($tax->rate,$iteam->price,$iteam->quantity,$iteam->discount);
                                                                        $totalTaxPrice+=$taxPrice;
                                                                        $data+=$taxPrice;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{$tax->name .' ('.$tax->rate .'%)'}}</td>
                                                                        <td>{{ currency_format_with_sym($taxPrice)}}</td>
                                                                    </tr>
                                                                @endforeach
                                                                @php
                                                                    array_push($TaxPrice_array,$data);
                                                                @endphp
                                                            </table>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    </td>
                                                    <td style="white-space: break-spaces;">{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                                    @php
                                                        $tr_tex = (array_key_exists($key,$TaxPrice_array) == true) ? $TaxPrice_array[$key] : 0;
                                                    @endphp
                                                    <td class="">{{ currency_format_with_sym(($iteam->price * $iteam->quantity - $iteam->discount) + $tr_tex)}}</td>
                                                </tr>
                                            @endforeach


                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><b>{{__('Total')}}</b></td>
                                                <td><b>{{$totalQuantity}}</b></td>
                                                <td><b>{{ currency_format_with_sym($totalRate)}}</b></td>
                                                <td><b>{{ currency_format_with_sym($totalDiscount)}}</b></td>
                                                <td><b>{{ currency_format_with_sym($totalTaxPrice)}}</b></td>

                                            </tr>
                                            <tr>
                                                <td colspan="7"></td>
                                                <td class="text-right"><b>{{__('Sub Total')}}</b></td>
                                                <td class="text-right">{{ currency_format_with_sym($purchaseOrder->getSubTotal())}}</td>
                                            </tr>
                                                <tr>
                                                    <td colspan="7"></td>
                                                    <td class="text-right"><b>{{__('Discount')}}</b></td>
                                                    <td class="text-right">{{ currency_format_with_sym($purchaseOrder->getTotalDiscount())}}</td>
                                                </tr>
                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    <tr>
                                                        <td colspan="7"></td>
                                                        <td class="text-right"><b>{{$taxName}}</b></td>
                                                        <td class="text-right">{{  currency_format_with_sym($taxPrice) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="7"></td>
                                                <td class="blue-text text-right"><b>{{__('Total')}}</b></td>
                                                <td class="blue-text text-right">{{ currency_format_with_sym($purchaseOrder->getTotal())}}</td>
                                            </tr>
                                            </tfoot>
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
