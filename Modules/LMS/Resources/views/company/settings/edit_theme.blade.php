@extends('layouts.main')

@section('page-title')
    {{ __('Store Theme Settings') }}
@endsection
@section('page-breadcrumb')
    {{ __('Settings') }},
    {{ __('Store Theme Settings') }}
@endsection

@section('page-action')
    <div>
        <a href="{{ route('setting.storeanalytic') }}" class="btn btn-sm btn-info py-2" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Store Analytic ') }}" >
            <i class="ti ti-brand-google-analytics"></i>
        </a>
        <a class="btn btn-sm btn-primary py-2" data-ajax-popup="true" data-size="md"
            data-title="{{ __('Qr code') }}" data-url="{{ route('qrcode') }}" data-toggle="tooltip"
            title="{{ __('Qr code') }}">
            <i class="ti ti-qrcode"></i>
        </a>

        <a href="{{ route('store.slug',$store->slug) }}" class="btn btn-sm btn-warning py-2" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Privew') }}" target="_blank">
            <i class="ti ti-eye"></i>
        </a>
    </div>
@endsection

<link rel="stylesheet" href="{{ asset('Modules/LMS/Resources/assets/css/custom.css') }}">

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-lg-3 col-12">
                    @include('lms::company.settings.setup')
                </div>
                @if (Auth::user()->type != 'super admin')
                    <div class="col-xl-9 col-12">
                        <div class="col-lg-12 col-sm-12 col-md-12">
                            <div class="row">


                                    {{ Form::open(['route' => ['store.storeeditproducts', [$store->slug, $theme]], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                    @foreach ($getStoreThemeSetting as $json_key => $section)
                                        @php
                                            $id = '';
                                            if ($section['section_name'] == 'Banner') {
                                                $id = 'Header_Setting';
                                            }
                                            if ($section['section_name'] == 'Home-Featured Course') {
                                                $id = 'Features_Setting';
                                            }
                                            if ($section['section_name'] == 'Home-Categories') {
                                                $id = 'Categories';
                                            }
                                            if ($section['section_name'] == 'Home-On Sale') {
                                                $id = 'Sale_Setting';
                                            }
                                            if ($section['section_name'] == 'Home-Email-subscriber') {
                                                $id = 'Email_Subscriber_Setting';
                                            }
                                            if ($section['section_name'] == 'Home-Footer') {
                                                $id = 'Footer_1';
                                            }
                                            if ($section['section_name'] == 'Home-Footer') {
                                                $id = 'Footer_2';
                                            }
                                            if ($section['section_name'] == 'Home-Footer') {
                                                $id = 'Footer_3';
                                            }
                                            if ($section['section_name'] == 'Home-Brand-Logo') {
                                                $id = 'Brand_Logo';
                                            }

                                        @endphp
                                        <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                            value="{{ $section['section_name'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                            value="{{ $section['section_slug'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                            value="{{ $section['array_type'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                            value="{{ $section['loop_number'] }}">
                                        @php
                                            $loop = 1;
                                            $section = (array) $section;
                                        @endphp
                                        @if (
                                            $json_key == 0 ||
                                                ($json_key - 1 > -1 && $getStoreThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div class="card"  id="{{ $id }}">
                                                <div class="card-header d-flex justify-content-between">
                                                    <div>
                                                        <h5> {{ $section['section_name'] }} </h5>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="form-check form-switch form-switch-right">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input mx-2 off switch"
                                                                data-toggle="switchbutton"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                                {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                        @endif

                                        <div class="card-body">
                                            @php

                                                $loops = 1;
                                            @endphp
                                            @if ($section['array_type'] == 'multi-inner-list')
                                                @php
                                                    $loops = $section['loop_number'];
                                                @endphp
                                            @endif
                                            @for ($i = 0; $i < $loops; $i++)
                                                <div class="row">
                                                    <?php
                                                                    foreach ($section['inner-list'] as $inner_list_key => $field) {
                                                                    $field = (array)$field;
                                                                ?>

                                                    @php
                                                        $text_class = '';
                                                        if (in_array($field['field_slug'], ['homepage-email-subscriber-title', 'homepage-email-subscriber-sub-text', 'homepage-email-subscriber-thumbnail'])) {
                                                            $text_class = 'subscriber_box';
                                                        }
                                                    @endphp
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                        value="{{ $field['field_name'] }}">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                        value="{{ $field['field_slug'] }}">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                        value="{{ $field['field_help_text'] }}">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                        value="{{ $field['field_default_text'] }}">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                        value="{{ $field['field_type'] }}">

                                                    @if ($field['field_type'] == 'text')
                                                        <div class="col-sm-6  {{ $text_class }}">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ $field['field_name'] }}</label>
                                                                @php
                                                                    $checked1 = $field['field_default_text'];
                                                                    if (!empty($section[$field['field_slug']][$i])) {
                                                                        $checked1 = $section[$field['field_slug']][$i];
                                                                    }
                                                                @endphp
                                                                @if ($section['array_type'] == 'multi-inner-list')
                                                                    <input type="text"
                                                                        name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                        class="form-control" value="{{ $checked1 }}"
                                                                        placeholder="{{ $field['field_help_text'] }}">
                                                                @else
                                                                    <input type="text"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                        class="form-control"
                                                                        value="{{ $field['field_default_text'] }}"
                                                                        placeholder="{{ $field['field_help_text'] }}"
                                                                        id="">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($field['field_type'] == 'text area')
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ $field['field_name'] }}</label>
                                                                @php
                                                                    $checked1 = $field['field_default_text'];
                                                                    if (!empty($section[$field['field_slug']][$i])) {
                                                                        $checked1 = $section[$field['field_slug']][$i];
                                                                    }
                                                                @endphp
                                                                @if ($section['array_type'] == 'multi-inner-list')
                                                                    <textarea class="form-control" name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                        rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                @else
                                                                    <textarea class="form-control"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" {{-- name="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]" --}}
                                                                        rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($field['field_type'] == 'photo upload')
                                                        <div class="col-sm-6 {{ $text_class }}">
                                                            @if ($section['array_type'] == 'multi-inner-list')
                                                                @php
                                                                    $checked2 = $field['field_default_text'];
                                                                    if (!empty($section[$field['field_slug']])) {
                                                                        $checked2 = $section[$field['field_slug']][$i];
                                                                        if (is_array($checked2)) {
                                                                            $checked2 = $checked2['field_prev_text'];
                                                                        }
                                                                    }
                                                                @endphp
                                                                <div class="form-group">
                                                                    <label
                                                                        class="form-label">{{ $field['field_name'] }}</label>
                                                                    <input type="hidden"
                                                                        name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                        value="{{ $checked2 }}">
                                                                    <input type="file"
                                                                        name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                        class="form-control"
                                                                        placeholder="{{ $field['field_help_text'] }}">
                                                                </div>
                                                                @if (isset($checked2) && !is_array($checked2))
                                                                    <img src="{{ get_file($checked2) }}"
                                                                        style="width: auto; max-height: 80px;"
                                                                        class="cate-img-shadow">
                                                                @else
                                                                    <img src="{{ get_file($field['field_default_text']) }}"
                                                                        style="width: auto; max-height: 80px;"
                                                                        class="cate-img-shadow">
                                                                @endif
                                                            @else
                                                                <div class="form-group">
                                                                    <label
                                                                        class="form-label">{{ $field['field_name'] }}</label>
                                                                    <input type="hidden"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                        value="{{ $field['field_default_text'] }}">
                                                                    <input type="file"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                        class="form-control"
                                                                        placeholder="{{ $field['field_help_text'] }}">
                                                                </div>

                                                                <img src="{{ get_file( $field['field_default_text']) }}"
                                                                    class="cate-img-shadow"
                                                                    @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;" @else style="width: 200px; height: 200px;" @endif>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if ($field['field_type'] == 'button')
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ $field['field_name'] }}</label>
                                                                <input type="text"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                    class="form-control"
                                                                    value="{{ $field['field_default_text'] }}"
                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @php
                                                        $checked = '';
                                                        if ($field['field_slug'] == 'homepage-email-subscriber-display') {
                                                            $checked = $field['field_default_text'] == 'on' ? 'checked' : '';
                                                        }
                                                        if ($field['field_slug'] == 'homepage-email-subscriber-display') {
                                                            $checked = $field['field_default_text'] == 'on' ? 'checked' : '';
                                                        }
                                                    @endphp

                                                    @if ($field['field_type'] == 'checkbox')
                                                        <div class="col-sm-6">
                                                            <label class="form-label">{{ $field['field_name'] }}</label>
                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                @if ($section['array_type'] == 'multi-inner-list')
                                                                    @php
                                                                        $checked1 = '';
                                                                        if (!empty($section[$field['field_slug']][$i]) && $section[$field['field_slug']][$i] == 'on') {
                                                                            $checked1 = 'checked';
                                                                        }
                                                                    @endphp
                                                                    <input type="hidden"
                                                                        name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                        value="off">
                                                                    <input type="checkbox" class="form-check-input mx-2"
                                                                        name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                        id="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"
                                                                        {{ $checked1 }}>
                                                                @else
                                                                    <input type="hidden"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input mx-2 enable_subscriber"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                        id=" array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}] "
                                                                        {{ $checked }}>
                                                                @endif

                                                                <label class="form-check-label"
                                                                    for="array[ {{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"></label>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($field['field_type'] == 'multi file upload')
                                                        <div class="form-group">
                                                            <label class="form-label">{{ $field['field_name'] }}</label>

                                                            <input type="file"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][multi_image][]"
                                                                class="form-control custom-input-file" multiple>
                                                        </div>
                                                        <div id="img-count" class="badge badge-success rounded-pill"></div>
                                                        <div class="col-12">
                                                            <div class="card-wrapper p-3 lead-common-box">
                                                                @if (!empty($field['image_path']))
                                                                    @foreach ($field['image_path'] as $key => $file_pathh)
                                                                        <div class="card mb-3 border shadow-none product_Image"
                                                                            data-value="{{ $file_pathh }}">
                                                                            <div class="px-3 py-3">
                                                                                <div class="row align-items-center">
                                                                                    <div class="col ml-n2">
                                                                                        <p class="card-text small text-muted">
                                                                                            <input type="hidden"
                                                                                                name='array[{{ $json_key }}][prev_image][]'
                                                                                                value="{{ $file_pathh }}">
                                                                                            <img class="rounded"
                                                                                                src=" {{ get_file($file_pathh) }}"
                                                                                                width="70px"
                                                                                                alt="Image placeholder"
                                                                                                data-dz-thumbnail>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="col-auto actions">
                                                                                        <a class="action-item"
                                                                                            href=" {{ get_file($file_pathh) }}"
                                                                                            download=""
                                                                                            data-toggle="tooltip"
                                                                                            data-original-title="{{ __('Download') }}">
                                                                                            <i class="fas fa-download"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="col-auto actions">
                                                                                        <a name="deleteRecord"
                                                                                            class="action-item deleteRecord"
                                                                                            data-name="{{get_file($file_pathh) }}">
                                                                                            <i class="fas fa-trash"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <?php } ?>
                                                </div>
                                            @endfor

                                        </div>
                                        @if ($json_key + 1 <= count($getStoreThemeSetting) - 1)
                                            @if ($getStoreThemeSetting[$json_key + 1]['section_slug'] != $section['section_slug'])
                                        </div>
                                        @endif
                                        @endif
                                    @endforeach
                                </div>
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <div class="card-footer">
                            <div class="col-sm-12">
                                <div class="text-end">
                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary py-3 px-3']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endif

    </div>
    <!-- [ sample-page ] end -->
    </div>
    </div>
@endsection

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
@push('scripts')

<script>
    $(document).ready(function() {
        $('.repeater').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
            },
            hide: function(deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            isFirstItemUndeletable: true
        })
    });
    $(".deleteRecord").click(function(e) {
        e.preventDefault();
        var id = $(this).data("name");
        var data = {
            'image': id,
            '_token': $("meta[name='csrf-token']").attr("content")
        };

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('product.image.delete') }}",
            data: data,
            context: this,
            success: function(data) {
                $(this).closest('.product_Image').remove();
                $('.submit_all').click();
            }
        });
    });
</script>
<script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
<script>
    var Dropzones = function() {
        var e = $('[data-toggle="dropzone1"]'),
            t = $(".dz-preview");
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        e.length && (Dropzone.autoDiscover = !1, e.each(function() {
            var e, a, n, o, i;
            e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                url: "{{ route('store.storeeditproducts', [$store->slug, $theme]) }}",
                headers: {
                    'x-csrf-token': CSRF_TOKEN,
                },
                thumbnailWidth: null,
                thumbnailHeight: null,
                previewsContainer: n.get(0),
                previewTemplate: n.html(),
                maxFiles: 10,
                parallelUploads: 10,
                autoProcessQueue: true,
                uploadMultiple: true,
                acceptedFiles: a ? null : "image/*",
                success: function(file, response) {
                    if (response.status == "success") {
                        show_toastr('success', response.success, 'success');
                    } else {
                        show_toastr('Error', response.msg, 'error');
                    }
                },
                error: function(file, response) {
                    // Dropzones.removeFile(file);
                    if (response.error) {
                        show_toastr('Error', response.error, 'error');
                    } else {
                        show_toastr('Error', response, 'error');
                    }
                },
                init: function() {
                    var myDropzone = this;
                }

            }, n.html(""), e.dropzone(i)
        }))
    }()

    $("#eventBtn").click(function() {
        $("#BigButton").clone(true).appendTo("#fileUploadsContainer").find("input").val("").end();
    });
    $("#testimonial_eventBtn").click(function() {
        $("#BigButton2").clone(true).appendTo("#fileUploadsContainer2").find("input").val("").end();
    });

    $(document).on('click', '#remove', function() {
        var qq = $('.BigButton').length;

        if (qq > 1) {
            var dd = $(this).attr('data-id');

            $(this).parents('#BigButton').remove();
        }
    });

    $("input[type='file']").on("change", function() {
        var numFiles = $(this).get(0).files.length
        $('#img-count').html(numFiles + ' Images selected');
    })
</script>
@endpush
