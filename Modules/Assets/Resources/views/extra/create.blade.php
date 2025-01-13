{{ Form::open(['route' => ['extra.store',$asset->id], 'method' => 'post']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn', ['template_module' => 'asset', 'module' => 'Assets'])
        @endif
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('serial_code', __('Serial Code'), ['class' => 'form-label']) }}
            {{ Form::text('serial_code',$asset->serial_code,['class' => 'form-control', 'required' => 'required', 'step' => '1', 'placeholder' => 'Enter serial Code']) }}
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('quantity', __('Quantity'), ['class' => 'form-label']) }}
            {{ Form::text('quantity', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Quantity']) }}
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
            {{ Form::date('date',date('Y-m-d'), ['class' => 'form-control ','required'=>'required','placeholder' => 'Select Date']) }}
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Enter Description']) }}
        </div>


    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
    </div>
</div>
{{ Form::close() }}
