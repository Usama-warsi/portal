@extends('layouts.main')

@php
        $company_settings       = getCompanyAllSetting();
        $defult_currancy_symbol = isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '';
@endphp

@section('page-title')
    {{__('Sales Agent')}}
@endsection

@section('page-breadcrumb')
    {{ __('Purchase')}} ,{{ __('Add Purchase Order')}}
@endsection

@section('content')
<div class="row">
    {{Form::open(array('url'=>'salesagent/purchase/store','method'=>'post','class'=>'w-100'))}}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                {{Form::label('order_name',__('Order Name'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    <span><i class="ti ti-address-card"></i></span>
                                    {{Form::text('order_name',null ,array('class'=>'form-control','required'=>'required'))}}
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('order_number',__('Order Number'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    <span><i class="ti ti-address-card"></i></span>
                                    {{Form::text('order_number', $purchaseOrderNumber ,array('class'=>'form-control','required'=>'required','disabled'=>'disabled' ))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('order_date', __('Order date'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::date('order_date',date('Y-m-d'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Date', 'min' => date('Y-m-d')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('delivery_date', __('Delivery Date'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::date('delivery_date',date('Y-m-d'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Date']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div class="col-md-12 px-3 pb-4">
                            <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                                <a  class="btn btn-md text-light bg-primary newfield mr-1" id="add-field-btn"
                                    title="{{ __('Add New Field') }}">
                                    <i class="ti ti-plus"></i>{{ __('Add item') }}
                                </a>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="col-2">{{ __('Programs') }}</th>
                                    <th class="col-3">{{ __('Items') }}</th>
                                    <th class="col-1">{{ __('Quantity') }}</th>
                                    <th class="col-1">{{ __('Price') }}</th>
                                    <th class="col-auto">{{ __('Discount') }}</th>
                                    <th class="col-auto">{{__('Tax')}} (%)</th>
                                    <th class="col-auto">{{__('Amount')}} <br><small class="text-danger font-weight-bold">{{__('after discount & tax')}}</small></th>
                                    <th class="col-auto text-end"></th>
                                </tr>
                            </thead>
                            <tbody class="add_list repeater-container" id="repeater-container">
                                @for ($i = 0; $i <= 0; $i++)
                                    <tr class="form-group-container" data-id="form-group-container{{ $i }}">
                                        <td class="col-2 form-group price-input search-form">
                                            <div class="btn-box">
                                                {{ Form::select('order_details[' . $i . '][program_id]', $programs, null, ['class' => 'form-control program_id ', 'required' => 'required', 'placeholder' => 'Select program...']) }}
                                            </div>
                                        </td>
                                        <td class="col-2 form-group price-input search-form">
                                            <div class="btn-box emp_div">
                                                {{ Form::select('order_details[' . $i . '][item]', [], null, ['class' => 'sub form-control select item_id item js-searchBox ', 'data-url' => 'route(salesagent.program.product)', 'id' => 'options_id_' . $i, 'required' => 'required', 'placeholder' => 'Select item...']) }}
                                            </div>
                                        </td>
                                        
                                        <td class="col-auto form-group price-input search-form">
                                            {{ Form::text('order_details[' . $i . '][quantity]','', array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Quantity'),'required'=>'required')) }}
                                        </td>
                                        <td class="col-auto form-group price-input search-form">
                                                {{ Form::text('order_details[' . $i . '][price]','', array('class' => 'form-control price','required'=>'required','placeholder'=>__('Price'),'required'=>'required')) }}
                                        </td>
                                        <td class="col-auto form-group price-input search-form">
                                            <div class="input-group">
                                                {{ Form::text('order_details[' . $i . '][discount]','', array('class' => 'form-control discount','required'=>'required','disabled'=>'disabled','placeholder'=>__('Discount'))) }}
                                                {{ Form::hidden('order_details[' . $i . '][discountHidden]','', array('class' => 'form-control discountHidden')) }}
                                                {{ Form::hidden('order_details[' . $i . '][discountFromAmount]','', array('class' => 'form-control discountFromAmount')) }}
                                                {{ Form::hidden('order_details[' . $i . '][discountToAmount]','', array('class' => 'form-control discountToAmount')) }}
                                                {{ Form::hidden('order_details[' . $i . '][discountAfterRange]','', array('class' => 'form-control discountAfterRange')) }}
                                                <span class="input-group-text bg-transparent discountType">{{ $defult_currancy_symbol }}</span>
                                            </div> 
                                        </td>
                                        <td>
                                        <div class="col-auto form-group">
                                                <div class="input-group">
                                                    <div class="taxes"></div>
                                                    {{ Form::hidden('order_details[' . $i . '][tax]','', array('class' => 'form-control tax')) }}
                                                    {{ Form::hidden('order_details[' . $i . '][itemTaxPrice]','', array('class' => 'form-control itemTaxPrice')) }}
                                                    {{ Form::hidden('order_details[' . $i . '][itemTaxRate]','', array('class' => 'form-control itemTaxRate')) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="col-auto text-end amount">
                                            0.00
                                        </td>
                                        <td class="col-auto form-group col-md-1 ml-auto text-end">
                                            <button type="button" class="btn btn-sm btn-danger disabled delete-icon">
                                                <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip" title="Delete"></i>
                                            </button>
                                        </td>
                                        
                                    </tr>
                                    <tr class="order_details_description" data-id="order_details_description{{ $i }}">
                                        <td colspan="2">
                                            <div class="form-group"><textarea class="form-control order_description" rows="2" placeholder="Description"  name='order_details[{{ $i }}][description]' cols="50"></textarea></div>
                                        </td>
                                        <td colspan="5"></td>
                                    </tr>

                                @endfor
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{__('Sub Total')}} ({{$defult_currancy_symbol}})</strong></td>
                                    <td class="text-end subTotal">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{__('Discount')}} ({{$defult_currancy_symbol}})</strong></td>
                                    <td class="text-end totalDiscount">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{__('Tax')}} ({{$defult_currancy_symbol}})</strong></td>
                                    <td class="text-end totalTax">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td class="blue-text"><strong>{{__('Total Amount')}} ({{$defult_currancy_symbol}})</strong></td>
                                    <td class="blue-text text-end totalAmount"></td>
                                    {{ Form::hidden('totalAmount','', array('class' => 'form-control totalAmount')) }}

                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" onclick="location.href = '{{route('programs.index')}}';" class="btn btn-light">
            <input type="submit" value="{{__('Create')}}" class="btn  btn-primary mx-2">
        </div>
    {{ Form::close() }}
</div>
@endsection

@push('scripts')

<script>
    
</script>

<script>

        $(document).on('change', '.item', function () {
            items($(this));
        });

        function items(data)
        {
            var el = data;
            var program_id = $(el.parent().parent().parent().find('.program_id')).val();
            var iteams_id = data.val();
            var url = data.data('url');
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },

                data: {
                    'product_id': iteams_id,
                    'program_id': program_id
                },
                cache: false,
                success: function (data) {
                    var item = JSON.parse(data);

                        console.log(item);
                    $(el.parent().parent().parent().find('.quantity')).val(1);
                    if(item.product != null)
                    {
                        var id = el.attr('id');
                        var match = id.match(/options_id_(\d+)/);
                        if (match && match[1]) {
                            var valueAfterOptionsId = match[1];
                        } 

                        $(el.parent().parent().parent().find('.price')).val(item.product.purchase_price);
                        $(el.parent().parent().parent().parent().parent().find(`[name="order_details[${valueAfterOptionsId}][description]"]`)).val(item.product.description);
                    }else{
                        $(el.parent().parent().parent().find('.price')).val(0)
                        $(el.parent().parent().parent().parent().parent().find(`[name="order_details[${valueAfterOptionsId}][description]"]`)).val('');
                    }

                    var taxes = '';
                    var tax = [];

                    var totalItemTaxRate = 0;

                    if (item.taxes == 0) {
                        taxes += '-';

                    } else {
                        for (var i = 0; i < item.taxes.length; i++) {
                            taxes += '<span class="badge bg-primary p-2 px-3 rounded mt-1 mr-1 product_tax">' + item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span>';
                            tax.push(item.taxes[i].id);
                            totalItemTaxRate += parseFloat(item.taxes[i].rate);
                        }
                    }

                    if (item.program_discount_type === 'percentage') {
                        var itemDiscount = ((item.product.purchase_price * item.discount ) / 100);
                    } else if (item.program_discount_type === 'fixed') {
                        var itemDiscount = item.discount;
                    }

                    var itemTaxPrice = 0;
                    if(item.product != null)
                    {
                        var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * ((item.product.purchase_price * 1) - itemDiscount));
                    }
                    
                    $(el.parent().parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                    $(el.parent().parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                    $(el.parent().parent().parent().find('.taxes')).html(taxes);
                    $(el.parent().parent().parent().find('.tax')).val(tax);
                    $(el.parent().parent().parent().find('.discount')).val(item.discount);
                    $(el.parent().parent().parent().find('.discountHidden')).val(itemDiscount);
                    $(el.parent().parent().parent().find('.discountFromAmount')).val(item.discount_range.from_amount);
                    $(el.parent().parent().parent().find('.discountToAmount')).val(item.discount_range.to_amount);
                    $(el.parent().parent().parent().find('.discountAfterRange')).val(item.discount_range.discount);
                    
                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        
                    }

                    var totalItemPrice = 0;
                    var inputs_quantity = $(".quantity");

                    var priceInput = $('.price');
                    for (var j = 0; j < priceInput.length; j++) {
                        totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
                    }


                    var totalItemDiscountPrice = 0;
                    var itemDiscountPriceInput = $('.discountHidden');

                    for (var k = 0; k < itemDiscountPriceInput.length; k++) {
                        totalItemDiscountPrice += (parseFloat(itemDiscountPriceInput[k].value * parseFloat(inputs_quantity[k].value)) );
                    }

                    var totalItemTaxPrice = 0;
                    var itemTaxPriceInput = $('.itemTaxPrice');
                    for (var j = 0; j < itemTaxPriceInput.length; j++) {
                        totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                        var itemdiscount = $(el.parent().parent().parent().find('.discountHidden')).val();
                        $(el.parent().parent().parent().find('.amount')).html((parseFloat(item.totalAmount)- parseFloat(itemdiscount)) + parseFloat(itemTaxPriceInput[j].value));
                    }

                    $('.subTotal').html(totalItemPrice.toFixed(2));
                    $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                    $('.totalAmount').html(((parseFloat(totalItemPrice) - parseFloat(totalItemDiscountPrice)) + parseFloat(totalItemTaxPrice)).toFixed(2));
                    $('.totalAmount').val(((parseFloat(totalItemPrice) - parseFloat(totalItemDiscountPrice)) + parseFloat(totalItemTaxPrice)).toFixed(2));
                    $('.totalDiscount').html((parseFloat(totalItemDiscountPrice)).toFixed(2));
                },
            });
        }

        function calculate_Discount( price , discount , type )
        {
            if (type === 'percentage') 
            {
                var discount = ((price * discount ) / 100);

            } 
            else if (type === 'fixed') 
            {
                var discount = discount;
            }

            return discount ;
        }

        function calculate_Total()
        {
            var inputs_quantity = $(".quantity");

            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discountHidden');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {
                totalItemDiscountPrice += (parseFloat(itemDiscountPriceInput[k].value * parseFloat(inputs_quantity[k].value)) );
            }


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var totalItemPrice = 0;
            var inputs_quantity = $(".quantity");

            var priceInput = $('.price');
            for (var j = 0; j < priceInput.length; j++) {
                totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
            }


            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }


            $('.subTotal').html(totalItemPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
            $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
            $('.totalDiscount').html((parseFloat(totalItemDiscountPrice)).toFixed(2));

        }

        $(document).on('change', '.program_id', function() {
            var $this = $(this); // Store a reference to $(this)

            var id = $this.val();

            var dataId = $this.closest('.form-group-container').data('id');
            var match = dataId.match(/\d+/);

            if (match) {
                var index = parseInt(match[0], 10);
            }

            $.ajax({
                url: '{{ route('get.program.items') }}',
                type: 'POST',
                data: {
                    "program_id": id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    var slect_ids = 'options_id_' + index;
                    // Use the correct selector to target emp_div
                    var slect_div = $this.closest('.form-group-container').find('.emp_div');
                    $(slect_div).empty();

                    var product_select = `<select id="${slect_ids}" class="form-control product_id item js-searchBox" data-url="{{route('salesagent.program.product')}}" name="order_details[${index}][item]" data-placeholder="{{ __('Select program...') }}" required="required"></select>`;

                    $(slect_div).html(product_select);

                    // Use the correct selector to target the option
                    var option = $this.closest('.form-group-container').find('.emp_div').find('.item');
                    $(option).append('<option>Select Item...</option>');

                    $.each(data.productServices, function(i, item) {
                        $(option).append('<option value="' + i + '">' + item + '</option>');
                    });

                    if (data.program_discount_type === 'percentage') {
                        // If Percentage is selected, update the span with %
                        var $discountType = $($this.parent().parent().parent().find('.discountType'));
                        $discountType.text('%');
                        $discountType.attr('data-id', 'percentage');

                    } else if (data.program_discount_type === 'fixed') {
                        // If Fixed is selected, update the span with $
                        var $discountType = $($this.parent().parent().parent().find('.discountType'));
                        $discountType.text('$');
                        $discountType.attr('data-id', 'fixed');
                    }

                    $($this.parent().parent().parent().find('.quantity')).val('');
                    $($this.parent().parent().parent().find('.price')).val('');
                    $($this.parent().parent().parent().find('.discount')).val('');
                    $($this.parent().parent().parent().find('.discountHidden')).val('');
                    $($this.parent().parent().parent().find('.discountFromAmount')).val('');
                    $($this.parent().parent().parent().find('.discountToAmount')).val('');
                    $($this.parent().parent().parent().find('.discountAfterRange')).val('');
                    $($this.parent().parent().parent().find('.itemTaxPrice')).val('');
                    $($this.parent().parent().parent().find('.amount')).html('0');
                    $($this.parent().parent().parent().find('.taxes')).html('');
                    $($this.parent().parent().parent().find('.tax')).val('');
                    $($this.parent().parent().parent().find('.itemTaxRate')).val('');
                    calculate_Total();
                }   
            });
        }); 

        $(document).on('keyup', '.quantity', function () {
            var quntityTotalTaxPrice = 0;

            var el = $(this).parent().parent();
            var quantity = $(this).val();
            var price = $(el.find('.price')).val();
            var discount = $(el.find('.discount')).val();
            var discountType = $(el.find('.discountType')).data('id');
            var discount =  calculate_Discount(price , discount , discountType );

            if(discount.length <= 0)
            {
                discount = 0 ;
            }

            var totalItemPrice = (quantity * price) - (quantity * discount);
            var amount = (totalItemPrice);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));
            $(el.find('.discountHidden')).val(discount);
            calculate_Total();
        })

        $(document).on('keyup change', '.price', function () {
            var el = $(this).parent().parent();
            var price = parseFloat($(this).val());
            var quantity = $(el.find('.quantity')).val();
            var fromAmount = parseFloat($(el.find('.discountFromAmount')).val());
            var toAmount = parseFloat($(el.find('.discountToAmount')).val());


            if (!isNaN(price) && !isNaN(fromAmount) && !isNaN(toAmount) && price >= fromAmount && price <= toAmount)
            {
                var discount = $(el.find('.discountAfterRange')).val();
                $(el.find('.discount')).val(discount);

            }else{
                
                $(el.find('.discount')).val('');
            }

            var discount = $(el.find('.discount')).val();

            var discountType = $(el.find('.discountType')).data('id');
            var discount =  calculate_Discount(price , discount , discountType );
            
            if(discount.length <= 0)
            {
                discount = 0 ;
            }
            var totalItemPrice = (quantity * price)- (discount * quantity);

            var amount = (totalItemPrice);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));
            $(el.find('.discountHidden')).val(discount);
            calculate_Total();
        })

        // $(document).on('keyup change', '.discount', function () {
        //     var el = $(this).parent().parent().parent();
        //     var discount = $(this).val();

        //     var price = $(el.find('.price')).val();
        //     var quantity = $(el.find('.quantity')).val();

        //     var discountType = $(el.find('.discountType')).data('id');
        //     var discount =  calculate_Discount(price , discount , discountType );
            
        //     if(discount.length <= 0)
        //     {
        //         discount = 0 ;
        //     }

        //     var totalItemPrice = (quantity * price) - (quantity * discount);
        //     var amount = (totalItemPrice);

        //     var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
        //     var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
        //     $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

        //     $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));
        //     $(el.find('.discountHidden')).val(discount);

        //     var totalItemTaxPrice = 0;
        //     var itemTaxPriceInput = $('.itemTaxPrice');
        //     for (var j = 0; j < itemTaxPriceInput.length; j++) {
        //         totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
        //     }


        //     var totalItemPrice = 0;
        //     var inputs_quantity = $(".quantity");

        //     var priceInput = $('.price');
        //     for (var j = 0; j < priceInput.length; j++) {
        //         totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
        //     }

        //     var inputs = $(".amount");

        //     var subTotal = 0;
        //     for (var i = 0; i < inputs.length; i++) {
        //         subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
        //     }


        //     var totalItemDiscountPrice = 0;
        //     var itemDiscountPriceInput = $('.discountHidden');

        //     for (var k = 0; k < itemDiscountPriceInput.length; k++) {

        //         totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
        //     }


        //     $('.subTotal').html(totalItemPrice.toFixed(2));
        //     $('.totalTax').html(totalItemTaxPrice.toFixed(2));
        //     $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
        //     $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));

        // })
</script>

<script>
    $(document).ready(function() {
        let plusFieldIndex = 0;

        // Function to add a new field container
        function addNewField(index) {
            plusFieldIndex++;
            const newContainer = $("#repeater-container").find(".form-group-container").first().clone();

            newContainer.attr("data-id", "form-group-container" + plusFieldIndex);

            // Update the name attributes of select inputs with the correct index
            newContainer.find('.program_id').attr('name', 'order_details[' + plusFieldIndex + '][program_id]').val('');
            newContainer.find('.item_id').attr('name', 'order_details[' + plusFieldIndex + '][item]').val('');
            newContainer.find('.quantity').attr('name', 'order_details[' + plusFieldIndex + '][quantity]').val('');
            newContainer.find('.price').attr('name', 'order_details[' + plusFieldIndex + '][price]').val('');
            newContainer.find('.discount').attr('name', 'order_details[' + plusFieldIndex + '][discount]').val('');
            newContainer.find('.discountHidden').attr('name', 'order_details[' + plusFieldIndex + '][discountHidden]').val('');
            newContainer.find('.tax').attr('name', 'order_details[' + plusFieldIndex + '][tax]').val('');
            newContainer.find('.itemTaxPrice').attr('name', 'order_details[' + plusFieldIndex + '][itemTaxPrice]').val('');
            newContainer.find('.itemTaxRate').attr('name', 'order_details[' + plusFieldIndex + '][itemTaxRate]').val('');
            newContainer.find('.taxes').html('');
            newContainer.find('.amount').html('0.00');

            newContainer.find('.delete-icon').removeClass('disabled');
            newContainer.find('.delete-icon').removeClass('d-none');

            $("#repeater-container").append(newContainer);
            $("#repeater-container").append('<tr class="order_details_description" data-id="order_details_description' + plusFieldIndex + '" ><td colspan="2"><div class="form-group"><textarea class="form-control pro_description" rows="2" placeholder="Description" name="order_details[' + plusFieldIndex + '][description]" cols="50"></textarea></div><td colspan="5"></td></tr>');

            newContainer.find('.program_id').trigger('change');

        }

        // Add a new field when the button is clicked
        $("#add-field-btn").on("click", function() {
            addNewField(plusFieldIndex);
        });

        // Remove a field container when the delete icon is clicked 
        $(document).on("click", ".delete-icon:not(.disabled)", function() {
            var container = $(this).closest('.form-group-container');
            var description = $(".order_details_description[data-id='order_details_description" + container.data('id').replace('form-group-container', '') + "']");
            
            if (container.attr("id") !== "form-group-container0") {
                container.remove();
                description.remove();
            }
            calculate_Total();
        });
    });
</script>

@endpush
