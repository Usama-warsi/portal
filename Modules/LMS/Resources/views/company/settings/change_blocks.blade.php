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

<link rel="stylesheeModules/LMS/Resources/views/company/settings/change_blocks.blade.php Modules/LMS/Resources/views/company/settings/create_pixel.blade.php Modules/LMS/Resources/views/company/settings/edit_theme.blade.php Modules/LMS/Resources/views/company/settings/pwa.blade.php Modules/LMS/Resources/views/company/settings/qrcode.blade.php Modules/LMS/Resources/views/company/settings/seo.blade.php Modules/LMS/Resources/views/company/settings/setup.blade.php Modules/LMS/Resources/views/company/settings/show_qrcode.blade.php Modules/LMS/Resources/views/company/settings/store-analytic.blade.phpt" href="{{ asset('Modules/LMS/Resources/assets/css/custom.css') }}">

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-lg-3 col-12">
                    @include('lms::company.settings.setup')
                </div>
                <div class="col-xl-9 ">
                    <div class="card">
                        {{ Form::open(['route' => ['change.block.setting', [$store->slug, $store->theme_dir]], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">{{ __('Change Blocks') }}</h5>
                                {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-group sortable">
                                <input type="hidden" name="order" value="" id="hidden_order">
                                @for ($i = 1; $i <= 6; $i++)
                                    @foreach ($storethemesetting['block_order'] as $order_key => $order_value)
                                        @if ($i == $order_value)
                                            <li class="list-group-item d-flex align-items-center justify-content-between"
                                                data-id="{{ $order_key }}">
                                                @if ($order_key == 'banner')
                                                    <h6 class="mb-0">
                                                        <i class="me-3" data-feather="move"></i>
                                                        <span>{{ __('Banner') }}</span>
                                                    </h6>
                                                @elseif($order_key == 'homepage-header')
                                                    <h6 class="mb-0">
                                                        <i class="me-3" data-feather="move"></i>
                                                        <span>{{ __('Header') }}</span>
                                                    </h6>
                                                @elseif($order_key == 'homepage-featured-course')
                                                    <h6 class="mb-0">
                                                        <i class="me-3" data-feather="move"></i>
                                                        <span>{{ __('Feature Course') }}</span>
                                                    </h6>
                                                @elseif($order_key == 'homepage-categories')
                                                    <h6 class="mb-0">
                                                        <i class="me-3" data-feather="move"></i>
                                                        <span>{{ __('Category') }}</span>
                                                    </h6>
                                                @elseif($order_key == 'homepage-on-sale')
                                                    <h6 class="mb-0">
                                                        <i class="me-3" data-feather="move"></i>
                                                        <span>{{ __('Bestseller') }}</span>
                                                    </h6>
                                                @elseif($order_key == 'homepage-email-subscriber')
                                                    <h6 class="mb-0">
                                                        <i class="me-3" data-feather="move"></i>
                                                        <span>{{ __('Feature Category') }}</span>
                                                    </h6>
                                                @else
                                                    <h6 class="mb-0">
                                                        <i class="me-3" data-feather="move"></i>
                                                        <span>{{ __(ucfirst($order_key)) }}</span>
                                                    </h6>
                                                @endif
                                                @foreach ($getStoreThemeSetting as $jsn)
                                                    @if ($jsn['section_slug'] == $order_key)
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off:') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="{{ $order_key }}"
                                                                    value="off">
                                                                <input type="checkbox" name="{{ $order_key }}"
                                                                    @if ($jsn['section_enable'] == 'on') checked @endif
                                                                    class="form-check-input input-primary"
                                                                    id="{{ $order_key }}">
                                                                <label class="form-check-label"
                                                                    for="{{ $order_key }}"></label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </li>
                                        @endif
                                    @endforeach
                                @endfor
                            </ul>
                        </div>
                        <p class="ps-5">
                            <b>{{ __('Note: You can easily order change of card blocks using drag & drop.') }}</b>
                        </p>
                    </div>
                    {{ Form::close() }}
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

    <script src="{{ asset('Modules/LMS/Resources/assets/js/jquery-ui.js') }}"></script>
    <script>
        $(function() {
            $(".sortable").sortable();
            $(".sortable").disableSelection();
            $(".sortable").sortable({
                stop: function() {
                    var order = [];
                    $(this).find('li').each(function(index, data) {
                        order[index] = $(data).attr('data-id');
                    });
                    $('#hidden_order').val(order);

                }
            });
            var block_order = [];
            $(".sortable").find('li').each(function(index, data) {
                block_order[index] = $(data).attr('data-id');
            });
            $('#hidden_order').val(block_order);
        });
    </script>
@endpush
