@extends('layouts.main')
@section('page-title')
    {{__('Subcategory')}}
@endsection
@section('page-breadcrumb')
    {{__('Setup')}},
    {{__('Sub Category')}}
@endsection
@section('page-action')
<div class="text-end align-items-end d-flex justify-content-end">
    @permission('course subcategory create')
        <div class="btn btn-sm btn-primary btn-icon m-1">
            <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Subcategory')}}" data-url="{{route('course-subcategory.create')}}"><i class="ti ti-plus text-white"></i></a>
        </div>
    @endpermission
</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-12">
            @include('lms::layouts.system_setup')
        </div>
        <div class="col-xl-9">
            <!-- Listing -->
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5>{{__('All Subcategories')}}</h5>
                </div>
                <!-- Table -->
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Name')}}</th>
                                    <th scope="col">{{ __('Category')}}</th>
                                    <th scope="col">{{ __('Created at')}}</th>
                                    @if(Laratrust::hasPermission('course subcategory edit') ||  Laratrust::hasPermission('course subcategory delete'))
                                        <th scope="col" class="text-right">{{ __('Action')}}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse ($subcategorise as $subcategory)
                                    <tr>
                                        <td>{{ $subcategory->name }}</td>
                                        <td>{{!empty($subcategory->category_name)?$subcategory->category_name:''}}</td>
                                        <td>{{ company_date_formate($subcategory->created_at)}}</td>
                                        @if(Laratrust::hasPermission('course subcategory edit') ||  Laratrust::hasPermission('course subcategory delete'))
                                            <td class="text-right">
                                                @permission('course subcategory edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Subcategory')}}" data-url="{{route('course-subcategory.edit',[$subcategory->id])}}"><i class="ti ti-pencil text-white"></i></a>
                                                    </div>
                                                @endpermission

                                                @permission('course subcategory delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['course-subcategory.destroy', $subcategory->id] ]) !!}
                                                            <a href="#!" class="mx-3 btn btn-sm align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endpermission
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    @include('layouts.nodatafound')
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

