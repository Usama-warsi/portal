<div id="dynamic-form-fields">
    @foreach ($field_data->field as $value)
        @if($value->field_type == 'select')
            @if (!empty($sub_module))
                <label for="name" class="col-form-label"><b>{{ __($value->label) }}</b></label>
                <select class="form-select form-control" id="{{ __($value->field_name) }}" name="item" data-placeholder="{{ __($value->field_name) }}">
                    <option value="">{{ __($value->placeholder) }}</option>
                    @foreach (($sub_module->module == 'Product Service') ? $data[$value->label] : $data[$value->model_name] as $key => $dataItem)
                        <option value="{{ $key }}" {{$key == $itemId ? 'selected' : '' }}>{{ $dataItem }}</option>
                    @endforeach
                </select>
            @endif
        @endif
    @endforeach
</div>
