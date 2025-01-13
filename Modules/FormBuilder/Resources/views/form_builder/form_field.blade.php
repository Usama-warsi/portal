{{ Form::model($formField, array('route' => array('form.bind.store', $form->id))) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 pb-3">
            <span class="text-xs"><b>{{__('It will auto convert from response on selected module based on below setting. It will not convert old response.')}}</b></span>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('active', __('Active'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8 pt-1">
            <div class="d-flex radio-check">
                <div class="custom-control custom-radio custom-control-inline ">
                    <input type="radio" id="on" value="1" name="is_lead_active" class="form-check-input lead_radio" {{($form->is_lead_active == 1) ? 'checked' : ''}}>
                    <label class="form-check-labe" for="on">{{__('On')}}</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline" style="margin-left: 10px;">
                    <input type="radio" id="off" value="0" name="is_lead_active" class="form-check-input lead_radio" {{($form->is_lead_active == 0) ? 'checked' : ''}}>
                    <label class="form-check-labe" for="off">{{__('Off')}}</label>
                </div>
            </div>
        </div>
        <div class="form-group select_module d-none">
            {{ Form::label('', __('Module'), ['class' => 'form-label']) }}
            {{ Form::select('module', $formBuilderModule, null, ['class' => 'form-control text-capitalize module', 'required' => 'required']) }}
        </div>
    </div>
    <div id="relatedfields">
       
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Save')}}</button>
</div>
{{ Form::close() }}


<script>
    $(document).ready(function () {
        var lead_active = {{$form->is_lead_active}};
        if (lead_active == 1) {
            $('.module').trigger("change");
            $('.select_module').removeClass('d-none');
        }
    });
    $(document).on('click', function () {
        $('.lead_radio').on('click', function () {
            var inputValue = $(this).attr("value");
            if (inputValue == 1) {
                $('.module').trigger("change");
                $('.select_module').removeClass('d-none');
            } else {
                $('.select_module').addClass('d-none');
                $('#relatedfields').html('');
            }
            $('.lead_radio').removeAttr('checked');
            $(this).prop("checked", true);
        })
    });
    $(document).on("change", ".module", function() {
        var form_id = {{$form->id}};
        var module_id = $(this).val();
        if (module_id != 0) {
            $.ajax({
                url: '{{ route('form.builder.modules') }}',
                type: 'POST',
                data: {
                    module: module_id,
                    form_id: form_id
                },
                success: function(data) {
                    $('#relatedfields').html(data.html)
                    choices();
                }
            });
        } else {
            $('#relatedfields').html('')
        }
    });
</script>
