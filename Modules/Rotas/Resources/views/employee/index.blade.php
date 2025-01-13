@extends('layouts.main')
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('page-title')
    {{ __('Manage Employee') }}
@endsection
@section('page-breadcrumb')
    {{ __('Employee') }}
@endsection
@section('page-action')
    <div>
        @permission('rotaemployee import')
            <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Employee Import')}}" data-url="{{ route('rotaemployee.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
            </a>
        @endpermission
        <a href="{{ route('rotaemployee.grid') }}" class="btn btn-sm btn-primary btn-icon"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
        @permission('rotaemployee create')
            <a href="{{ route('rotaemployee.create') }}" data-title="{{ __('Create New Employee') }}" data-bs-toggle="tooltip"
                title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ isset($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch') }}
                                    </th>
                                    <th>{{ isset($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department') }}
                                    </th>
                                    <th>{{ isset($company_settings['hrm_designation_name']) ? $company_settings['hrm_designation_name'] : __('Designation') }}
                                    </th>
                                    <th>{{ __('Date Of Joining') }}</th>
                                    @if (Laratrust::hasPermission('rotaemployee edit') || Laratrust::hasPermission('rotaemployee delete'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                <tr>
                                    @if (!empty($employee->employee_id))
                                    <td>
                                                @permission('rotaemployee show')
                                                    <a class="btn btn-outline-primary"
                                                        href="{{ route('rotaemployee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}">{{ Modules\Rotas\Entities\Employee::employeeIdFormat($employee->employee_id) }}</a>
                                                @else
                                                    <a
                                                        class="btn btn-outline-primary">{{ Modules\Rotas\Entities\Employee::employeeIdFormat($employee->employee_id) }}</a>
                                                @endpermission
                                            </td>
                                        @else
                                            <td>--</td>
                                        @endif
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>
                                            {{ $employee->branches_name ? $employee->branches_name : '--' }}
                                        </td>
                                        <td>
                                            {{ $employee->departments_name ? $employee->departments_name : '--' }}
                                        </td>
                                        <td>
                                            {{ $employee->designations_name ? $employee->designations_name : '--' }}

                                        </td>
                                        <td>
                                            {{ !empty($employee->company_doj) ? company_date_formate($employee->company_doj) : '--' }}
                                        </td>
                                        @if (Laratrust::hasPermission('rotaemployee edit') || Laratrust::hasPermission('rotaemployee delete'))

                                            <td class="Action">

                                                <span>
                                                    @permission('rotaemployee edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="{{ route('rotaemployee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                                                                class="mx-3 btn btn-sm  align-items-center"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @if (!empty($employee->employee_id))
                                                        @permission('rotaemployee delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {{ Form::open(['route' => ['rotaemployee.destroy', $employee->id], 'class' => 'm-0']) }}
                                                                @method('DELETE')
                                                                <a
                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="Delete" aria-label="Delete"
                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                    data-confirm-yes="delete-form-{{ $employee->id }}"><i
                                                                        class="ti ti-trash text-white text-white"></i></a>

                                                                {{ Form::close() }}
                                                            </div>
                                                        @endpermission
                                                    @endif
                                                </span>

                                            </td>
                                        @endif
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
