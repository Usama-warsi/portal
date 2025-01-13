{{ Form::model($user_array, ['route' => ['rotas.add_remove_employee_popup'], 'method' => 'get']) }}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        {{ Form::hidden('id_array', $id_array) }}
        {{ Form::hidden('designation', $designation) }}
        @foreach ($user_array as $val)
            <div class="custom-control custom-checkbox d-inline-block mx-3 my-2">
                <input class="form-check-input" id="{{ $val['profile_id'] }}" name="{{ $val['profile_id'] }}" type="checkbox" value="{{ $val['designation_id'] }}" {{ ($val['has_location'] == 1) ? 'checked' : '' }}>
                <label for="{{ $val['profile_id'] }}" class="custom-label">{{ $val['name'] }}</label>
            </div>
        @endforeach
    </div>
    <div class="col-12">
        <div class="modal-footer border-0 p-0">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn  btn-primary rotas_edit_btn">{{ __('Upadte') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}
