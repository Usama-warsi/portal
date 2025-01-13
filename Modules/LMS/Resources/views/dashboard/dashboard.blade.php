@extends('layouts.main')
@section('page-title')
    {{ __('Dashboard') }}
@endsection

@section('page-breadcrumb')
    {{ __('LMS')}}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/LMS/Resources/assets/css/custom.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="page-content">
        <!-- Page title -->
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xxl-5">
                        <div class="card">
                            <div class="card-body stats welcome-card">
                                <div class="row align-items-center mb-4">
                                    <div class="col-xxl-12">
                                        <h3 class="mb-2" id="greetings"></h3>
                                        <h4 class="f-w-400" style="margin-bottom: 10px;">
                                            <img src="{{ get_file(!empty(Auth::user()->avatar) ? Auth::user()->avatar : 'uploads/users-avatar/avatar.png') }}" alt="user-image" class="wid-35 me-2 img-thumbnail rounded-circle">{{ __(Auth::user()->name) }}
                                        </h4>
                                        <p>{{ __('Have a nice day! Did you know that you can quickly add your favorite course or category to the store?') }}</p>
                                        <div class="dropdown quick-add-btn">
                                            <a class="btn btn-primary btn-q-add dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"> <i class="ti ti-plus drp-icon"></i>
                                                <span class="ms-2 me-2">{{ __('Quick add') }}</span>
                                            </a>
                                            @if(Laratrust::hasPermission('course create') || Laratrust::hasPermission('course category create') || Laratrust::hasPermission('course subcategory create'))
                                                <div class="dropdown-menu">
                                                    @permission('course create')
                                                        <a href="{{ route('course.create') }}" class="dropdown-item"><span>{{ __('Add new Course') }}</span>
                                                        </a>
                                                    @endpermission

                                                    @permission('course category create')
                                                        <a href="#" data-size="md" data-url="{{ route('course-category.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Category') }}"
                                                        class="dropdown-item" data-bs-placement="top"><span>{{ __('Add new Category') }}</span></a>
                                                    @endpermission

                                                    @permission('course subcategory create')
                                                        <a href="#" data-size="md" data-url="{{ route('course-subcategory.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Subcategory') }}" class="dropdown-item" data-bs-placement="top"><span>{{ __('Add new Subcategory') }}</span></a>
                                                    @endpermission
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card  min-h-390">
                            <div class="card-header d-flex justify-content-between">
                                <h5>{{ __('Top Course') }}</h5>
                            </div>
                            <div class="card-body top-10-scroll">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="sort" data-sort="name">{{ __('Course') }} </th>
                                                <th scope="col" class="sort text-right" data-sort="completion"> {{ __('Price') }}</th>
                                            </tr>
                                        </thead>
                                        @if (count($products) > 0 && !empty($item_id) && !empty($products))
                                            <tbody class="list">
                                                @foreach ($products as $product)
                                                    @foreach ($item_id as $k => $item)
                                                        @if ($product->id == $item)
                                                            <tr>
                                                                <th scope="row">
                                                                    <div class="media align-items-center gap-3">
                                                                        <div>
                                                                            @if (!empty($product->thumbnail))
                                                                                <img alt="Image placeholder" class="rounded" src="{{ get_file($product->thumbnail) }}" width="65px" height="50px">
                                                                            @else
                                                                                <img alt="Image placeholder" src="{{ get_file('uploads/thumbnail/default.jpg') }}" class="rounded" width="60px" height="50px">
                                                                            @endif
                                                                        </div>
                                                                        <div class="media-body ml-4">
                                                                            <span class="mb-0 h6 text-sm">{{ $product->title }}</span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td class="text-right">
                                                                    <div>
                                                                        <span class="completion mr-2 text-dark text-right">{{ currency_format_with_sym($product->price) }}</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        @else
                                            <tbody>
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="text-center">
                                                            <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                                            <h2>{{ __('Opps') }}...</h2>
                                                            <h6>{{ __('No data Found') }}. </h6>
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
                    <div class="col-xxl-7">
                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <div class="card-body stats">
                                        <div class="qrcode">
                                        </div>
                                        <h6 class="mb-2 mt-2">{{ $store->name }}</h6>
                                        <img src="{{ isset($qr_detail->image) ? get_file($qr_detail->image) : '' }}" id="image-buffers" style="display: none">
                                        <a href="#" class="btn btn-primary btn-sm text-sm cp_link mb-0" data-link="{{ $store['store_url'] }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="{{ __('Click to copy link') }}">{{ __('Store Link') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <div class="card-body stats">
                                        <div class="theme-avtar bg-info">
                                            <i class="fas fa-cube"></i>
                                        </div>
                                        <h6 class="mb-3 mt-4">{{ __('Total Course') }}</h6>
                                        <h4 class="mb-0">{{ $newproduct }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <div class="card-body stats">
                                        <div class="theme-avtar bg-warning">
                                            <i class="fas fa-cart-plus"></i>
                                        </div>
                                        <h6 class="mb-3 mt-4 ">{{ __('Total Sales') }}</h6>
                                        <h4 class="mb-0">{{ currency_format_with_sym($total_sale) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <div class="card-body stats">
                                        <div class="theme-avtar bg-danger">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <h6 class="mb-3 mt-4 ">{{ __('Total Course Orders') }}</h6>
                                        <h4 class="mb-0">{{ $total_order }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card min-h-390 overflow-auto">
                            <div class="card-header">
                                <h5>{{ __('Course Orders') }}</h5>
                            </div>
                            <div class="card-body">
                                <div id="apex-dashborad" data-color="primary" data-height="230"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>{{ __('Recent Course Orders') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('Orders') }}</th>
                                                <th scope="col" class="sort">{{ __('Date') }}</th>
                                                <th scope="col" class="sort">{{ __('Name') }}</th>
                                                <th scope="col" class="sort">{{ __('Value') }}</th>
                                                <th scope="col" class="sort">{{ __('Payment Type') }}</th>
                                                <th scope="col" class="text-center">{{ __('Status') }}</th>
                                                <th scope="col" class="text-end">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($new_orders) && count($new_orders) > 0)
                                                @foreach ($new_orders as $order)
                                                    @if ($order->status != 'Cancel Order')
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <a href="{{ route('course_orders.show', $order->id) }}" class="btn btn-outline-primary">
                                                                        <span class="btn-inner--text">{{ $order->order_id }}</span>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <h6 class="m-0">
                                                                    {{ company_date_formate($order->created_at) }}
                                                                </h6>
                                                            </td>
                                                            <td>
                                                                <h6 class="m-0">{{ $order->name }}</h6>
                                                            </td>
                                                            <td>
                                                                <h6 class="m-0"> {{ currency_format_with_sym($order->price) }} <h6>
                                                            </td>
                                                            <td>
                                                                <h6 class="m-0">{{ $order->payment_type }}<h6>
                                                            </td>
                                                            <td>
                                                                <div class="actions ml-3">
                                                                    <div class="d-flex row justify-content-center">
                                                                        <button type="button" class="btn btn-sm {{ $order->payment_status == 'success' || $order->payment_status == 'succeeded' || $order->payment_status == 'approved' ? 'btn-soft-success' : 'btn-soft-info' }} btn-icon rounded-pill">
                                                                            <span class="btn-inner--icon">
                                                                                @if ($order->payment_status == 'pendding')
                                                                                    <i class="fas fa-check"></i>
                                                                                @else
                                                                                    <i class="fa fa-check-double"></i>
                                                                                @endif
                                                                            </span>
                                                                            @if ($order->payment_status == 'pendding')
                                                                                <span class="btn-inner--text">
                                                                                    {{ __('Pending') }}:
                                                                                    {{company_date_formate($order->created_at) }}
                                                                                </span>
                                                                            @else
                                                                                <span class="btn-inner--text">
                                                                                    {{ __('Delivered') }}:
                                                                                    {{company_date_formate($order->updated_at) }}
                                                                                </span>
                                                                            @endif
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="actions ml-3">
                                                                    <div class="d-flex align-items-center justify-content-end">
                                                                        <div class="action-btn bg-warning ms-2">
                                                                            <a href="{{ route('course_orders.show', $order->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Details') }}"><i class="ti ti-eye text-white"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('Modules/LMS/Resources/assets/js/jquery.qrcode.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        var url_link = '{{ $store['store_url'] }}';

        var foreground_color =
            `{{ isset($qr_detail->foreground_color) ? $qr_detail->foreground_color : '#000000' }}`;
        var background_color =
            `{{ isset($qr_detail->background_color) ? $qr_detail->background_color : '#ffffff' }}`;
        var radius = `{{ isset($qr_detail->radius) ? $qr_detail->radius : 26 }}`;
        var qr_type = `{{ isset($qr_detail->qr_type) ? $qr_detail->qr_type : 0 }}`;
        var qr_font = `{{ isset($qr_detail->qr_text) ? $qr_detail->qr_text : 'VCard' }}`;
        var qr_font_color = `{{ isset($qr_detail->qr_text_color) ? $qr_detail->qr_text_color : '#f50a0a' }}`;
        var size = `{{ isset($qr_detail->size) ? $qr_detail->size : 9 }}`;

        $('.qrcode').empty().qrcode({
            render: 'image',
            size: 100,
            ecLevel: 'H',
            minVersion: 3,
            quiet: 1,
            text: url_link,
            fill: foreground_color,
            background: background_color,
            radius: .01 * parseInt(radius, 10),
            mode: parseInt(qr_type, 10),
            label: qr_font,
            fontcolor: qr_font_color,
            image: $("#image-buffers")[0],
            mSize: .01 * parseInt(size, 10)
        });
    </script>
    <script>
        var today = new Date()
        var curHr = today.getHours()

        if (curHr < 12) {
            document.getElementById("greetings").innerHTML = "{{ __('Good Morning,') }}";
        } else if (curHr < 18) {
            document.getElementById("greetings").innerHTML = "{{ __('Good Afternoon,') }}";
        } else {
            document.getElementById("greetings").innerHTML = "{{ __('Good Evening,') }}";
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.cp_link').on('click', function() {
                var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toastrs('Success', '{{ __('Link copied') }}', 'success')
            });
        });

        (function() {
            var options = {
                chart: {
                    height: 250,
                    type: 'area',
                    toolbar: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },


                series: [{
                    name: "{{ __('Order') }}",
                    data: {!! json_encode($chartData['data']) !!}
                }],

                xaxis: {
                    axisBorder: {
                        show: !1
                    },
                    type: "MMM",
                    categories: {!! json_encode($chartData['label']) !!},
                    title: {
                        text: 'Days'
                    }

                },
                colors: ['#e83e8c'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                yaxis: {
                    tickAmount: 3,
                    title: {
                        text: 'Amount'
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#apex-dashborad"), options);
            chart.render();
        })();
    </script>
@endpush
