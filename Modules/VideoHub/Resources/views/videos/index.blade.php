@extends('layouts.main')
@section('page-title')
    {{ __('Manage Video Hub') }}
@endsection

@push('css')
    <style>
        .text-single-line {
            line-height: 1.2em;
            height: 2.2em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }
    </style>
@endpush
@section('page-breadcrumb')
    {{ __('Video Hub') }}
@endsection

@section('page-action')
    <div>
        <a href="{{ route('videos.list') }}" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip"title="{{ __('List View') }}">
            <i class="ti ti-list text-white"></i>
        </a>
        @permission('video create')
            <a class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Add Video') }}" data-ajax-popup="true"
                data-url="{{ route('videos.create') }}" data-size="md" data-title="{{ __('Add New Video') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="raw mt-3">
        <div class="col-sm-12">
            <div class=" multi-collapse mt-2" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['videos.index'], 'method' => 'GET', 'id' => 'module_form']) }}
                        <div class="row d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 mb-2 relatedsubfields d-none"
                                id="relatedsubfields"></div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 mr-2 getsubfields d-none"
                                id="getsubfields"></div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box">
                                    <label for="filter"><b>{{ __('Module') }}</b></label>
                                    <select class="form-control modules " name="filter" id="module" tabindex="-1"
                                        aria-hidden="true">
                                        <option value="">{{ __('Select Module') }}</option>
                                        @foreach ($modules as $module)
                                            @foreach ($active_modules as $active_module)
                                                @if (Module_Alias_Name($active_module) == $module)
                                                    @php
                                                        if ($module == 'Product Service') {
                                                            $module = 'Items';
                                                        } elseif ($module == 'Insurance Management') {
                                                            $module = 'Insurance';
                                                        } elseif ($module == 'Property Management') {
                                                            $module = 'Property Manage';
                                                        } elseif ($module == 'Rental Management') {
                                                            $module = 'Rental';
                                                        } elseif ($module == 'Business Process Mapping') {
                                                            $module = 'Business Mapping';
                                                        }
                                                    @endphp
                                                    <option value="{{ $module }}"
                                                        {{ isset(request()->filter) && request()->filter == $module ? 'selected' : '' }}>
                                                        {{ $module }}
                                                    </option>
                                                @endif
                                            @endforeach
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
                                <a href="{{ route('videos.index') }}" class="btn btn-sm btn-danger"
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
        <section class="section">
            <div class="row  d-flex grid">
                @isset($videos)
                    @if ($videos != null)
                        @foreach ($videos as $video)
                            <div class="col-md-6 col-xl-3 All">
                                <div class="card">
                                    <div class="card-header border-0 pb-0">
                                        <div class="card-header-left col-8 mb-2">
                                            <h5 class="mb-2 text-single-line"><a
                                                href="{{ route('videos.show', $video->id) }}">{{ $video->title }}</a>
                                            </h5>
                                        </div>
                                        <div class="col-6 text-end mb-1">
                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="feather icon-more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @permission('video edit')
                                                            <a class="dropdown-item" data-ajax-popup="true" data-size="md"
                                                                data-title="{{ __('Edit Video') }}"
                                                                data-url="{{ route('videos.edit', [$video->id]) }}">
                                                                <i class="ti ti-pencil"></i> <span>{{ __('Edit') }}</span>
                                                            </a>
                                                        @endpermission
                                                        @permission('video delete')
                                                            {{ Form::open(['route' => ['videos.destroy', $video->id], 'class' => 'm-0']) }}
                                                            @method('DELETE')
                                                            <a href="#!"
                                                                class="dropdown-item bs-pass-para show_confirm text-danger"
                                                                aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $video->id }}">
                                                                <i class="ti ti-trash"></i>
                                                                <span>{{ __('Delete') }}</span>
                                                            </a>
                                                            {{ Form::close() }}
                                                        @endpermission
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="align-items-center">
                                            <a href="{{ route('videos.show', [$video->id]) }}">
                                                <img src="{{ isset($video->thumbnail) && !empty($video->thumbnail) && check_file($video->thumbnail) ? get_file($video->thumbnail) : asset('Modules/VideoHub/Resources/assets/upload/thumbnail-not-found.png') }}"
                                                    alt="Thumbnail" id="thumbnail" class="card-img" style="height: 200px">
                                            </a>
                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <div class="card mb-0">
                                            <div class="card-body p-3">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">{{ $video->countAttachment() }}</h6>
                                                        <p class="text-muted text-sm mb-0">{{ __('Attachments') }}</p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <h6 class="mb-0">{{ $video->comments_count }}</h6>
                                                        <p class="text-muted text-sm mb-0">{{ __('Comments') }}</p>
                                                    </div>
                                                    {{-- <p class="card-text text single-line">{{ $video->description }}</p> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endisset
                @auth('web')
                    @permission('video create')
                        <div class="col-md-3 All Ongoing Finished OnHold">
                            <a href="#" class="btn-addnew-project " style="padding: 90px 10px;" data-ajax-popup="true"
                                data-size="md" data-title="{{ __('Add New Video') }}" data-url="{{ route('videos.create') }}">
                                <div class="bg-primary proj-add-icon">
                                    <i class="ti ti-plus"></i>
                                </div>
                                <h6 class="mt-4 mb-2">{{ __('Add Video') }}</h6>
                                <p class="text-muted text-center">{{ __('Click here to Add New Video') }}</p>
                            </a>
                        </div>
                    @endpermission
                @endauth

            </div>
        </section>
    </div>


@endsection


@push('scripts')
    <script>
        $(document).ready(function(){
            $(document).on("change", "#module", function() {
                if ($(this).val() == 'Items') {
                    var modules = 'Product Service';
                } else if ($(this).val() == 'Insurance') {
                    var modules = 'Insurance Management';
                } else if ($(this).val() == 'Property Manage') {
                    var modules = 'Property Management';
                } else if ($(this).val() == 'Rental') {
                    var modules = 'Rental Management';
                } else if ($(this).val() == 'Business Mapping') {
                    var modules = 'Business Process Mapping';
                } else {
                    var modules = $(this).val();
                }
                var urlSub = "{{ !empty($_GET['sub_module']) ? $_GET['sub_module'] : '' }}";
    
                $.ajax({
                    url: '{{ route('videos.modules') }}',
                    type: 'POST',
                    data: {
                        "module": modules,
                    },
                    success: function(data) {
                        $('#getsubfields').empty();
                        $('.sub_modules').empty();
                        $('#relatedsubfields').empty();
                        var emp_selct = `
                            <label class='form-label'><b>${(data.module == 'Product Service') ? 'Item Type' : 'Sub Module'}</b></label>
                            <select class="form-control sub_modules mt-1" name="sub_module" id="sub_module" placeholder="Select Sub Module"></select>
                        `;
                        $('#getsubfields').html(emp_selct);
                        if (data.module == 'Product Service') {
                            $('.sub_modules').append(
                                '<option value="0"> {{ __('Select Item Type') }} </option>');
                        } else {
                            $('.sub_modules').append(
                                '<option value="0"> {{ __('Select Sub Module') }} </option>');
                        }
                        $.each(data.video_modules, function(key, value) {
    
                            $('.sub_modules').append('<option value="' + key + '" ' + (key ==
                                    urlSub ? 'selected' : '') + '>' + value +
                                '</option>');
    
                            $('select[name="sub_module"]').trigger('change');
                            $('select[name="sub_module"]').change(function() {
                                var selectValue = $(this).val();
    
                                if (selectValue != '') {
                                    $('.relatedsubfields').removeClass('d-none');
                                } else {
                                    $('.relatedsubfields').addClass('d-none');
                                }
                            });
    
                            if (!value) {
                                $('#getsubfields').empty();
                                field(key, 'nonSubModule');
                            }
                        })
                    },
                });
            });
    
            $(document).on("change", ".sub_modules", function() {
                field($(this), 'subModule');
            });
    
            function field(sub_module, type) {
                if (type == "nonSubModule") {
                    $('.getsubfields').addClass('d-none');
                    $('.relatedsubfields').removeClass('d-none');
                }
                (type == 'subModule') ? sub_module = sub_module.val(): sub_module = sub_module;
                var itemId = "{{ !empty($_GET['item']) ? $_GET['item'] : '' }}";
    
                $.ajax({
                    url: '{{ route('videos.getfield') }}',
                    type: 'POST',
                    data: {
                        "module": sub_module,
                        "itemId": itemId,
                    },
                    success: function(data) {
                        $('#relatedsubfields').empty();
                        $('#relatedsubfields').append(data.html)
    
                    },
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('select[name="filter"][id="module"]').prop('selected', true);

            $('select[name="filter"]').trigger("change");
        });
        $('select[name="filter"]').change(function() {
            var selectValue = $('select[name="filter"]:selected').val();

            if (selectValue != '') {
                $('.getsubfields').removeClass('d-none');
                // $('.relatedsubfields').removeClass('d-none');
            } else {
                $('.getsubfields').addClass('d-none');
            }
        });
    </script>
@endpush
