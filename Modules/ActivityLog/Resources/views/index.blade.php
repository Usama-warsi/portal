@php
    $creatorId = creatorId();
@endphp

@extends('layouts.main')
@section('page-title')
    {{ __('Manage Activity Log') }}
@endsection

@section('page-breadcrumb')
    {{ __('Activity Log') }}
@endsection

@section('page-action')
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class=" multi-collapse mt-2" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['activitylog.index'], 'method' => 'GET', 'id' => 'module_form']) }}
                        <div class="row d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box">
                                    <label for="staff">{{ __('Staff') }}</label>
                                    <select class="form-control staff " name="staff" id="staff" tabindex="-1"
                                        aria-hidden="true">
                                        <option value="">{{ __('Select staff') }}</option>
                                        @foreach ($staffs as $staff)
                                            @if ($staff->id == $creatorId)
                                                <span class="badge bg-dark"> {{ Auth::user()->roles->first()->name }}</span>
                                            @else
                                                <span class="badge bg-dark"> {{ __('') }}</span>
                                            @endif
                                            <option value="{{ $staff->id }}"
                                                {{ isset(request()->staff) && request()->staff == $staff->id ? 'selected' : '' }}>
                                                {{ $staff->name }}@if ($staff->id == $creatorId)
                                                    <span class="badge bg-dark">
                                                        {{'('. $staff->type.')' }}</span>
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box">
                                    <label for="filter">{{ __('Add-on') }}</label>
                                    <select class="form-control modules " name="filter" id="module" tabindex="-1"
                                        aria-hidden="true">
                                        @foreach ($modules as $module)
                                            <option value="{{ $module }}"
                                                {{ isset(request()->filter) && request()->filter == $module ? 'selected' : '' }}>
                                                {{ $module }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('module_form').submit(); return false;"
                                    data-bs-toggle="tooltip" title="{{ __('Search') }}"
                                    data-original-title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('activitylog.index') }}" class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                    data-original-title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
                                </a>
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="products">
                            <thead>
                                <tr>
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Module') }}</th>
                                    <th>{{ __('Sub Module') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Staff') }}</th>
                                    <th>{{ __('Activity Duration') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activitys as $activity)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $activity->module }}</td>
                                        <td>{{ $activity->sub_module }}</td>
                                        <td>{{ $activity->description . (!empty($activity->name) ? $activity->name : '') . '.' }}</td>
                                        <td>{{ (!empty($activity->name) ? $activity->name : '--') }}
                                            @if (!empty($activity->user_id)  && $activity->user_id == $creatorId)
                                                <span class="badge bg-primary">{{ $activity->type }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $activity->created_at->diffForHumans() }}</td>
                                        <td>
                                            @permission('activitylog delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['activitylog.destroy', $activity->id],
                                                        'id' => 'delete-form-' . $activity->id,
                                                    ]) !!}
                                                    <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
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

{{-- @push('scripts')
    <script>
        $('#module').on('change', function() {
            this.form.submit();
        })
    </script>
@endpush --}}
