<div class="card" id="recaptcha-sidenav">
    {{ Form::open(['route' => 'recaptcha.setting.store', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5 class="">{{ __('ReCaptcha Settings') }}</h5>
                <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/" target="_blank"
                    class="text-blue">
                    <small>{{ __('How to Get Google reCaptcha Site and Secret key') }}</small>
                </a>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="google_recaptcha_is_on" class="form-check-input input-primary"
                        id="google_recaptcha_is_on"
                        {{ isset($settings['google_recaptcha_is_on']) && $settings['google_recaptcha_is_on'] == 'on' ? ' checked ' : '' }}>
                    <label class="form-check-label" for="google_recaptcha_is_on"></label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group col switch-width">
                    {{ Form::label('google_recaptcha_version', __('Google Recaptcha Version'), ['class' => ' col-form-label']) }}

                    {{ Form::select('google_recaptcha_version', $google_recaptcha_version, isset($settings['google_recaptcha_version']) ? $settings['google_recaptcha_version'] : 'v2-checkbox', ['id' => 'google_recaptcha_version', 'class' => 'form-control choices', 'searchEnabled' => 'true']) }}
                </div>
            </div>
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="google_recaptcha_key" class="form-label">{{ __('Google Recaptcha Key') }}</label>
                    <input class="form-control google_recaptcha" required="required"
                        placeholder="{{ __('Enter Google Recaptcha Key') }}" name="google_recaptcha_key" type="text"
                        value="{{ isset($settings['google_recaptcha_key']) ? $settings['google_recaptcha_key'] : '' }}"
                        {{ isset($settings['google_recaptcha_is_on']) && $settings['google_recaptcha_is_on'] == 'on' ? '' : ' disabled' }}
                        id="google_recaptcha_key">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="google_recaptcha_secret" class="form-label">{{ __('Google Recaptcha Secret') }}</label>
                    <input class="form-control google_recaptcha" required="required"
                        placeholder="{{ __('Enter Google Recaptcha Secret') }}" name="google_recaptcha_secret"
                        type="text"
                        value="{{ isset($settings['google_recaptcha_secret']) ? $settings['google_recaptcha_secret'] : '' }}"
                        {{ isset($settings['google_recaptcha_is_on']) && $settings['google_recaptcha_is_on'] == 'on' ? '' : ' disabled' }}
                        id="google_recaptcha_secret">
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
    $(document).on('click', '#google_recaptcha_is_on', function() {
        if ($('#google_recaptcha_is_on').prop('checked')) {
            $(".google_recaptcha").removeAttr("disabled");
        } else {
            $('.google_recaptcha').attr("disabled", "disabled");
        }
    });
</script>
