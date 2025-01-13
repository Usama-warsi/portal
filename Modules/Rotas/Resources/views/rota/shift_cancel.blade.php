{{ Form::model($rota, ['route' => ['rotas.shift.disable'], 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            {{ Form::hidden('rota_id', $rota->id) }}
            {{ Form::hidden('shift_status', 'request') }}
            <div class="form-group">
                {{ Form::label('', __("Can't work this shift ?"), ['class' => 'form-label']) }}
                {{ Form::textarea('shift_cancel_employee_msg', null, ['class' => 'form-control autogrow', 'rows' => '2', 'style' => 'resize: none']) }}
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <input type="submit" class="btn btn-primary" value="{{ __('Save') }}">
    </div>
</div>
{{ Form::close() }}
