{{Form::model($pageOption, array('route' => array('custom-page.update', $pageOption->id), 'method' => 'PUT')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {{Form::label('name',__('Name'),array('class'=>'form-label'))}}
                    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name')))}}
                </div>
            </div>
            <div class=" col-md-6">
                <div class="form-check form-switch custom-control-inline">
                {{Form::label('enable_page_header',__('Page Header Display'),array('class'=>'form-check-label mb-3')) }}
                    <input type="checkbox" class="form-check-input" name="enable_page_header" id="enable_page_header" {{ ($pageOption['enable_page_header'] == 'on') ? 'checked=checked' : '' }}>
                    <label class="form-check-label" for="enable_page_header"></label>
                </div>
            </div>
            <div class="form-group col-md-12">
                {{Form::label('contents',__('Contents'),array('class'=>'form-label')) }}
                {{Form::textarea('contents',null,array('class'=>'form-control summernote','rows'=>3,'placeholder'=>__('Contents')))}}
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
