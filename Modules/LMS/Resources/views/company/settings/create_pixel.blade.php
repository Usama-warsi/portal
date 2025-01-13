{{ Form::open(['method'=>'POST','route'=>array('lms.pixel.store',$store->slug)]) }}
    <div class="modal-body">
		<div class="row">
		    <div class="col-12">
			<div class="form-group">
			    {{ Form::label('Platform', __('Platform'), ['class' => 'col-form-label']) }}
			    {{ Form::select('platform',$pixals_platforms,null, ['class' => 'form-control', 'placeholder'=>'Please Select','required'=>'required']) }}
			</div>
			<div class="form-group">
			    {{  Form::label('Pixel Id',__('Pixel Id'),['class'=>'col-form-label'])  }}
			    {{ Form::text('pixel_id','',array('class'=>'form-control','placeholder'=>'Enter Pixel Id','required'=>'required')) }}
			</div>
		    </div>
		</div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn btn-primary ms-2">
    </div>
{{ Form::close() }}
