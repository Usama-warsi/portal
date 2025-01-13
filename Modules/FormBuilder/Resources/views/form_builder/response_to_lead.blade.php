{{ Form::model($formField, array('route' => array('form.response.convert.lead.store', $form->id))) }}
<div class="modal-body">
         
    <div class="row">
            <div class="col-12 form-group">
            {{ Form::label('subject', __('Subject'),['class'=>'col-form-label']) }}
            {{ Form::text('subject', '', array('class' => 'form-control','required'=> 'required')) }}
             </div>
            <div class="col-12 form-group">
            {{ Form::label('name', __('Name'),['class'=>'col-form-label']) }}
            {{ Form::select('name', $response_data,$jsonRemovedField->user_id ?? null, array('class' => 'form-control','required'=>'required')) }}      
             </div>
             <div class="col-12 form-group">
            {{ Form::label('email', __('Email Address'),['class'=>'col-form-label']) }}
            {{ Form::select('email', $response_data,$jsonRemovedField->user_id ?? null, array('class' => 'form-control','required'=>'required')) }}     
             </div>
             <div class="col-12 form-group">
            {{ Form::label('phone', __('Phone'),['class'=>'col-form-label']) }}
            {{ Form::select('phone', $response_data,$jsonRemovedField->user_id ?? null, array('class' => 'form-control')) }}     
            <div class="text-xs text-danger">{{ __('Please add phone no with country code. (ex. +91)') }}</div>
            </div>
            <div class="col-12 form-group">
            {{ Form::label('user_id', __('User'),['class'=>'col-form-label']) }}
            {{ Form::select('user_id', $users,$jsonRemovedField->user_id ?? null, array('class' => 'form-control','required'=>'required')) }}
                    @if(count($users) == 0)
                        <div class="text-muted text-xs">
                            {{__('Please create new users')}} <a href="{{route('users.index')}}">{{__('here.')}}</a>
                        </div>
                    @endif       
             </div>
             <div class="col-12 form-group">
             {{ Form::label('pipeline_id', __('Pipelines'),['class'=>'col-form-label']) }}
             {{ Form::select('pipeline_id', $pipelines,$jsonRemovedField->pipeline_id ?? null, array('class' => 'form-control','required'=>'required')) }}
             </div>
    

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Save')}}</button>
</div>
{{ Form::close() }}

