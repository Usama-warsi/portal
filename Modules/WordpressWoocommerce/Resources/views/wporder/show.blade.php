@extends('layouts.main')

@section('page-title')
    {{__('Woocommerce Order')}}
@endsection

@section('page-breadcrumb')
{{ __('Order') }},
{{ __('show') }}
@endsection

@section('content')
    <div class="mt-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-fluid">
                    <div class="card-header ">
                        <h6 class="mb-0">{{__('Order')}} {{'#'.$wp_order['id']}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-4">{{__('Shipping Information')}}</h6>
                                <address class="mb-0 text-sm">
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-3 h6 text-sm">{{__('Name')}}</dt>
                                        <dd class="col-sm-9 text-sm"> {{ !empty($wp_order['shipping']['first_name']) ? $wp_order['shipping']['first_name'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('Company')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['shipping']['company']) ? $wp_order['shipping']['company'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('City')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['shipping']['city']) ? $wp_order['shipping']['city'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('Country')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['shipping']['country']) ? $wp_order['shipping']['country'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('Postal Code')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['shipping']['postcode']) ? $wp_order['shipping']['postcode'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('Phone')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['shipping']['phone']) ? $wp_order['shipping']['phone'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('state')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['shipping']['state']) ? $wp_order['shipping']['state'] : ''}}</dd>
                                    </dl>
                                </address>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-4">{{__('Billing Information')}}</h6>
                                <address class="mb-0 text-sm">
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-3 h6 text-sm">{{__('Name')}}</dt>
                                        <dd class="col-sm-9 text-sm"> {{ !empty($wp_order['billing']['first_name']) ? $wp_order['billing']['first_name'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('Company')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['billing']['company']) ? $wp_order['billing']['company'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('City')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['billing']['city']) ? $wp_order['billing']['city'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('Country')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['billing']['country']) ? $wp_order['billing']['country'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('Postal Code')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['billing']['postcode']) ? $wp_order['billing']['postcode'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('Phone')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['billing']['phone']) ? $wp_order['billing']['phone'] : ''}}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{__('state')}}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($wp_order['billing']['state']) ? $wp_order['billing']['state'] : ''}}</dd>
                                    </dl>
                                </address>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr class="border-top-0">
                                        <th>{{__('Item')}}</th>
                                        <th>{{__('Price')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wp_order['line_items'] as $key=>$product)
                                        <tr>
                                            <td class="total">
                                            <span class="h6 text-sm">
                                                    {{$product['name']}}
                                            </span>
                                            </td>
                                            <td>
                                                {{ currency_format_with_sym($product['total'])}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-fluid">
                    <div class="card-header border-0">
                        <h6 class="mb-0">{{__('Items from Order ').'#'.$wp_order['id']}}</h6>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{__('Description')}}</th>
                                        <th>{{__('Price')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{__('Grand Total')}} :</td>
                                        <td>{{ currency_format_with_sym($wp_order['total'])}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Discount')}} :</th>
                                        <th>{{(!empty($wp_order['discount_total']))?$wp_order['discount_total']: currency_format_with_sym(0)}}</th>
                                    </tr>
                                    <tr>
                                        <th>{{__('Total')}} :</th>
                                        <th>{{ currency_format_with_sym($wp_order['total']) }}</th>
                                    </tr>
                                    <tr>
                                        <th>{{__('Payment Type')}} :</th>
                                        <th>{{ $wp_order['payment_method'] }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
