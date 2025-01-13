@extends('layouts.main')
@section('page-title')
    {{__('Course Coupons')}}
@endsection
@section('page-breadcrumb')
    {{__('Course Coupons')}}
@endsection
@section('page-action')
<div>
    @permission('course coupon create')
        <div class="btn btn-sm btn-primary btn-icon ms-1">
            <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Add Coupon')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Add Coupon')}}" data-url="{{ route('course-coupon.create') }}"><i class="ti ti-plus text-white"></i></a>
        </div>
    @endpermission
</div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#code-generate', function () {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>

<script>
    $(document).on('change', '#product-coupon-store #enable_flat', function (e) {
    if ($(this).is(':checked')) {
        $('#product-coupon-store .flat_discount').show();
        $('#product-coupon-store .nonflat_discount').hide();
    } else {
        $('#product-coupon-store .flat_discount').hide();
        $('#product-coupon-store .nonflat_discount').show();
    }
});
</script>

@endpush
@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead class="thead-light">
                                <tr>
                                    <th> {{__('Name')}}</th>
                                    <th> {{__('Code')}}</th>
                                    <th> {{__('Discount')}}</th>
                                    <th> {{__('Limit')}}</th>
                                    <th> {{__('Used')}}</th>
                                    <th class="text-right"> {{__('Action')}}</th>
                                </tr>
                            </thead>
                            @if(count($productcoupons) > 0 && !empty($productcoupons))
                                <tbody>
                                    @foreach ($productcoupons as $coupon)
                                        @php
                                            $couponused = \Modules\LMS\Entities\CourseOrder::where('coupon',$coupon->id)->get()->count();
                                        @endphp
                                        <tr class="font-style">
                                            <td>{{ $coupon->name }}</td>
                                            <td>{{ $coupon->code }}</td>
                                            @if($coupon->enable_flat == 'off')
                                                <td>{{ $coupon->discount.'%'}}</td>
                                            @endif
                                            @if($coupon->enable_flat == 'on')
                                                <td>{{ $coupon->flat_discount.' '.('(Flat)')}}</td>
                                            @endif
                                            <td>{{ $coupon->limit }}</td>
                                            <td>{{ $couponused }}</td>
                                            <td class="text-right">
                                                @permission('course coupon show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('course-coupon.show',$coupon->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip"
                                                            title="{{ __('Details') }}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                                    </div>
                                                @endpermission

                                                @permission('course coupon edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-size="md" data-url="{{route('course-coupon.edit',[$coupon->id])}}" data-ajax-popup="true" data-title="{{__('Edit Coupon')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}">
                                                        <span class="text-white">  <i class="ti ti-pencil"></i> </span>
                                                        </a>
                                                    </div>
                                                @endpermission

                                                @permission('course coupon delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['course-coupon.destroy', $coupon->id]]) !!}
                                                            <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endpermission
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                <tbody>
                                    <tr>
                                        <td colspan="7">
                                            <div class="text-center">
                                                <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                                <h2>{{__('Opps')}}...</h2>
                                                <h6>{{__('No data Found')}}. </h6>
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
    </div>
@endsection
