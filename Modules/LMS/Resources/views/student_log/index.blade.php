@extends('layouts.main')
@section('page-title')
    {{ __('Student Log') }}
@endsection
@section('page-breadcrumb')
    {{ __('Student') }},
    {{ __('Student Logs') }}
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;
            $('.current_date').val(today);
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class="" id="multiCollapseExample1" style="">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['student.logs'], 'method' => 'get', 'id' => 'studentlogs_filter']) }}
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                <div class="btn-box">
                                    {{ Form::label('month', __('Month'), ['class' => 'form-label']) }}
                                    {{ Form::month('month', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), ['class' => 'month-btn form-control month-btn']) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                <div class="btn-box">
                                    {{ Form::label('student', __('Student'), ['class' => 'form-label']) }}
                                    {{ Form::select('student', $studentsList, isset($_GET['student']) ? $_GET['student'] : '', ['class' => 'form-control select ', 'id' => 'id']) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                onclick="document.getElementById('studentlogs_filter').submit(); return false;"
                                data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                data-original-title="{{ __('apply') }}">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="{{ route('student.logs') }}" class="btn btn-sm btn-danger "
                                data-bs-toggle="tooltip"
                                data-original-title="Reset">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                            </a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{ __('Student Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Last Login') }}</th>
                                    <th>{{ __('Ip') }}</th>
                                    <th>{{ __('Country') }}</th>
                                    <th>{{ __('Device') }}</th>
                                    <th>{{ __('Os') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        @php
                                            $json = json_decode($student->details);
                                        @endphp

                                        <td>{{ $student->student_name }}</td>
                                        <td>{{ $student->student_email }}</td>
                                        <td>{{ $student->date }}</td>
                                        <td>{{ $student->ip }}</td>
                                        <td>{{ $json->country }}</td>
                                        <td>{{ $json->device_type }}</td>
                                        <td>{{ $json->os_name }}</td>

                                        <td>
                                            @permission('student logs show')
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('View')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('View Student Logs')}}" data-url="{{route('studentlog.show', [$student->id])}}"><i class="ti ti-eye text-white"></i></a>
                                                </div>
                                            @endpermission

                                            @permission('student logs delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['studentlog.destroy', $student->id]]) !!}
                                                        <a href="#!" class="mx-3 btn btn-sm align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
