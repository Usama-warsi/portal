{!! Form::open(array('route' => array('headers.store', $id), 'method' => 'POST')) !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-12 col-md-12">
            {!! Form::label('title', __('Header'),['class'=>'form-label']) !!}
            {!! Form::text('title', null, ['class' => 'form-control','required' => 'required']) !!}
        </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{ __('Create') }}" class="btn btn-primary" id="submit-all">
        </div>
    </div>
</div>
{!! Form::close() !!}
