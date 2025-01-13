{{ Form::open(array('route' => array('spreadsheets.folder.store',$parent_id), 'enctype' => "multipart/form-data")) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('', __('Folder Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required','placeholder' => __('Enter Folder Name')]) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}



