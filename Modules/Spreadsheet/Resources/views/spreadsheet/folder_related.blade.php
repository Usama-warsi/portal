{{ Form::open(['route' => ['spreadsheets.related.store',$id], 'method' => 'post']) }}
    <div class="modal-body">
        <div class="col-md-12">
            <div id="repeater-container">
                <div id="form-container">
                    <div class="form-group-container" id="form-group-container1">
                        <div class="row">
                            @php
                                $folder_related = $spreadsheet->related;
                                $folder = $spreadsheet->related_assign;

                            @endphp
                            <div class="form-group col-md-12 ml-4">
                                <select name="related_id" class='form-control font-style' id="related-to">
                                    <option value="0" selected disabled>{{ __('Related To') }}</option>
                                    @foreach ($related as $key => $relate)
                                    <option value="{{ $key }}" {{ $key == $folder_related ? 'selected' : '' }}>{{ $relate }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($spreadsheet->related != null)
                                <div id="value_id_name">
                                    {!! Form::select('value[]', $value ?? [], explode(',', $spreadsheet->related_assign), [
                                        'class' => 'form-control choices',
                                        'multiple' => true,
                                        'id' => 'values_name',
                                    ]) !!}
                                </div>
                            @else
                                <div id="value_id_name">
                                    <select class="form-control choices" name="value[]" id="values_name" placeholder="{{__('Select Item')}}"  multiple>
                                        <option value="">{{__('Select related first')}}</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
    </div>

{{ Form::close() }}

<script>
    $(document).on("change", "#related-to", function() {
        var relatedId = $(this).val();
        $.ajax({
            url: '{{ route('spreadsheets.relateds.get') }}',
            type: 'POST',
            data: {
                "related_id": relatedId,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data)
            {
                $('#value_id_name').empty();
                var option = '<select class="form-control choices" name="value[]" id="values_name" placeholder="{{__('Select Item')}}"  multiple>';
                    option += '<option value="" disabled>{{__('Select Item')}}</option>';

                    $.each(data, function (key, value) {
                        option += '<option value="' + key + '">' + value + '</option>';
                    });
                    option += '</select>';

                    $("#value_id_name").append(option);
                    var multipleCancelButton = new Choices('#values_name', {
                        removeItemButton: true,
                    });
            },
        });
    });
</script>







