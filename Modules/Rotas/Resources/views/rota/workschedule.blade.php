@extends('layouts.main')
@section('page-title')
    {{ __('Manage Work Schedule') }}
@endsection
@section('page-breadcrumb')
    {{ __('Work Schedule') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Rotas/Resources/assets/css/custom.css') }}">
@endpush
@section('content')
    @permission('rotas work schedule manage')
        <div class="row">
            <div class="mt-2" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['rota.workschedule'], 'method' => 'get', 'id' => 'rotas_workschedule']) }}
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row d-flex align-items-center justify-content-end">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                        <div class="btn-box">
                                            {{ Form::label('employee', __('Employee'), ['class' => 'form-label']) }}
                                            {{ Form::select('employee', $employees,isset($_GET['employee']) ? $_GET['employee'] : '', ['class' => 'form-control ']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('rotas_workschedule').submit(); return false;"
                                    data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                    data-original-title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>

                                <a href="{{ route('rota.workschedule') }}" class="btn btn-sm btn-danger "
                                    data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                    data-original-title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                </a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            @php
                    $work_schedule = !empty($employee->work_schedule) ? json_decode($employee->work_schedule,true) : \Modules\Rotas\Entities\Rota::WorkSchedule();
           @endphp
            <div class="col-sm-12">
                <div class="card">
                    <div class="bg-none">
                        <div class="row company-setting" id="rotas_sch">
                            @if(!empty($employee))
                                {{ Form::open(['route' => ['rota.workschedule.save', $employee->id],'method' => 'post']) }}
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Monday'), ['class' => 'form-control-label']) }}
                                                    {!! Form::select('work_schedule[monday]',['working' => __('Working'), 'day_off' => __('Day Off')],
                                                        !empty($work_schedule) ? $work_schedule['monday'] : null,
                                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Tuesday'), ['class' => 'form-control-label']) }}
                                                    {!! Form::select(
                                                        'work_schedule[tuesday]',
                                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                        !empty($work_schedule) ? $work_schedule['tuesday'] : null,
                                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Wednesday'), ['class' => 'form-control-label']) }}
                                                    {!! Form::select(
                                                        'work_schedule[wednesday]',
                                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                        !empty($work_schedule) ? $work_schedule['wednesday'] : null,
                                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Thursday'), ['class' => 'form-control-label']) }}
                                                    {!! Form::select(
                                                        'work_schedule[thursday]',
                                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                        !empty($work_schedule) ? $work_schedule['thursday'] : null,
                                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Friday'), ['class' => 'form-control-label']) }}
                                                    {!! Form::select(
                                                        'work_schedule[friday]',
                                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                        !empty($work_schedule) ? $work_schedule['friday'] : null,
                                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Saturday'), ['class' => 'form-control-label']) }}
                                                    {!! Form::select(
                                                        'work_schedule[saturday]',
                                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                        !empty($work_schedule) ? $work_schedule['saturday'] : null,
                                                        ['data-placeholder' => 'Work Schedule', 'class' => 'form-control js-single-select select2 manager_manag_emp'],
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Sunday'), ['class' => 'form-control-label']) }}
                                                    {!! Form::select(
                                                        'work_schedule[sunday]',
                                                        ['working' => __('Working'), 'day_off' => __('Day Off')],
                                                        !empty($work_schedule) ? $work_schedule['sunday'] : null,
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endpermission
@endsection

