{{ Form::open(array('url' => 'assets-category')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Category'),['class'=>'col-form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','placeholder'=> 'Enter Category' ,'required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Create')}}</button>
</div>

{{ Form::close() }}