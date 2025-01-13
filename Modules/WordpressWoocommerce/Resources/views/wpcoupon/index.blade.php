@extends('layouts.main')

@section('page-title')
    {{__('Manage Coupon')}}
@endsection

@section('page-breadcrumb')
   {{__('Coupon')}}
@endsection
@section('page-action')

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="wp_coupon">
                            <thead>
                                <tr>
                                    <th>{{__('Code')}}</th>
                                    <th>{{__('Discount')}}</th>
                                    <th>{{__('Coupon type')}}</th>
                                    <th>{{__('Limit')}}</th>
                                    <th>{{__('Used')}}</th>
                                    <th>{{__('Expiry date')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($wp_coupons as $wp_coupon)
                                    <tr>
                                        <td>{{ $wp_coupon['code'] }}</td>
                                        <td>{{ $wp_coupon['amount'] }}</td>
                                        <td>{{ str_replace('_', ' ', ucfirst($wp_coupon['discount_type'])) }}</td>
                                        <td>{{ $wp_coupon['usage_limit'] }}</td>
                                        <td>{{ $wp_coupon['usage_limit'] }}</td>
                                        <td>{{ company_date_formate($wp_coupon['date_expires']) }}</td>
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
