@extends('layouts.main')
@section('page-title')
    {{__('Course')}}
@endsection
@section('title')
    {{__('Courses')}}
@endsection
@section('page-breadcrumb')
    {{__('Course')}}
@endsection
@section('page-action')
<div class="text-end align-items-end justify-content-end">
        @stack('addButtonHook')
        @permission('course create')
            <div class="btn btn-sm btn-primary btn-icon mx-1">
                <a href="{{route('course.create')}}" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Course')}}"><i class="ti ti-plus text-white"></i></a>
            </div>
        @endpermission
    </div>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <!-- Listing -->
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h5>{{__('All Courses')}}</h5>
            </div>
            <!-- Table -->
            <div class="col-lg-12 col-md-12">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Thumbnail')}}</th>
                                    <th scope="col">{{ __('Type')}}</th>
                                    <th scope="col">{{ __('Title')}}</th>
                                    <th scope="col">{{ __('Category')}}</th>
                                    <th scope="col">{{ __('Status')}}</th>
                                    <th scope="col">{{ __('Chapters')}}</th>
                                    <th scope="col">{{ __('Enrolled')}}</th>
                                    <th scope="col">{{ __('Price')}}</th>
                                    <th scope="col">{{ __('Created at')}}</th>
                                    @if(Laratrust::hasPermission('course edit') ||  Laratrust::hasPermission('course delete'))
                                        <th scope="col" class="text-right">{{ __('Action')}}</th>
                                    @endif
                                </tr>
                            </thead>
                            @if(!empty($courses) && count($courses) > 0)
                                <tbody class="list">
                                    @foreach($courses as $course)
                                        <tr>
                                            <td scope="row">
                                                <div class="media align-items-center">
                                                    <div>
                                                        @if(!empty($course->thumbnail))
                                                            <a href="{{get_file($course->thumbnail)}}" target="_blank">
                                                                <img alt="Image placeholder" src="{{get_file($course->thumbnail)}}" class="rounded" style="width: 70px; height:50px">
                                                            </a>
                                                        @else
                                                            <a href="{{asset('Modules/LMS/Resources/assets/image/default.jpg')}}" target="_blank">
                                                                <img alt="Image placeholder" src="{{asset('Modules/LMS/Resources/assets/image/default.jpg')}}" class="rounded" style="width: 80px; height:50px">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{$course->type}}</td>
                                            <td>{{$course->title}}</td>
                                            <td>{{!empty($course->category_name)?$course->category_name:'-'}}</td>
                                            <td>{{$course->status}}</td>
                                            <td>{{!empty($course->chapter_count)?$course->chapter_count->count():'0'}}</td>
                                            <td>{{!empty($course->student_count)?$course->student_count->count():'0'}}</td>
                                            <td>{{ ($course->is_free == 'on')? 'Free' : $course->price}}</td>
                                            <td>{{ company_date_formate( $course->created_at)}}</td>
                                            @if(Laratrust::hasPermission('course edit') ||  Laratrust::hasPermission('course delete'))
                                                <td class="text-right">
                                                    <!-- Actions -->
                                                    <div class="actions">
                                                        @permission('course edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{route('course.edit',\Illuminate\Support\Facades\Crypt::encrypt($course->id))}}" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                > <span class="text-white"> <i class="ti ti-pencil"></i></span></a>
                                                            </div>
                                                        @endpermission
                                                        @permission('course delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['course.destroy', $course->id]]) !!}
                                                                    <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                                        <i class="ti ti-trash text-white"></i>
                                                                    </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        @endpermission
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                <tbody>
                                    <tr>
                                        <td colspan="10">
                                            <div class="text-center">
                                                <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                                <h2>{{__('Opps')}}...</h2>
                                                <h6>{{__('No data Found')}}. </h6>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

