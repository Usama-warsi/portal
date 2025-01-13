@permission('salesagent manage')
<div class="card" id="salesagent-sidenav">
    {{ Form::open(array('route' => 'salesagents.setting.save','method' => 'post')) }}
    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5 class="">{{ __('Sales Agent Settings') }}</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group">
                    {{Form::label('salesagent_prefix',__('Sales Agent Prefix'),array('class'=>'form-label')) }}
                    {{Form::text('salesagent_prefix',!empty(company_setting('salesagent_prefix')) ? company_setting('salesagent_prefix') :'#AGENT',array('class'=>'form-control', 'placeholder' => 'Enter Customer Prefix'))}}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
    </div>
    {{Form::close()}}
</div>
@endpermission
<script>
     $(document).on("change", "select[name='bill_template'], input[name='bill_color']", function ()
     {
            var template = $("select[name='bill_template']").val();
            var color = $("input[name='bill_color']:checked").val();
            $('#bill_frame').attr('src', '{{url('/bill/preview')}}/' + template + '/' + color);
        });
</script>
