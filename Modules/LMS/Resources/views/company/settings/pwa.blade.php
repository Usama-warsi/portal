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
                <div class="col-xl-9 ">
                    <div class="card">
                        {{ Form::open(['route' => ['lms.pwa.setting.store', [$store->id]], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">{{ __('PWA') }}</h5>
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch custom-switch-v1">
                                    <input type="checkbox" class="form-check-input enable_pwa" name="enable_pwa"
                                        id="enable_pwa" {{ $store['enable_pwa'] == 'on' ? 'checked=checked' : '' }}>

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row gy-4">
                                            <div class="col-lg-6">
                                                <div class="form-group mb-0 pwa_is_enable">
                                                    {{ Form::label('pwa_app_title', __('App Title'), ['class' => 'form-label']) }}
                                                    {{ Form::text('pwa_app_title', !empty($pwa_data->name) ? $pwa_data->name : '', ['class' => 'form-control', 'placeholder' => __('App Title'), isset($store['enable_pwa']) && $store['enable_pwa'] == 'on' ? '' : ' disabled']) }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mb-0 pwa_is_enable">
                                                    {{ Form::label('pwa_app_name', __('App Name'), ['class' => 'form-label']) }}
                                                    {{ Form::text('pwa_app_name', !empty($pwa_data->short_name) ? $pwa_data->short_name : '', ['class' => 'form-control', 'placeholder' => __('App Name'), isset($store['enable_pwa']) && $store['enable_pwa'] == 'on' ? '' : ' disabled']) }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mb-0 pwa_is_enable ">
                                                    {{ Form::label('pwa_app_background_color', __('App Background Color'), ['class' => 'form-label']) }}
                                                    <div class="d-flex align-items-center color-picker-wrapper">

                                                        {{ Form::color('pwa_app_background_color', !empty($pwa_data->background_color) ? $pwa_data->background_color : '', ['class' => 'form-control color-picker', 'placeholder' => __('18761234567'), isset($store['enable_pwa']) && $store['enable_pwa'] == 'on' ? '' : ' disabled']) }}

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mb-0 pwa_is_enable">
                                                    {{ Form::label('pwa_app_theme_color', __('App Theme Color'), ['class' => 'form-label']) }}
                                                    <div class="d-flex align-items-center color-picker-wrapper">
                                                        {{ Form::color('pwa_app_theme_color', !empty($pwa_data->theme_color) ? $pwa_data->theme_color : '', ['class' => 'form-control color-picker', 'placeholder' => __('18761234567'), isset($store['enable_pwa']) && $store['enable_pwa'] == 'on' ? '' : ' disabled']) }}

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }} </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- [ sample-page ] end -->
    </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <script>
        $(document).on('click', '#enable_pwa', function() {
            if ($('#enable_pwa').prop('checked')) {
                $("#pwa_app_title").removeAttr("disabled");
                $("#pwa_app_name").removeAttr("disabled");
                $("#pwa_app_background_color").removeAttr("disabled");
                $("#pwa_app_theme_color").removeAttr("disabled");
            } else {
                $('#pwa_app_title').attr("disabled", "disabled");
                $('#pwa_app_name').attr("disabled", "disabled");
                $('#pwa_app_background_color').attr("disabled", "disabled");
                $('#pwa_app_theme_color').attr("disabled", "disabled");
            }
        });
    </script>
@endpush
