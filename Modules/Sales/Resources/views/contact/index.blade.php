@extends('layouts.main')
@section('page-title')
    {{ __('Manage Contact') }}
@endsection
@section('title')
    {{ __('Contact') }}
@endsection
@section('page-breadcrumb')
    {{ __('Contact') }}
@endsection
@section('page-action')
    <div>
        @permission('contact import')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{ __('Contact Import') }}"
                data-url="{{ route('contact.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
                    class="ti ti-file-import"></i>
            </a>
        @endpermission
        <a href="{{ route('contact.grid') }}" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>

        @permission('contact create')
            <a data-url="{{ route('contact.create', ['contact', 0]) }}" data-size="lg" data-ajax-popup="true"
                data-bs-toggle="tooltip"data-title="{{ __('Create New Contact') }}" title="{{ __('Create') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('filter')
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Sales/Resources/assets/css/custom.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                    <th scope="col" class="sort" data-sort="budget">{{ __('Email') }}</th>
                                    <th scope="col" class="sort" data-sort="status">{{ __('Phone') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('City') }}</th>
                                    <th scope="col" class="sort" data-sort="Assigned User">{{ __('Assigned User') }}
                                    </th>
                                    @if (Laratrust::hasPermission('contact show') ||
                                            Laratrust::hasPermission('contact edit') ||
                                            Laratrust::hasPermission('contact delete'))
                                        <th scope="col" class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contacts as $contact)
                                    <tr>
                                        <td>
                                            @if (module_is_active('SalesForce'))
                                                @include('salesforce::layouts.addHook', [
                                                    'object' => $contact,
                                                    'type' => 'Contact',
                                                ])
                                            @endif
                                            @permission('contact edit')
                                                <a href="{{ route('contact.edit', $contact->id) }}" data-size="md"
                                                    data-title="{{ __('Contact Details') }}" class="action-item text-primary">
                                                    {{ ucfirst($contact->name) }}
                                                </a>
                                            @else
                                                <a href="#" data-size="md" data-title="{{ __('Contact Details') }}"
                                                    class="action-item text-primary">
                                                    {{ ucfirst($contact->name) }}
                                                </a>
                                            @endpermission
                                        </td>
                                        <td>
                                            <span class="budget">
                                                {{ $contact->email }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="budget">
                                                {{ $contact->phone }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="budget ">{{ ucfirst($contact->contact_city) }}</span>
                                        </td>
                                        <td>
                                            <span class="col-sm-12"><span
                                                    class="text-m">{{ ucfirst(!empty($contact->assign_user) ? $contact->assign_user->name : '-') }}</span></span>
                                        </td>
                                        @if (Laratrust::hasPermission('contact show') ||
                                                Laratrust::hasPermission('contact edit') ||
                                                Laratrust::hasPermission('contact delete'))
                                            <td class="text-end">
                                                @permission('contact show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a data-size="md" data-url="{{ route('contact.show', $contact->id) }}"
                                                            data-bs-toggle="tooltip" title="{{ __('Quick View') }}"
                                                            data-ajax-popup="true" data-title="{{ __('Contact Details') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white ">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('contact edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('contact.edit', $contact->id) }}"data-size="md"
                                                            class="btn btn-sm d-inline-flex align-items-center text-white "
                                                            data-bs-toggle="tooltip"data-title="{{ __('Contact Edit') }}"
                                                            title="{{ __('Details') }}"><i class="ti ti-pencil"></i></a>
                                                    </div>
                                                @endpermission
                                                @permission('contact delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['contact.destroy', $contact->id]]) !!}
                                                        <a href="#!"
                                                            class="btn btn-sm   align-items-center text-white show_confirm"
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
