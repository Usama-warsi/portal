{{Form::model($blog, array('route' => array('blog.update', $blog->id), 'method' => 'PUT','enctype'=>'multipart/form-data')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {{Form::label('title',__('Title'))}}
                {{Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Title')))}}
                @error('title')
                <span class="invalid-title" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <div class="form-file mb-3">
                    <label for="blog_cover_image" class="form-label">{{ __('Blog Cover image') }}</label>
                    <input type="file" class="form-control" name="blog_cover_image" id="blog_cover_image" aria-label="file example">
                    <a href="{{get_file($blog->blog_cover_image)}}" target="_blank">
                        <img src="{{get_file($blog->blog_cover_image)}}" name="blog_cover_image" id="blog_cover_image"  alt="user-image" class="avatar avatar-lg mt-3">
                    </a>
                    <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                </div>
            </div>
        </div>
        <div class="form-group col-md-12">
            {{Form::label('detail',__('Detail'),array('class'=>'form-control-label')) }}
            {{Form::textarea('detail',null,array('class'=>'form-control summernote','rows'=>3,'placeholder'=>__('Detail')))}}
            @error('detail')
            <span class="invalid-detail" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{Form::close()}}

<script src="{{ asset('js/custom.js') }}"></script>
<link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
<script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
