@php
    $company_setting = getCompanyAllSetting();
@endphp

<div class="card" id="zoom-sidenav">
    {{ Form::open(['route' => 'zoom-meeting.setting.store', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header">
        <h5>{{ __('Zoom Metting') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-label ">{{ __('Zoom Account ID') }}</label> <br>
                <input class="form-control" placeholder="{{ __('Zoom Account ID') }}" name="zoom_account_id" type="text"
                    value="{{ isset($company_setting['zoom_account_id']) ? $company_setting['zoom_account_id'] : ''  }}">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label ">{{ __('Zoom Client ID') }}</label> <br>
                <input class="form-control" placeholder="{{ __('Zoom Client ID') }}" name="zoom_client_id" type="text"
                    value="{{ isset($company_setting['zoom_client_id']) ? $company_setting['zoom_client_id'] :'' }}">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label ">{{ __('Zoom Client Secret') }}</label> <br>
                <input class="form-control" placeholder="{{ __('Zoom Client Secret') }}" name="zoom_client_secret" type="text"
                    value="{{ isset($company_setting['zoom_client_secret']) ? $company_setting['zoom_client_secret'] :'' }}">
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
    </div>
    {{ Form::close() }}
</div>
