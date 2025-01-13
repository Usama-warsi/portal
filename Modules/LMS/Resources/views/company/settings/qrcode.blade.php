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
        <a href="{{ route('setting.storeanalytic') }}" class="btn btn-sm btn-info py-2" data-bs-toggle="tooltip"
            data-bs-placement="top" title="{{ __('Store Analytic ') }}">
            <i class="ti ti-brand-google-analytics"></i>
        </a>
        <a class="btn btn-sm btn-primary py-2" data-ajax-popup="true" data-size="md" data-title="{{ __('Qr code') }}"
            data-url="{{ route('qrcode') }}" data-toggle="tooltip" title="{{ __('Qr code') }}">
            <i class="ti ti-qrcode"></i>
        </a>

        <a href="{{ route('store.slug', $store->slug) }}" class="btn btn-sm btn-warning py-2" data-bs-toggle="tooltip"
            data-bs-placement="top" title="{{ __('Privew') }}" target="_blank">
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
                <div class="col-xl-6">
                    {{ Form::open(['route' => ['lms.qrcode.setting.store', $store->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 flex-grow-1">{{ __('Qr Code Settings') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('Foreground Color', __('Foreground Color'), ['class' => 'form-label']) }}
                                        <input type="color" name="foreground_color"
                                            value="{{ isset($qr_detail->foreground_color) ? $qr_detail->foreground_color : '#000000' }}"
                                            class="form-control foreground_color qr_data"
                                            data-multiple-caption="{count} files selected" multiple="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('Background Color', __('Background Color'), ['class' => 'form-label']) }}
                                        <input type="color" name="background_color"
                                            value="{{ isset($qr_detail->background_color) ? $qr_detail->background_color : '#ffffff' }}"
                                            class="form-control background_color qr_data"
                                            data-multiple-caption="{count} files selected" multiple="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('Corner Radius', __('Corner Radius'), ['class' => 'form-label']) }}
                                        <input type="range" name="radius" class="radius qr_data" min="1"
                                            max="50" step="1" style="width:100%;"
                                            value="{{ isset($qr_detail->radius) ? $qr_detail->radius : 26 }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row gy-2 gx-2 my-3 gallery-btn">

                                        @foreach ($qr_code as $k => $value)
                                            <div class="col-auto " id="">
                                                <label for="enable_{{ $k }}" class="btn btn-secondary qr_type">
                                                    <input type="radio" class="d-none btn btn-secondary qr_type_click"
                                                        @if (isset($qr_detail->qr_type) && $qr_detail->qr_type == $k) checked @endif name="qr_type"
                                                        value="{{ $k }}" id="{{ $k }}" /><i
                                                        class="me-2" data-feather="folder"></i>
                                                    {{ __($value) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <span id="qr_type_option"
                                    style="{{ $qr_detail == null ? 'display: none' : 'display: block' }}">
                                    <div id="text_div">
                                        <div class="col-md-12 mt-2 ">
                                            <div class="form-group">
                                                {{ Form::label('Text', __('Text'), ['class' => 'form-label']) }}
                                                <input type="text" name="qr_text"
                                                    value="{{ isset($qr_detail->qr_text) ? $qr_detail->qr_text : '' }}"
                                                    class="form-control qr_text qr_keyup">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('Text Color', __('Text Color'), ['class' => 'form-label']) }}
                                                <input type="color" name="qr_text_color"
                                                    value="{{ isset($qr_detail->qr_text_color) ? $qr_detail->qr_text_color : '#f50a0a' }}"
                                                    class="form-control qr_text_color qr_data">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-2" id="image_div">
                                        <div class="form-group">
                                            {{ Form::label('image', __('Image'), ['class' => 'form-label']) }}

                                            <input type="file" name="image" accept=".png, .jpg, .jpeg"
                                                class="form-control qr_image qr_data">
                                            <input type="hidden" name="old_image" value="">

                                            <img id="image-buffer"
                                                src="{{ isset($qr_detail->image) ? get_file($qr_detail->image) : '' }}"
                                                class="d-none">

                                        </div>
                                    </div>

                                    <div class="col-md-12" id="size_div">
                                        <div class="form-group">
                                            {{ Form::label('Size', __('Size'), ['class' => 'form-label']) }}
                                            <input type="range" name="size" class="qr_size qr_data"
                                                value="{{ isset($qr_detail->size) ? $qr_detail->size : 9 }}"
                                                min="1" max="50" step="1" style="width:100%;">
                                        </div>
                                    </div>

                                </span>

                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }} </button>
                            </div>
                        </div>

                    </div>
                    {{ Form::close() }}
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="code">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('Modules/LMS/Resources/assets/js/jquery.qrcode.min.js') }}"></script>

    <script>
        $('.qr_type').on('click', function () {
        $("input[type=radio][name='qr_type']").attr('checked', false);
        $("input[type=radio][name='qr_type']").parent().removeClass('btn-primary');
        $("input[type=radio][name='qr_type']").parent().addClass('btn-secondary');


        var value=$(this).children().attr('checked', true);
        var qr_type_val=$(this).children().attr('id');

        if(qr_type_val == 0){
            $('#qr_type_option').slideUp();
            $(this).removeClass('btn-secondary');
            $(this).addClass('btn-primary');
        }else if(qr_type_val == 2){
            $('#qr_type_option').slideDown();
            $('#text_div').slideDown();
            $('#image_div').slideUp();
            $(this).removeClass('btn-secondary');
            $(this).addClass('btn-primary');
        } else if(qr_type_val == 4){
            $('#qr_type_option').slideDown();
            $('#text_div').slideUp();
            $('#image_div').slideDown();
            $(this).removeClass('btn-secondary');
            $(this).addClass('btn-primary');
        }
        generate_qr();
    });

    </script>

    <script>
        function generate_qr() {

            if($("input[name='qr_type']:checked").parent().hasClass('btn-primary')==false)
            {

                var chekced=$("input[name='qr_type']:checked").parent().addClass('btn-primary');
                var qr_type_val=$("input[name='qr_type']:checked").attr('id');
                if(qr_type_val == 0){
                    $('#qr_type_option').slideUp();
                    $(this).removeClass('btn-secondary');
                    $(this).addClass('btn-primary');
                }else if(qr_type_val == 2){
                    $('#qr_type_option').slideDown();
                    $('#text_div').slideDown();
                    $('#image_div').slideUp();
                    $(this).removeClass('btn-secondary');
                    $(this).addClass('btn-primary');
                } else if(qr_type_val == 4){
                    $('#qr_type_option').slideDown();
                    $('#text_div').slideUp();
                    $('#image_div').slideDown();
                    $(this).removeClass('btn-secondary');
                    $(this).addClass('btn-primary');
                }

            }
            var slug = '{{ $store->slug }}';
            var card_url = `{{ url('/store-lms') }}/${slug}`;

            $('.code').empty().qrcode({
                render: 'image',
                size: 380,
                ecLevel: 'H',
                minVersion: 3,
                quiet: 1,
                text: card_url,
                fill: $('.foreground_color').val(),
                background: $('.background_color').val(),
                radius: .01 * parseInt($('.radius').val(), 10),
                mode: parseInt($("input[name='qr_type']:checked").val(), 10),
                label: $('.qr_text').val(),
                fontcolor: $('.qr_text_color').val(),
                image: $("#image-buffer")[0],
                mSize: .01 * parseInt($('.qr_size').val(), 10)
            });


        }
        $('.qr_data').on('change', function () {
        generate_qr();
    });

     $('.qr_keyup').on('keyup', function () {
         generate_qr();
     });


    $(document).on('change', '.qr_image', function(e) {
        var img_reader, img_input = $('.qr_image')[0];
        img_input.files && img_input.files[0] && ((img_reader = new window.FileReader).onload = function (event) {
            $("#image-buffer").attr("src", event.target.result);
            setTimeout(generate_qr, 250)
        }, img_reader.readAsDataURL(img_input.files[0]))
    });
    generate_qr();
    </script>

    <script>
        $(document).ready(function() {

            var slug = '{{ $store->slug }}';
            var url_link = `{{ url('/store-lms') }}/${slug}`;
            $(`.qr-link`).text(url_link);
            $('.qrcode').qrcode(url_link);

            let ele = $(".emojiarea").emojioneArea();
            $.each( ele, function( key, value ) {

                ele[key].emojioneArea.on("keyup", function(btn, event) {
                    var get_id = ele[key].getAttribute('id');
                    var get_val = btn.html();
                    get_val = get_val.replace('&nbsp','');

                    $(`#${get_id}_preview`).text(get_val);
                    $(`.description-div`).show();
                    if ($('.description-text').val() == "") {
                        $(`.description-div`).hide();
                    }
                });
            });

            });
    </script>
@endpush
