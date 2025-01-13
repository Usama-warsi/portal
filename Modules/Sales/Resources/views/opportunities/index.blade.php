@extends('layouts.main')
@section('page-title')
    {{ __('Manage Opportunities') }}
@endsection
@section('title')
    {{ __('Opportunities') }}
@endsection
@section('page-breadcrumb')
    {{ __('Opportunities') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Sales/Resources/assets/css/custom.css') }}">
@endpush
@section('page-action')
    <div>
        @stack('addButtonHook')

        <a href="{{ route('opportunities.grid') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('Kanban View') }}">
            <i class="ti ti-layout-kanban"></i>
        </a>
        @permission('opportunities create')
            <a data-url="{{ route('opportunities.create', ['opportunities', 0]) }}" data-size="lg" data-ajax-popup="true"
                data-bs-toggle="tooltip" data-title="{{ __('Create New Opportunities') }}" title="{{ __('Create') }}"
                class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('filter')
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                    <th scope="col" class="sort" data-sort="budget">{{ __('Account') }}</th>
                                    <th scope="col" class="sort" data-sort="status">{{ __('Stage') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Amount') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Assigned User') }}</th>
                                    @if (Laratrust::hasPermission('opportunities show') ||
                                            Laratrust::hasPermission('opportunities edit') ||
                                            Laratrust::hasPermission('opportunities delete'))
                                        <th scope="col" class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($opportunitiess as $opportunities)
                                    <tr>
                                        <td>
                                            @if (module_is_active('SalesForce'))
                                                @include('salesforce::layouts.addHook', [
                                                    'object' => $opportunities,
                                                    'type' => 'Opportunity',
                                                ])
                                            @endif
                                            @permission('opportunities edit')
                                                <a href="{{ route('opportunities.edit', $opportunities->id) }}" data-size="md"
                                                    data-title="{{ __('Opportunities Details') }}"
                                                    class="action-item text-primary">
                                                    {{ ucfirst($opportunities->name) }}
                                                </a>
                                            @else
                                                <a href="#" data-size="md" data-title="{{ __('Opportunities Details') }}"
                                                    class="action-item text-primary">
                                                    {{ ucfirst($opportunities->name) }}
                                                </a>
                                            @endpermission
                                        </td>
                                        <td>
                                            <span
                                                class="budget">{{ ucfirst(!empty($opportunities->accounts) ? $opportunities->accounts->name : '-') }}</span>
                                        </td>
                                        <td>
                                            <span class="budget">
                                                {{ ucfirst(!empty($opportunities->stages) ? $opportunities->stages->name : '-') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="budget">{{ currency_format_with_sym($opportunities->amount) }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="budget">{{ ucfirst(!empty($opportunities->assign_user) ? $opportunities->assign_user->name : '-') }}</span>
                                        </td>
                                        @if (Laratrust::hasPermission('opportunities show') ||
                                                Laratrust::hasPermission('opportunities edit') ||
                                                Laratrust::hasPermission('opportunities delete'))
                                            <td class="text-end">
                                                @permission('opportunities show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a data-size="md"
                                                            data-url="{{ route('opportunities.show', $opportunities->id) }}"
                                                            data-bs-toggle="tooltip"title="{{ __('Quick View') }}"
                                                            data-ajax-popup="true"
                                                            data-title="{{ __('Opportunities Details') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white ">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('opportunities edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('opportunities.edit', $opportunities->id) }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white "
                                                            data-bs-toggle="tooltip" title="{{ __('Details') }}"
                                                            data-title="{{ __('Opportunities Edit') }}"><i
                                                                class="ti ti-pencil"></i></a>
                                                    </div>
                                                @endpermission
                                                @permission('opportunities delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['opportunities.destroy', $opportunities->id]]) !!}
                                                        <a href="#!"
                                                            class="mx-3 btn btn-sm   align-items-center text-white show_confirm"
                                                            data-bs-toggle="tooltip" title='Delete'>
                                                            <i class="ti ti-trash"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endpermission
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
