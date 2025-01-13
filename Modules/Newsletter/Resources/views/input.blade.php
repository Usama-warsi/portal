<div id="dynamic-form-fields">

@foreach ($field_data->field as  $value)
   @if($value->field_type == 'text')
   <label for="name" class="col-form-label"><b>{{ __($value->label) }}</b>  </label>
   <input type="text" class="form-control"   name="{{ __($value->field_name) }}"  placeholder="{{ __($value->field_name) }}">

   @elseif($value->field_type == 'number')
   <label for="name" class="col-form-label"><b>{{ __($value->label) }}</b></label>
   <input type="number" class="form-control"   name="{{ __($value->field_name) }}"  placeholder="{{ __($value->field_name) }}">

   @elseif($value->field_type == 'date')
   <label for="name" class="col-form-label"><b>{{ __($value->label) }}</b></label>
   <input type="date" class="form-control"   name="{{ __($value->field_name) }}"  placeholder="{{ __($value->field_name) }}" >

   @elseif($value->field_type == 'time')
   <label for="name" class="col-form-label"><b>{{ __($value->label) }}</b></label>
   <input type="time" class="form-control"   name="{{ __($value->field_name) }}"  placeholder="{{ __($value->field_name) }}">

   @elseif ($value->field_type == 'datetime-local')
   <label for="name" class="col-form-label"><b>{{ __($value->label) }}</b></label>
   <input class="form-control"  name="{{ __($value->field_name) }}" placeholder="{{ __($value->field_name) }}"
       type="datetime-local">

   @elseif($value->field_type == 'select')
   @if (!empty($news_module))
       <label for="name" class="col-form-label"><b>{{ __($value->label) }}</b></label>
       <select class="form-select form-control" id="{{ __($value->field_name) }}" name="{{ __($value->field_name) }}" data-placeholder="{{ __($value->field_name) }}" >
        <option value="">{{ __($value->placeholder) }}</option>
        @foreach ($data[$value->model_name] as $key => $dataItem)
        <option value="{{ $key }}">{{ $dataItem }}</option>
         @endforeach
    </select>
   @endif

   @elseif($value->field_type == 'radio')
   <label for="name" class="col-form-label"><b>{{ __($value->label) }}</b></label>
        @if($value->field_name == 'gender')
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" value="male" id="male">
            <label class="form-check-label" for="male">
                {{  __('Male') }}
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" value="female" id="female">
            <label class="form-check-label" for="female">
                {{  __('Female') }}
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" value="other" id="other">
            <label class="form-check-label" for="other">
                {{  __('Other') }}
            </label>
        </div>
    @endif

   @endif
@endforeach
</div>






