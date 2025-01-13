@extends('layouts.main')
@section('page-title')
    {{ __('Commission Plan Edit') }}
@endsection
@section('page-breadcrumb')
    {{ __('Commission Plan') }},
    {{ __('Edit') }}
@endsection
@section('content')
    <div class="row">
        {{ Form::model($commissionPlanId, ['route' => ['commission-plan.update', $commissionPlanId->id], 'method' => 'PUT']) }}

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h3>{{ __('Commission Plan') }}</h3>

                    <div class="col-md-8">
                        <div class="form-group">
                            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                            {{ Form::text('name', $commissionPlanId->name, ['class' => 'form-control', 'placeholder' => __('Enter Commission Name'), 'required' => 'required']) }}
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group">
                            {{ Form::label('date', __('Start Date / End Date'), ['class' => 'form-label']) }}
                            <div class='input-group'>
                                <input type='text' name="date" class="flatpickr-to-input form-control"
                                    value="{{ $date }}" placeholder="Select date range" />
                                <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            {{ Form::label('user_id', __('Users'), ['class' => 'form-label']) }}
                            {{ Form::select('user_id[]', $commissionPlan, explode(',', $commissionPlanId['user_id']), ['class' => 'form-control choices', 'id' => 'choices-multiple', 'multiple' => 'multiple', 'required' => 'required']) }}
                        </div>
                    </div>

                    <div class="col-sm-8">
                        <div class="form-group">
                            <label class="form-label mt-2" for="example3cols3Input">{{ __('Commission Type') }}</label>
                            <div class="row ms-1">
                                <div class="form-check col-md-2">
                                    <input class="form-check-input" type="radio" name="percentage_type" value="percentage"
                                        id="commission_percentage"
                                        {{ isset($commissionPlanId['commission_type']) && $commissionPlanId['commission_type'] == 'percentage' ? 'checked' : '' }}>
                                    <label class="form-check-label pointer" for="commission_percentage">
                                        {{ __('Percentage') }}
                                    </label>
                                </div>
                                <div class="form-check col-md-2">
                                    <input class="form-check-input" type="radio" name="percentage_type" value="fixed"
                                        id="commission_fixed"
                                        {{ isset($commissionPlanId['commission_type']) && $commissionPlanId['commission_type'] == 'fixed' ? 'checked' : '' }}>
                                    <label class="form-check-label pointer" for="commission_fixed">
                                        {{ __('Fixed') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <h4>{{ __('Commission Structure') }}</h4>
                            {{ Form::select('commissionstr', $commissionModule, !empty($getCommStr['commissionstr']) ? $getCommStr['commissionstr'] : '', ['class' => 'form-control choices', 'required' => 'required', 'id' => 'commission_str', 'placeholder' => 'Please Select']) }}
                        </div>
                    </div>

                    <div class="col-md-8 commission_structure">
                    </div>

                    <div class="modal-footer text-end col-md-8">
                        <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                    </div>

                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection

@push('scripts')
    <script>
        function commissionPlan(status = 'trigger') {

            var selectedValues = $('#commission_str').val();
            var commissionPlanId = '{{ $commissionPlanId->id }}'
            $.ajax({
                url: '{{ route('commission.plan.attribute') }}',
                type: 'POST',
                data: {
                    "attribute_id": selectedValues,
                    "_token": "{{ csrf_token() }}",
                    "commissionPlanId": commissionPlanId,
                    "status": status,
                },
                beforeSend: function() {
                    $(".loader-wrapper").removeClass('d-none');
                },
                success: function(response) {

                    $(".loader-wrapper").addClass('d-none');
                    if (response != false) {
                        $('.commission_structure').html(response.html);
                    } else {
                        $('.commission_structure').html('');
                        toastrs('Error', 'Something went wrong, please try again!',
                            'error');
                    }

                    @if (!empty($getCommStr['conditional']))
                        setTimeout(function() {
                            var selectedValue = '{{ $getCommStr['conditional'] }}';

                            // // invoice
                            if (selectedValue == 'ladder_of_invoice') {
                                $('.conditional-repeater').show();
                                $('.non-conditional-repeater').hide();

                            } else if (selectedValue == 'non_conditional') {
                                $('.conditional-repeater').hide();
                                $('.non-conditional-repeater').show();
                            }
                        }, 1000);
                    @endif

                    @if (!empty($getCommStr['project_type']))
                        setTimeout(function() {
                            $('#project_wise').trigger('change');

                            $('select[name=project_id]').trigger('change');

                        }, 1000);
                    @endif

                }
            });
        }

        $(document).on('change', '#commission_str', function() {
            commissionPlan('change');
        });
    </script>

    @if (!empty($getCommStr['commissionstr']))
    <script>
        setTimeout(function() {
            commissionPlan();
            // $('#commission_str').trigger('change');
        }, 300);
    </script>
    @endif
@endpush
