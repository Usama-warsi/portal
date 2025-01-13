{{Form::open(array('url'=>'course-student','method'=>'post'))}}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('name',__('Name'),['class'=>'form-label']) }}
                    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Student Name'),'required'=>'required'))}}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('email',__('Email'),['class'=>'form-label'])}}
                    {{Form::email('email',null,array('class'=>'form-control','placeholder'=>__('Enter Student Email'),'required'=>'required'))}}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('password',__('Password'),['class'=>'form-label'])}}
                    {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter Student Password'),'minlength'=>"6",'required'=>'required'))}}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('phone_number',__('Phone Number'),['class'=>'form-label'])}}
                    {{Form::text('phone_number',null,array('class'=>'form-control','placeholder'=>__('Enter Student Phone Number'),'required'=>'required'))}}
                    <div class=" text-xs text-danger">
                        {{ __('Please add mobile number with country code. (ex. +91)') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
    </div>
{{Form::close()}}
