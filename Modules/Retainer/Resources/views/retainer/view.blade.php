@extends('layouts.main')
@section('page-title')
    {{ __('Retainer Detail') }}
@endsection
@push('css-page')
    <style>
        #card-element {
            border: 1px solid #a3afbb !important;
            border-radius: 10px !important;
            padding: 10px !important;
        }
    </style>
@endpush
@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script type="text/javascript">
        @if (
            $retainer->getDue() > 0 &&
                !empty($company_payment_setting) &&
                $company_payment_setting['is_stripe_enabled'] == 'on' &&
                !empty($company_payment_setting['stripe_key']) &&
                !empty($company_payment_setting['stripe_secret']))

            var stripe = Stripe('{{ $company_payment_setting['stripe_key'] }}');
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            var style = {
                base: {
                    // Add your base input styles here. For example:
                    fontSize: '14px',
                    color: '#32325d',
                },
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {
                style: style
            });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            // Create a token or display an error when the form is submitted.
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        $("#card-errors").html(result.error.message);
                        toastrs('error', result.error.message, 'error');
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            });

            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            }
        @endif

        @if (isset($company_payment_setting['paystack_public_key']))
            $(document).on("click", "#pay_with_paystack", function() {
                $('#paystack-payment-form').ajaxForm(function(res) {
                    var amount = res.total_price;
                    if (res.flag == 1) {

                        var handler = PaystackPop.setup({
                            key: '{{ $company_payment_setting['paystack_public_key'] }}',
                            email: res.email,
                            amount: res.total_price * 100,
                            currency: res.currency,
                            ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                                1
                            ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                            metadata: {
                                custom_fields: [{
                                    display_name: "Email",
                                    variable_name: "email",
                                    value: res.email,
                                }]
                            },

                            callback: function(response) {
                                window.location.href =
                                    '{{ url('customer/retainer/paystack') }}' + '/' +
                                    '{{ \Illuminate\Support\Facades\Crypt::encrypt($retainer->id) }}' +
                                    '/' + amount + '/' + response.reference;
                            },
                            onClose: function() {
                                alert('window closed');
                            }
                        });
                        handler.openIframe();
                    } else if (res.flag == 2) {
                        toastrs('Error', res.msg, 'msg');
                    } else {
                        toastrs('Error', res.message, 'msg');
                    }

                }).submit();
            });
        @endif

        @if (isset($company_payment_setting['flutterwave_public_key']))
            //    Flaterwave Payment
            $(document).on("click", "#pay_with_flaterwave", function() {
                $('#flaterwave-payment-form').ajaxForm(function(res) {

                    if (res.flag == 1) {
                        var amount = res.total_price;
                        var API_publicKey = '{{ $company_payment_setting['flutterwave_public_key'] }}';
                        var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                        var flutter_callback = "{{ url('/retainer/flaterwave') }}";
                        var x = getpaidSetup({
                            PBFPubKey: API_publicKey,
                            customer_email: '{{ Auth::user()->email }}',
                            amount: res.total_price,
                            currency: '{{ \Modules\Retainer\Entities\Retainer::getValByName('site_currency') }}',
                            txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) +
                                'fluttpay_online-' + '{{ date('Y-m-d') }}' + '?amount=' + amount,
                            meta: [{
                                metaname: "payment_id",
                                metavalue: "id"
                            }],
                            onclose: function() {},
                            callback: function(response) {
                                var txref = response.tx.txRef;
                                if (
                                    response.tx.chargeResponseCode == "00" ||
                                    response.tx.chargeResponseCode == "0"
                                ) {
                                    window.location.href =
                                        '{{ url('customer/retainer/flaterwave') }}' + '/' +
                                        '{{ \Illuminate\Support\Facades\Crypt::encrypt($retainer->id) }}' +
                                        '/' + txref;
                                } else {
                                    // redirect to a failure page.
                                }
                                x
                                    .close(); // use this to close the modal immediately after payment.
                            }
                        });
                    } else if (res.flag == 2) {
                        toastrs('Error', res.msg, 'msg');
                    } else {
                        toastrs('Error', data.message, 'msg');
                    }

                }).submit();
            });
        @endif

        @if (isset($company_payment_setting['razorpay_public_key']))
            // Razorpay Payment
            $(document).on("click", "#pay_with_razorpay", function() {
                $('#razorpay-payment-form').ajaxForm(function(res) {
                    if (res.flag == 1) {
                        var amount = res.total_price;
                        var totalAmount = res.total_price * 100;
                        var coupon_id = res.coupon;
                        var options = {
                            "key": "{{ $company_payment_setting['razorpay_public_key'] }}", // your Razorpay Key Id
                            "amount": totalAmount,
                            "name": 'Plan',
                            "currency": '{{ \Modules\Retainer\Entities\Retainer::getValByName('site_currency') }}',
                            "description": "",
                            "handler": function(response) {
                                window.location.href = '{{ url('customer/retainer/razorpay') }}' +
                                    '/' +
                                    '{{ \Illuminate\Support\Facades\Crypt::encrypt($retainer->id) }}' +
                                    '/' + amount;


                            },
                            "theme": {
                                "color": "#528FF0"
                            }
                        };

                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    } else if (res.flag == 2) {
                        toastrs('Error', res.msg, 'msg');
                    } else {
                        toastrs('Error', data.message, 'msg');
                    }

                }).submit();
            });
        @endif


        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('success', '{{ __('Link Copy on Clipboard') }}', 'success')
        });
    </script>
    <script>
        $(document).on('click', '#shipping', function() {
            var url = $(this).data('url');
            var is_display = $("#shipping").is(":checked");
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    'is_display': is_display,
                },
                success: function(data) {}
            });
        })
    </script>
    <script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            maxFilesize: 20,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{ route('retainer.attechment', [$retainer->id]) }}",
            success: function(file, response) {
                if (response.is_success) {
                    // dropzoneBtn(file, response);
                    location.reload();
                    myDropzone.removeFile(file);
                    toastrs('{{ __('Success') }}', 'File Successfully Uploaded', 'success');
                } else {
                    location.reload();
                    myDropzone.removeFile(response.error);
                    toastrs('Error', response.error, 'error');
                }
            },
            error: function(file, response) {
                myDropzone.removeFile(file);
                location.reload();
                if (response.error) {
                    toastrs('Error', response.error, 'error');
                } else {
                    toastrs('Error', response, 'error');
                }
            }
        });

        myDropzone.on("sending", function(file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("retainer_id", {{ $retainer->id }});
        });
    </script>
@endpush

@section('page-action')
    <div>
        @if ($retainer->is_convert == 0 && $retainer->status != 4)
            @permission('retainer convert invoice')
                <div class="action-btn mb-1">
                    {!! Form::open([
                        'method' => 'get',
                        'route' => ['retainer.convert_invoice', $retainer->id],
                        'id' => 'proposal-form-' . $retainer->id,
                    ]) !!}
                    <a href="#" class="btn btn-sm bg-success align-items-center bs-pass-para show_confirm"
                        data-bs-toggle="tooltip" title="" data-bs-original-title="{{ __('Convert to Invoice') }}"
                        aria-label="Delete" data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                        data-confirm-yes="proposal-form-{{ $retainer->id }}">
                        <i class="ti ti-exchange text-white"></i>
                    </a>
                    {{ Form::close() }}
                </div>
            @endpermission
        @else
            @permission('retainer convert invoice')
                <div class="action-btn ms-2">
                    <a href="{{ route('invoice.show', \Crypt::encrypt($retainer->converted_invoice_id)) }}"
                        class="btn btn-sm bg-success align-items-center" data-bs-toggle="tooltip"
                        title="{{ __('Already convert to Invoice') }}">
                        <i class="ti ti-eye text-white"></i>
                    </a>
                </div>
            @endpermission
        @endif

        <div class="action-btn ms-2">
            <a href="#" class="btn btn-sm btn-primary  cp_link"
                data-link="{{ route('pay.retainer', \Illuminate\Support\Facades\Crypt::encrypt($retainer->id)) }}"
                data-bs-toggle="tooltip" title="{{ __('Copy') }}"
                data-original-title="{{ __('Click to copy Retainer link') }}">
                <span class="btn-inner--icon text-white"><i class="ti ti-file"></i></span>
            </a>
        </div>
    </div>
@endsection

@section('content')
    @permission('retainer send')
        @if ($retainer->status != 4)
            <div class="row">
                <div class="card ">
                    <div class="card-body">
                        <div class="row timeline-wrapper">
                            <div class="col-md-6 col-lg-4 col-xl-4">
                                <div class="timeline-icons"><span class="timeline-dots"></span>
                                    <i class="ti ti-plus text-primary"></i>
                                </div>
                                <h6 class="text-primary my-3">{{ __('Create Retainer') }}</h6>
                                <p class="text-muted text-sm mb-3">
                                    <i
                                        class="ti ti-clock mr-2"></i>{{ __('Created on ') }}{{ company_date_formate($retainer->issue_date) }}
                                </p>
                                @permission('retainer edit')
                                    <a href="{{ route('retainer.edit', \Crypt::encrypt($retainer->id)) }}"
                                        class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                        data-original-title="{{ __('Edit') }}"><i
                                            class="ti ti-edit mr-2"></i>{{ __('Edit') }}</a>
                                @endpermission
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-4">
                                <div class="timeline-icons"><span class="timeline-dots"></span>
                                    <i class="ti ti-mail text-warning"></i>
                                </div>
                                <h6 class="text-warning my-3">{{ __('Send Retainer') }}</h6>
                                <p class="text-muted text-sm mb-3">
                                    @if ($retainer->status != 0)
                                        <i class="ti ti-clock mr-2"></i>{{ __('Sent on') }}
                                        {{ company_date_formate($retainer->send_date) }}
                                    @else
                                        @permission('retainer send')
                                            <small>{{ __('Status') }} : {{ __('Not Sent') }}</small>
                                        @endpermission
                                    @endif
                                </p>

                                @if ($retainer->status == 0)
                                    @permission('retainer send')
                                        <a href="{{ route('retainer.sent', $retainer->id) }}" class="btn btn-sm btn-warning"
                                            data-bs-toggle="tooltip" data-original-title="{{ __('Mark Sent') }}"><i
                                                class="ti ti-send mr-2"></i>{{ __('Send') }}</a>
                                    @endpermission
                                @endif
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-4">
                                <div class="timeline-icons"><span class="timeline-dots"></span>
                                    <i class="ti ti-report-money text-info"></i>
                                </div>
                                <h6 class="text-info my-3">{{ __('Get Paid') }}</h6>
                                <p class="text-muted text-sm mb-3">{{ __('Status') }} : {{ __('Awaiting payment') }} </p>
                                @if ($retainer->status != 0 && $retainer->is_convert == 0)
                                    @permission('retainer payment create')
                                        <a href="#" data-url="{{ route('retainer.payment', $retainer->id) }}"
                                            data-ajax-popup="true" data-title="{{ __('Add Payment') }}" class="btn btn-sm btn-info"
                                            data-original-title="{{ __('Add Payment') }}"><i
                                                class="ti ti-report-money mr-2"></i>{{ __('Add Payment') }}</a> <br>
                                    @endpermission
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endpermission

    @if (\Auth::user()->type == 'company')
        @if ($retainer->status != 0)
            <div class="row justify-content-between align-items-center mb-3">
                <div class="col-md-6">
                    <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="retainer-tab" data-bs-toggle="pill"
                                data-bs-target="#retainer" type="button">{{ __('Retainer') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="receipt-summary-tab" data-bs-toggle="pill"
                                data-bs-target="#receipt-summary" type="button">{{ __('Receipt Summary') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="retainer-attechment-tab" data-bs-toggle="pill"
                                data-bs-target="#retainer-attechment" type="button">{{ __('Attachment') }}</button>
                        </li>
                    </ul>
                </div>

                <div class="col-md-6 d-flex align-items-center justify-content-between justify-content-md-end">
                    <div class="all-button-box mx-2">
                        <a href="{{ route('retainer.resent', $retainer->id) }}"
                            class="btn btn-sm btn-primary">{{ __('Resend Retainer') }}</a>
                    </div>
                    <div class="all-button-box mx-2">
                        <a href="{{ route('retainer.pdf', Crypt::encrypt($retainer->id)) }}" target="_blank"
                            class="btn btn-sm btn-primary">{{ __('Download') }}</a>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="row justify-content-between align-items-center mb-3">
            <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                @permission('retainer send')
                    <div class="all-button-box mx-2">
                        <a href="#" class="btn btn-xs btn-primary btn-icon-only width-auto"
                            data-url="{{ route('retainer.sent', $retainer->id) }}" data-ajax-popup="true"
                            data-title="{{ __('Send Invoice') }}">
                            {{ __('Send Mail') }}
                        </a>
                    </div>
                @endpermission
                <div class="all-button-box mx-2">
                    <a href="{{ route('retainer.pdf', Crypt::encrypt($retainer->id)) }}" target="_blank"
                        class="btn btn-xs btn-primary btn-icon-only width-auto">
                        {{ __('Download') }}
                    </a>
                </div>

                @if (
                    $retainer->is_convert == 0 &&
                        $retainer->getDue() > 0 &&
                        !empty($company_payment_setting) &&
                        ($company_payment_setting['is_stripe_enabled'] == 'on' ||
                            $company_payment_setting['is_paypal_enabled'] == 'on' ||
                            $company_payment_setting['is_paystack_enabled'] == 'on' ||
                            $company_payment_setting['is_flutterwave_enabled'] == 'on' ||
                            $company_payment_setting['is_razorpay_enabled'] == 'on' ||
                            $company_payment_setting['is_mercado_enabled'] == 'on' ||
                            $company_payment_setting['is_paytm_enabled'] == 'on' ||
                            $company_payment_setting['is_mollie_enabled'] == 'on' ||
                            $company_payment_setting['is_paypal_enabled'] == 'on' ||
                            $company_payment_setting['is_skrill_enabled'] == 'on' ||
                            $company_payment_setting['is_coingate_enabled'] == 'on' ||
                            $company_payment_setting['is_paymentwall_enabled'] == 'on'))
                    <div class="all-button-box">
                        <a href="#" class="btn btn-xs btn-primary btn-icon-only width-auto" data-bs-toggle="modal"
                            data-bs-target="#paymentModal">
                            {{ __('Pay Now') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade active show" id="retainer" role="tabpanel"
                    aria-labelledby="pills-user-tab-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice">
                                <div class="invoice-print">
                                    <div class="row invoice-title mt-2">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                            <h2>{{ __('Retainer') }}</h2>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                            <h3 class="retainer-number">
                                                {{ Modules\Retainer\Entities\Retainer::retainerNumberFormat($retainer->retainer_id) }}
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
                                                        {{ company_date_formate($retainer->issue_date) }}<br><br>
                                                    </small>
                                                </div>
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
                                                    <strong>{{ __('Tax Number ') }} :
                                                    </strong>{{ !empty($customer->tax_number) ? $customer->tax_number : '' }}
                                                </small>
                                            </div>
                                        @endif
                                        @if (!empty($customer->shipping_name))
                                            @if (company_setting('retainer_shipping_display') == 'on')
                                                <div class="col ">
                                                    <small>
                                                        <strong>{{ __('Shipped To') }} :</strong><br>
                                                        {{ !empty($customer->shipping_name) ? $customer->shipping_name : '' }}<br>
                                                        {{ !empty($customer->shipping_address) ? $customer->shipping_address : '' }}<br>
                                                        {{ !empty($customer->shipping_city) ? $customer->shipping_city . ' ,' : '' }}
                                                        {{ !empty($customer->shipping_state) ? $customer->shipping_state . ' ,' : '' }}
                                                        {{ !empty($customer->shipping_zip) ? $customer->shipping_zip : '' }}<br>
                                                        {{ !empty($customer->shipping_country) ? $customer->shipping_country : '' }}<br>
                                                        {{ !empty($customer->shipping_phone) ? $customer->shipping_phone : '' }}<br>
                                                        <strong>{{ __('Tax Number ') }} :
                                                        </strong>{{ !empty($customer->tax_number) ? $customer->tax_number : '' }}
                                                    </small>
                                                </div>
                                            @endif
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
                                                        class="badge fix_badge rounded px-3 p-1 bg-primary ">{{ __(Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                                @elseif($retainer->status == 1)
                                                    <span
                                                        class="badge fix_badge rounded px-3 p-1 bg-info">{{ __(Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                                @elseif($retainer->status == 2)
                                                    <span
                                                        class="badge fix_badge rounded px-3 p-1 bg-secondary">{{ __(Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                                @elseif($retainer->status == 3)
                                                    <span
                                                        class="badge fix_badge rounded px-3 p-1 bg-warning">{{ __(Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                                @elseif($retainer->status == 4)
                                                    <span
                                                        class="badge fix_badge rounded px-3 p-1 bg-danger">{{ __(Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                                @endif
                                            </small>
                                        </div>

                                        @if (!empty($customFields) && count($retainer->customField) > 0)
                                            @foreach ($customFields as $field)
                                                <div class="col text-md-end">
                                                    <small>
                                                        <strong>{{ $field->name }} :</strong><br>
                                                        <span>{{ !empty($retainer->customField) ? (isset($retainer->customField[$field->id]) ? $retainer->customField[$field->id] : '-') : '-' }}</span>
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
                                                <table class="table mb-0 table-striped">
                                                    <tr>
                                                        <th data-width="40" class="text-dark">#</th>
                                                        @if ($retainer->retainer_module == 'account')
                                                            <th class="text-dark">{{ __('Item Type') }}</th>
                                                            <th class="text-dark">{{ __('Item') }}</th>
                                                        @elseif($retainer->retainer_module == 'taskly')
                                                            <th class="text-dark">{{ __('Project') }}</th>
                                                        @endif
                                                        <th class="text-dark">{{ __('Quantity') }}</th>
                                                        <th class="text-dark">{{ __('Rate') }}</th>
                                                        <th class="text-dark">{{ __('Discount') }}</th>
                                                        <th class="text-dark">{{ __('Tax') }}</th>
                                                        <th class="text-dark">{{ __('Description') }}</th>
                                                        <th class="text-right text-dark" width="12%">
                                                            {{ __('Price') }}<br>
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
                                                        $data = 0;
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
                                                                    $taxDataPrice = \Modules\Retainer\Entities\Retainer::taxRate($taxe->rate, $iteam->price, $iteam->quantity, $iteam->discount);
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
                                                                <td>{{ !empty($iteam->product_type) ? Str::ucfirst($iteam->product_type) : '--' }}
                                                                </td>
                                                                <td>{{ !empty($iteam->product()) ? $iteam->product()->name : '' }}
                                                                </td>
                                                            @elseif($retainer->retainer_module == 'taskly')
                                                                <td>{{ !empty($iteam->product()) ? $iteam->product()->title : '' }}
                                                                </td>
                                                            @endif
                                                            <td>{{ $iteam->quantity }}</td>
                                                            <td>{{ currency_format_with_sym($iteam->price) }}</td>
                                                            <td>{{ currency_format_with_sym($iteam->discount) }}</td>
                                                            <td>

                                                                @if (!empty($iteam->tax))
                                                                    <table>
                                                                        @php
                                                                            $totalTaxRate = 0;
                                                                            $data = 0;
                                                                        @endphp
                                                                        @foreach ($taxes as $tax)
                                                                            @php
                                                                                $taxPrice = \Modules\Retainer\Entities\Retainer::taxRate($tax->rate, $iteam->price, $iteam->quantity, $iteam->discount);

                                                                                $totalTaxPrice += $taxPrice;
                                                                                $data += $taxPrice;
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $tax->name . ' (' . $tax->rate . '%)' }}
                                                                                </td>
                                                                                <td>{{ currency_format_with_sym($taxPrice) }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                        @php
                                                                            array_push($TaxPrice_array, $data);
                                                                        @endphp
                                                                    </table>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>

                                                            <td style="white-space: break-spaces;">
                                                                {{ !empty($iteam->description) ? $iteam->description : '-' }}
                                                            </td>
                                                            @php
                                                                $tr_tex = array_key_exists($key, $TaxPrice_array) == true ? $TaxPrice_array[$key] : 0;
                                                            @endphp
                                                            <td class="">
                                                                {{ currency_format_with_sym($iteam->price * $iteam->quantity - $iteam->discount + $tr_tex) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            @if ($retainer->retainer_module == 'account')
                                                                <td></td>
                                                            @endif
                                                            <td><b>{{ __('Total') }}</b></td>
                                                            <td><b>{{ $totalQuantity }}</b></td>
                                                            <td><b>{{ currency_format_with_sym($totalRate) }}</b></td>
                                                            <td><b>{{ currency_format_with_sym($totalDiscount) }}</b></td>
                                                            <td><b>{{ currency_format_with_sym($totalTaxPrice) }}</b></td>
                                                            <td></td>
                                                        </tr>
                                                        @php
                                                            $colspan = 6;
                                                            if ($retainer->retainer_module == 'account') {
                                                                $colspan = 7;
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td colspan="{{ $colspan }}"></td>
                                                            <td class="text-right"><b>{{ __('Sub Total') }}</b></td>
                                                            <td class="text-right">
                                                                {{ currency_format_with_sym($retainer->getSubTotal()) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="{{ $colspan }}"></td>
                                                            <td class="text-right"><b>{{ __('Discount') }}</b></td>
                                                            <td class="text-right">
                                                                {{ currency_format_with_sym($retainer->getTotalDiscount()) }}
                                                            </td>
                                                        </tr>
                                                        @if (!empty($taxesData))
                                                            @foreach ($taxesData as $taxName => $taxPrice)
                                                                <tr>
                                                                    <td colspan="{{ $colspan }}"></td>
                                                                    <td class="text-right"><b>{{ $taxName }}</b>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ currency_format_with_sym($taxPrice) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        <tr>
                                                            <td colspan="{{ $colspan }}"></td>
                                                            <td class="blue-text text-right"><b>{{ __('Total') }}</b>
                                                            </td>
                                                            <td class="blue-text text-right">
                                                                {{ currency_format_with_sym($retainer->getTotal()) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="{{ $colspan }}"></td>
                                                            <td class="text-right"><b>{{ __('Paid') }}</b></td>
                                                            <td class="text-right">
                                                                {{ currency_format_with_sym($retainer->getTotal() - $retainer->getDue()) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="{{ $colspan }}"></td>
                                                            <td class="text-right"><b>{{ __('Due') }}</b></td>
                                                            <td class="text-right">
                                                                {{ currency_format_with_sym($retainer->getDue()) }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- </div> -->
                    </div>
                </div>
                <div class="tab-pane fade" id="receipt-summary" role="tabpanel" aria-labelledby="pills-user-tab-2">
                    <h5 class="h4 d-inline-block font-weight-400 my-2">{{ __('Receipt Summary') }}</h5>
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table mb-0 pc-dt-simple" id="retainer-receipt-summary">
                                    <tr>
                                        <th class="text-dark">{{ __('Date') }}</th>
                                        <th class="text-dark">{{ __('Amount') }}</th>
                                        <th class="text-dark">{{ __('Payment Type') }}</th>
                                        <th class="text-dark">{{ __('Account') }}</th>
                                        <th class="text-dark">{{ __('Reference') }}</th>
                                        <th class="text-dark">{{ __('Description') }}</th>
                                        <th class="text-dark">{{ __('Receipt') }}</th>
                                        <th class="text-dark">{{ __('OrderId') }}</th>
                                        @permission('retainer payment delete')
                                            <th class="text-dark">{{ __('Action') }}</th>
                                        @endpermission
                                    </tr>
                                    @if (!empty($retainer->payments) || !empty($bank_transfer_payments))
                                        @foreach ($bank_transfer_payments as $bank_transfer_payment)
                                            <tr>
                                                <td>{{ company_datetime_formate($bank_transfer_payment->created_at) }}
                                                </td>
                                                <td class="text-right">
                                                    {{ currency_format_with_sym($bank_transfer_payment->price) }}
                                                </td>
                                                <td>{{ $bank_transfer_payment->payment_type }}</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>
                                                    @if (!empty($bank_transfer_payment->attachment))
                                                        <a href="{{ get_file($bank_transfer_payment->attachment) }}"
                                                            target="_blank"> <i class="ti ti-file"></i></a>
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>{{ $bank_transfer_payment->order_id }}</td>
                                                <td>
                                                    <div class="action-btn bg-primary ms-2">
                                                        @if ($bank_transfer_payment->payment_type == 'Bank Transfer')
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ route('invoice.bank.request.edit', $bank_transfer_payment->id) }}"
                                                                data-ajax-popup="true" data-size="md"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-title="{{ __('Request Action') }}"
                                                                data-bs-original-title="{{ __('Action') }}">
                                                                <i class="ti ti-caret-right text-white"></i>
                                                            </a>
                                                        @elseif($bank_transfer_payment->payment_type == 'Bank Account')
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ route('invoice.bankaccount.request.edit', $bank_transfer_payment->id) }}"
                                                                data-ajax-popup="true" data-size="md"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-title="{{ __('Request Action') }}"
                                                                data-bs-original-title="{{ __('Action') }}">
                                                                <i class="ti ti-caret-right text-white"></i>
                                                            </a>
                                                        @endif
                                                    </div>

                                                    <div class="action-btn bg-danger ms-2">
                                                        @if ($bank_transfer_payment->payment_type == 'Bank Transfer')
                                                            {{ Form::open(['route' => ['bank-transfer-request.destroy', $bank_transfer_payment->id], 'class' => 'm-0']) }}
                                                            @method('DELETE')
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $bank_transfer_payment->id }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            {{ Form::close() }}
                                                        @elseif($bank_transfer_payment->payment_type == 'Bank Account')
                                                            {{ Form::open(['route' => ['invoice.bankaccount.request.destroy', $bank_transfer_payment->id], 'class' => 'm-0']) }}
                                                            @method('DELETE')
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $bank_transfer_payment->id }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            {{ Form::close() }}
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @foreach ($retainer->payments as $key => $payment)
                                            <tr>
                                                <td>{{ company_date_formate($payment->date) }}</td>
                                                <td>{{ currency_format_with_sym($payment->amount) }}</td>
                                                <td>{{ $payment->payment_type }}</td>
                                                @if (module_is_active('Account'))
                                                    <td>{{ !empty($payment->bankAccount) ? $payment->bankAccount->bank_name . ' ' . $payment->bankAccount->holder_name : '--' }}
                                                    </td>
                                                @else
                                                    <td>--</td>
                                                @endif
                                                <td>{{ !empty($payment->reference) ? $payment->reference : '--' }}</td>
                                                <td>{{ !empty($payment->description) ? $payment->description : '--' }}
                                                </td>
                                                <td>
                                                    @if (!empty($payment->add_receipt) && empty($payment->receipt))
                                                        <a href="{{ get_file($payment->add_receipt) }}" download=""
                                                            class="btn btn-sm btn-primary btn-icon rounded-pill"
                                                            target="_blank"><span class="btn-inner--icon"><i
                                                                    class="ti ti-download"></i></span></a>
                                                        <a href="{{ get_file($payment->add_receipt) }}"
                                                            class="btn btn-sm btn-secondary btn-icon rounded-pill"
                                                            target="_blank"><span class="btn-inner--icon"><i
                                                                    class="ti ti-crosshair"></i></span></a>
                                                    @elseif (!empty($payment->receipt) && empty($payment->add_receipt) && $payment->type == 'STRIPE')
                                                        <a href="{{ $payment->receipt }}" target="_blank">
                                                            <i class="ti ti-file"></i>
                                                        </a>
                                                    @elseif($payment->payment_type == 'Bank Transfer' || $payment->payment_type == 'Bank Account')
                                                        <a href="{{ !empty($payment->receipt) ? (check_file($payment->receipt) ? get_file($payment->receipt) : '#!') : '#!' }}"
                                                            target="_blank">
                                                            <i class="ti ti-file"></i>
                                                        </a>
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>{{ !empty($payment->order_id) ? $payment->order_id : '--' }}</td>

                                                @permission('retainer payment delete')
                                                    <td>

                                                        @if ($payment->payment_type != 'Bank Account')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'post',
                                                                'route' => ['retainer.payment.destroy', $retainer->id, $payment->id],
                                                                'id' => 'delete-form-' . $payment->id,
                                                            ]) !!}

                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $payment->id }}">
                                                                <i class="ti ti-trash text-white text-white"></i>
                                                            </a>

                                                            {!! Form::close() !!}
                                                        </div>
                                                        @else
                                                          -
                                                        @endif
                                                    </td>
                                                @endpermission

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="{{ Laratrust::hasPermission('retainer product delete') ? '11' : '10' }}"
                                                class="text-center text-dark">
                                                <p>{{ __('No Data Found') }}</p>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="retainer-attechment" role="tabpanel" aria-labelledby="pills-user-tab-4">
                    <div class="row">
                        <h5 class="d-inline-block my-3">{{ __('Attachments') }}</h5>
                        <div class="col-3">
                            <div class="card border-primary border">
                                <div class="card-body table-border-style">
                                    <div class="col-md-12 dropzone browse-file" id="dropzonewidget">
                                        <div class="dz-message my-5" data-dz-message>
                                            <span>{{ __('Drop files here to upload') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="card border-primary border">
                                <div class="card-body table-border-style">
                                    <div class="table-responsive">
                                        <table class="table mb-0 pc-dt-simple" id="attachment">
                                            <thead>
                                                <tr>
                                                    <th class="text-dark">{{ __('#') }}</th>
                                                    <th class="text-dark">{{ __('File Name') }}</th>
                                                    <th class="text-dark">{{ __('File Size') }}</th>
                                                    <th class="text-dark">{{ __('Date Created') }}</th>
                                                    <th class="text-dark">{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            @forelse($retainer_attachment as $key =>$attachment)
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $attachment->file_name }}</td>
                                                <td>{{ $attachment->file_size }}</td>
                                                <td>{{ company_date_formate($attachment->created_at) }}</td>
                                                <td>
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="{{ url($attachment->file_path) }}"
                                                            class="mx-3 btn btn-sm align-items-center"
                                                            title="{{ __('Download') }}" target="_blank" download>
                                                            <i class="ti ti-download text-white"></i>
                                                        </a>
                                                    </div>
                                                    <div class="action-btn bg-danger ms-2">
                                                        {{ Form::open(['route' => ['retainer.attachment.destroy', $attachment->id], 'class' => 'm-0']) }}
                                                        @method('DELETE')
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $attachment->id }}">
                                                            <i class="ti ti-trash text-white text-white"></i>
                                                        </a>
                                                        {{ Form::close() }}
                                                    </div>
                                                </td>
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
                </div>
            </div>
        </div>
    </div>

@endsection
