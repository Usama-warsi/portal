@extends('layouts.main')
@section('page-title')
    {{__('Coupon Detail')}}
@endsection
@section('title')
    {{__('Coupon Detail')}}
@endsection
@section('page-breadcrumb')
    {{ __('Coupon') }},
    {{$coursecoupon->code}}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">
                        <h4>{{$coursecoupon->code}}</h4>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead class="thead-light">
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="selection-datatable" rowspan="1" colspan="1" aria-label=" Coupon: activate to sort column ascending" style="width: 354px;"> Coupon</th>
                                    <th class="sorting" tabindex="0" aria-controls="selection-datatable" rowspan="1" colspan="1" aria-label=" User: activate to sort column ascending" style="width: 411px;"> User</th>
                                    <th class="sorting" tabindex="0" aria-controls="selection-datatable" rowspan="1" colspan="1" aria-label=" Date: activate to sort column ascending" style="width: 642px;"> Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($productCoupons as $userCoupon)
                                <tr role="row" class="odd">
                                    <td>{{ !empty($coursecoupon->name)?$coursecoupon->name:'' }}</td>
                                    <td>{{ !empty($userCoupon->name)?$userCoupon->name:'' }}</td>
                                    <td>{{ $userCoupon->created_at }}</td>
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
