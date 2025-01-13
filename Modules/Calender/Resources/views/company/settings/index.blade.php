@php
    $company_setting = getCompanyAllSetting();
@endphp
<div class="" id="google_calendar_sidenav">
    <div class="card">
        {{ Form::open(['url' => route('google.calender.settings'), 'enctype' => 'multipart/form-data']) }}
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-10">
                    <h5 class="">{{ __('Google Calendar') }}</h5>
                    <small>{{ __('These details will be used to collect your Google Calendar events.') }}</small>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                    <div class="form-check form-switch custom-switch-v1 float-end">
                        <input type="checkbox" name="google_calendar_enable" class="form-check-input input-primary"
                            id="google_calendar_enable"{{ (isset($company_setting['google_calendar_enable']) ? $company_setting['google_calendar_enable'] : 'off') == 'on' ? ' checked ' : '' }}>
                        <label class="form-check-label" for="google_calendar_enable"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                    {{ Form::label('Google calendar id', __('Google Calendar Id'), ['class' => 'col-form-label']) }}
                    <input type="text" class="form-control is_google_calender_on"
                        value="{{ isset($company_setting['google_calender_id']) ? $company_setting['google_calender_id'] : '' }}"
                        name="google_calender_id" placeholder="Google Calendar Id"
                        {{ (isset($company_setting['google_calendar_enable']) ? $company_setting['google_calendar_enable'] : 'off') == 'on' ? '' : ' disabled' }}>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                    {{ Form::label('Google calendar json file', __('Google Calendar json File'), ['class' => 'col-form-label']) }}
                    <input type="file" class="form-control is_google_calender_on" accept=".json"
                        name="google_calender_json_file" id="file"
                        {{ (isset($company_setting['google_calendar_enable']) ? $company_setting['google_calendar_enable'] : 'off') == 'on' ? '' : ' disabled' }}>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-6">
                    @if (company_setting('google_calendar_enable') == 'on')
                        @if (check_file(company_setting('google_calender_json_file')))
                            <label for="file" class="form-label">{{ __('Download Calendar Json File') }}</label>
                            <a href="{{ get_file(company_setting('google_calender_json_file')) }}"
                                class="btn btn-primary mr-3" download>
                                <i class="ti ti-download"></i>
                            </a>
                        @endif
                    @endif

                </div>
                <div class="col-6 text-end">
                    <input class="btn btn-print-invoice btn-primary" type="submit" value="{{ __('Save Changes') }}">
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
<script>
    $(document).on('click', '#google_calendar_enable', function() {
        if ($('#google_calendar_enable').prop('checked')) {
            $(".is_google_calender_on").removeAttr("disabled");
        } else {
            $('.is_google_calender_on').attr("disabled", "disabled");
        }
    });
</script>
