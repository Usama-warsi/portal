@extends('layouts.main')
@section('page-title')
    {{__('Blog')}}
@endsection

@section('page-breadcrumb')
    {{__('Blog')}}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/LMS/Resources/assets/css/custom.css') }}">
@endpush
@push('scripts')
    <script>
        $(document).on('click', '#social_button', function () {
            if ($('.data-check')) {
                $('.social-btn').addClass('d-block');
                $('.social-btn').removeClass('d-none');

            } else {
                $('.social-btn').addClass('d-none');
                $('.social-btn').removeClass('d-block');
            }
        });
    </script>
    <script>
        $(document).on('change', 'body #blog_social_form #enable_social_button', function (e) {
            $('body #blog_social_form #enable_social_button').toggleClass('data-check');
            if ($('body #blog_social_form #enable_social_button').hasClass('data-check')) {
                $('body #blog_social_form .social-btn').hide();
            } else {
                $('body #blog_social_form .social-btn').show();
            }
        });
        $(document).on('change', 'body #store_blog_from #enable_social_button', function (e) {
            if ($('body #store_blog_from #enable_social_button').is(':checked')) {
                $('body #store_blog_from .sub_social_button').show();
            } else {
                $('body #store_blog_from .sub_social_button').hide();
            }
        });
    </script>
      <script>
        $(document).ready(function () {
            $(document).on('keyup', '.search-user', function () {
                var value = $(this).val();
                $('.employee_tableese tbody>tr').each(function (index) {
                    var name = $(this).attr('data-name');
                    if (name.includes(value)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endpush
@section('page-action')
<div>
    {{-- @permission('social media blog') --}}
        <div class="btn btn-sm btn-primary btn-icon ms-1">
            <a href="#" class="social_button" id="social_button" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Social Media')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Manage Social Blog Button')}}" data-url="{{route('blog.social')}}"><i class="ti ti-social text-white"></i></a>
        </div>
    {{-- @endpermission --}}
    @permission('blog create')
        <div class="btn btn-sm btn-primary btn-icon ms-1">
            <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Blog')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create New Blog')}}" data-url="{{route('blog.create')}}"><i class="ti ti-plus text-white"></i></a>
        </div>
    @endpermission
</div>
@endsection
@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <!-- Table -->
            <div class="card-header">
                <h5>{{__('All Blogs')}}</h5>
            </div>
            <!-- Table -->
            <div class="card-body table-border-style">
                <div class="table-responsive overflow_hidden">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">{{__('Blog Cover Image')}}</th>
                                <th scope="col" class="sort" data-sort="name">{{__('Title')}}</th>
                                <th scope="col" class="sort" data-sort="name">{{__('Created At')}}</th>
                                @if(Laratrust::hasPermission('blog edit') ||  Laratrust::hasPermission('blog delete'))
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        @if(!empty($blogs) && count($blogs) >0)
                            <tbody>
                                @foreach($blogs as $blog)
                                    <tr data-name="{{$blog->title}}">
                                        <td scope="row">
                                            <div class="media align-items-center">
                                                <div>
                                                    @if(!empty($blog->blog_cover_image))
                                                        <a href="{{get_file($blog->blog_cover_image)}}" target="_blank">
                                                            <img alt="Image placeholder" src="{{get_file($blog->blog_cover_image)}}" class="rounded-circle" style="width: 70px; height: 50px;">
                                                        </a>
                                                    @else
                                                        <a href="{{asset('Modules/LMS/Resources/assets/image/default.jpg')}}" target="_blank">
                                                            <img alt="Image placeholder" src="{{asset('Modules/LMS/Resources/assets/image/default.jpg')}}" class="rounded-circle" style="width: 70px; height: 50px;">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        </td>
                                        <td class="sorting_1">{{$blog->title}}</td>
                                        <td class="sorting_1">{{ company_date_formate($blog->created_at)}}</td>
                                        @if(Laratrust::hasPermission('blog edit') ||  Laratrust::hasPermission('blog delete'))
                                            <td class="action text-right">
                                                @permission('blog edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Blog')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Blog')}}" data-url="{{route('blog.edit',[$blog->id])}}"><i class="ti ti-pencil text-white"></i></a>
                                                    </div>
                                                @endpermission

                                                @permission('blog delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['blog.destroy', $blog->id] ]) !!}
                                                            <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endpermission
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <tbody>
                                <tr>
                                    <td colspan="7">
                                        <div class="text-center">
                                            <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                            <h2>{{__('Opps')}}...</h2>
                                            <h6>{{__('No data Found')}}. </h6>
                                            <h6>{{__('Please Upload Practices Files')}}. </h6>
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
@endsection
