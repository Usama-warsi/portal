{!! Form::open(array('route' => array('course_orders.store'), 'method' => 'POST','enctype'=>'multipart/form-data')) !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-6">
            {!! Form::label('date', __('Date'),['class'=>'form-label']) !!}
            {!! Form::date('date', null, ['class' => 'form-control','required' => 'required']) !!}
        </div>
        <div class="form-group col-lg-6">
            {!! Form::label('student_id', __('Student'),['class'=>'form-label']) !!}
            {!! Form::select('student_id',$students, null, ['class' => 'form-control','required' => 'required','placeholder'=>'Select Student']) !!}
        </div>
        <div class="form-group col-lg-6">
            {!! Form::label('course', __('Course'),['class'=>'form-label']) !!}
            <div id="course-div">
                <select class="form-control choices" name="course[]" id="course" placeholder="{{__('Select Course')}}"  multiple>
                    <option value="">{{__('Select Course')}}</option>
                </select>
            </div>
        </div>
        <div class="form-group col-lg-6">
            {!! Form::label('price', __('Price'),['class'=>'form-label']) !!}
            {!! Form::text('price', null, ['class' => 'form-control price','required' => 'required','disabled'=>'disabled']) !!}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('receipt', __('Payment Receipt'), ['class' => 'form-label']) }}
            <div class="choose-files ">
                <label for="receipt">
                    <div class="bg-primary mb-2"> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                    <input type="file" class="form-control file" name="receipt" id="receipt" aria-label="file example" onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">
                    <img src="" id="blah2" width="25%"/>
                    <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                </label>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary" id="submit-all">
</div>
{!! Form::close() !!}

<script>
    $(document).on('change', '#student_id', function () {
        var student_id = $(this).val();
        $.ajax({
            url: '{{route('getcourse')}}',
            type: 'POST',
            data: {
                "student_id": student_id, "_token": "{{ csrf_token() }}",
            },
            success: function (data) {
                $('#course-div').empty();
                var course_option = '<select class="form-control" name="course[]" id="course" placeholder="{{__('Select Course')}}"  multiple>';
                 course_option += '<option value="" disabled>{{__('Select Course')}}</option>';
                $.each(data, function (key, value) {
                    course_option += '<option value="' + key + '">' + value + '</option>';
                });
                course_option += '</select>';

                $("#course-div").append(course_option);
                var multipleCancelButton = new Choices('#course', {
                    removeItemButton: true,
                });
            }
        });
    });

    $(document).on('change', '#course', function() {
        var course = $(this).val();
        $.ajax({
            url: '{{ route('getcourse.price') }}',
            type: 'POST',
            data: {
                "course": course,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('.price').val(data);
            }
       });
    });

</script>
