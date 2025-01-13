@extends('layouts.main')

@php
        $company_settings       = getCompanyAllSetting();
        $defult_currancy_symbol         = isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '';
@endphp

@section('page-title')
    {{__('Sales Agent')}}
@endsection

@section('page-breadcrumb')
    {{ __('Sales Agent')}} ,{{ __('Program Create')}}
@endsection

@section('content')
<div class="row">
    {{Form::open(array('url'=>'programs','method'=>'post','class'=>'w-100'))}}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::label('name',__('Program Name'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    <span><i class="ti ti-address-card"></i></span>
                                    {{Form::text('name',null ,array('class'=>'form-control','required'=>'required'))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group month">
                                <div class="btn-box">
                                    {{ Form::label('from_date', __('Start Date'), ['class' => 'form-label']) }}
                                    {{ Form::date('from_date', null, ['class' => 'form-control', 'placeholder' => 'Select Start Date', 'min' => date('Y-m-d')]) }}

                                </div>           
                            </div>              
                        </div>
                        <div class="col-md-3">
                            <div class="form-group month">
                                <div class="btn-box">
                                    {{ Form::label('to_date', __('End Date'), ['class' => 'form-label']) }}
                                    {{ Form::date('to_date', null, ['class' => 'form-control form-control', 'placeholder' => 'Select End Date','min' => date('Y-m-d')]) }}
                                </div>           
                            </div>              
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('description', __('Details'),['class'=>'form-label']) }}
                            {{ Form::textarea('description', null, array('class' => 'form-control','rows'=>4,'required'=>'required','id'=>"description",'placeholder'=> __('Add Details'))) }}
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('discount_type', __('Discount type'), array('class' => 'form-label')) }}
                                <div class="row m-3 ">
                                    <div class="col form-check form-switch custom-switch-v1">
                                        {{ Form::radio('discount_type', 'percentage', true, ['id' => 'percentage_discount','class' => 'form-check-input input-primary']) }}
                                        {{ Form::label('percentage_discount', __('Percentage'), ['class' => 'form-control-label']) }}
                                    </div>
                                    <div class="col form-check form-switch custom-switch-v1">
                                        {{ Form::radio('discount_type', 'fixed', null, ['id' => 'fixed_discount','class' => 'form-check-input input-primary']) }}
                                        {{ Form::label('fixed_discount', __('Fixed'), ['class' => 'form-control-label']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('sales_agents_applicable', __('Approved Program Participants'),['class'=>'form-label']) }}
                            <select class=" multi-select choices" id="sales_agents_applicable" name="sales_agents_applicable[]"  multiple="multiple" data-placeholder="{{ __('Select Sales Agents ...') }}">
                                @foreach($salesAgents as $user)
                                        <option value="{{$user->id}}">{{$user->name}} - {{$user->email}}</option>
                                @endforeach
                            </select>
                            <p class="text-danger d-none" id="sales_agents_applicable_validation">{{__('Sales Agent field is required.')}}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('sales_agents_view', __('Can View & Request to Join Program'),['class'=>'form-label']) }}
                            <select class="multi-select choices" id="sales_agents_view" name="sales_agents_view[]"  multiple="multiple" data-placeholder="{{ __('Select Sales Agents ...') }}">
                                @foreach($salesAgents as $user)
                                        <option value="{{$user->id}}">{{$user->name}} - {{$user->email}}</option>
                                @endforeach
                            </select>
                            <p class="text-danger d-none" id="sales_agents_view_validation">{{__('Sales Agent field is required.')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Items')}}</h5>
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div class="col-md-12">
                            <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                                <a  class="btn btn-sm text-light bg-primary newfield" id="add-field-btn"
                                    title="{{ __('Add New Field') }}">
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="border py-2 my-2 fw-bold" style="background: #f1f1f1">
                                <div class="row" >
                                    <div class="col-2 m-1">{{ __('Item Type') }}</div>
                                    <div class="col-3">{{__('Items')}}</div>
                                    <div class="col-2">{{__('From amount')}} <sup class="text-danger">*</sup></div>
                                    <div class="col-2">{{__('To amount')}} <sup class="text-danger">*</sup></div>
                                    <div class="col-2">{{__('Discount')}}</div>
                                    <div class="col-auto"></div>
                                </div>
                            </div>
                            <div class="add_list repeater-container px-2" id="repeater-container">
                                    @for ($i = 0; $i <= 0; $i++)
                                        <div class="row filter-css mt-2 form-group-container" data-id="form-group-container{{ $i }}">
                                            <div class="col-2 form-group price-input  search-form">
                                                <div class="btn-box">
                                                    {{ Form::select('program_details[' . $i . '][product_type]', $product_type, null, ['class' => 'form-control product_type ', 'required' => 'required']) }}
                                                </div>
                                            </div>
                                            <div class="col-3 form-group price-input  search-form">
                                                <div class="btn-box emp_div">
                                                    {{ Form::select('program_details[' . $i . '][items][]', $product_services, null, ['class' => 'sub form-control select product_id item  js-searchBox multi-select', 'id' => 'options_id_' . $i, 'multiple' => ' ', 'required' => 'required', 'placeholder' => 'Select option']) }}
                                                </div>
                                            </div>
                                            <div class="col-2 form-group price-input  search-form">
                                                <div class="input-group">
                                                    {{ Form::text('program_details[' . $i . '][from_amount]','', array('class' => 'form-control from_amount','required'=>'required','placeholder'=>__('From Amount'),'required'=>'required')) }}
                                                    <span class="input-group-text bg-transparent">{{ $defult_currancy_symbol }}</span>
                                                </div> 
                                            </div>
                                            <div class="col-2 form-group price-input search-form">
                                                <div class="input-group">
                                                    {{ Form::text('program_details[' . $i . '][to_amount]','', array('class' => 'form-control to_amount','required'=>'required','placeholder'=>__('To Amount'),'required'=>'required')) }}
                                                    <span class="input-group-text bg-transparent">{{ $defult_currancy_symbol }}</span>
                                                </div> 
                                            </div>
                                            <div class="col-2 form-group price-input  search-form">
                                                <div class="input-group">
                                                    {{ Form::text('program_details[' . $i . '][discount]','', array('class' => 'form-control discount','required'=>'required','placeholder'=>__('Discount'))) }}
                                                    <span class="input-group-text bg-transparent discountType">{{ '%' }}</span>
                                                </div> 
                                            </div>
                                            <div class="col-auto form-group col-md-1 ml-auto text-end">
                                                <button type="button" class="btn btn-sm btn-danger  disabled delete-icon">
                                                    <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip" title="Delete"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endfor
                            </div>
                        </div>
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

<script src="{{asset('js/jquery-ui.min.js')}}"></script>
<script src="{{asset('js/jquery.repeater.min.js')}}"></script>
<script src="{{ asset('js/jquery-searchbox.js') }}"></script>


<script>
    $(function(){
        $("#submit").click(function() {
            var agent_a =  $("#sales_agents_applicable_validation option:selected").length;
            var agent_v =  $("#sales_agents_view_validation option:selected").length;

            if(agent_a == 0){
            $('#user_validation').removeClass('d-none')
                return false;
            }else{
            $('#user_validation').addClass('d-none')
            }

            if(agent_v == 0){
            $('#user_validation').removeClass('d-none')
                return false;
            }else{
            $('#user_validation').addClass('d-none')
            }
        });
    });

    $(document).ready(function() {
        // Listen for click events on the radio inputs
        $('input[name="discount_type"]').click(function() {
            // Check which radio button is selected
            if ($(this).val() === 'percentage') {
                // If Percentage is selected, update the span with %
                $('.discountType').text('%');
            } else if ($(this).val() === 'fixed') {
                // If Fixed is selected, update the span with $
                $('.discountType').text('$');
            }
        });
    });
</script>

<script>

    $(document).ready(function() {
        setTimeout(() => {
            $('.product_type').trigger('change');
        }, 100);
    });

    $(document).on('change', '.product_type', function() {
        var $this = $(this); // Store a reference to $(this)

        var id = $this.val();

        var dataId = $this.closest('.form-group-container').data('id');
        var match = dataId.match(/\d+/);

        if (match) {
            var index = parseInt(match[0], 10);
        }

        var product_type = $this.val();

        $.ajax({
            url: '{{ route('get.item') }}',
            type: 'POST',
            data: {
                "product_type": product_type,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                var slect_ids = 'options_id_' + index;

                // Use the correct selector to target emp_div
                var slect_div = $this.closest('.form-group-container').find('.emp_div');
                $(slect_div).empty();

                var product_select = `<select id="${slect_ids}" class="multi-select1 choices form-control product_id item js-searchBox" name="program_details[${index}][items][]" multiple="multiple" data-placeholder="{{ __('Select multiple items Here.......') }}" required="required"></select>`;

                $(slect_div).html(product_select);

                // Use the correct selector to target the option
                var option = $this.closest('.form-group-container').find('.emp_div').find('.multi-select1');

                $.each(data, function(i, item) {
                    $(option).append('<option value="' + i + '">' + item + '</option>');
                });

                // Initialize Choices for the correct select element
                new Choices('#' + slect_ids, {
                    removeItemButton: true,
                });
            }
        });
    });

    
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
        newContainer.find('.product_type').attr('name', 'program_details[' + plusFieldIndex + '][product_type]').val('product');
        newContainer.find('.from_amount').attr('name', 'program_details[' + plusFieldIndex + '][from_amount]').val('');
        newContainer.find('.to_amount').attr('name', 'program_details[' + plusFieldIndex + '][to_amount]').val('');
        newContainer.find('.product_id').attr('name', 'program_details[' + plusFieldIndex + '][items][]').val('');
        newContainer.find('.discount').attr('name', 'program_details[' + plusFieldIndex + '][discount]').val('');

        newContainer.find('.delete-icon').removeClass('disabled');
        newContainer.find('.delete-icon').removeClass('d-none');

        $("#repeater-container").append(newContainer);

        newContainer.find('.product_type').trigger('change');

    }

    // Add a new field when the button is clicked
    $("#add-field-btn").on("click", function() {
        addNewField(plusFieldIndex);
    });

    // Remove a field container when the delete icon is clicked
    $(document).on("click", ".delete-icon:not(.disabled)", function() {
        var container = $(this).closest('.form-group-container');
        if (container.attr("id") !== "form-group-container0") {
            container.remove();
        }
    });
});
</script>

@endpush
