@if($workflow->input_type == 'text')

    {{Form::text($request->input_name,'',array('class'=>'form-control','placeholder'=>__('Enter text')))}}

@elseif($workflow->input_type == 'date')

    {{ Form::date($request->input_name, date('Y-m-d'), array('class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Due Date')) }}

@elseif($workflow->input_type == 'number')

    {{ Form::number($request->input_name, '', array('class' => 'form-control',"min"=>"0",'placeholder' => __('Enter Number'))) }}

@elseif($workflow->input_type == 'select')

    @if (!empty($workflow))
        {{ Form::select($request->input_name, $data, null, ['class' => 'form-control']) }}
    @else
        <p>{{ __('No workflow data available.')}}</p>
    @endif

@endif


