{{ Form::open(['url' => 'commoncases', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn', [
                'template_module' => 'cases',
                'module' => 'Sales',
            ])
        @endif
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                {!! Form::select('status', $status, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Select Status',
                ]) !!}
            </div>
        </div>

        @if ($type == 'account')
            <div class="col-6">
                <div class="form-group">
                    {{ Form::label('account', __('Account'), ['class' => 'form-label']) }}
                    {!! Form::select('account', $account, $id, ['class' => 'form-control']) !!}
                </div>
            </div>
        @else
            <div class="col-6">
                <div class="form-group">
                    {{ Form::label('account', __('Account'), ['class' => 'form-label']) }}
                    {!! Form::select('account', $account, null, ['class' => 'form-control']) !!}
                </div>
            </div>
        @endif
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('priority', __('Priority'), ['class' => 'form-label']) }}
                {!! Form::select('priority', $priority, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Select Priority',
                ]) !!}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('contact', __('Contact'), ['class' => 'form-label']) }}
                {!! Form::select('contact', $contact_name, null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
                {!! Form::select('type', $case_type, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Select Case Type',
                ]) !!}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('user', __('Assigned User'), ['class' => 'form-label']) }}
                {!! Form::select('user', $user, null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="col-6 mb-3 field" data-name="attachments">
            <div class="form-group">
                <div class="attachment-upload">
                    <div class="attachment-button">
                        <div class="pull-left">
                            {{ Form::label('User', __('Attachment'), ['class' => 'form-label']) }}
                            <input type="file"name="attachments" class="form-control mb-3"
                                onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                            <img id="blah" width="20%" height="20%" />
                        </div>
                    </div>
                    <div class="attachments"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-6">
        <div class="form-group">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter Description')]) }}
        </div>
    </div>
    @if (module_is_active('CustomField') && !$customFields->isEmpty())
        <div class="col-6">
            <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                @include('customfield::formBuilder')
            </div>
        </div>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Save'), ['class' => 'btn  btn-primary ']) }}{{ Form::close() }}
</div>
{{ Form::close() }}
