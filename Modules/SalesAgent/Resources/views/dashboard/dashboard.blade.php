@extends('layouts.main')

@section('page-title')
    {{__('Dashboard')}}
@endsection

@section('page-breadcrumb')
    {{ __('Sales Agent')}}
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        var WorkedHoursChart = (function () {
            var $chart = $('#purchase_orders');

            function init($this) {
                var options = {
                    chart: {
                        height: 400,
                        type: 'bar',
                        zoom: {
                            enabled: false
                        },
                        toolbar: {
                            show: false
                        },
                        shadow: {
                            enabled: false,
                        },

                    },
                    plotOptions: {
                bar: {
                    columnWidth: '30%',
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
                    stroke: {
                show: true,
                width: 1,
                colors: ['#fff']
            },
                    series: [{
                        name: 'Orders',
                        data: {!! json_encode($PurchaseOrderData) !!},
                    }],
                    xaxis: {
                        labels: {
                            // format: 'MMM',
                            style: {
                                colors: '#293240',
                                fontSize: '12px',
                                fontFamily: "sans-serif",
                                cssClass: 'apexcharts-xaxis-label',
                            },
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true,
                            borderType: 'solid',
                            color: '#f2f2f2',
                            height: 50,
                            offsetX: 0,
                            offsetY: 0
                        },
                        title: {
                            text: 'Purchase Orders Status'
                        },
                        categories: {!! json_encode(\Modules\SalesAgent\Entities\SalesAgentPurchase::$purchaseOrder) !!},
                    },
                    yaxis: {
                        labels: {
                            style: {
                                color: '#f2f2f2',
                                fontSize: '12px',
                                fontFamily: "Open Sans",
                            },
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true,
                            borderType: 'solid',
                            color: '#f2f2f2',
                            height: 50,
                            offsetX: 0,
                            offsetY: 0
                        }
                    },
                    fill: {
                        type: 'solid',
                        opacity: 1

                    },
                    markers: {
                        size: 4,
                        opacity: 0.7,
                        strokeColor: "#000",
                        strokeWidth: 3,
                        hover: {
                            size: 7,
                        }
                    },
                    grid: {
                        borderColor: '#f2f2f2',
                        strokeDashArray: 5,
                    },
                    dataLabels: {
                        enabled: false
                    }
                }
                // Get data from data attributes
                var dataset = $this.data().dataset,
                    labels = $this.data().labels,
                    color = $this.data().color,
                    height = $this.data().height,
                    type = $this.data().type;

                // Inject synamic properties
                // options.colors = [
                //     PurposeStyle.colors.theme[color]
                // ];
                // options.markers.colors = [
                //     PurposeStyle.colors.theme[color]
                // ];

                // Init chart
                var chart = new ApexCharts($this[0], options);
                // Draw chart
                setTimeout(function () {
                    chart.render();
                }, 300);
            }

            // Events
            if ($chart.length) {
                $chart.each(function () {
                    init($(this));
                });
            }
        })();
    </script>
@endpush
@section('content')
        <div class="row">
            
            @if(\Auth::user()->type == 'company')
                <div class="col-xl-2 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="theme-avtar bg-info">
                                <i class="fas fa-users bg-info text-white"></i>
                            </div>
                            <p class="text-muted text-sm"></p>
                            <h6 class="mt-4 mb-4">{{ __('Total Agents') }}</h6>
                            <h3 class="mb-0">{{ $totalAgents }} <span class="text-success text-sm"></span></h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="theme-avtar bg-primary">
                                <i class="fas fa-users bg-primary text-white"></i>
                            </div>
                            <p class="text-muted text-sm "></p>
                            <h6 class="mt-4 mb-4">{{ __('Active Agents') }}</h6>
                            <h3 class="mb-0">{{ $activeAgents }} <span class="text-success text-sm"></span></h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="theme-avtar bg-danger">
                                <i class="fas fa-users bg-danger text-white"></i>
                            </div>
                            <p class="text-muted text-sm"></p>
                            <h6 class="mt-4 mb-4">{{ __('Inactive Agents') }}</h6>
                            <h3 class="mb-0">{{ $inactiveAgents }} <span class="text-success text-sm"></span></h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="theme-avtar bg-secondary">
                                <i class="fas fa-cart-plus bg-secondary text-white"></i>
                            </div>
                            <p class="text-muted text-sm"></p>
                            <h6 class="mt-4 mb-4">{{ __('Total Programs') }}</h6>
                            <h3 class="mb-0">{{ $totalPrograms }} <span class="text-success text-sm"></span></h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="theme-avtar bg-warning">
                                <i class="fas fa-bullhorn bg-warning text-white"></i>
                            </div>
                            <p class="text-muted text-sm"></p>
                            <h6 class="mt-4 mb-4">{{ __('Total Orders') }}</h6>
                            <h3 class="mb-0">{{ $totalSalesOrders }} <span class="text-success text-sm"></span></h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="theme-avtar bg-success">
                                <i class="fas fa-shipping-fast bg-success text-white"></i>
                            </div>
                            <p class="text-muted text-sm"></p>
                            <h6 class="mt-4 mb-4">{{ __(' Delivered Orders') }}</h6>
                            <h3 class="mb-0">{{ \Modules\SalesAgent\Entities\SalesAgent::totalOrderDelivered() }} <span class="text-success text-sm"></span></h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-12">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table mb-0 pc-dt-simple" id="assets">
                                    <thead>
                                        <tr>
                                            <th>{{ __('name') }}</th>
                                            <th>{{ __('Contact') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Total Orders') }}</th>
                                            <th>{{ __('Total Value') }}</th>
                                            <th>{{ __('Delivered Orders') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($salesAgents as $k => $Agent)
                                    <tr class="font-style">
                                        <td>{{ $Agent['name'] }}</td>
                                        <td>{{ $Agent['contact'] }}</td>
                                        <td>{{ $Agent['email'] }}</td>
                                        <td>{{ \Modules\SalesAgent\Entities\SalesAgent::totalOrder($Agent->id) }}</td>
                                        <td>{{ \Modules\SalesAgent\Entities\SalesAgent::totalOrderValue($Agent->id) }}</td>
                                        <td>{{ \Modules\SalesAgent\Entities\SalesAgent::totalOrderDelivered($Agent->id) }}</td>
    
                                        
                                    </tr>
                                @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(\Auth::user()->type == 'salesagent')    
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-primary">
                                        <i class="fas fa-cart-plus bg-primary text-white"></i>
                                    </div>
                                    <p class="text-muted text-sm"></p>
                                    <h6 class="mt-4 mb-4">{{ __('Programs Participated') }}</h6>
                                    <h3 class="mb-0">{{ \Modules\SalesAgent\Entities\Program::getProgramsBySalesAgentId()->count() }} <span class="text-success text-sm"></span></h3>
                                </div>
                            </div>
                        </div>
        
                        <div class="col-xl-6 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-info">
                                        <i class="fas fa-tag bg-info text-white"></i>
                                    </div>
                                    <p class="text-muted text-sm "></p>
                                    <h6 class="mt-4 mb-4">{{ __('Total Items') }}</h6>
                                    <h3 class="mb-0">{{ \Modules\SalesAgent\Entities\SalesAgent::getAllProgramItems() }} <span class="text-success text-sm"></span></h3>
                                </div>
                            </div>
                        </div>
        
                        <div class="col-xl-6 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-danger">
                                        <i class="fas fa-shipping-fast bg-danger text-white"></i>
                                    </div>
                                    <p class="text-muted text-sm"></p>
                                    <h6 class="mt-4 mb-4">{{ __('Total Purchase Orders') }}</h6>
                                    <h3 class="mb-0">{{ \Modules\SalesAgent\Entities\SalesAgent::totalOrder() }} <span class="text-success text-sm"></span></h3>
                                </div>
                            </div>
                        </div>
        
                        <div class="col-xl-6 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-warning">
                                        <i class="fas fa-dollar-sign bg-warning text-white"></i>
                                    </div>
                                    <p class="text-muted text-sm"></p>
                                    <h6 class="mt-4 mb-4">{{ __('Total Purchase Orders value') }}</h6>
                                    <h3 class="mb-0">{{ currency_format_with_sym(\Modules\SalesAgent\Entities\SalesAgent::totalOrderValue()) }} <span class="text-success text-sm"></span></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6">
                    <div class="card">
                        <div class="card-header ">
                            <h5>{{__('Purchase Orders By Delivery Status')}}</h5>
                        </div>
                        <div class="card-body p-2">
                            <div id="purchase_orders" data-color="primary"  data-height="230"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
@endsection




