@extends('layouts.main')
@section('page-title')
    {{ __('Manage Video Hub') }}
@endsection

@push('css')
    {{-- <style>
        .description {
            display: block;
            width: 500px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style> --}}
@endpush

@section('page-breadcrumb')
    {{ __('Video Hub') }}
@endsection

@section('page-action')
    <div>
        <a href="{{ route('videos.index') }}" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
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
<div class="raw">
    <div class="col-sm-12">
        <div class=" multi-collapse mt-2" id="multiCollapseExample1">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['videos.list'], 'method' => 'GET', 'id' => 'module_form']) }}
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
                            <a href="{{ route('videos.list') }}" class="btn btn-sm btn-danger"
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
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="video">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Module') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($videos as $video)
                                    <tr>
                                        <td>
                                            <h5 class="mb-2" style="white-space: nowrap;
                                            width: 250px;
                                            overflow: hidden;
                                            text-overflow: ellipsis;">
                                            <a href="{{ route('videos.show', $video->id) }}">{{ $video->title }}</a>
                                            </h5>
                                        </td>
                                        <td>{{ $video->module_name }}</td>
                                        <td>
                                            <p style="white-space: nowrap;
                                                width: 700px;
                                                overflow: hidden;
                                                text-overflow: ellipsis;" class="mt-3">{{ !empty($video->description) ? $video->description : '' }}
                                            </p>
                                        </td>
                                        <td class="Action text-end">
                                            @if (!empty($video->thumbnail))
                                                <div class="action-btn bg-secondary ms-2">
                                                    <a href="{{ isset($video->thumbnail) && !empty($video->thumbnail) ? get_file($video->thumbnail) : asset('Modules/VideoHub/Resources/assets/upload/thumbnail-not-found.png') }}"
                                                        target=_blank
                                                        class="btn btn-sm d-inline-flex align-items-center text-white "
                                                        data-title="{{ __('View Thumbnail Image') }}"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ __('View Thumbnail Image') }}"><i
                                                            class="ti ti-crosshair"></i></a>
                                                </div>
                                            @endif
                                            @permission('video view')
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{ route('videos.show', [$video->id]) }}"
                                                        class="btn btn-sm d-inline-flex align-items-center text-white "
                                                        data-title="{{ __('View') }}" data-bs-toggle="tooltip"
                                                        title="{{ __('View') }}"><i class="ti ti-eye"></i></a>
                                                </div>
                                            @endpermission
                                            @permission('video edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a data-size="md" data-url="{{ route('videos.edit', $video->id) }}"
                                                        class="btn btn-sm d-inline-flex align-items-center text-white "
                                                        data-ajax-popup="true" data-bs-toggle="tooltip"
                                                        data-title="{{ __('Video Edit') }}" title="{{ __('Edit') }}"><i
                                                            class="ti ti-pencil"></i></a>
                                                </div>
                                            @endpermission
                                            @permission('video delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['videos.destroy', $video->id]]) !!}
                                                    <a href="#!"
                                                        class="btn btn-sm   align-items-center text-white show_confirm"
                                                        data-bs-toggle="tooltip" title='Delete'>
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endpermission
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
                                console.log(selectValue);
    
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
