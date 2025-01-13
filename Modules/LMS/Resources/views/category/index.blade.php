@extends('layouts.main')
@section('page-title')
    {{__('Category')}}
@endsection
@section('page-breadcrumb')
    {{('Setup')}},
    {{('Category')}}
@endsection
@section('page-action')
    <div class="text-end align-items-end d-flex justify-content-end">
        @permission('course category create')
            <div class="btn btn-sm btn-primary btn-icon m-1">
                <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Category')}}" data-url="{{route('course-category.create')}}"><i class="ti ti-plus text-white"></i></a>
            </div>
        @endpermission
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/LMS/Resources/assets/css/custom.css') }}">
@endpush
@push('scripts')

@endpush
@section('content')
    <div class="row">
        <div class="col-lg-3 col-12">
            @include('lms::layouts.system_setup')
        </div>
        <div class="col-xl-9">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5>{{__('All Categories')}}</h5>
                </div>
                <!-- Table -->
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Image')}}</th>
                                    <th scope="col">{{ __('Name')}}</th>
                                    <th scope="col">{{ __('Created at')}}</th>
                                    @if(Laratrust::hasPermission('course category edit') ||  Laratrust::hasPermission('course category delete'))
                                        <th scope="col" class="text-center">{{ __('Action')}}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse ($categorise as $category)
                                    <tr>
                                        <td scope="row">
                                            <div class="media align-items-center">
                                                <div class="">

                                                    @if(!empty($category->category_image))
                                                        <a href="{{get_file($category->category_image)}}" target="_blank">
                                                            <img alt="Image placeholder" src="{{get_file($category->category_image)}}" class="rounded" style="width:70px; height:50px;">
                                                        </a>
                                                    @else
                                                        <a href="{{asset('Modules/LMS/Resources/assets/image/category_image/default.png')}}" target="_blank">
                                                            <img alt="Image placeholder" src="{{asset('Modules/LMS/Resources/assets/image/category_image/default.png')}}" class="rounded" style="width:70px; height:50px;">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td> {{ $category->name }} </td>
                                        <td> {{ company_date_formate($category->created_at)}} </td>
                                        @if(Laratrust::hasPermission('course category edit') ||  Laratrust::hasPermission('course category delete'))
                                            <td class="text-center">
                                                @permission('course category edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Category')}}" data-url="{{route('course-category.edit',[$category->id])}}"><i class="ti ti-pencil text-white"></i></a>
                                                    </div>
                                                @endpermission

                                                @permission('course category delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['course-category.destroy', $category->id] ]) !!}
                                                            <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
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
