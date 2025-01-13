@php
    $company_settings = getCompanyAllSetting();
@endphp
{{ Form::model($trainer, ['route' => ['trainer.update', $trainer->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch', !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch'), ['class' => 'col-form-label']) }}
                {{ Form::select('branch', $branches, null, ['class' => 'form-control ', 'placeholder' => __('Select Branch'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('firstname', __('First Name'), ['class' => 'col-form-label']) }}
                {{ Form::text('firstname', null, ['class' => 'form-control', 'required' => 'required','placeholder'=>'Enter First Name']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('lastname', __('Last Name'), ['class' => 'col-form-label']) }}
                {{ Form::text('lastname', null, ['class' => 'form-control', 'required' => 'required','placeholder'=>'Enter Last Name']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('contact', __('Contact'), ['class' => 'col-form-label']) }}
                {{ Form::text('contact', null, ['class' => 'form-control', 'required' => 'required','placeholder'=>'Enter Mobile Number']) }}
                <div class=" text-xs text-danger">
                    {{ __('Please add mobile number with country code. (ex. +91)') }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'col-form-label']) }}
                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required','placeholder'=>'Enter Email']) }}
            </div>
        </div>
        <div class="form-group col-lg-12">
            {{ Form::label('expertise', __('Expertise'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('expertise', null, ['class' => 'form-control', 'placeholder' => __('Enter Expertise'), 'rows' => '3']) }}
        </div>
        <div class="form-group col-lg-12">
            {{ Form::label('address', __('Address'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => __('Enter Address'), 'rows' => '3']) }}
        </div>
        @if (module_is_active('CustomField') && !empty($customFields))
        <div class="col-md-6">
            <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                @include('customfield::formBuilder', [
                    'fildedata' => $child->customField,
                ])
            </div>
        </div>
    @endif
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
