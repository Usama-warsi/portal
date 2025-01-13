@php 
    $company_settings = getCompanyAllSetting(); 
@endphp
@permission('rotas manage')
<div class="card" id="rotas-sidenav">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5 class="">{{ __('Rotas Dashboard Settings') }}</h5>
            </div>
        </div>
    </div>
    {{ Form::open(['route' => 'rotas.setting.store','method' => 'post']) }}
    <div class="card-body">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <h5 class=" h6 mb-1">{{ __('Rota Settings') }}</h5>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-check form-switch d-inline-block mx-2">
                    {!! Form::checkbox('emp_show_rotas_price', 1, isset($company_settings['emp_show_rotas_price']) ? 1 : 0, ['required' => false, 'class' => 'custom-control-input form-check-input', 'id' => 'emp_show_rotas_price', 'role' => 'switch']) !!}
                    {{ Form::label('emp_show_rotas_price', __('Show employee rotas price'), ['class' => 'custom-label text-dark']) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-check form-switch d-inline-block mx-2">
                    {!! Form::checkbox('emp_show_avatars_on_rota', 1, isset($company_settings['emp_show_avatars_on_rota']) ? 1 : 0, ['required' => false, 'class' => 'custom-control-input form-check-input', 'id' => 'emp_show_avatars_on_rota', 'role' => 'switch']) !!}
                    {{ Form::label('emp_show_avatars_on_rota', __('Show employee avatars on rota'), ['class' => 'custom-label text-dark']) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-check form-switch d-inline-block mx-2">
                    {!! Form::checkbox('emp_hide_rotas_hour', 1, isset($company_settings['emp_hide_rotas_hour']) ? 1 : 0, ['required' => false, 'class' => 'custom-control-input form-check-input', 'id' => 'emp_hide_rotas_hour', 'role' => 'switch']) !!}
                    {{ Form::label('emp_hide_rotas_hour', __('Hide employee rotas hour'), ['class' => 'custom-label text-dark']) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-check form-switch d-inline-block mx-2">
                    {!! Form::checkbox('include_unpublished_shifts', 1, isset($company_settings['include_unpublished_shifts']) ? 1 : 0, ['required' => false, 'class' => 'custom-control-input form-check-input', 'id' => 'include_unpublished_shifts', 'role' => 'switch']) !!}
                    {{ Form::label('include_unpublished_shifts', __('Include unpublished shifts on the dashboard and report'), ['class' => 'custom-label text-dark']) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-check form-switch d-inline-block mx-2">
                    {!! Form::checkbox('emp_only_see_own_rota', 1, isset($company_settings['emp_only_see_own_rota']) ? 1 : 0, ['required' => false, 'class' => 'custom-control-input form-check-input', 'id' => 'emp_only_see_own_rota', 'role' => 'switch']) !!}
                    {{ Form::label('emp_only_see_own_rota', __('Employees only see themselves on the rota'), ['class' => 'custom-label text-dark']) }}
                </div>
            </div>
            <br><br><br>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-2">
                        {{ Form::label('', __('Week Starts'), ['class' => 'form-label text-dark h6']) }}
                        {!! Form::select('company_week_start', ['monday' => __('Monday'), 'tuesday' => __('Tuesday'), 'wednesday' => __('Wednesday'), 'thursday' => __('Thursday'), 'friday' => __('Friday'), 'saturday' => __('Saturday'), 'sunday' => __('Sunday')], isset($company_settings['company_week_start']) ? $company_settings['company_week_start'] : null, ['required' => true, 'data-placeholder' => __('Select Day'), 'class' => 'form-control js-single-select']) !!}
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        {{ Form::label('leave_year_start', __('Year Starts'), ['class' => 'form-label text-dark h6']) }}
                        {!! Form::select('leave_start_month', ['01' => __('January'), '02' => __('February'), '03' => __('March'), '04' => __('April'), '05' => __('May'), '06' => __('June'), '07' => __('July'), '08' => __('August'), '09' => __('September'), '10' => __('October'), '11' => __('November'), '12' => __('December')], isset($company_settings['leave_start_month']) ? $company_settings['leave_start_month'] : 1, ['required' => true, 'data-placeholder' => __('Select Month'), 'class' => 'form-control js-single-select']) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        {{ Form::label('', __('Shift Notes'), ['class' => 'form-label text-dark h6']) }}
                        {!! Form::select('see_note', ['none' => __('Only admins and managers can see shift notes'), 'self' => __('Employees can only see notes for their own shifts and open shifts'), 'all' => __('Employees can see shift notes for everybody')], isset($company_settings['see_note']) ? $company_settings['see_note'] : null, ['required' => false, 'class' => 'form-control ']) !!}
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 ">
                        {{ Form::label('breck_paid', __('Break'), ['class' => 'form-label text-dark h6']) }}
                        <br>
                        <div class="custom-control custom-radio d-inline-block mx-2">
                            <input type="radio" name="break_paid" value="paid" class="form-check-input"
                                {{ $company_settings['break_paid'] == 'paid' ? 'checked' : '' }}>
                            <label class="custom-label text-dark">{{ __('Paid') }}</label>
                        </div>

                        <div class="custom-control custom-radio d-inline-block mx-2">
                            <input type="radio" name="break_paid" value="unpaid"
                                class="form-check-input"
                                {{ $company_settings['break_paid'] == 'unpaid' ? 'checked' : '' }}>
                            <label class="custom-label text-dark">{{ __('Unpaid') }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>

        <div class="card-footer text-end py-0 pe-2 border-0">
            <input name="from" type="hidden" value="password">
            <input name="from" type="hidden" value="password">
            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
        </div>

    </div>
    {{ Form::close() }}
</div>

    <div id="rotas-work-schedule" class="card">
        <div class="card-header">
            <h5>{{ __('Rotas Work Schedule') }}</h5>
            <small class="text-muted">{{ __('') }}</small>
        </div>
        <div class="bg-none">
            <div class="row company-setting">
                {{ Form::open(['route' => 'rotas.setting.save','method' => 'post']) }}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-3">
                                <div class="form-group">
                                    {{ Form::label('', __('Monday'), ['class' => 'form-label']) }}
                                    {!! Form::select('work_schedule[monday]',['working' => __('Working'), 'day_off' => __('Day Off')],
                                        !empty(\Modules\Rotas\Entities\Rota::WorkSchedule()) ? \Modules\Rotas\Entities\Rota::WorkSchedule()['monday'] : null,
                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-3">
                                <div class="form-group">
                                    {{ Form::label('', __('Tuesday'), ['class' => 'form-label']) }}
                                    {!! Form::select(
                                        'work_schedule[tuesday]',
                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                        !empty(\Modules\Rotas\Entities\Rota::WorkSchedule()) ? \Modules\Rotas\Entities\Rota::WorkSchedule()['tuesday'] : null,
                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-3">
                                <div class="form-group">
                                    {{ Form::label('', __('Wednesday'), ['class' => 'form-label']) }}
                                    {!! Form::select(
                                        'work_schedule[wednesday]',
                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                        !empty(\Modules\Rotas\Entities\Rota::WorkSchedule()) ? \Modules\Rotas\Entities\Rota::WorkSchedule()['wednesday'] : null,
                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-3">
                                <div class="form-group">
                                    {{ Form::label('', __('Thursday'), ['class' => 'form-label']) }}
                                    {!! Form::select(
                                        'work_schedule[thursday]',
                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                        !empty(\Modules\Rotas\Entities\Rota::WorkSchedule()) ? \Modules\Rotas\Entities\Rota::WorkSchedule()['thursday'] : null,
                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-3">
                                <div class="form-group">
                                    {{ Form::label('', __('Friday'), ['class' => 'form-label']) }}
                                    {!! Form::select(
                                        'work_schedule[friday]',
                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                        !empty(\Modules\Rotas\Entities\Rota::WorkSchedule()) ? \Modules\Rotas\Entities\Rota::WorkSchedule()['friday'] : null,
                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-3">
                                <div class="form-group">
                                    {{ Form::label('', __('Saturday'), ['class' => 'form-label']) }}
                                    {!! Form::select(
                                        'work_schedule[saturday]',
                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                        !empty(\Modules\Rotas\Entities\Rota::WorkSchedule()) ? \Modules\Rotas\Entities\Rota::WorkSchedule()['saturday'] : null,
                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-3">
                                <div class="form-group">
                                    {{ Form::label('', __('Sunday'), ['class' => 'form-label']) }}
                                    {!! Form::select(
                                        'work_schedule[sunday]',
                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                        !empty(\Modules\Rotas\Entities\Rota::WorkSchedule()) ? \Modules\Rotas\Entities\Rota::WorkSchedule()['sunday'] : null,
                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                    ) !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card-footer text-end py-0 pe-2 border-0">
                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>

    </div>
@endpermission
