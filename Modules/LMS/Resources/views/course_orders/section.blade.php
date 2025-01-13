<script>
    var selector = "body";
    if ($(selector + " .repeater").length) {
        var $repeater = $(selector + ' .repeater').repeater({
            initEmpty: false,
            defaultValues: {
                'status': 1
            },
            show: function() {
                $(this).slideDown();
                var file_uploads = $(this).find('input.multi');
                if (file_uploads.length) {
                    $(this).find('input.multi').MultiFile({
                        max: 3,
                        accept: 'png|jpg|jpeg',
                        max_size: 2048
                    });
                }
                JsSearchBox();


            },

            ready: function(setIndexes) {
                // $dragAndDrop.on('drop', setIndexes);
            },
            isFirstItemUndeletable: true
        });
    }
</script>
<script>
$(document).on('change', '.course', function() {
    var course = $(this).val();

        // Disable the selected option in other select fields
        $('.course').each(function() {
            $(this).find('option[value="' + course + '"]').prop('hidden ', true);
        });
    var el = $(this);
    $.ajax({
        url: '{{ route('getcourse.price') }}',
        type: 'POST',
        data: {
            "course": course,
            "_token": "{{ csrf_token() }}",
        },
        success: function(data) {
            $(el.parent().parent().find('.price')).val(data);
            $(el.parent().parent().find('.amount')).html(data);
            $(el.parent().parent().find('.discount')).val(0);
            var totalItemPrice = 0;
            var priceInput = $('.price');
            for (var j = 0; j < priceInput.length; j++) {
                totalItemPrice += parseFloat(priceInput[j].value);
            }
            $('.subTotal').html(totalItemPrice.toFixed(2));

            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
            }
            $('.totalAmount').html((parseFloat(totalItemPrice) - parseFloat(
                            totalItemDiscountPrice)).toFixed(2));

            $('.total_amount').val((parseFloat(totalItemPrice) - parseFloat(
                            totalItemDiscountPrice)).toFixed(2));

        }
    });
});

$(document).on('click', '[data-repeater-create]', function() {
    $('.course :selected').each(function() {
        var id = $(this).val();
        if (id) {
            $(".course option[value='" + id + "']").addClass('d-none');
        }
    });
});

</script>
@if ($action == 'edit')
    <script>
        $(document).ready(function() {

            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
                for (var i = 0; i < value.length; i++) {
                    var courseValue = value[i].course; // Assuming value[i].course contains the desired value
                    var tr = $('#sortable-table tbody').find('tr').filter(function() {
                         $(this).find('.course').val() == courseValue;
                    });
                    changeItem(courseValue,tr.find('.course'));

                    }
            }
        });
    </script>

    <script>
        function changeItem(courseValue,element) {
            var el = element;
            $.ajax({
                url: '{{ route('order.course',$course_orders_summary->id) }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'course_id': courseValue,
                },

                cache: false,
                success: function(data) {

                    if (data != null) {
                        $(el.parent().parent().find('.price')).val(data.price);
                        $(el.parent().parent().find('.discount')).val(data
                        .discount);

                    } else {
                        $(el.parent().parent().find('.price')).val(0);
                        $(el.parent().parent().find('.discount')).val(0);
                    }



                    $(".discount").trigger('change');
                }
            });
        }
    </script>
@endif
@php
    $company_settings = getCompanyAllSetting();
@endphp
<h5 class="h4 d-inline-block font-weight-400 mb-4">{{ __('Course Summary') }}</h5>
    <div class="card repeater" @if ($action == 'edit') data-value='{!! $course_orders_summary->course !!}' @endif>
        <div class="item-section py-4">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-12 d-flex align-items-center justify-content-md-end px-5">
                    <a href="#" data-repeater-create="" class="btn btn-primary mr-2" data-toggle="modal"
                        data-target="#add-bank">
                        <i class="ti ti-plus"></i> {{ __('Add item') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body table-border-style mt-2">
            <div class="table-responsive">
                <table class="table mb-0 table-custom-style" data-repeater-list="items" id="sortable-table">
                    <thead>
                        <tr>
                            <th>{{ __('Courses') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Discount') }}</th>
                            <th></th>
                            <th class="text-end">{{ __('Amount') }} (%)</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody class="ui-sortable" data-repeater-item>
                        <tr>
                            <td width="25%" class="form-group pt-0">
                                {{ Form::select('product_id', $course, null, ['class' => 'form-control course ', 'required' => 'required','placeholder' => __('Select Course')]) }}

                            </td>
                            <td>
                                <div class="form-group price-input input-group search-form">
                                    {{ Form::text('price', '', ['class' => 'form-control price', 'required' => 'required', 'placeholder' => __('Price'), 'required' => 'required','readonly'=>'readonly']) }}
                                    <span
                                        class="input-group-text bg-transparent">{{ isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="form-group price-input input-group search-form">
                                    {{ Form::text('discount', '', ['class' => 'form-control discount', 'required' => 'required', 'placeholder' => __('Discount')]) }}
                                    <span
                                        class="input-group-text bg-transparent">{{ isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '' }}</span>
                                </div>
                            </td>

                            <td></td>
                            <td class="text-end amount">0.00</td>
                            <td>
                                <a href="#" class="bs-pass-para repeater-action-btn " data-repeater-delete>
                                    <div class="repeater-action-btn action-btn bg-danger ms-2">
                                        <i class="ti ti-trash text-white text-white"></i>
                                    </div>
                                </a>

                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td></td>
                            <td><strong>{{ __('Sub Total') }}
                                    ({{ isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '' }})</strong>
                            </td>
                            <td class="text-end subTotal">0.00</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td></td>
                            <td><strong>{{ __('Discount') }}
                                    ({{ isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '' }})</strong>
                            </td>
                            <td class="text-end totalDiscount">0.00</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td></td>
                            {{ Form::hidden('total_amount', null, ['class' => 'form-control total_amount']) }}
                            <td class="blue-text"><strong>{{ __('Total Amount') }}
                                    ({{ isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '' }})</strong></td>
                            <td class="text-end totalAmount blue-text">0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


