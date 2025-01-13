@extends('layouts.main')

@section('page-title')
    {{ __('Manage Training') }}
@endsection

@section('page-breadcrumb')
    {{ __('Training List') }}
@endsection

@php
    $company_settings = getCompanyAllSetting();
@endphp

@section('page-action')
    <div>
        @permission('training create')
            <a href="#" data-url="{{ route('training.create') }}" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create New Training') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
                data-bs-original-title="{{ __('Create') }}">
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
                                    <th>{{ !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch') }}
                                    </th>
                                    <th>{{ __('Training Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Trainer') }}</th>
                                    <th>{{ __('Training Duration') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    @if (Laratrust::hasPermission('training edit') ||
                                            Laratrust::hasPermission('training delete') ||
                                            Laratrust::hasPermission('training show'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trainings as $training)
                                    <tr>
                                        <td>{{ !empty($training->branches) ? $training->branches->name : '' }}</td>
                                        <td>{{ !empty($training->types) ? $training->types->name : '' }} <br></td>
                                        <td>
                                            @if ($training->status == 0)
                                                <span
                                                    class="badge bg-warning p-2 px-3 rounded mt-1 status-badge6">{{ __($status[$training->status]) }}</span>
                                            @elseif($training->status == 1)
                                                <span
                                                    class="badge bg-primary p-2 px-3 rounded mt-1 status-badge6">{{ __($status[$training->status]) }}</span>
                                            @elseif($training->status == 2)
                                                <span
                                                    class="badge bg-success p-2 px-3 rounded mt-1 status-badge6">{{ __($status[$training->status]) }}</span>
                                            @elseif($training->status == 3)
                                                <span
                                                    class="badge bg-danger p-2 px-3 rounded mt-1 status-badge6">{{ __($status[$training->status]) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ !empty($training->employees) ? $training->employees->name : '' }} </td>
                                        <td>{{ !empty($training->trainers) ? $training->trainers->firstname : '' }}</td>
                                        <td>{{ company_date_formate($training->start_date) . ' to ' . company_date_formate($training->end_date) }}
                                        </td>
                                        <td>{{ currency_format_with_sym($training->training_cost) }}</td>
                                        <td class="Action">
                                            @if (Laratrust::hasPermission('training edit') ||
                                                    Laratrust::hasPermission('training delete') ||
                                                    Laratrust::hasPermission('training show'))
                                                <span>
                                                    @permission('training show')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="{{ route('training.show', \Illuminate\Support\Facades\Crypt::encrypt($training->id)) }}"
                                                                class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                                data-url="" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-bs-original-title="{{ __('Show') }}">
                                                                <i class="ti ti-eye text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @permission('training edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                data-size="lg"
                                                                data-url="{{ route('training.edit', $training->id) }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Training') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @permission('training delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['training.destroy', $training->id],
                                                                'id' => 'delete-form-' . $training->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            </form>
                                                        </div>
                                                    @endpermission
                                                </span>
                                            @endif
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
