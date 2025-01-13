@extends('layouts.main')

@section('page-title')
    {{__('Manage Order')}}
@endsection

@section('page-breadcrumb')
   {{__('Order')}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="wp_order">
                            <thead>
                                <tr>
                                    <th>{{__('Order ID')}}</th>
                                    <th>{{__('Customer')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Total')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($wp_orders as $wp_order)
                                    <tr>
                                        @php
                                            $customer_id = $wp_order['customer_id'];
                                            $customer = \Modules\WordpressWoocommerce\Http\Controllers\WpOrderController::get_customer_details($customer_id);
                                        @endphp
                                        <td>
                                            @if(\Auth::user()->isAbleTo('woocommerce order show'))
                                                <a href="{{route('wp-order.show',$wp_order['id'])}}" class="btn btn-outline-primary">
                                                    <span class="btn-inner--text">{{'#'.$wp_order['id']}}</span>
                                                </a>
                                            @else
                                                <span class="btn-inner--text">{{$wp_order['id']}}</span>
                                            @endif
                                        </td>
                                        <td>{{ !empty($customer['first_name'])?$customer['first_name']:'-' }}</td>
                                        <td>{{ company_date_formate($wp_order['date_created']) }}</td>
                                        <td>{{ $wp_order['status'] }}</td>
                                        <td>{{ $wp_order['total'] }}</td>
                                        <td>
                                            <div>
                                                <div class="actions">
                                                    @permission('woocommerce order show')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="{{route('wp-order.show',$wp_order['id'])}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{ __('Details') }}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                                        </div>
                                                    @endpermission
                                                </div>
                                            </div>
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
@endsection
