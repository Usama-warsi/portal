{{ Form::open(array('route' => ['spreadsheets.folder.update',$spreadsheet->id], 'enctype' => "multipart/form-data")) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group">
                {{ Form::label('',__('Folder Name'),['class'=>'form-label']) }}
                {{ Form::text('name',$spreadsheet->folder_name,['class'=>'form-control','required'=>'required']) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
    </div>
{{ Form::close() }}

