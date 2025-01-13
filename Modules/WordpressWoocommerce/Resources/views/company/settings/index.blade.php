<div class="card" id="Woocommerce_sidenav">
    {{ Form::open(array('route' => 'wordpress.setting','method' => 'post')) }}
    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5 class="">{{ __('Woocommerce Settings') }}</h5>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="woocommerce_setting_is_on" class="form-check-input input-primary" id="woocommerce_setting_is_on" {{ (isset($settings['woocommerce_setting_is_on']) && $settings['woocommerce_setting_is_on']=='on') ?' checked ':'' }} >
                    <label class="form-check-label" for="woocommerce_setting_is_on"></label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group">
                    {{Form::label('store_url',__('Store Url'),array('class'=>'form-label')) }}
                    {{Form::text('store_url',isset($settings['store_url']) ? $settings['store_url'] :'',array('class'=>'form-control', 'placeholder' => 'Enter Store Url'))}}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{Form::label('consumer_key',__('Consumer Key'),array('class'=>'form-label')) }}
                    {{Form::text('consumer_key',isset($settings['consumer_key']) ? $settings['consumer_key'] :'',array('class'=>'form-control', 'placeholder' => 'Enter Consumer Key'))}}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{Form::label('consumer_secret',__('Consumer Secret'),array('class'=>'form-label')) }}
                    {{Form::text('consumer_secret',isset($settings['consumer_secret']) ? $settings['consumer_secret'] :'',array('class'=>'form-control', 'placeholder' => 'Enter Consumer Secret'))}}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
    </div>
    {{Form::close()}}
</div>

<script>
    $(document).ready(function () {
        woocommerceSetting();
    });
    $(document).on('click','#woocommerce_setting_is_on',function(){
        woocommerceSetting();
    });

    function woocommerceSetting() {
        if( $('#woocommerce_setting_is_on').prop('checked') )
        {
            $("#store_url").removeAttr("disabled");
            $("#consumer_key").removeAttr("disabled");
            $("#consumer_secret").removeAttr("disabled");
        } else {
            $('#store_url').attr("disabled", "disabled");
            $('#consumer_key').attr("disabled", "disabled");
            $('#consumer_secret').attr("disabled", "disabled");
        }
    }
</script>
