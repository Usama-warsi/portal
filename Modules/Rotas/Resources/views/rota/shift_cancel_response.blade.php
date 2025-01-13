    {{ Form::model($rota, ['route' => ['rotas.shift.disable.reply'], 'method' => 'post']) }}
    <div class="row">
    <div class="modal-body">

        <div class="col-xs-12 col-sm-12 col-md-12">
            {{ Form::hidden('rota_id', $rota->id) }}
            <p>{{ $msg }}</p>
            <div class="form-group">
                {{ Form::label('', __('Unavailability Reason'), ['class' => 'form-label']) }}
                {{ Form::textarea('shift_cancel_employee_msg', null, ['class' => 'form-control autogrow', 'rows' => '2', 'style' => 'resize: none', 'readonly' => true, 'disabled' => true]) }}
            </div>

            <div class="form-group">
                {{ Form::label('', __('Reply'), ['class' => 'form-label']) }}
                {{ Form::textarea('shift_cancel_owner_msg', null, ['class' => 'form-control autogrow', 'rows' => '2', 'style' => 'resize: none']) }}
            </div>
            <div class="form-group">
                {{ Form::label('', __('Unavailability Requested'), ['class' => 'form-label']) }}
                <br>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="shift_status" checked value="disable">
                    {{ __('Approve') }}
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="shift_status" value="enable">
                    {{ __('Deny') }}
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
        </div>


        {{ Form::close() }}
