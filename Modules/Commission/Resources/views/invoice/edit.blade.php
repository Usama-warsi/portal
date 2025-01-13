
@if(($type == 'invoice'))
<div class="col-6 d-none commission_plan">
    <div class="form-group">
        {{ Form::label('commission_plan', __('Commission Plan'), ['class' => 'form-label']) }}
        {!! Form::select('commission_plan', [], null, [
            'class' => 'form-control commission_plan',
            'placeholder' => 'Select Commission',
            'id' => 'comissionPlan',
        ]) !!}
    </div>
</div>
@elseif($type == 'salesinvoice')
<div class="col-6 ">
    <div class="form-group">
        {{ Form::label('commission_plan', __('Commission Plan'), ['class' => 'form-label']) }}
        {!! Form::select('commission_plan',$salesCommissions, null, [
            'class' => 'form-control ',
            'placeholder' => 'Select Commission',
        ]) !!}
    </div>
</div>
@endif

<div class="col-md-6 d-none commission_plan">
    <div class="form-group">
        {{ Form::label('agent', __('Select Agent'), ['class' => 'form-label']) }}
        <div id="comissionAgents">
            {!! Form::select('agent[]', $agent, explode(',',$invoice->agent), [
                'class' => 'form-control choices',
                'multiple',
                'id' => 'selectAgent',
            ]) !!}
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        getPlans($('#account_type').val());
    });
    $(document).on('change', '.commission_plan', function() {
        var selectedPlan = $(this).val();
        $.ajax({
            url: '{{ route('getagent') }}',
            type: 'POST',
            data: {
                "selectedPlan": selectedPlan,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#comissionAgents').empty();

                var agents = '<select class="form-control" name="agent" id="selectAgent" placeholder="{{__('Select CommissionPlan')}}"  multiple>';
                agents += '<option value="" disabled>{{__('Select Agent')}}</option>';
                $.each(data, function (key, value) {
                    agents += '<option value="' + key + '">' + value + '</option>';
                });
                agents += '</select>';

                $("#comissionAgents").append(agents);
                var multipleCancelButton = new Choices('#selectAgent', {
                    removeItemButton: true,
                });
            }
        });
    });

    $(document).on('change', '#account_type', function() {
        var selected = $(this).val();
        getPlans(selected);
    });

    function getPlans(selected){
        $.ajax({
            url: '{{ route('get.commission.plans') }}',
            type: 'POST',
            data: {
                "selected": selected, // Change the parameter name
                "_token": "{{ csrf_token() }}",
            },

            success: function(data) {
                $('.commission_plan').addClass('d-none');

                $('#comissionPlan').empty();
                if (data.commissionstr != 0) {
                    $('.commission_plan').removeClass('d-none');

                    $('#comissionPlan').append( '<option value="" disabled >{{ __('Select Commission') }}</option>');

                    var invoiceCommissionPlan = '{{$invoice->commission_plan}}';

                    $.each(data.commissions, function(key, value) {
                        var option = $('<option>', {
                            value: key,
                            text: value
                        });

                        if (key == invoiceCommissionPlan) {
                            option.attr('selected', 'selected');
                        }

                        $('#comissionPlan').append(option);
                    });

                }
            }
        });
    }
</script>


