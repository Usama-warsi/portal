{{ Form::model($rota, ['route' => ['rota.update', $rota->id], 'method' => 'PUT', 'class' => 'rotas_edit_frm']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn', ['template_module' => 'rota', 'module' => 'Rotas'])
        @endif
    </div>
    <div class="row">
        {{ Form::input('hidden', 'u_url', route('rota.update', $rota->id)) }}
        {{ Form::input('hidden', 'rota_id', $rota->id) }}
        {{ Form::input('hidden', 'user_id', $user_id) }}
        {{ Form::input('hidden', 'rotas_date', $date) }}
        {{ Form::input('hidden', 'location_id', null, ['id' => 'rotas_ctrate_location']) }}
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('', __('Start Time'), ['class' => 'form-label']) }}
                {!! Form::time('start_time', null, [
                    'class' => 'form-control start_time rotas_time start_data',
                    'placeholder' => 'Select time',
                    'required' => true,
                ]) !!}
            </div>
            <p class="text-danger d-none" id="start_time_validation">{{ __('This filed is required.') }}</p>

        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('', __('End Time'), ['class' => 'form-label']) }}
                {!! Form::time('end_time', null, [
                    'class' => 'form-control end_time rotas_time end_data',
                    'placeholder' => 'Select time',
                    'required' => true,
                ]) !!}
            </div>
            <p class="text-danger d-none" id="end_time_validation">{{ __('This filed is required.') }}</p>

        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('', __('Break'), ['class' => 'form-label']) }}
                {{ Form::input('number', 'break_time', null, ['class' => 'form-control', 'required' => true]) }}
            </div>
        </div>
        {{-- <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __('Role'), ['class' => 'form-label']) }}
                {{ Form::select('role_id', $role_option,null, ['class' => 'form-control multi-select', 'id'=>'choices-multiplepop_roleotiuon' ]) }}
            </div>
        </div> --}}
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('', __('Note'), ['class' => 'form-label']) }}
                {{ Form::textarea('note', null, ['class' => 'form-control autogrow note_data', 'rows' => '2', 'style' => 'resize: none']) }}
                <small>{{ __('Employees can only see notes for their own shifts') }}</small>
            </div>
            <p class="text-danger d-none" id="note_validation">{{ __('This filed is required.') }}</p>
        </div>
    </div>
</div>


<div class="col-12">
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" id="submit" class="btn  btn-primary rotas_edit_btn">{{ __('Update') }}</button>
    </div>
</div>
{{ Form::close() }}


<script>
    $("#submit").click(function() {
        var start = $('.start_data').val();

        if (!isNaN(start)) {
            $('#start_time_validation').removeClass('d-none')
            return false;
        } else {
            $('#start_time_validation').addClass('d-none')
        }
        var end = $('.end_data').val();
        if (!isNaN(end)) {
            $('#end_time_validation').removeClass('d-none')
            return false;
        } else {
            $('#end_time_validation').addClass('d-none')
        }
        var note = $('.note_data').val();
        if (!isNaN(note)) {
            $('#note_validation').removeClass('d-none')
            return false;
        } else {
            $('#note_validation').addClass('d-none')
        }
    });
</script>
