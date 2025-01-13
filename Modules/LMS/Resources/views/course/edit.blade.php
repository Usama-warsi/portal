@extends('layouts.main')
@section('page-title')
    {{ __('Course') }}
@endsection
@section('page-breadcrumb')
    {{__('Course')}},
    {{__('Edit')}}
@endsection

@section('page-action')
    <div class="d-flex justify-content-end ">
        <ul class="nav nav-pills cust-nav rounded mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab"
                    aria-controls="pills-home" aria-selected="true">{{ __('Create Header & Edit Course') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile" role="tab"
                    aria-controls="pills-profile" aria-selected="false">{{ __('Practice & FAQs') }}</a>
            </li>
        </ul>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/LMS/Resources/assets/css/custom.css') }}">
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
    {{-- Switch --}}
    <script>
        $(document).ready(function() {
            type();
            $(document).on('click', '#customSwitches', function() {
                if ($(this).is(":checked")) {
                    $('#price').addClass('d-none');
                    $('#price').removeClass('d-block');
                } else {
                    $('#price').addClass('d-block');
                    $('#price').removeClass('d-none');
                }
            });
            $(document).on('click', '#customSwitches2', function() {
                if ($(this).is(":checked")) {
                    $('#discount').addClass('d-block');
                    $('#discount').removeClass('d-none');
                } else {
                    $('#discount').addClass('d-none');
                    $('#discount').removeClass('d-block');
                }
            });

            function type() {
                if ($('#customSwitches3').is(":checked")) {
                    $('#preview_type').addClass('d-block');
                    $('#preview_type').removeClass('d-none');

                    preview();
                } else {
                    $('#preview_type').addClass('d-none');
                    $('#preview_type').removeClass('d-block');

                    $('#preview-iframe-div').addClass('d-none');
                    $('#preview-iframe-div').removeClass('d-block');

                    $('#preview-video-div').addClass('d-none');
                    $('#preview-video-div').removeClass('d-block');

                    $('#preview-image-div').addClass('d-none');
                    $('#preview-image-div').removeClass('d-block');

                }
            }

            $(document).on('click', '#customSwitches3', function() {
                if ($('#customSwitches3').is(":checked")) {
                    $('#preview_type').addClass('d-block');
                    $('#preview_type').removeClass('d-none');

                    preview();
                } else {
                    $('#preview_type').addClass('d-none');
                    $('#preview_type').removeClass('d-block');

                    $('#preview-iframe-div').addClass('d-none');
                    $('#preview-iframe-div').removeClass('d-block');

                    $('#preview-video-div').addClass('d-none');
                    $('#preview-video-div').removeClass('d-block');

                    $('#preview-image-div').addClass('d-none');
                    $('#preview-image-div').removeClass('d-block');

                }
            });

            function preview() {
                $("#preview_type").change(function() {
                    $(this).find("option:selected").each(function() {
                        var optionValue = $(this).attr("value");
                        if (optionValue == 'Image') {

                            $('#preview-image-div').removeClass('d-none');
                            $('#preview-image-div').addClass('d-block');

                            $('#preview-iframe-div').addClass('d-none');
                            $('#preview-iframe-div').removeClass('d-block');

                            $('#preview-video-div').addClass('d-none');
                            $('#preview-video-div').removeClass('d-block');

                        } else if (optionValue == 'iFrame') {

                            $('#preview-image-div').addClass('d-none');
                            $('#preview-image-div').removeClass('d-block');

                            $('#preview-iframe-div').removeClass('d-none');
                            $('#preview-iframe-div').addClass('d-block');

                            $('#preview-video-div').addClass('d-none');
                            $('#preview-video-div').removeClass('d-block');

                        } else if (optionValue == 'Video File') {

                            $('#preview-image-div').addClass('d-none');
                            $('#preview-image-div').removeClass('d-block');

                            $('#preview-iframe-div').addClass('d-none');
                            $('#preview-iframe-div').removeClass('d-block');


                            $('#preview-video-div').removeClass('d-none');
                            $('#preview-video-div').addClass('d-block');
                        }
                    });
                }).change();
            }
        });
    </script>
    {{-- Subcategory --}}
    <script>
        $(document).on('change', '#category_id', function() {
            var category_id = $(this).val();
            $.ajax({
                url: '{{ route('course.getsubcategory') }}',
                type: 'POST',
                data: {
                    "category_id": category_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#subcategory-div').empty();

                    var subcategory_option = '<select class="form-control" name="subcategory[]" id="subcategory" placeholder="{{__('Select Subcategory')}}"  multiple>';
                    subcategory_option += '<option value="" disabled>{{__('Select Subcategory')}}</option>';
                    $.each(data, function (key, value) {
                        subcategory_option += '<option value="' + key + '">' + value + '</option>';
                    });
                    subcategory_option += '</select>';

                    $("#subcategory-div").append(subcategory_option);
                    var multipleCancelButton = new Choices('#subcategory', {
                        removeItemButton: true,
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            var multipleCancelButton = new Choices(
               '#subcategory', {
                   removeItemButton: true,
               }
           );
        });
    </script>
    {{-- Dropzone --}}


    <script>
        var Dropzones = function() {
            var e = $('[data-toggle="dropzone1"]'),
                t = $(".dz-preview");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            e.length && (Dropzone.autoDiscover = !1, e.each(function() {
                var e, a, n, o, i;
                e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                    url: "{{ route('course.practicesfiles', [$course_id]) }}",
                    headers: {
                        'x-csrf-token': CSRF_TOKEN,
                    },
                    thumbnailWidth: 100,
                    thumbnailHeight: 100,
                    previewsContainer: n.get(0),
                    previewTemplate: n.html(),
                    maxFiles: 10,
                    parallelUploads: 10,
                    autoProcessQueue: true,
                    uploadMultiple: true,
                    acceptedFiles: a ? null : "image/*",
                    success: function(file, response) {


                        if (response.status == "success") {
                            toastrs('success', response.success, 'success');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            toastrs('Error', response.error, 'error');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function(file, response) {
                        if (response.error) {
                            toastrs('Error', response.error, 'error');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            toastrs('Error', response.error, 'error');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    },
                    init: function() {
                        var myDropzone = this;
                    }

                }, n.html(""), e.dropzone(i)
            }))
        }();

        /*FILE DELETE*/
        $(".deleteRecord").click(function() {
            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                url: '{{ route('practices.file.delete', '_id') }}'.replace('_id', id),
                type: 'DELETE',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function(response) {
                    toastrs('Success', response.success, 'success');
                    $('.product_Image[data-id="' + response.id + '"]').remove();
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                },
                error: function(response) {
                    toastrs('Error', response.error, 'error');
                }

            });
        });
    </script>

    <script>

        $(".list-group-item").click(function() {
            $('.list-group-item').filter(function() {
                return this.href == id;
            }).parent().removeClass('text-primary');
        });
    </script>
@endpush
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab">
                        <div class="row">
                            {{-- Content --}}
                            <div class="col-lg-6">
                                <div class="mb-3  d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">{{ __('Create Header') }}</h5>
                                    @permission('header create')
                                    <a href="#" data-url="{{ route('headers.create', $course_id) }}"
                                        class="me-2 btn btn-sm align-items-center bg-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ __('Create Header') }}"
                                        data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Header') }}"><i
                                            class="ti ti-plus text-white"></i>
                                    </a>
                                    @endpermission
                                </div>
                                <div class="card shadow-none border border-primary">
                                    <div class="card-body">
                                        <div class="border">
                                                @foreach ($headers as $header)
                                                    <div
                                                        class="p-3 border-bottom mb-2 d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0 d-flex align-items-center">
                                                            <b>{{ $header->title }}</b>
                                                        </h6>
                                                        <div class="d-flex actions">
                                                            @permission('chapter create')
                                                            <div class="action-btn bg-primary ms-2">
                                                                <a href="{{ route('chapters.create', [$course_id, $header->id]) }}"
                                                                    class="btn btn-sm d-inline-flex align-items-center"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Create Chapter') }}"
                                                                    ><i class="ti ti-plus text-white"></i>
                                                                </a>
                                                            </div>
                                                            @endpermission

                                                            @permission('header edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="btn btn-sm d-inline-flex align-items-center"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit Header') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Header') }}"
                                                                    data-url="{{ route('headers.edit', [$header->id, $course_id]) }}"><i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                            @endpermission

                                                            @permission('header delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['headers.destroy', [$header->id, $course_id]]]) !!}
                                                                <a href="#!"
                                                                    class="btn btn-sm align-items-center show_confirm"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Delete') }}">
                                                                    <i class="ti ti-trash text-white"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                            @endpermission
                                                        </div>
                                                    </div>
                                                    @if (!empty($header->chapters_data))
                                                        <div class="p-3">
                                                            @foreach ($header->chapters_data as $chapters)
                                                                <div
                                                                    class="border border-primary mb-2 rounded p-2 d-flex align-items-center    justify-content-between ">
                                                                    <p class="mb-0 d-flex align-items-center">
                                                                        <i data-feather="play-circle"
                                                                            class="me-2"></i>
                                                                        <span class="ml-3">{{ $chapters->name }}</span>
                                                                    </p>
                                                                    <div class="d-flex gap-2">
                                                                        @permission('chapter edit')
                                                                        <div class="action-btn bg-info">
                                                                            <a href="{{ route('chapters.edit', [$course_id, $chapters->id, $header->id]) }}" class="btn btn-sm d-inline-flex align-items-center" data-bs-placement="top" title="{{ __('Edit Chapter') }}" data-title="{{ __('Edit Chapter') }}"><i class="ti ti-pencil text-white"></i>
                                                                            </a>
                                                                        </div>
                                                                        @endpermission

                                                                        @permission('chapter delete')
                                                                        {!! Form::open([ 'method' => 'DELETE', 'route' => ['chapters.destroy', [$chapters->id, $course_id, $header->id]],]) !!}
                                                                        <div class="action-btn bg-danger">
                                                                            <a href="#!" class="btn btn-sm align-items-center show_confirm"  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Delete') }}">
                                                                                <i class="ti ti-trash text-white"></i>
                                                                            </a>
                                                                        </div>
                                                                        {!! Form::close() !!}
                                                                        @endpermission
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    @endif
                                                @endforeach
                                                <div class="border border-dashed border-secondary m-3 rounded p-3">
                                                    @permission('header create')
                                                        <a href="#" data-url="{{ route('headers.create', $course_id) }}"
                                                        class="text-muted text-center d-flex align-items-center justify-content-center" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="{{ __('Create Header') }}"
                                                        data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Header') }}"><i data-feather="plus-circle" class="me-2"></i>{{__('Create New Header')}}
                                                        </a>
                                                    @endpermission
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- EDIT COURSE --}}
                            <div class="col-lg-6">
                                <div class="mb-3 d-flex align-item-center justify-content-between">
                                    <h5 class="mt-1 mb-2">{{ __('Edit Course') }}</h5>
                                </div>
                                <div class="card shadow-none border border-primary">
                                    <div class="card-body">
                                        {{ Form::model($course, ['route' => ['course.update', $course->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                                        @csrf
                                        <div class="form-group col-md-12">
                                            {{ Form::label('title', __('Topic Title'), ['class' => 'form-label']) }}
                                            {{ Form::text('title', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
                                        </div>
                                        <div class="form-group col-md-12 col-lg-12">
                                            {{ Form::label('course_requirements', __('Course Requirements'), ['class' => 'form-label']) }}
                                            {!! Form::textarea('course_requirements', null, ['class' => 'form-control summernote ', 'rows' => 3, 'required' => 'required']) !!}
                                        </div>
                                        <div class="form-group col-md-12 col-lg-12">
                                            {{ Form::label('course_description', __('Course Description'), ['class' => 'form-label']) }}
                                            {!! Form::textarea('course_description', null, ['class' => 'form-control summernote', 'rows' => 3, 'required' => 'required']) !!}
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group {{ $course->type == 'Course' ? '' : 'd-none' }}"
                                                    id="course-content-2">
                                                    {{ Form::label('category', __('Select Category'), ['class' => 'form-label']) }}
                                                    {!! Form::select('category', $category, null, ['class' => 'form-control', 'id' => 'category_id','placeholder'=>'Select Category']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group {{ $course->type == 'Course' ? '' : 'd-none' }}"
                                                    id="course-content-3">
                                                    {{ Form::label('subcategory', __('Select Subcategory'), ['class' => 'form-label']) }}
                                                    <div id="subcategory-div">
                                                        {!! Form::select('subcategory[]', $sub_category, explode(',', $course->sub_category), [
                                                            'class' => 'form-control choices',
                                                            'multiple',
                                                            'id' => 'subcategory',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label('level', __('Select Level'), ['class' => 'form-label']) }}
                                                    {!! Form::select('level', $level, null, ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label('lang', __('Language'), ['class' => 'form-label']) }}
                                                    {{ Form::text('lang', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group {{ $course->type == 'Quiz' ? 'd-none' : '' }}"
                                                    id="duration">
                                                    {{ Form::label('duration', __('Duration'), ['class' => 'form-label']) }}
                                                    {{ Form::text('duration', null, ['class' => 'form-control font-style']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                                                    {!! Form::select('status', $status, null, ['class' => 'form-control ']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="custom-control form-group ml-3 custom-switch">
                                                            <div
                                                                class="form-check form-check form-switch custom-control-inline">
                                                                <input type="checkbox" class="form-check-input"
                                                                    role="switch" id="customSwitches" name="is_free"
                                                                    {{ $course->is_free == 'on' ? 'checked' : '' }}>
                                                                {{ Form::label('customSwitches', __('This is free'), ['class' => 'form-check-label']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{ $course->is_free == 'on' ? 'd-none' : '' }}"
                                                        id="discount-div">
                                                        <div class="row">
                                                            <div
                                                                class="custom-control form-group col-md-6 ml-3 custom-switch">
                                                                <div
                                                                    class="form-check form-check form-switch custom-control-inline">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        role="switch" id="customSwitches2"
                                                                        name="has_discount"
                                                                        {{ $course->has_discount == 'on' ? 'checked' : '' }}>
                                                                    {{ Form::label('customSwitches2', __('Discount'), ['class' => 'form-check-label']) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div
                                                            class="custom-control form-group col-md-12 ml-3 custom-switch">
                                                            <div
                                                                class="form-check form-check form-switch custom-control-inline">
                                                                <input type="checkbox" class="form-check-input"
                                                                    role="switch" id="customSwitches4"
                                                                    name="featured_course"
                                                                    {{ $course->featured_course == 'on' ? 'checked' : '' }}>
                                                                {{ Form::label('customSwitches4', __('Featured Course'), ['class' => 'form-check-label']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div
                                                                class="custom-control form-group col-md-6 ml-3 custom-switch">
                                                                <div
                                                                    class="form-check form-check form-switch custom-control-inline">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        role="switch" id="customSwitches3"
                                                                        name="is_preview"
                                                                        {{ $course->is_preview == 'on' ? 'checked' : '' }}>
                                                                    {{ Form::label('customSwitches3', __('Preview'), ['class' => 'form-check-label']) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 ml-auto {{ $course->is_free == 'on' ? 'd-none' : '' }}"
                                                id="price">
                                                {{ Form::label('price', __('Price'), ['class' => 'form-label']) }}
                                                {{ Form::text('price', null, ['class' => 'form-control font-style']) }}
                                            </div>
                                            <div class="form-group col-md-6 ml-auto {{ $course->has_discount == 'on' ? '' : 'd-none' }}"
                                                id="discount">
                                                {{ Form::label('discount', __('Discount'), ['class' => 'form-label']) }}
                                                {{ Form::text('discount', null, ['class' => 'form-control font-style']) }}
                                            </div>
                                            <div class="form-group col-md-6 ml-auto {{ $course->is_preview == 'on' ? '' : 'd-none' }}"
                                                id="preview_type">
                                                {{ Form::label('preview_type', __('Preview Type'), ['class' => 'form-label']) }}
                                                {{ Form::select('preview_type', $preview_type, null, ['class' => 'form-control font-style', 'id' => 'preview_type']) }}
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group d-none" id="preview-video-div">
                                                    <div class="col-12">
                                                        <div class="form-file">
                                                            <label for="preview_video"
                                                                class="form-label">{{ __('Preview Video') }}</label>
                                                            <input type="file" class="form-control"
                                                                name="preview_video" id="preview_video"
                                                                aria-label="file example">
                                                                <a href="{{ get_file($course->preview_content) }}" target="_blank">
                                                                    <video height="100px" controls="" class="mt-2">
                                                                        <source id="preview_video" src="{{ get_file($course->preview_content) }}" type="video/mp4">
                                                                    </video>
                                                                </a>
                                                            <div class="invalid-feedback">
                                                                {{ __('invalid form file') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group ml-auto d-none" id="preview-iframe-div">
                                                    {{ Form::label('preview_iframe', __('Preview iFrame'), ['class' => 'form-label']) }}
                                                    <input class="form-control font-style" name="preview_iframe"
                                                        type="text" id="preview_iframe"
                                                        value="{{ $course->preview_type == 'iFrame' ? $course->preview_content : '' }}">
                                                </div>
                                                <div class="form-group d-none" id="preview-image-div">
                                                    <div class="col-12">
                                                        <div class="form-file">
                                                            <label for="preview_image"
                                                                class="form-label">{{ __('Preview Image') }}</label>
                                                            <input type="file" class="form-control"
                                                                name="preview_image" id="preview_image"
                                                                aria-label="file example">
                                                                @if(!empty($course->thumbnail))
                                                                    <a href="{{ get_file($course->preview_content) }}"
                                                                        target="_blank">
                                                                        <img src="{{ get_file($course->preview_content) }}"
                                                                            name="preview_image" id="preview_image"
                                                                            alt="user-image" class="avatar avatar-lg mt-3">
                                                                    </a>
                                                                @endif
                                                            <div class="invalid-feedback">
                                                                {{ __('invalid form file') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="form-file">
                                                        <label for="thumbnail"
                                                            class="form-label">{{ __('Upload Thumbnail') }}</label>
                                                        <input type="file" class="form-control" name="thumbnail"
                                                            id="thumbnail" aria-label="file example">
                                                        <a href="{{get_file($course->thumbnail) }}"
                                                            target="_blank">
                                                            <img @if(!empty($course->thumbnail))src="{{get_file($course->thumbnail) }}" @endif
                                                                name="thumbnail" id="thumbnail"
                                                                class="avatar avatar-lg mt-2">
                                                        </a>
                                                        <div class="invalid-feedback">{{ __('invalid form file') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <input type="submit" value="{{ __('Update') }}"
                                                    class="btn btn-primary w-100 btn-submit" id="submit-all">
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- PRACTICES & FAQS --}}
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                        aria-labelledby="pills-profile-tab">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3  d-flex align-items-center justify-content-between">
                                    <h4>{{ __('Practice') }}</h4>
                                </div>
                                <div class="card shadow-none  border border-primary ">
                                    <div class="card-body">
                                        {{ Form::open(['method' => 'post', 'id' => 'frmTarget', 'enctype' => 'multipart/form-data']) }}
                                            <div class="dropzone border cust-drop-box border-secondary border-dashed dropzone-multiple" data-toggle="dropzone1"
                                                data-dropzone-url="http://" data-dropzone-multiple>
                                                <div class="fallback">
                                                    <div class="custom-file">
                                                        <input type="file"
                                                            class="custom-file-input btn btn-primary" id="dropzone-1"
                                                            name="file" multiple>
                                                        <label class="custom-file-label "
                                                            for="customFileUpload">{{ __('Choose file') }}</label>
                                                    </div>
                                                </div>
                                                <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                                    <li class="list-group-item px-0">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto">
                                                                <div class="avatar">
                                                                    <img class="rounded" src=""
                                                                        alt="Image placeholder" data-dz-thumbnail>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h6 class="text-sm mb-1" data-dz-name>...</h6>
                                                                <p class="small text-muted mb-0" data-dz-size></p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="action-btn bg-danger btn-sm ms-2">
                                                                    <a href="#"
                                                                        class="mx-3 btn btn-sm  align-items-center"
                                                                        data-dz-remove>
                                                                        <i class="ti ti-trash text-white"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            @foreach ($practices_files as $practices_file)
                                                <div class="mt-4 border border-secondary border-dashed rounded p-2 d-flex justify-content-between align-items-center flex-sm-row flex-column gap-2 ">
                                                    <span>
                                                        {{ $practices_file->file_name }}
                                                    </span>
                                                    <div class="d-flex">
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a href="{{ get_file($practices_file->files) }}"
                                                                download=""
                                                                class="btn btn-sm align-items-center"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Download') }}"><i
                                                                    class="ti ti-download text-white"></i>
                                                            </a>
                                                        </div>
                                                        @permission('practice file edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class=" btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Edit File Name') }}" data-ajax-popup="true"
                                                                    data-size="md" data-title="{{ __('Edit File Name') }}"
                                                                    data-url="{{ route('practices.filename.edit', [$practices_file]) }}"><i
                                                                        class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission

                                                        @permission('practice file delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            <a class="deleteRecord btn btn-sm align-items-center"
                                                                name="deleteRecord" data-id="{{ $practices_file->id }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Delete') }}"><i
                                                                    class="ti ti-trash text-white"></i>
                                                            </a>
                                                        </div>
                                                        @endpermission
                                                    </div>
                                                </div>
                                            @endforeach
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <h4>{{ __('FAQs') }}</h4>
                                    @permission('course faq create')
                                        <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create FAQs') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create FAQs') }}" data-url="{{ route('course-faqs.create', $course_id) }}"> <i class="ti ti-plus text-white"></i>
                                        </a>
                                    @endpermission
                                </div>
                                <div class="card shadow-none  border border-primary">
                                    <div class="card-body">
                                        <div id="faq-accordion" class="accordion">
                                            @if (count($faqs) > 0 && !empty($faqs))
                                                @foreach ($faqs as $k_f => $faq)
                                                    <div class="accordion-item mb-3 border rounded">
                                                        <h2 class="accordion-header"
                                                            id="heading-{{ $k_f }}">
                                                            <div class="accordion-button bg-light d-flex gap-2 justify-content-between flex-column flex-sm-row"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#collapse-{{ $k_f }}"
                                                                aria-expanded="{{ $k_f == 0 ? 'true' : 'false' }}"
                                                                aria-controls="collapse-{{ $k_f }}">
                                                                <span class="d-flex align-items-center">
                                                                    <svg class="me-3 d-inline-block"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="14" height="15"
                                                                        viewBox="0 0 14 15" fill="none">
                                                                        <path opacity="0.4"
                                                                            d="M6.89874 11.3004C9.12126 11.3004 10.923 9.49864 10.923 7.27612C10.923 5.0536 9.12126 3.25189 6.89874 3.25189C4.67622 3.25189 2.87451 5.0536 2.87451 7.27612C2.87451 9.49864 4.67622 11.3004 6.89874 11.3004Z"
                                                                            fill="#25314C" />
                                                                        <path
                                                                            d="M6.89865 8.71332C7.69241 8.71332 8.33588 8.06985 8.33588 7.27609C8.33588 6.48233 7.69241 5.83887 6.89865 5.83887C6.10489 5.83887 5.46143 6.48233 5.46143 7.27609C5.46143 8.06985 6.10489 8.71332 6.89865 8.71332Z"
                                                                            fill="#25314C" />
                                                                        <path
                                                                            d="M12.5038 7.27614C12.5038 7.51184 12.3084 7.70731 12.0727 7.70731H10.8999C10.9171 7.56358 10.9229 7.41986 10.9229 7.27614C10.9229 7.13242 10.9171 6.98869 10.8999 6.84497H12.0727C12.3084 6.84497 12.5038 7.04043 12.5038 7.27614Z"
                                                                            fill="#25314C" />
                                                                        <path
                                                                            d="M2.8744 7.27614C2.8744 7.41986 2.88014 7.56358 2.89739 7.70731H1.72462C1.48892 7.70731 1.29346 7.51184 1.29346 7.27614C1.29346 7.04043 1.48892 6.84497 1.72462 6.84497H2.89739C2.88014 6.98869 2.8744 7.13242 2.8744 7.27614Z"
                                                                            fill="#25314C" />
                                                                        <path
                                                                            d="M7.32986 2.10213V3.27493C7.18614 3.25768 7.04242 3.25191 6.8987 3.25191C6.75497 3.25191 6.61125 3.25768 6.46753 3.27493V2.10213C6.46753 1.86642 6.66299 1.67096 6.8987 1.67096C7.1344 1.67096 7.32986 1.86642 7.32986 2.10213Z"
                                                                            fill="#25314C" />
                                                                        <path
                                                                            d="M7.32986 11.2773V12.4501C7.32986 12.6858 7.1344 12.8813 6.8987 12.8813C6.66299 12.8813 6.46753 12.6858 6.46753 12.4501V11.2773C6.61125 11.2946 6.75497 11.3004 6.8987 11.3004C7.04242 11.3004 7.18614 11.2946 7.32986 11.2773Z"
                                                                            fill="#25314C" />
                                                                    </svg>
                                                                    {{ $faq->question }}
                                                                </span>
                                                                <div class="flex-grow-1 d-flex justify-content-end">
                                                                    @permission('course faq edit')
                                                                    <div class="action-btn bg-info ms-2">
                                                                        <a href="#"
                                                                            class="btn btn-sm d-inline-flex align-items-center"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"
                                                                            title="{{ __('Edit FAQs') }}"
                                                                            data-ajax-popup="true" data-size="lg"
                                                                            data-title="{{ __('Edit FAQs') }}"
                                                                            data-url="{{ route('course-faqs.edit', [$faq->id, $course_id]) }}"><i
                                                                                class="ti ti-pencil text-white"></i>
                                                                        </a>
                                                                    </div>
                                                                    @endpermission

                                                                    @permission('course faq delete')
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['course-faqs.destroy', [$faq->id, $course_id]]]) !!}
                                                                    <div class="action-btn bg-danger ms-2">
                                                                        <a href="#!"
                                                                            class="btn btn-sm  align-items-center show_confirm"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"
                                                                            title="{{ __('Delete') }}">
                                                                            <i class="ti ti-trash text-white"></i>
                                                                        </a>
                                                                    </div>
                                                                    {!! Form::close() !!}
                                                                    @endpermission
                                                                </div>
                                                            </div>
                                                        </h2>
                                                        <div id="collapse-{{ $k_f }}"
                                                            class="accordion-collapse collapse @if ($k_f == 0) show @endif"
                                                            aria-labelledby="heading-{{ $k_f }}"
                                                            data-bs-parent="#faq-accordion">
                                                            <div class="accordion-body">
                                                                {{ $faq->answer }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <tbody>
                                                    <tr>
                                                        <td colspan="7">
                                                            <div class="text-center">
                                                                <i class="fas fa-folder-open text-primary"
                                                                    style="font-size: 48px;"></i>
                                                                <h2>{{ __('Opps') }}...</h2>
                                                                <h6>{{ __('No data Found') }}. </h6>
                                                                <h6>{{ __('Please Create New FAQs') }}. </h6>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
