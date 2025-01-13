    <div class="card" id="toyyibpay-sidenav">
        {{ Form::open(['route' => 'toyyibpay.company_setting.store', 'enctype' => 'multipart/form-data']) }}

        <div class="card-header">
            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-10">
                    <h5 class="">{{ __('Toyyibpay') }}</h5>
                    <small>{{ __('These details will be used to collect subscription plan payments.Each subscription plan will have a payment button based on the below configuration.') }}</small>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                    <div class="form-check form-switch custom-switch-v1 float-end">
                        <input type="checkbox" name="toyyibpay_payment_is_on" class="form-check-input input-primary" id="toyyibpay_payment_is_on"
                        {{ (isset($settings['toyyibpay_payment_is_on']) ? $settings['toyyibpay_payment_is_on'] : 'off') == 'on' ? ' checked ' : '' }}>
                        <label class="form-check-label" for="toyyibpay_payment_is_on"></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_toyyibpay_secrect_key" class="form-label">{{ __('Secrect Key') }}</label>
                        <input class="form-control public_webhook_toyyibpay" placeholder="{{ __('Secrect Key') }}"
                            name="company_toyyibpay_secrect_key" type="text" value="{{ isset($settings['company_toyyibpay_secrect_key']) ? $settings['company_toyyibpay_secrect_key'] : '' }}"
                            {{ (isset($settings['toyyibpay_payment_is_on']) ? $settings['toyyibpay_payment_is_on'] : 'off')  == 'on' ? '' : ' disabled' }} id="company_toyyibpay_secrect_key">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_toyyibpay_category_code" class="form-label">{{ __('Category Code') }}</label>
                        <input class="form-control public_webhook_toyyibpay" placeholder="{{ __('Category Code') }}" name="company_toyyibpay_category_code"
                            type="text" value="{{ isset($settings['company_toyyibpay_category_code']) ? $settings['company_toyyibpay_category_code'] : '' }}"
                            {{ (isset($settings['toyyibpay_payment_is_on']) ? $settings['toyyibpay_payment_is_on'] : 'off')  == 'on' ? '' : ' disabled' }} id="company_toyyibpay_category_code">
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer text-end">
            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
        </div>
        {{ Form::close() }}
    </div>

<script>
    $(document).on('click', '#toyyibpay_payment_is_on', function() {
        if ($('#toyyibpay_payment_is_on').prop('checked')) {
            $(".public_webhook_toyyibpay").removeAttr("disabled");
        } else {
            $('.public_webhook_toyyibpay').attr("disabled", "disabled");
        }
    });
</script>
