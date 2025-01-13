@extends('retainer::layouts.retainerpayheader')
@section('page-title')
    {{ __('Retainer Detail') }}
@endsection
@push('css')
    <style>
        #card-element {
            border: 1px solid #a3afbb !important;
            border-radius: 10px !important;
            padding: 10px !important;
        }
    </style>
@endpush

@section('action-btn')
    @if ($retainer->status != 0)
        <div class="row justify-content-center align-items-center ">
            <div class="col-12 d-flex align-items-center justify-content-between justify-content-md-end">

                <div class="all-button-box mr-3">
                    <a href="{{ route('retainer.pdf', \Crypt::encrypt($retainer->id)) }}" target="_blank"
                       class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" title="{{ __('Print') }}">
                        <span class="btn-inner--icon text-white"><i class="ti ti-printer"></i>{{ __('Print') }}</span>
                    </a>

                    @if ($retainer->getDue() > 0)
                        <a id="paymentModals"  class="btn btn-sm btn-primary">
                            <span class="btn-inner--icon text-white"><i class="ti ti-credit-card"></i></span>
                            <span class="btn-inner--text text-white">{{ __(' Pay Now') }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection

@section('content')
    <div class="row ">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="retainer">
                        <div class="retainer-print">
                            <div class="row retainer-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                    <h2>{{ __('Retainer') }}</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                    <h3 class="retainer-number">

                                        {{ \Modules\Retainer\Entities\Retainer::retainerNumberFormat($retainer->retainer_id, $retainer->created_by,$retainer->workspace) }}
                                    </h3>
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
                                                <strong>{{ __('Issue Date') }} :</strong><br>

                                                {{ company_date_formate($retainer->issue_date, $retainer->created_by,$retainer->workspace) }}<br><br>

                                            </small>
                                        </div>
                                        {{-- <div>
                                            <small>
                                                <strong>{{ __('Due Date') }} :</strong><br>


                                                {{ company_date_formate($retainer->due_date, $retainer->created_by,$retainer->workspace) }}<br><br>

                                            </small>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if (!empty($customer->billing_name))
                                    <div class="col">
                                        <small class="font-style">
                                            <strong>{{ __('Billed To') }} :</strong><br>
                                                {{ !empty($customer->billing_name) ? $customer->billing_name : '' }}<br>
                                                {{ !empty($customer->billing_address) ? $customer->billing_address : '' }}<br>
                                                {{ !empty($customer->billing_city) ? $customer->billing_city . ' ,' : '' }}
                                                {{ !empty($customer->billing_state) ? $customer->billing_state . ' ,' : '' }}
                                                {{ !empty($customer->billing_zip) ? $customer->billing_zip : '' }}<br>
                                                {{ !empty($customer->billing_country) ? $customer->billing_country : '' }}<br>
                                                {{ !empty($customer->billing_phone) ? $customer->billing_phone : '' }}<br>
                                        </small>
                                    </div>
                                @endif
                                @if (company_setting('retainer_shipping_display', $retainer->created_by,$retainer->workspace) == 'on')
                                    <div class="col">
                                        <small>
                                            <strong>{{ __('Shipped To') }} :</strong><br>
                                            {{ !empty($customer->shipping_name) ? $customer->shipping_name : '' }}<br>
                                            {{ !empty($customer->shipping_address) ? $customer->shipping_address : '' }}<br>
                                            {{ !empty($customer->shipping_city) ? $customer->shipping_city .' ,': '' }}
                                            {{ !empty($customer->shipping_state) ? $customer->shipping_state .' ,': '' }}
                                            {{ !empty($customer->shipping_zip) ? $customer->shipping_zip : '' }}<br>
                                            {{ !empty($customer->shipping_country) ? $customer->shipping_country : '' }}<br>
                                            {{ !empty($customer->shipping_phone) ? $customer->shipping_phone : '' }}<br>
                                        </small>
                                    </div>
                                @endif

                                <div class="col">
                                    <div class="float-end mt-3">
                                        {!! DNS2D::getBarcodeHTML(
                                            route('pay.retainer', \Illuminate\Support\Facades\Crypt::encrypt($retainer->id)),
                                            'QRCODE',
                                            2,
                                            2,
                                        ) !!}
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong>{{ __('Status') }} :</strong><br>

                                        @if ($retainer->status == 0)
                                            <span
                                                class="badge bg-primary p-2 px-3 rounded">{{ __(\Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                        @elseif($retainer->status == 1)
                                            <span
                                                class="badge bg-info p-2 px-3 rounded">{{ __(\Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                        @elseif($retainer->status == 2)
                                            <span
                                                class="badge bg-secondary p-2 px-3 rounded">{{ __(\Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                        @elseif($retainer->status == 3)
                                            <span
                                                class="badge bg-warning p-2 px-3 rounded">{{ __(\Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                        @elseif($retainer->status == 4)
                                            <span
                                                class="badge bg-danger p-2 px-3 rounded">{{ __(\Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                        @endif
                                    </small>
                                </div>



                                @if (!empty($customFields) && count($retainer->customField) > 0)
                                @foreach ($customFields as $field)
                                    <div class="col text-end">
                                        <small>
                                            <strong>{{ $field->name }} :</strong><br>
                                        @if ($field->type == 'attachment')
                                            <a href="{{ get_file($retainer->customField[$field->id]) }}" target="_blank">
                                                <img src=" {{ get_file($retainer->customField[$field->id]) }} " class="wid-75 rounded me-3">
                                            </a>
                                        @else
                                            {{!empty($retainer->customField[$field->id])?$retainer->customField[$field->id]:'-'}}
                                        @endif
                                            <br><br>
                                        </small>
                                    </div>
                                @endforeach
                                @endif
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-weight-bold">{{ __('Item Summary') }}</div>
                                    <small>{{ __('All items here cannot be deleted.') }}</small>
                                    <div class="table-responsive mt-2">
                                        <table class="table mb-0 ">
                                            <tr>
                                                <th data-width="40" class="text-dark">#</th>
                                                @if ($retainer->retainer_module == 'account')
                                                    <th class="text-dark">{{__('Item Type')}}</th>
                                                    <th class="text-dark">{{__('Item')}}</th>
                                                @elseif($retainer->retainer_module == 'taskly')
                                                    <th class="text-dark">{{ __('Project') }}</th>
                                                @endif

                                                <th class="text-dark">{{ __('Quantity') }}</th>
                                                <th class="text-dark">{{ __('Rate') }}</th>
                                                <th class="text-dark">
                                                    {{ __('Discount') }}
                                                </th>
                                                <th class="text-dark">{{ __('Tax') }}</th>
                                                <th class="text-dark">{{ __('Description') }}</th>
                                                <th class="text-end text-dark" width="12%">{{ __('Price') }}<br>
                                                    <small
                                                        class="text-danger font-weight-bold">{{ __('after tax & discount') }}</small>
                                                </th>
                                            </tr>
                                            @php
                                                $totalQuantity = 0;
                                                $totalRate = 0;
                                                $totalTaxPrice = 0;
                                                $totalDiscount = 0;
                                                $taxesData = [];
                                                $TaxPrice_array = [];
                                            @endphp
                                            @foreach ($iteams as $key => $iteam)
                                                @if (!empty($iteam->tax))
                                                    @php
                                                        $taxes = \Modules\Retainer\Entities\Retainer::tax($iteam->tax);
                                                        $totalQuantity += $iteam->quantity;
                                                        $totalRate += $iteam->price;
                                                        if ($retainer->retainer_module == 'account') {
                                                            $totalDiscount += $iteam->discount;
                                                        } elseif ($retainer->retainer_module == 'taskly') {
                                                            $totalDiscount = $retainer->discount;
                                                        }

                                                        foreach ($taxes as $taxe) {
                                                            $taxDataPrice = \Modules\Retainer\Entities\Retainer::taxRate($taxe->rate, $iteam->price, $iteam->quantity,$iteam->discount);
                                                            if (array_key_exists($taxe->name, $taxesData)) {
                                                                $taxesData[$taxe->name] = $taxesData[$taxe->name] + $taxDataPrice;
                                                            } else {
                                                                $taxesData[$taxe->name] = $taxDataPrice;
                                                            }
                                                        }
                                                    @endphp
                                                @endif
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    @if ($retainer->retainer_module == 'account')
                                                        <td>{{!empty($iteam->product_type) ? Str::ucfirst($iteam->product_type) : '--'}}</td>
                                                        <td>{{!empty($iteam->product())?$iteam->product()->name:''}}</td>
                                                    @elseif($retainer->retainer_module == 'taskly')
                                                        <td>{{ !empty($iteam->product()) ? $iteam->product()->title : '' }}
                                                        </td>
                                                    @endif
                                                    <td>{{ $iteam->quantity }}</td>
                                                    <td>{{ currency_format_with_sym($iteam->price, $retainer->created_by,$retainer->workspace) }}
                                                    </td>
                                                    <td>
                                                        {{ currency_format_with_sym($iteam->discount, $retainer->created_by,$retainer->workspace) }}
                                                    </td>
                                                    <td>

                                                        @if (!empty($iteam->tax))
                                                            <table>
                                                                @php
                                                                    $totalTaxRate = 0;
                                                                    $data = 0;
                                                                @endphp
                                                                @foreach ($taxes as $tax)
                                                                    @php
                                                                        $taxPrice = \Modules\Retainer\Entities\Retainer::taxRate($tax->rate, $iteam->price, $iteam->quantity,$iteam->discount);
                                                                        $totalTaxPrice += $taxPrice;
                                                                        $data+=$taxPrice;

                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $tax->name . ' (' . $tax->rate . '%)' }}
                                                                        </td>
                                                                        <td>{{ currency_format_with_sym($taxPrice, $retainer->created_by,$retainer->workspace) }}
                                                                        </td>
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

                                                    <td>{{ !empty($iteam->description) ? $iteam->description : '-' }}</td>
                                                    @php
                                                        $tr_tex = (array_key_exists($key,$TaxPrice_array) == true) ? $TaxPrice_array[$key] : 0;
                                                    @endphp
                                                    <td class="text-end">
                                                        {{ currency_format_with_sym(($iteam->price*$iteam->quantity) -$iteam->discount + $tr_tex ,$retainer->created_by,$retainer->workspace)}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                @if($retainer->retainer_module == "account")
                                                    <td></td>
                                                @endif
                                                <td><b>{{ __('Total') }}</b></td>
                                                <td><b>{{ $totalQuantity }}</b></td>
                                                <td><b>{{ currency_format_with_sym($totalRate, $retainer->created_by,$retainer->workspace) }}</b>
                                                </td>
                                                <td><b>{{ currency_format_with_sym($totalDiscount, $retainer->created_by,$retainer->workspace) }}</b></td>
                                                <td><b>{{ currency_format_with_sym($totalTaxPrice, $retainer->created_by,$retainer->workspace) }}</b></td>
                                                <td></td>
                                            </tr>
                                            @php
                                                $colspan = 6;
                                                if($retainer->retainer_module == "account"){
                                                    $colspan = 7;
                                                }
                                            @endphp
                                            <tr>
                                                <td colspan="{{$colspan}}"></td>
                                                <td class="text-end"><b>{{ __('Sub Total') }}</b></td>
                                                <td class="text-end">
                                                    {{ currency_format_with_sym($retainer->getSubTotal(), $retainer->created_by,$retainer->workspace) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{$colspan}}"></td>
                                                <td class="text-end"><b>{{ __('Discount') }}</b></td>
                                                <td class="text-end">
                                                    {{ currency_format_with_sym($retainer->getTotalDiscount(), $retainer->created_by,$retainer->workspace) }}
                                                </td>
                                            </tr>
                                            @if (!empty($taxesData))
                                                @foreach ($taxesData as $taxName => $taxPrice)
                                                    <tr>
                                                        <td colspan="{{$colspan}}"></td>
                                                        <td class="text-end"><b>{{ $taxName }}</b></td>
                                                        <td class="text-end">
                                                            {{ currency_format_with_sym($taxPrice, $retainer->created_by,$retainer->workspace) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="{{$colspan}}"></td>
                                                <td class="blue-text text-end"><b>{{ __('Total') }}</b></td>
                                                <td class="blue-text text-end">
                                                    {{ currency_format_with_sym($retainer->getTotal(), $retainer->created_by,$retainer->workspace) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{$colspan}}"></td>
                                                <td class="text-end"><b>{{ __('Paid') }}</b></td>
                                                <td class="text-end">
                                                    {{ currency_format_with_sym($retainer->getTotal() - $retainer->getDue() , $retainer->created_by,$retainer->workspace) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{$colspan}}"></td>
                                                <td class="text-end"><b>{{ __('Due') }}</b></td>
                                                <td class="text-end">
                                                    {{ currency_format_with_sym($retainer->getDue(), $retainer->created_by,$retainer->workspace) }}
                                                </td>
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
        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{ __('Receipt Summary') }}</h5>
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table ">
                            <tr>
                                <th class="text-dark">{{ __('Date') }}</th>
                                <th class="text-dark">{{ __('Amount') }}</th>
                                <th class="text-dark">{{ __('Payment Type') }}</th>
                                <th class="text-dark">{{ __('Account') }}</th>
                                <th class="text-dark">{{ __('Reference') }}</th>
                                <th class="text-dark">{{ __('Receipt') }}</th>
                                <th class="text-dark">{{ __('Description') }}</th>
                                <th class="text-dark">{{ __('OrderId') }}</th>
                            </tr>
                            @forelse($retainer->payments as $key =>$payment)
                                <tr>
                                    <td>{{ company_date_formate($payment->date, $retainer->created_by,$retainer->workspace) }}</td>
                                    <td>{{ currency_format_with_sym($payment->amount, $retainer->created_by,$retainer->workspace) }}</td>
                                    <td>{{ $payment->payment_type }}</td>
                                    <td>{{ !empty($payment->bankAccount) ? $payment->bankAccount->bank_name . ' ' . $payment->bankAccount->holder_name : '--' }}
                                    </td>
                                    <td>{{ !empty($payment->reference) ? $payment->reference : '--' }}</td>
                                    <td>
                                        @if(!empty($payment->add_receipt) && empty($payment->receipt))
                                            <a href="{{ get_file($payment->add_receipt)}}" download="" class="btn btn-sm btn-primary btn-icon rounded-pill" target="_blank"><span class="btn-inner--icon"><i class="ti ti-download"></i></span></a>
                                            <a href="{{ get_file($payment->add_receipt)}}"  class="btn btn-sm btn-secondary btn-icon rounded-pill" target="_blank"><span class="btn-inner--icon"><i class="ti ti-crosshair"></i></span></a>
                                        @elseif (!empty($payment->receipt) && empty($payment->add_receipt) && $payment->type == 'STRIPE')
                                            <a href="{{$payment->receipt}}" target="_blank">
                                                 <i class="ti ti-file"></i>
                                            </a>
                                        @elseif($payment->payment_type == 'Bank Transfer')
                                            <a href="{{ !empty($payment->receipt) ? (check_file($payment->receipt)) ? get_file($payment->receipt) : '#!' : '#!' }}" target="_blank" >
                                                <i class="ti ti-file"></i>
                                            </a>
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>{{ !empty($payment->description) ? $payment->description : '--' }}</td>
                                    <td>{{ !empty($payment->order_id) ? $payment->order_id : '--' }}</td>
                                </tr>
                            @empty
                                @include('layouts.nodatafound')
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
    @if ($retainer->getDue() > 0)
        <div id="paymentModal" class="modal" tabindex="-1" aria-labelledby="exampleModalLongTitle" aria-modal="true"
             role="dialog" data-keyboard="false" data-backdrop="static">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">{{ __('Add Payment') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row pb-3 px-2">
                            <section class="">
                                <ul class="nav nav-pills  mb-3" id="pills-tab" role="tablist">
                                    @if (company_setting('bank_transfer_payment_is_on', $retainer->created_by,$retainer->workspace) == 'on' &&
                                        !empty(company_setting('bank_number', $retainer->created_by,$retainer->workspace)) )
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-home-tab" data-bs-toggle="pill"
                                                data-bs-target="#bank-payment" type="button" role="tab"
                                                aria-controls="pills-home" aria-selected="true">{{ __('Bank trasfer') }}</a>
                                        </li>
                                    @endif
                                    @stack('retainer_payment_tab')
                                </ul>

                                <div class="tab-content" id="pills-tabContent">
                                    @if (company_setting('bank_transfer_payment_is_on', $retainer->created_by,$retainer->workspace) == 'on' &&
                                        !empty(company_setting('bank_number', $retainer->created_by,$retainer->workspace)) )
                                        <div class="tab-pane fade " id="bank-payment" role="tabpanel"
                                            aria-labelledby="bank-payment">
                                            <form method="post" action="{{ route('invoice.pay.with.bank') }}"
                                                class="require-validation" id="payment-form" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="type" value="retainer">
                                                <div class="row mt-2">
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ __('Bank Details :') }}</label>
                                                            <p class="">
                                                                {!!company_setting('bank_number', $retainer->created_by,$retainer->workspace) !!}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ __('Payment Receipt') }}</label>
                                                            <div class="choose-files">
                                                            <label for="payment_receipt">
                                                                <div class=" bg-primary "> <i class="ti ti-upload px-1"></i></div>
                                                                <input type="file" class="form-control" required="" accept="image/png, image/jpeg, image/jpg, .pdf" name="payment_receipt" id="payment_receipt" data-filename="payment_receipt" onchange="document.getElementById('blah3').src = window.URL.createObjectURL(this.files[0])">
                                                            </label>
                                                            <p class="text-danger error_msg d-none">{{ __('This field is required')}}</p>

                                                            <img class="mt-2" width="70px"  id="blah3">
                                                        </div>
                                                            <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger">{{ __('first, make a payment and take a screenshot or download the receipt and upload it.')}}</small>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="amount">{{ __('Amount') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-prepend"><span
                                                                    class="input-group-text">{{ !empty(company_setting('defult_currancy', $retainer->created_by,$retainer->workspace)) ? company_setting('defult_currancy', $retainer->created_by,$retainer->workspace) : '$' }}</span></span>
                                                            <input class="form-control" required="required"
                                                                min="0" name="amount" type="number"
                                                                value="{{ $retainer->getDue() }}" min="0"
                                                                step="0.01" max="{{ $retainer->getDue() }}"
                                                                id="amount">
                                                            <input type="hidden" value="{{ $retainer->id }}"
                                                                name="invoice_id">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="error" style="display: none;">
                                                            <div class='alert-danger alert'>
                                                                {{ __('Please correct the errors and try again.') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <button type="button" class="btn  btn-light"
                                                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                    <button class="btn btn-primary"
                                                        type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @stack('retainer_payment_div')
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


@endsection
@push('scripts')
    <script>
        $("#paymentModals").click(function(){
            $("#paymentModal").modal('show');
            $("ul li a").removeClass("active");
            $(".tab-pane").removeClass("active show");
            $("ul li:first a:first").addClass("active");
            $(".tab-pane:first").addClass("active show");
        });
    </script>
@endpush
