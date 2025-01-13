{{ Form::open(['route' => ['distribution.store', $asset->id], 'method' => 'post']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn', ['template_module' => 'asset', 'module' => 'Assets'])
        @endif
    </div>
    <div class="row">
        <div class="form-group">
            {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
            {{ Form::select('employee_id', $employees, null, ['class' => 'form-control ', 'placeholder' => __('Select Employee'), 'required' => 'required']) }}
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('serial_code', __('Serial Code'), ['class' => 'form-label']) }}
            {{ Form::text('serial_code', isset($asset->serial_code)? $asset->serial_code: null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter serial Code']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('dist_number', __('Distribution Number'), ['class' => 'form-label']) }}
            {{ Form::text('dist_number', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Number']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('dis_quantity', __('Quantity'), ['class' => 'form-label']) }}
            {{ Form::text('dis_quantity', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Quantity']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('assign_date', __('Assign Date'), ['class' => 'form-label']) }}
            {{ Form::date('assign_date', date('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Select assign Date', 'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('return_date', __('Return Date'), ['class' => 'form-label']) }}
            {{ Form::date('return_date', date('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Select Return Date', 'required' => 'required']) }}
        </div>
        @if (module_is_active('Hrm'))
            <div class="form-group col-md-12">
                {{ Form::label('assets_branch', __('Assets Branch'), ['class' => 'form-label']) }}
                {{ Form::select('assets_branch', $branches,null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Branch']) }}
            </div>
        @endif
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
