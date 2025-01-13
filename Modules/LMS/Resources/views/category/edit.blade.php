{{Form::model($category,array('route' => array('course-category.update', $category->id), 'method' => 'PUT' , 'enctype'=>'multipart/form-data')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6">
            {!! Form::label('name', __('Name'),['class'=>'form-label']) !!}
            {!! Form::text('name', null, ['class' => 'form-control','required' => 'required','placeholder'=>'Enter Category Name']) !!}
        </div>
        <div class="form-group col-lg-6">
                <div class="form-file">
                    <label for="category_image" class="form-label">{{ __('Upload category_image') }}</label>
                    <input type="file" class="form-control file" name="category_image" id="category_image" aria-label="file example" data-filename="image_update" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                    <img id="blah" @if(!empty($category->category_image)) src="{{get_file($category->category_image)}}" @else src="{{asset('Modules/LMS/Resources/assets/image/category_image/default.png')}}" @endif alt="your image" width="25%" class=" mt-2"/>
                    <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                </div>
        </div>
        <div class="form-group col-md-12">
            {{Form::label('description',__('Description'),array('class'=>'form-label')) }}
            {{Form::textarea('description',null,array('class'=>'form-control summernote','rows'=>3,'placeholder'=>__('Description')))}}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary" id="submit-all">
</div>
{!! Form::close() !!}

<script src="{{ asset('js/custom.js') }}"></script>
<link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
<script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
