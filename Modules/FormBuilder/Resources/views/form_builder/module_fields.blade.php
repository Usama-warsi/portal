
<div class="row px-2">
    @if ($module->module == 'Lead')
        @if ($module->submodule == 'Lead')
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('subject_id', __('Subject'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('subject_id', $fields,$jsonRemovedField->subject_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('name_id', __('Name'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('name_id', $fields,$jsonRemovedField->name_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('email_id', __('Email'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('email_id', $fields,$jsonRemovedField->email_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('user_id', __('User'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('user_id', $users,$jsonRemovedField->user_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                    @if(count($users) == 0)
                        <div class="text-muted text-xs">
                            {{__('Please create new users')}} <a href="{{route('users.index')}}">{{__('here.')}}</a>
                        </div>
                    @endif
                </div>
            </div>
        @elseif($module->submodule == 'Deal')
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('deal_name_id', __('Deal Name'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('deal_name_id', $fields,$jsonRemovedField->deal_name_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('price_id', __('Price'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('price_id', $fields,$jsonRemovedField->price_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('phone_no_id', __('Phone No'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('phone_no_id', $fields,$jsonRemovedField->phone_no_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('clients_id', __('Client'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('clients_id[]', $clients,!empty($jsonRemovedField->clients_id) ? explode(',',$jsonRemovedField->clients_id) : null, array('class' => 'form-control choices','id'=>'choices-multiple','multiple'=>'','required'=>'required')) }}

                    @if(count($clients) <= 0 && Auth::user()->type == 'company')
                        <div class="text-muted text-xs">
                            {{__('Please create new client')}} <a href="{{route('users.index')}}">{{__('here')}}</a>.
                        </div>
                    @endif
                </div>
            </div>
        @endif
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('pipeline_id', __('Pipelines'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('pipeline_id', $pipelines,$jsonRemovedField->pipeline_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
    @elseif($module->module == 'Taskly' && $module->submodule == 'Project')
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('project_name_id', __('Name'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('project_name_id', $fields,$jsonRemovedField->project_name_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('description_id', __('Description'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('description_id', $fields,$jsonRemovedField->description_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('users_id', __('Users'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('users_id[]', $users,$jsonRemovedField->users_id ?? null, array('class' => 'form-control choices users_list','id'=>'choices-multiple','multiple'=>'','required'=>'required')) }}
            </div>
        </div>
    @elseif ($module->module == 'MachineRepairManagement' && $module->submodule == 'Machine')
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('machine_name_id', __('Name'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('machine_name_id', $fields,$jsonRemovedField->machine_name_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('manufacturer_name_id', __('Manufacturer'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('manufacturer_name_id', $fields,$jsonRemovedField->manufacturer_name_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('model_id', __('Model'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('model_id', $fields,$jsonRemovedField->model_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('installation_date_id', __('Installation Date'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('installation_date_id', $fields,$jsonRemovedField->installation_date_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('description_id', __('Description'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('description_id', $fields,$jsonRemovedField->description_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('status', __('Status'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('status', $status, $jsonRemovedField->status ?? null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
    @elseif ($module->module == 'CMMS' && $module->submodule == 'Location')
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('name_id', __('Name'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('name_id', $fields,$jsonRemovedField->name_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('address_id', __('Address'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('address_id', $fields,$jsonRemovedField->address_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
    @elseif ($module->module == 'Sales')
        @if ($module->submodule == 'Contact')
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('name_id', __('Name'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('name_id', $fields,$jsonRemovedField->name_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('email_id', __('Email'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('email_id', $fields,$jsonRemovedField->email_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('phone_no_id', __('Phone'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('phone_no_id', $fields,$jsonRemovedField->phone_no_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('postal_code_id', __('Postal Code'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('postal_code_id', $fields,$jsonRemovedField->postal_code_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
        @elseif ($module->submodule == 'Opportunities')
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('name_id', __('Name'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('name_id', $fields,$jsonRemovedField->name_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('amount_id', __('Amount'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('amount_id', $fields,$jsonRemovedField->amount_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('probability_id', __('Probability'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('probability_id', $fields,$jsonRemovedField->probability_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('close_date_id', __('Close Date'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('close_date_id', $fields,$jsonRemovedField->close_date_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('opportunities_stage_id', __('Opportunities Stage'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('opportunities_stage_id', $opportunities_stage,$jsonRemovedField->opportunities_stage_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
        @endif
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('account_id', __('Account'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('account_id', $account,$jsonRemovedField->account_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('user_id', __('User'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('user_id', $users,$jsonRemovedField->user_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                    @if(count($users) == 0)
                        <div class="text-muted text-xs">
                            {{__('Please create new users')}} <a href="{{route('users.index')}}">{{__('here.')}}</a>
                        </div>
                    @endif
                </div>
            </div>
    @elseif ($module->module == 'Contract' && $module->submodule == 'Contract')
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('subject_id', __('Subject'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('subject_id', $fields,$jsonRemovedField->subject_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('value_id', __('Value'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('value_id', $fields,$jsonRemovedField->value_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('start_date_id', __('Start Date'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('start_date_id', $fields,$jsonRemovedField->start_date_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('end_date_id', __('End Date'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('end_date_id', $fields,$jsonRemovedField->end_date_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('type_id', __('Type'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('type_id', $contractType,$jsonRemovedField->type_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
            @if(count($contractType) <= 0)
                <div class="text-muted text-xs">
                    {{__('Please create new contract type')}} <a href="{{route('contract_type.index')}}">{{__('here')}}</a>.
                </div>
            @endif
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('user_id', __('User'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('user_id', $users,$jsonRemovedField->user_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                @if(count($users) == 0)
                    <div class="text-muted text-xs">
                        {{__('Please create new users')}} <a href="{{route('users.index')}}">{{__('here.')}}</a>
                    </div>
                @endif
            </div>
        </div>
    @elseif ($module->module == 'Internalknowledge' || $module->module == 'Notes')
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('title_id', __('Title'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('title_id', $fields,$jsonRemovedField->title_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                {{ Form::label('description_id', __('Description'),['class'=>'col-form-label']) }}
            </div>
        </div>
        <div class="col-8">
            <div class="form-group">
                {{ Form::select('description_id', $fields,$jsonRemovedField->description_id ?? null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        @if ($module->module == 'Internalknowledge' && $module->submodule == 'Book')
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('users_id', __('Users'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('users_id[]', $users,$jsonRemovedField->users_id ?? null, array('class' => 'form-control choices users_list','id'=>'choices-multiple','multiple'=>'','required'=>'required')) }}
                    @if(count($users) == 0)
                        <div class="text-muted text-xs">
                            {{__('Please create new users')}} <a href="{{route('users.index')}}">{{__('here.')}}</a>
                        </div>
                    @endif
                </div>
            </div>
        @elseif ($module->module == 'Internalknowledge' && $module->submodule == 'Article')
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('book_id', __('Book'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('book_id', $books,$jsonRemovedField->book_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('type_id', __('Type'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('type_id', $type, $jsonRemovedField->type_id ?? null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
        @elseif ($module->module == 'Notes' && $module->submodule == 'Note')
            <div class="col-4">
                <div class="form-group">
                    {{ Form::label('color', __('Color'),['class'=>'col-form-label']) }}
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    {{ Form::select('color', $color, $jsonRemovedField->color ?? null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
        @endif
    @endif
    {{ Form::hidden('form_id',$form->id) }}
    {{ Form::hidden('form_response_id',(!empty($formField)) ? $formField->id : '') }}
</div>