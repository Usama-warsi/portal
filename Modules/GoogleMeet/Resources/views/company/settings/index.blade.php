@permission('googlemeet manage')
    <div class="card" id="googlemeet-sidenav">
        {{ Form::open(['route' => 'googlemeet.setting.store', 'enctype' => 'multipart/form-data']) }}
        <div class="card-header">
            <h5>{{ __('Google Meet Settings') }}</h5>
            <small><b class="text-danger">{{ __('Note: ') }}</b>{{ __('While creating json credentials add this URL in "Authorised redirect URIs" Section -') }} {{ env('APP_URL').'oauth' }} </small>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                    {{Form::label('Google Meet json file',__('Google Meet Json File'),['class'=>'col-form-label']) }}
                    <input type="file" class="form-control"  name="google_meet_json_file" id="google_meet_json_file" >
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
        </div>
        {{ Form::close() }}
    </div>
@endpermission
