{{ Form::open(['route' => ['spreadsheets.share', $spreadsheet->id], 'method' => 'post']) }}
    <div class="modal-body">
        <div class="col-md-12">
            <div class="row">
                <h4 class="col-md-6">{{ __('Staff')}}</h4>
                <div class="col-md-6 d-flex justify-content-end my-3 mt-0">
                    <div class="all-button-box">
                        <button type="button" class="btn btn-sm btn-primary btn-icon m-1 float-end ms-2"
                            id="add-field-btn" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ __('Condition Field') }}">
                            <i class="ti ti-plus mr-1"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div id="repeater-container">
                @php
                    $user_and_pers    = json_decode($spreadsheet->user_and_per);
                @endphp
                @if (!is_null($user_and_pers) && (is_array($user_and_pers) || count($user_and_pers) > 0))
                    @foreach ($user_and_pers as $keys => $user_and_per)
                        <div class="form-group-container" id="{{ 'form-group-container'.$keys }}">
                            <div class="row">
                                <div class="form-group col-md-8 ml-auto">
                                    <select name={{ 'fields[' . $keys . '][user_id]' }} class='form-control font-style'>
                                        <option value="0" selected disabled>{{ __('Select Staff') }}</option>
                                        @foreach ($staff as $key => $member)
                                            <option value="{{ $key }}" {{ ($key == $user_and_per->user_id) ? 'selected' : '' }}>{{ $member }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3 ml-auto">
                                    <select name={{ 'fields[' . $keys . '][permission]' }}
                                        class='form-control font-style'>
                                        @foreach (\Modules\Spreadsheet\Entities\Spreadsheets::$permission as $key => $value)
                                            <option {{ $key == $user_and_per->permission ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-1 ml-auto ">
                                    <a class="delete-icon"><i class="fas fa-trash text-danger"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div id="form-container">
                        <div class="form-group-container" id="form-group-container1">
                            <div class="row">
                                <div class="form-group col-md-8 ml-auto">
                                    <select name="fields[0][user_id]" class='form-control font-style'>
                                        <option value="0" selected disabled>{{ __('Select Staff') }}</option>
                                        @foreach ($staff as $key => $member)
                                            <option value="{{ $key }}">{{ $member }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3 ml-auto">
                                    <select name="fields[0][permission]" class='form-control font-style'>
                                        <option value="" selected disabled>{{ __('Please Select') }}</option>
                                        @foreach (\Modules\Spreadsheet\Entities\Spreadsheets::$permission as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-1 ml-auto" style="margin-top: 10px;">
                                    <a class="delete-icon disabled"><i class="fas fa-trash text-danger"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Share'),array('class'=>'btn  btn-primary'))}}
    </div>

{{ Form::close() }}

<script>

    $(document).ready(function() {
      let plusFieldIndex = 0;

        $(".form-group-container").each(function() {
            const index = parseInt($(this).attr("id").replace("form-group-container", ""));
            if (index > plusFieldIndex) {
                plusFieldIndex = index;
            }
        });

        function addNewField(index) {
            const newContainer = $("#repeater-container").find(".form-group-container").first().clone();

            plusFieldIndex++;
            newContainer.attr("id", "form-group-container" + plusFieldIndex);
            newContainer.find("select[name^='fields[0]'][name$='[user_id]']").attr("name",
                "fields[" + plusFieldIndex + "][user_id]");
            newContainer.find("select[name^='fields[0]'][name$='[permission]']").attr("name", "fields[" +
                plusFieldIndex + "][permission]");

            newContainer.find('.delete-icon').removeClass('disabled');
            newContainer.find('.delete-icon').removeClass('d-none');

            $("#repeater-container").append(newContainer);
        }

        $("#add-field-btn").on("click", function() {
            addNewField(plusFieldIndex);
        });

        $(document).on("click", ".delete-icon:not(.disabled)", function() {
            var container = $(this).closest('.form-group-container');

            if (container.attr("id") !== "form-group-container0") {
                container.remove();
            }
        });
    });

</script>





