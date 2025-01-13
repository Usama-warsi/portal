<form method="POST" action="{{ route('googlemeet.store') }}" accept-charset="UTF-8">
    @csrf
    <div class="modal-body">
        <div class="text-end">
            @if (module_is_active('AIAssistant'))
                @include('aiassistant::ai.generate_ai_btn', [
                    'template_module' => 'google_meet',
                    'module' => 'GoogleMeet',
                ])
            @endif
        </div>
        <div class="row">
            <div class="form-group col-md-12 mb-1">
                <label for="title" class="col-form-label">{{ __('Title') }}</label>
                <input class="form-control" placeholder="{{ __('Enter Meeting Title') }}" required="required"
                    name="title" type="text" id="title">
            </div>
            <div class="form-group col-md-12 mb-1">
                <label for="description" class="col-form-label">{{ __('Description') }}</label>
                <input class="form-control" placeholder="{{ __('Enter Meeting Description') }}" required="required"
                    name="description" type="text" id="description">
            </div>
            <div class="form-group col-md-6  mb-1">
                <div>
                    {{ Form::label('user_id', __('Users'), ['class' => 'col-form-label']) }}
                    {{ Form::select('users[]', $users, null, ['class' => 'form-control choices', 'id' => 'choices-multiple', 'multiple' => '', 'required' => 'required']) }}
                </div>
            </div>
            <div class="form-group col-md-6  mb-1">
                <label for="datetime" class="col-form-label">{{ __('Start Date/Time') }}</label>
                <input class="form-control" value="{{ date('Y-m-d h:i') }}" placeholder="{{ __('Select Date/Time') }}"
                    required="required" name="start_date" type="datetime-local">
            </div>
            <div class="form-group col-md-6  mb-1">
                <label for="duration" class="col-form-label">{{ __('Duration') }}</label>
                <input class="form-control" placeholder="{{ __('Enter Duration') }}" required="required" name="duration"
                    type="number" id="duration">
            </div>
            <div class="col-md-6 mt-3">
                @if (module_is_active('Calender') && company_setting('google_calendar_enable') == 'on')
                    @include('calender::setting.synchronize')
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div>
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button class="btn  btn-primary" type="submit" id="create-client">{{ __('Create') }}</button>
        </div>
    </div>
</form>
