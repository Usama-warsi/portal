{{ Form::open(['route' => 'commission-receipt.store', 'enctype' => 'multipart/form-data', 'id' => 'receipt-form']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-sm-6">
            {{ Form::label('month', __('Select Month'), ['class' => 'form-label']) }}
            {{ Form::select('month', $month, date('m'), ['class' => 'form-control ', 'id' => 'month']) }}
        </div>
        <div class="form-group col-sm-6">
            {{ Form::label('year', __('Select Year'), ['class' => 'form-label']) }}
            {{ Form::select('year', $year, date('Y'), ['class' => 'form-control ']) }}

        </div>
        <div class="form-group">
            {{ Form::label('commission_str', __('Commission Structure'), ['class' => 'col-form-label']) }}
            {!! Form::select('commission_str', $commissionModule, null, [
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Enter Commission Structure',
                'id' => 'commission_str',
            ]) !!}
        </div>
        <div class="form-group" id="comissionAgents">
            {{ Form::label('agent', __('Agent'), ['class' => 'col-form-label']) }}
            {{ Form::select('agent', [], null, ['class' => 'form-control ', 'id' => 'selectAgent', 'placeholder' => 'Select Agent']) }}
        </div>

        <div class="form-group">
            {{ Form::label('amount', __('Commission Amount'), ['class' => 'form-label']) }}
            {{ Form::text('amount', null, ['class' => 'form-control commission_amount', 'required' => 'required', 'readonly' => 'true', 'id' => 'commission_amount']) }}
        </div>
    </div>
    <div class="modal-footer">

        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::submit(__('Receipt'), ['class' => 'btn  submit btn-warning']) }}
        <button type="button" class="btn submit btn-primary" id="clickToPaidButton">{{ __('Click To Paid') }}</button>
    </div>
    <input type="hidden" id="commissionPlan" name="comissionPlanId">
</div>
{{ Form::close() }}
<script>
    document.getElementById('clickToPaidButton').addEventListener('click', function () {
        // Submit the form to the 'commission.bank.payment' route
        document.getElementById('receipt-form').action = "{{ route('commission.bank.payment') }}";
        document.getElementById('receipt-form').submit();
    });
</script>
<script>
    $('#commission_str').on('change', function() {
        var commission_str = $(this).val();
        getCommissionAgent(commission_str);
    });

    function getCommissionAgent(commission_str) {
        $.ajax({
            url: '{{ route('get.commission.agent') }}',
            type: 'POST',
            data: {
                "commission_str": commission_str,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#projectType').empty()
                if (data.str == 'Project') {
                    $('#projectType').append(data.html)
                }
                $('#comissionAgents').empty()
                var agents =
                    '<select class="form-control" name="agent" id="selectAgent" placeholder="{{ __('Select Agent') }}"  >';
                agents += '<option value="" >{{ __('Select Agent') }}</option>';
                $.each(data.users, function(key, value) {
                    agents += '<option value="' + key + '">' + value + '</option>';
                });
                agents += '</select>';
                $('#comissionAgents').append(
                    '<label for="selectAgent" class="form-label">Select Agent</label>')
                $('#comissionAgents').append(agents)

            }
        });
    }
    $(document).on('change', '#selectAgent', function() {
        var selectAgent = $(this).val();
        var selectStr =$("#commission_str option:selected").val();

        $.ajax({
            url: '{{ route('commission.cal') }}',
            type: 'POST',
            data: {
                "selectAgent": selectAgent,
                "selectStr": selectStr,

                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {

                $('#commission_amount').val(data.comission);
                $('#commissionPlan').val(data.comissionPlanId);
            }
        });
    });

</script>
