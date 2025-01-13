@extends('layouts.main')
@section('page-title')
    {{ __('Manage Commission Receipt') }}
@endsection
@section('page-breadcrumb')
    {{ __('Commission Receipt') }}
@endsection

@php
    $currancy_symbol = admin_setting('defult_currancy_symbol');

@endphp
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-8 col-xl-7">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-form-label">
                                    <div class="invoice-number">
                                        <img src="{{ get_file(sidebar_logo()) }}" width="170px;">
                                    </div>

                                    <div class="invoice" id="printableArea">
                                        <div class="invoice-print">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="invoice-title">
                                                    </div>
                                                    <hr>
                                                    <div class="row text-sm">
                                                        <div class="col-md-6">
                                                            @php
                                                                $agent = App\Models\User::where('id', $commissionReceipt->agent)
                                                                    ->pluck('name')
                                                                    ->first();
                                                            @endphp
                                                            <address>
                                                                <strong>{{ __('Name') }} :</strong>
                                                                {{ $agent }}<br>
                                                                <strong>{{ __('Position') }} :</strong>
                                                                {{ __('Employee') }}<br>
                                                                <strong>{{ __('Receipt Date') }} :</strong>
                                                                {{ company_date_formate($commissionReceipt->created_at) }}<br>
                                                            </address>
                                                        </div>
                                                        <div class="col-md-6 text-end">
                                                            <address>
                                                                <strong>{{ !empty(company_setting('company_name')) ? company_setting('company_name') : '' }}
                                                                </strong><br>
                                                                {{ !empty(company_setting('company_address')) ? company_setting('company_address') : '' }}
                                                                ,
                                                                {{ !empty(company_setting('company_city')) ? company_setting('company_city') : '' }},<br>
                                                                {{ !empty(company_setting('company_state')) ? company_setting('company_state') : '' }}<br>

                                                            </address>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table  table-md">
                                                            <tbody>
                                                                <tr class="font-bold">
                                                                    <th>{{ __('Id') }}</th>
                                                                    <th>{{ __('Item') }}</th>
                                                                    <th>{{ __('Price') }}</th>
                                                                    <th>{{ __('quantity') }}</th>
                                                                    <th>{{ __('discount') }}</th>
                                                                    <th class="text-right">{{ __('Amount') }}</th>
                                                                </tr>
                                                                @php
                                                                    $amount = $subtotal = $totalDiscount = 0;
                                                                @endphp
                                                                @foreach ($invoiceProducts as $key => $invoiceProduct)
                                                                    @php
                                                                        $amount += $invoiceProduct->price * $invoiceProduct->quantity - $invoiceProduct->discount;
                                                                        $subtotal += $invoiceProduct->price * $invoiceProduct->quantity;
                                                                        $totalDiscount += $invoiceProduct->discount;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $key + 1 }}</td>
                                                                        <td>{{ optional(Modules\ProductService\Entities\ProductService::find($invoiceProduct->product_id))->name }}
                                                                        </td>
                                                                        <td>{{ $invoiceProduct->price }}</td>
                                                                        <td>{{ $invoiceProduct->quantity }}</td>
                                                                        <td>{{ $invoiceProduct->discount }}</td>
                                                                        <td> {{ $amount }} </td>
                                                                    </tr>
                                                                @endforeach
                                                                <tr>
                                                                    <td colspan="4"></td>
                                                                    <td class="text-right"><b>{{ __('Sub Total') }}</b>
                                                                    </td>
                                                                    <td class="text-right"> {{ $subtotal }} </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4"></td>
                                                                    <td class="text-right"><b>{{ __('Discount') }}</b></td>
                                                                    <td class="text-right"> {{ $totalDiscount }} </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4"></td>
                                                                    <td class="text-right"><b>{{ __('Total') }}</b></td>
                                                                    <td class="text-right">
                                                                        {{ $subtotal + $totalDiscount }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4"></td>
                                                                    <td class="text-right"><b>{{ __('Commission') }}</b>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ $commissionReceipt->amount }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-5">
                    <div class="card subscription-counter">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mt-1">Payment Method</h5>
                        </div>
                        <div class="card-body">
                            <div class="subscription-summery">


                                <div class="summery-footer">
                                    <div class="row">
                                        @if (admin_setting('bank_transfer_payment_is_on') == 'on')
                                            <div class="col-sm-12 col-lg-12 col-md-12">
                                                <div class="card">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <label for="bank-payment">
                                                                        <h5 class="mb-0 text-capitalize pointer">
                                                                            {{ __('Bank Transfer') }}</h5>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div class="form-check">
                                                                <input class="form-check-input payment_method"
                                                                    name="payment_method" id="bank-payment" type="radio"
                                                                    data-payment-action="{{ route('receipt.banktransfer') }}">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="form-label">{{ __('Bank Details :') }}</label>
                                                                    <p class="">
                                                                        {!! admin_setting('bank_number') !!}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="form-label">{{ __('Payment Receipt') }}</label>
                                                                    <div class="choose-files">
                                                                        <label for="temp_receipt">
                                                                            <div class=" bg-primary "> <i
                                                                                    class="ti ti-upload px-1"></i></div>
                                                                            <input type="file"
                                                                                class="form-control temp_receipt"
                                                                                accept="image/png, image/jpeg, image/jpg, .pdf"
                                                                                name="temp_receipt" id="temp_receipt"
                                                                                data-filename="temp_receipt"
                                                                                onchange="document.getElementById('blah3').src = window.URL.createObjectURL(this.files[0])">
                                                                        </label>
                                                                        <p class="text-danger error_msg d-none">
                                                                            {{ __('This field is required') }}</p>

                                                                        <img class="mt-2" width="70px" src=""
                                                                            id="blah3">
                                                                    </div>
                                                                    <div class="invalid-feedback">
                                                                        {{ __('invalid form file') }}</div>
                                                                </div>
                                                            </div>
                                                            <small
                                                                class="text-danger">{{ __('first, make a payment and take a screenshot or download the receipt and upload it.') }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @stack('company_plan_payment')
                                    </div>
                                    <div
                                        class="cart-footer-total-row bg-primary text-white rounded p-3 d-flex align-items-center justify-content-between">
                                        <div class="mini-total-price">
                                            <div class="price">
                                                <h3 class="text-white mb-0 total">
                                                    {{ $commissionReceipt->amount . $currancy_symbol }}</h3>
                                            </div>
                                        </div>
                                        {{ Form::open(['', 'method' => 'post', 'id' => 'payment_form', 'enctype' => 'multipart/form-data',]) }}
                                            <input type="hidden" name="commissionReceiptId" value="{{$commissionReceipt->id}}">
                                            @if ($commissionReceipt->amount > 0)
                                                <div class="text-end form-btn">
                                                </div>
                                            @endif
                                        {{ Form::close() }}
                                    </div>
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
@push('scripts')

    <script>
        $(document).ready(function() {
            var numItems = $('.payment_method').length

            if (numItems > 0) {
                $('.form-btn').append(
                    '<button type="submit" class="btn btn-dark payment-btn" >{{ __('Pay Now') }}</button>');
                setTimeout(() => {
                    $(".payment_method").first().attr('checked', true);
                    $(".payment_method").first().trigger('click');
                }, 200);
            } else {
                $('.form-btn').append(
                    "<span class='text-danger'>{{ __('Admin payment settings not set') }}</span>");
            }

            var payment_action = $('[data-payment-action]').attr("data-payment-action");
            if(payment_action != '' && payment_action != undefined)
            {
                $("#payment_form").attr("action",payment_action);
                $(".temp_receipt").attr("required", "required");
            }
            else
            {
                $("#payment_form").attr("action",'');
            }

        });




        $('#payment_form').submit(function(e) {

            e.preventDefault(); // Prevent form submission

            var file = document.getElementById('temp_receipt').files[0];

            if (file != undefined) {
                $('.error_msg').addClass('d-none');

                // Create a new FormData object
                const formData = new FormData();

                // Add file data from the file input element
                const file = $('#temp_receipt')[0].files[0];
                formData.append('payment_receipt', file, file.name);

                // Add data from the form's input elements
                $('#payment_form input').each(function() {
                    formData.append(this.name, this.value);
                });

                var url = $('#payment_form').attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 'success') {
                            toastrs('Success', response.msg, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            toastrs('Error', response.msg, 'error');
                        }
                        // Handle success response
                    },
                    error: function(xhr, status, error) {
                        toastrs('Error', error, 'error');
                        // Handle error response
                    }
                });

            } else {
                $('.error_msg').removeClass('d-none');
            }

        });
    </script>
@endpush
