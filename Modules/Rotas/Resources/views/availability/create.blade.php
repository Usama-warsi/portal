{{ Form::open(['url' => 'availabilitie', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <div class="row">

                @if (Auth::user()->type == 'company')
                    <div class="col-6">
                        <div class="form-group">
                            {{ Form::label('', __('Employee'), ['class' => 'form-label']) }}
                            {!! Form::select('employee_id', $filter_employees, null, [
                                'required' => true,
                                'class' => 'form-control',
                            ]) !!}
                        </div>

                    </div>
                @else
                    {!! Form::hidden('employee_id', Auth::id()) !!}
                @endif
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('', __('Name'), ['class' => 'form-label']) }}
                        {{ Form::text('name', null, ['class' => 'form-control', 'required' => '']) }}
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        {{ Form::label('', __('Start Date'), ['class' => 'form-label']) }}
                        {{ Form::date('start_date', date('Y-m-d'), ['class' => 'form-control', 'required' => true]) }}
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        {{ Form::label('', __('End Date'), ['class' => 'form-label']) }}
                        {{ Form::date('end_date', date('Y-m-d'), ['class' => 'form-control', 'required' => false]) }} </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        {{ Form::label('', __('Repeat Every'), ['class' => 'form-label']) }}
                        {!! Form::select(
                            'repeat_week',
                            ['1' => __('Week'), '2' => __('2 Week'), '3' => __('3 Week'), '4' => __('4 Week')],
                            null,
                            ['required' => false, 'id' => '', 'class' => 'form-control'],
                        ) !!}
                    </div>
                </div>
                <div class="col-12 text-danger">
                    {{ __('If you add availabiliti of same date than system will refer last record') }}</div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-12">
            <div id="schedule4" class="jqs-demo mb-3"></div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
</div>
{{ Form::close() }}
