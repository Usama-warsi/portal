{!! Form::open(['route' => 'course-subcategory.store','method' => 'post']) !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6">
            {!! Form::label('name', __('Name'),['class'=>'form-label']) !!}
            {!! Form::text('name', null, ['class' => 'form-control','required' => 'required','placeholder'=>'Enter Sub Category Name']) !!}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('category',__('Category'),array('class'=>'form-label')) }}
            {!! Form::select('category', $category, null,array('class' => 'form-control','required' => 'required','placeholder'=>'Select Category')) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary" id="submit-all">
</div>
{!! Form::close() !!}
