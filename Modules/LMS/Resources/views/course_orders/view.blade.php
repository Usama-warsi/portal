@extends('layouts.main')
@section('page-title')
    {{__('Course Order')}}
@endsection
@section('title')
    {{__('Course Orders')}}
@endsection
@section('page-breadcrumb')
    {{ __('Order') }},
    {{ __('show') }}
@endsection
@section('page-action')
<div class="text-end align-items-end d-flex justify-content-end">
    <a href="#" id="{{env('APP_URL').'/'.$store->slug.'/order/'.$order_id}}" class="btn btn-sm btn-primary btn-icon m-1" onclick="copyToClipboard(this)" title="Copy link" data-bs-toggle="tooltip" data-original-title="{{__('Click to Copy Link')}}"><i class="ti ti-link text-white"></i></a>
</div>
@endsection

@section('content')

    <div class="mt-4">
        <div id="printableArea">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-fluid">
                        <div class="card-header ">
                            <h6 class="mb-0">{{__('Order')}} {{$courseorder->order_id}}</h6>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-4">{{__('Shipping Information')}}</h6>
                            <address class="mb-0 text-sm">
                                <dl class="row mt-4 align-items-center">
                                    <dt class="col-sm-3 h6 text-sm">{{__('Name')}}</dt>
                                    <dd class="col-sm-9 text-sm"> {{ !empty($student_data->name) ? $student_data->name : ''}}</dd>
                                    <dt class="col-sm-3 h6 text-sm">{{__('E-mail')}}</dt>
                                    <dd class="col-sm-9 text-sm">{{ !empty($student_data->email) ? $student_data->email : ''}}</dd>
                                </dl>
                            </address>
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
                                        @php
                                            $sub_tax = 0;
                                            $total = 0;
                                        @endphp
                                            @foreach($order_products as $key=>$product)

                                                <tr>
                                                    <td class="total">
                                                    <span class="h6 text-sm">
                                                        @if(isset($product->product_name))
                                                            {{$product->product_name}}
                                                        @else
                                                            {{$product->name}}
                                                        @endif
                                                    </span>
                                                        @php
                                                            $total_tax = 0
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        {{ currency_format_with_sym($product->price)}}
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
                            <h6 class="mb-0">{{__('Items from Order '). $courseorder->order_id}}</h6>
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
                                            <td>{{ currency_format_with_sym($sub_total)}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('Applied Coupon')}} :</th>
                                            <th>{{(!empty($courseorder->discount_price))?$courseorder->discount_price: currency_format_with_sym(0)}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('Total')}} :</th>
                                            <th>{{ currency_format_with_sym($grand_total) }}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('Payment Type')}} :</th>
                                            <th>{{ $courseorder['payment_type'] }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filesname').val();
        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).save();

        }
    </script>
    <script>
        $("#deliver_btn").on('click', '#delivered', function () {
            var status = $('#delivered').attr('data-value');
            var data = {
                delivered: status,
            }
            $.ajax({
                url: '{{ route('course_orders.update',$courseorder->id) }}',
                method: 'PUT',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    toastrs('success', data.success, 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            });
        });
    </script>
    <script>
        function myFunction() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            toastrs('Success', 'Link copied', 'success');
        }
    </script>
    <script>
        function copyToClipboard(element) {
            var copyText = element.id;
            document.addEventListener('copy', function (e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            toastrs('Success', 'Url copied to clipboard', 'success');
        }
    </script>
@endpush
