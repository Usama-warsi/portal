@if (in_array('Invoice', $commissionStr))

    <div class="form-group">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="conditional" value="ladder_of_invoice" id="conditional"
                {{ isset($commission_str['conditional']) && $commission_str['conditional'] == 'ladder_of_invoice' ? 'checked' : '' }}>

            <label class="form-check-label pointer is_condition" for="conditional">
                {{ __('Conditional') }}
            </label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="conditional" value="non_conditional"
                id="non_conditional"
                {{ isset($commission_str['conditional']) && $commission_str['conditional'] == 'non_conditional' ? 'checked' : '' }}>
            <label class="form-check-label pointer is_non_conditional" for="non_conditional">
                {{ __('Non-Conditional') }}
            </label>
        </div>
    </div>

    {{-- conditional repeater --}}
    <div class="col-md-12 conditional-repeater" data-value='{!! isset($commission_str['checklist_items']) ? json_encode($commission_str['checklist_items']) : '' !!}'>
        <div class="card-body table-border-style ">
            <div class="table-responsive">
                <table class="table  mb-0 table-custom-style" data-repeater-list="checklist_items" id="sortable-table">
                    <thead>
                        <th class="text-center">{{ __('From Amount') }}</th>
                        <th class="text-center">{{ __('To Amount') }}</th>
                        <th class="text-center">{{ __('Commission') }}
                            <span class="commission-label"></span>
                        </th>
                        <th class="text-center">
                            <a data-repeater-create="" class="btn btn-primary btn-sm add-row text-white"
                                data-toggle="modal" data-target="#add-bank">
                                <i class="fas fa-plus"></i> {{ __('Add') }}</a>
                        </th>
                        <th class="text-center"></th>
                    </thead>
                    <tbody data-repeater-item>
                        <tr>
                            <td class="form-group">
                                <input type="text" class="form-control from_amount" name="from_amount">
                            </td>
                            <td>
                                <input type="text" class="form-control to_amount" name="to_amount">
                            </td>
                            <td>
                                <input type="text" class="form-control commission" name="commission">
                            </td>
                            <td>
                                <a href="javascript:;"
                                    class="ti ti-trash text-white action-btn bg-danger p-3 desc_delete"
                                    data-repeater-delete></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- non conditional repeater --}}
    <div class="col-md-12 non-conditional-repeater" data-value='{!! isset($commission_str['checklist_items']) ? json_encode($commission_str['checklist_items']) : '' !!}'>
        <div class="card-body table-border-style ">
            <div class="table-responsive">
                <table class="table  mb-0 table-custom-style" data-repeater-list="checklist_items">
                    <thead>
                        <th class="text-center">{{ __('Commission') }}
                            <span class="commission-label"></span>
                        </th>

                        <th class="text-center"></th>
                    </thead>
                    <tbody data-repeater-item>
                        <tr>
                            <td>
                                <input type="text" class="form-control commission" name="commission_total">
                            </td>
                            <td>
                                <a href="javascript:;"
                                    class="ti ti-trash text-white action-btn bg-danger p-3 non_condition_delete"
                                    data-repeater-delete></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@if (in_array('Sales Invoice', $commissionStr))
    <div class="form-group">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="conditional" value="ladder_of_invoice" id="conditional"
                {{ isset($commission_str['conditional']) && $commission_str['conditional'] == 'ladder_of_invoice' ? 'checked' : '' }}>
            <label class="form-check-label pointer is_condition" for="conditional">
                {{ __('Conditional') }}
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="conditional" value="non_conditional"
                id="non_conditional"
                {{ isset($commission_str['conditional']) && $commission_str['conditional'] == 'non_conditional' ? 'checked' : '' }}>
            <label class="form-check-label pointer is_non_conditional" for="non_conditional">
                {{ __('Non-Conditional') }}
            </label>
        </div>
    </div>

    {{-- conditional repeater --}}
    <div class="col-md-12 conditional-repeater" data-value='{!! isset($commission_str['checklist_items']) ? json_encode($commission_str['checklist_items']) : '' !!}'>
        <div class="card-body table-border-style ">
            <div class="table-responsive">
                <table class="table  mb-0 table-custom-style" data-repeater-list="checklist_items" id="sortable-table">
                    <thead>
                        <th class="text-center">{{ __('From Amount') }}</th>
                        <th class="text-center">{{ __('To Amount') }}</th>
                        <th class="text-center">{{ __('Commission') }}
                            <span class="commission-label">
                        </th>
                        <th class="text-center">
                            <a data-repeater-create="" class="btn btn-primary btn-sm add-row text-white"
                                data-toggle="modal" data-target="#add-bank">
                                <i class="fas fa-plus"></i> {{ __('Add') }}</a>
                        </th>
                        <th class="text-center"></th>
                    </thead>
                    <tbody data-repeater-item>
                        <tr>

                            <td class="form-group">
                                <input type="text" class="form-control from_amount" name="from_amount">
                            </td>
                            <td>
                                <input type="text" class="form-control to_amount" name="to_amount">
                            </td>
                            <td>
                                <input type="text" class="form-control commission" name="commission">
                            </td>
                            <td>
                                <a href="javascript:;"
                                    class="ti ti-trash text-white action-btn bg-danger p-3 desc_delete"
                                    data-repeater-delete></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- non conditional repeater --}}
    <div class="col-md-12 non-conditional-repeater" data-value='{!! isset($commission_str['checklist_items']) ? json_encode($commission_str['checklist_items']) : '' !!}'>
        <div class="card-body table-border-style ">
            <div class="table-responsive">
                <table class="table  mb-0 table-custom-style" data-repeater-list="checklist_items">
                    <thead>
                        <th class="text-center">{{ __('Commission') }}
                            <span class="commission-label">
                        </th>

                        <th class="text-center"></th>
                    </thead>
                    <tbody data-repeater-item>
                        <tr>
                            <td>
                                <input type="text" class="form-control commission" name="commission_total">
                            </td>
                            <td>
                                <a href="javascript:;"
                                    class="ti ti-trash text-white action-btn bg-danger p-3 non_condition_delete"
                                    data-repeater-delete></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@if (in_array('Project', $commissionStr))
    <div class="form-group">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="project_type" value="project" id="project_wise"
                {{ isset($commission_str['project_type']) && $commission_str['project_type'] == 'project' ? 'checked' : '' }}>
            <label class="form-check-label pointer" for="project_wise">
                {{ __('Project Wise') }}
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="project_type" value="task" id="task_wise"
                {{ isset($commission_str['project_type']) && $commission_str['project_type'] == 'task' ? 'checked' : '' }}>
            <label class="form-check-label pointer" for="task_wise">
                {{ __('Task Wise') }}
            </label>
        </div>
    </div>

    <div class="row all_project" style=" display: none;">
        <div class="form-group">
            {{ Form::label('project_id', __('Select Project'), ['class' => 'form-label']) }}
            {!! Form::select('project_id', $projects, isset($commission_str['project_id']) ? $commission_str['project_id'] : null, ['id' => 'project_id', 'class' => 'form-control', 'placeholder' => 'Select Project']) !!}
        </div>
    </div>


    <div class="row all_task" style="display: none;">
        <div class="form-group">
            {{ Form::label('task_id', __('Select Task'), ['class' => 'form-label', 'placeholder' => 'Select Task']) }}
            {{ Form::select('task_id', [], null, ['class' => 'form-control', 'id' => 'task_select', 'placeholder' => 'Select Task']) }}
        </div>
    </div>


    <div class="row radio_button" style="display: none;">
        <div class="form-group ">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="conditional" value="ladder_of_invoice"
                    id="conditional"
                    {{ isset($commission_str['conditional']) && $commission_str['conditional'] == 'ladder_of_invoice' ? 'checked' : '' }}>
                <label class="form-check-label pointer is_condition" for="conditional">
                    {{ __('Conditional') }}
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="conditional" value="non_conditional"
                    id="non_conditional"
                    {{ isset($commission_str['conditional']) && $commission_str['conditional'] == 'non_conditional' ? 'checked' : '' }}>
                <label class="form-check-label pointer is_non_conditional" for="non_conditional">
                    {{ __('Non-Conditional') }}
                </label>
            </div>
        </div>
    </div>
    {{-- conditional repeater --}}
    <div class="col-md-12 conditional-repeater" data-value='{!! isset($commission_str['checklist_items']) ? json_encode($commission_str['checklist_items']) : '' !!}'>
        <div class="card-body table-border-style ">
            <div class="table-responsive">
                <table class="table  mb-0 table-custom-style" data-repeater-list="checklist_items"
                    id="sortable-table">
                    <thead>
                        <th class="text-center">{{ __('From Amount') }}</th>
                        <th class="text-center">{{ __('To Amount') }}</th>
                        <th class="text-center">{{ __('Commission') }}
                            <span class="commission-label">
                        </th>
                        <th class="text-center">
                            <a data-repeater-create="" class="btn btn-primary btn-sm add-row text-white"
                                data-toggle="modal" data-target="#add-bank">
                                <i class="fas fa-plus"></i> {{ __('Add') }}</a>
                        </th>
                        <th class="text-center"></th>
                    </thead>
                    <tbody data-repeater-item>
                        <tr>

                            <td class="form-group">
                                <input type="text" class="form-control from_amount repeater_val"
                                    name="from_amount">
                            </td>
                            <td>
                                <input type="text" class="form-control to_amount repeater_val" name="to_amount">
                            </td>
                            <td>
                                <input type="text" class="form-control commission repeater_val" name="commission">
                            </td>
                            <td>
                                <a href="javascript:;"
                                    class="ti ti-trash text-white action-btn bg-danger p-3 desc_delete"
                                    data-repeater-delete></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- non conditional repeater --}}
    <div class="col-md-12 non-conditional-repeater" data-value='{!! isset($commission_str['checklist_items']) ? json_encode($commission_str['checklist_items']) : '' !!}'>
        <div class="card-body table-border-style ">
            <div class="table-responsive">
                <table class="table  mb-0 table-custom-style" data-repeater-list="checklist_items">
                    <thead>
                        <th class="text-center">{{ __('Commission') }}
                            <span class="commission-label">
                        </th>

                        <th class="text-center"></th>
                    </thead>
                    <tbody data-repeater-item>
                        <tr>
                            <td>
                                <input type="text" class="form-control commission repeater_val"
                                    name="commission_total">
                            </td>
                            <td>
                                <a href="javascript:;"
                                    class="ti ti-trash text-white action-btn bg-danger p-3 non_condition_delete"
                                    data-repeater-delete></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
<script>
    //get project wise task
    $(document).on('change', 'select[name=project_id]', function() {
        var project_id = $(this).val();
        gettask(project_id);
    });

    function gettask(project_id) {
        $.ajax({
            url: '{{ route('gettask') }}',
            type: 'POST',
            data: {
                "project_id": project_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#task_select').empty();
                $('#task_select').append(
                    '<option value="">{{ __('Select Task') }}</option>');
                $.each(data, function(key, value) {

                    $('#task_select').append('<option value="' + key + '" ' + (key == '{{ $commission_str['task_id'] ?? '' }}' ? 'selected' : '') + '>' + value + '</option>');
                });
            }
        });
    }
</script>

<script>
    // conditional-repeater
    var selector = "body";
    if ($(selector + " .conditional-repeater").length) {

        var $repeater = $(selector + ' .conditional-repeater').repeater({
            initEmpty: false,
            defaultValues: {
                'status': 1
            },

            isFirstItemUndeletable: true
        });
        var value = $(selector + " .conditional-repeater").attr('data-value');

        if (typeof value != 'undefined' && value.length != 0) {
            value = JSON.parse(value);
            $repeater.setList(value);
        }
    }
</script>

<script>
    // non-conditional-repeater
    var selector = "body";
    if ($(selector + " .non-conditional-repeater").length) {

        var $repeater = $(selector + ' .non-conditional-repeater').repeater({
            initEmpty: false,
            defaultValues: {
                'status': 1
            },

            isFirstItemUndeletable: true
        });
        var value = $(selector + " .non-conditional-repeater").attr('data-value');

        if (typeof value != 'undefined' && value.length != 0) {
            value = JSON.parse(value);
            $repeater.setList(value);
        }
    }
</script>

<script>
    //show radio button project and task
    $(document).ready(function() {
        $('.all_project, .all_task, .radio_button').hide();

        $('input[name="project_type"]').on('change', function() {
            var selectedValue = $(this).val();

            $('.all_project, .all_task, .radio_button').hide();

            if (selectedValue == "project") {
                $('.all_project').show();
                // $('.repeater_val').val('');
                $('.conditional-repeater').hide();
                $('.non-conditional-repeater').hide();
            } else if (selectedValue == "task") {
                $('#project_id').val('');
                $('.conditional-repeater').hide();
                $('.non-conditional-repeater').hide();
                $('.repeater_val').val('');

                $('.all_project, .all_task').show();
            }
        });

        //edit time
        $('#project_id').on('change', function() {
            var selectedValue = $(this).val();

            if (selectedValue) {
                $('.radio_button').show();
                $('input[name="conditional"]').prop("checked", false);
                @if (isset($commission_str['conditional']) && $commission_str['conditional'] == 'ladder_of_invoice')
                    $('input[type="radio"][value="ladder_of_invoice"]').prop('checked', true);
                    $('input[type="radio"][value="ladder_of_invoice"]').trigger('change')
                @elseif (isset($commission_str['conditional']) && $commission_str['conditional'] == 'non_conditional')
                    $('input[type="radio"][value="non_conditional"]').prop('checked', true);
                    $('input[type="radio"][value="non_conditional"]').trigger('change')
                @else
                    $('input[name="conditional"]').prop("checked", false);
                @endif
            } else {
                $('.radio_button').hide();
            }
        });

        $('#task_select').on('change', function() {
            var selectedValue = $(this).val();
            if (selectedValue) {
                $('.radio_button').show();
                $('input[name="conditional"]').prop("checked", false);

            } else {
                $('.radio_button').hide();
            }
        });
    });
</script>

<script>
    //conditional & non-conditional Repeater hide and show
    $(document).ready(function() {
        $('.conditional-repeater').hide();
        $('.non-conditional-repeater').hide();
        $('input[name="conditional"]').change(function() {
            var selectedValue = $(this).val();
            // invoice
            if (selectedValue == 'ladder_of_invoice') {
                $('.conditional-repeater').show();
                $('.non-conditional-repeater').hide();

            } else if (selectedValue == 'non_conditional') {
                $('.conditional-repeater').hide();
                $('.non-conditional-repeater').show();
            }
        });
    });
</script>

<script>
    //    conditional & non-conditional Commission Labels [%,Fixed]
    $(document).ready(function() {
        // Function to update Commission labels inside the repeater
        function updateRepeaterCommissionText(selectedType) {
            var commissionText = '';
            if (selectedType === 'percentage') {
                commissionText = ' (%)';
            } else if (selectedType === 'fixed') {
                commissionText = ' (Fixed)';
            }

            // Update the Commission labels in each repeater row
            $('.conditional-repeater .commission-label').text(commissionText);
            $('.non-conditional-repeater .commission-label').text(commissionText);
        }

        // Initialize Commission text based on the default selected radio button
        var initialSelectedType = $('input[name=percentage_type]:checked').val();
        updateRepeaterCommissionText(initialSelectedType);

        // Listen for changes in the radio buttons outside the repeater
        $(document).on('change', 'input[name=percentage_type]', function() {
            var percentage_type = $(this).val();
            updateRepeaterCommissionText(percentage_type);
        });
    });
</script>
