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
                <div class="col-xl-9">
                    <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="seo-tab" data-bs-toggle="pill" data-bs-target="#seo" type="button">{{__('SEO')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="info-tab" data-bs-toggle="pill" data-bs-target="#info" type="button">{{__('Info')}}</button>
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade active show" id="seo" role="tabpanel" aria-labelledby="pills-user-tab-1">
                                    <div class="card mt-4">
                                        {{ Form::open(['route' => ['lms.seo.setting.store', [$store->slug, $store->theme_dir]], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0">{{ __('SEO') }}</h5>
                                                {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                {{ Form::label('meta_keyword', __('Meta Keywords'), ['class' => 'form-label']) }}
                                                                {{ Form::text('meta_keyword', $store->meta_keyword, ['class' => 'form-control', 'rows' => '3', 'placeholder' => __('Enter Meta Keywords')]) }}
                                                            </div>
                                                            @error('metakeywords')
                                                                <span class="invalid-favicon text-xs text-danger"
                                                                    role="alert">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                {{ Form::label('meta_description', __('Meta Description'), ['class' => 'form-label']) }}
                                                                {{ Form::textarea('meta_description', $store->meta_description, ['class' => 'form-control', 'rows' => '3', 'cols' => '30', 'placeholder' => __('Enter Meta Description')]) }}
                                                            </div>
                                                            @error('meta_description')
                                                                <span class="invalid-favicon text-xs text-danger"
                                                                    role="alert">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        {{-- Meta Image --}}
                                                        <div class="col-12 form-group">
                                                            {{ Form::label('meta_image', __('Meta Image'), ['class' => 'form-label']) }}
                                                            <input type="file" class="form-control" name="meta_image"
                                                                    id="meta_image" aria-label="file example" onchange="document.getElementById('blah3').src = window.URL.createObjectURL(this.files[0])">
                                                            <a href="{{get_file($store->meta_image)}}"
                                                                target="_blank">
                                                                <img @if(!empty($store->meta_image)) src="{{ get_file($store->meta_image) }}" @endif
                                                                    name="meta_image" id="blah3"
                                                                    class="avatar avatar-lg mt-2">
                                                            </a>
                                                        </div>
                                                        {{-- End Meta Image --}}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            {{ Form::label('google_analytic', __('Google Analytic'), ['class' => 'form-label']) }}
                                                            {{ Form::text('google_analytic',$store->google_analytic, ['class' => 'form-control', 'placeholder' => 'UA-XXXXXXXXX-X']) }}
                                                        </div>
                                                        @error('google_analytic')
                                                            <span class="invalid-google_analytic" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            {{ Form::label('fbpixel_code', __('Facebook Pixel'), ['class' => 'form-label']) }}
                                                            {{ Form::text('fbpixel_code', $store->fbpixel_code, ['class' => 'form-control', 'placeholder' => 'UA-0000000-0']) }}
                                                        </div>
                                                        @error('facebook_pixel_code')
                                                            <span class="invalid-google_analytic" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="pills-user-tab-2">
                                    <div class="card mt-4">
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0">{{ __('Pixel Fields') }}</h5>
                                            @permission('lms pixel fields create')
                                                <a  class="btn btn-sm btn-primary py-2" data-ajax-popup="true"
                                                data-size="md" data-title="{{ __('Create Pixel') }}"
                                                data-url="{{ route('lms.pixel.create',$store->slug) }}"  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
                                                    <i class="ti ti-plus text-white"></i>
                                                </a>
                                            @endpermission
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <table id="pc-dt-simple" class="table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th> {{__('Platform')}}</th>
                                                                <th> {{__('Pixel ID')}}</th>
                                                                @if(Laratrust::hasPermission('lms pixel fields delete'))
                                                                    <th class="text-right" width="200px"> {{__('Action')}}</th>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($PixelFields as $pixel)
                                                                <tr>
                                                                    <td>{{ ucfirst($pixel->platform) }}</td>
                                                                    <td>{{ $pixel->pixel_id }}</td>
                                                                    @if(Laratrust::hasPermission('lms pixel fields delete'))
                                                                        <td class="Action">
                                                                            @permission('lms pixel fields delete')
                                                                                <div class="action-btn bg-danger ms-2">
                                                                                    {{Form::open(array('route'=>array('lms.pixel.delete', $pixel->id),'class' => 'm-0','method' => 'DELETE'))}}
                                                                                        <a
                                                                                        class="mx-3 btn btn-sm  align-items-center show_confirm"
                                                                                        data-bs-toggle="tooltip"  data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}"><i
                                                                                            class="ti ti-trash text-white text-white"></i></a>
                                                                                    {!! Form::close() !!}
                                                                                </div>
                                                                            </span>
                                                                            @endpermission
                                                                        </td>
                                                                    @endif
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
                        </div>
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
