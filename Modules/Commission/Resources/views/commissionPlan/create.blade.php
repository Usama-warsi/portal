{{ Form::open(['route' => 'commission-plan.store','enctype' => 'multipart/form-data','id' => 'user']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Commission Name']) }}
        </div>
            <div class="form-group">
                {{ Form::label('date', __('Start Date / End Date'), ['class' => 'form-label']) }}
                <div class='input-group'>
                    <input type='text' name="date"  class="flatpickr-to-input form-control"
                        placeholder="Select date range" data-flatpickr='{"mode": "single"}' />
                    <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                </div>
            </div>

            <div class="form-group">
                {{ Form::label('user_id', __('Users'), ['class' => 'form-label']) }}
                {{ Form::select('user_id[]', $commissionPlan, null, ['class' => 'form-control choices', 'id' => 'choices-multiple', 'multiple' => 'multiple']) }}
            </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::submit(__('Create'), ['class' => 'btn  submit btn-primary']) }}
    </div>
    {{ Form::close() }}

@push('scripts')
    <script>
     $("#user").on("submit", function(event) {
            var selectedUsers = $("#choices-multiple").val();
            if (!selectedUsers || selectedUsers.length === 0) {
                $('#user_validation').removeClass('d-none');
                event.preventDefault(); // Prevent form submission
            } else {
                $('#user_validation').addClass('d-none');
            }
        });
    </script>
@endpush



