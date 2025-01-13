@extends('layouts.main')
@section('page-title')
    {{__('Course Order')}}
@endsection

@section('page-breadcrumb')
    {{ __('Course Order') }}
@endsection

@section('content')
    <div class="row">
        {{ Form::model($Course_orders_summary, ['route' => ['course_orders.update', $Course_orders_summary->id], 'method' => 'PUT']) }}
        <input type="hidden" name="action_type" id="action_type" value="edit">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="row" id="customer-box">
                                <div class="form-group col-md-6" >
                                    {{ Form::label('student_id', __('Student'), ['class' => 'form-label']) }}
                                    {{ Form::select('student_id', $students, null, ['class' => 'form-control student_id', 'id' => 'student_id','required' => 'required']) }}
                                    @if (empty($students->count()))
                                        <div class=" text-xs">
                                            {{ __('Please create Customer/Client first.') }}<a
                                                @if (module_is_active('Account')) href="{{ route('student.index') }}"  @else href="{{ route('student.index') }}" @endif><b>{{ __('Create Customer/Client') }}</b></a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('issue_date', __('Issue Date'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            {{ Form::date('issue_date', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Date']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('course_number', __('Course Number'), ['class' => 'form-label']) }}
                                        {{ Form::number('course_number', null, ['class' => 'form-control', 'required' => 'required']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="loader" class="card card-flush">
            <div class="card-body">
                <div class="row">
                    <img class="loader" src="{{ asset('public/images/loader.gif') }}" alt="">
                </div>
            </div>
        </div>
        <div class="col-12 section_div">

        </div>

        <div class="modal-footer">
            <input type="button" value="{{ __('Cancel') }}" onclick="location.href = '{{ route('course_orders.index') }}';"
                class="btn btn-light">
            <input type="submit" value="{{ __('Edit') }}" class="btn btn-primary">
        </div>
        {{ Form::close() }}
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('js/jquery-searchbox.js') }}"></script>


    <script>
        $(document).ready(function() {
            var student_id = $('#student_id').val();
            var action = $("#action_type").val();
            SectionGet(student_id,action);
        });
        $(document).on('change', "#student_id", function() {
            var student_id = $(this).val();
            var action = 'create';
            SectionGet(student_id,action);
        });

        function SectionGet(student_id,action) {

            $.ajax({
                type: 'post',
                url: "{{ route('course.section.type') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    student_id: student_id,
                    action: action,
                    order_id: {{$Course_orders_summary->id}},
                },
                beforeSend: function() {
                    $("#loader").removeClass('d-none');
                },
                success: function(response) {
                    if (response != false) {
                        $('.section_div').html(response.html);
                        $("#loader").addClass('d-none');
                        // for item SearchBox ( this function is  custom Js )
                        JsSearchBox();
                    } else {
                        $('.section_div').html('');
                        toastrs('Error', 'Something went wrong please try again !', 'error');
                    }
                },
            });
        }

        $(document).on('keyup change', '.discount', function() {
            var el = $(this).parent().parent().parent();
            var discount = $(this).val();
            if (discount.length <= 0) {
                discount = 0;
            }
            var price = $(el.find('.price')).val();
            var totalItemPrice = price - discount;


            var amount = (totalItemPrice);

            $(el.find('.amount')).html(parseFloat(amount));
            var totalItemPrice = 0;
            var priceInput = $('.price');
            for (var j = 0; j < priceInput.length; j++) {
                totalItemPrice += (parseFloat(priceInput[j].value));
            }

            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }

            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {
                if (itemDiscountPriceInput[k].value == '') {
                    itemDiscountPriceInput[k].value = parseFloat(0);
                }
                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
            }


            $('.subTotal').html(totalItemPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
            $('.total_amount').val((parseFloat(subTotal)).toFixed(2));
        })

        $(document).on('click', '[data-repeater-delete]', function ()
        {
            var el = $(this).parent().parent();
            var price = $(el.find('.price')).val();
            var discount = $(el.find('.discount')).val();
            var subtotal = $('.subTotal').html();
            var totalAmount = $('.totalAmount').html();
            var totalDiscount = $('.totalDiscount').html();
            var totalItemPrice = subtotal-price;
            var amount = price-discount;

            $('.subTotal').html(totalItemPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(totalAmount-amount)).toFixed(2));
            $('.totalDiscount').html((totalDiscount-discount).toFixed(2));
            $('.total_amount').val((parseFloat(totalAmount-amount)).toFixed(2));
        });
    </script>

@endpush
