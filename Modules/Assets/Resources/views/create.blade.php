{{ Form::open(['url' => 'asset','enctype'=>'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn', ['template_module' => 'asset', 'module' => 'Assets'])
        @endif
    </div>
    <div class="row">

        <div class="form-group col-md-6">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            {{ Form::text('name'  , null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Name']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}
            {{ Form::select('category',$category, null, ['class' => 'form-control', 'placeholder' => 'Enter Category']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('purchase_date', __('Purchase Date'), ['class' => 'form-label']) }}
            {{ Form::date('purchase_date', date('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Select Purchase Date', 'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('supported_date', __('Supported Date'), ['class' => 'form-label']) }}
            {{ Form::date('supported_date', date('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Select Supported Date', 'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('serial_code', __('Serial Code'), ['class' => 'form-label']) }}
            {{ Form::text('serial_code', null, ['class' => 'form-control', 'required' => 'required', 'step' => '1', 'placeholder' => 'Enter serial Code']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('quantity', __('Quantity'), ['class' => 'form-label']) }}
            {{ Form::number('quantity', null, ['class' => 'form-control', 'required' => 'required', 'step' => '1', 'placeholder' => 'Enter Quantity', 'id' => 'quantity']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('assets_unit', __('Unit Price'), ['class' => 'form-label']) }}
            {{ Form::number('assets_unit', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.01', 'placeholder' => 'Enter Unit Price', 'id' => 'assets_unit']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('purchase_cost', __('Purchase Cost'), ['class' => 'form-label']) }}
            {{ Form::number('purchase_cost', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.01', 'placeholder' => 'Purchase Cost', 'id' => 'purchase_cost', 'readonly' => 'readonly']) }}
        </div>
        @stack('add_branch_in_asset_create')
        <div class="form-group col-md-6">
            {{ Form::label('warranty_period', __('Warranty Period'), ['class' => 'form-label']) }}
            {{ Form::number('warranty_period', null, ['class' => 'form-control', 'required' => 'required', 'step' => '1', 'placeholder' => 'Enter Warranty Period']) }}
            <small class="text-danger font-weight-bold">{{__('Add Month For Warranty Period')}}</small>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('location', __('Location'), ['class' => 'form-label']) }}
            {{ Form::text('location', null, ['class' => 'form-control', 'required' => 'required','placeholder' => 'Enter Location']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('asset_image', __('Image'), ['class' => 'col-form-label']) }}
            <div class="choose-file">
                <label for="Image">
                    <input type="file" class="form-control" name="asset_image" id="asset_image"
                        data-filename="asset_image" accept="image/*,.jpeg,.jpg,.png" required="required"
                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" style="width:460px;">
                    <img id="blah" width="25%" class="mt-3">
                </label>
            </div>
        </div>


        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Enter Description']) }}
        </div>
        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customfield::formBuilder')
                </div>
            </div>
        @endif

    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
    </div>
</div>
{{ Form::close() }}

<script>
    // Add an event listener to the Quantity and Unit Price fields
    document.getElementById('quantity').addEventListener('input', updatePurchaseCost);
    document.getElementById('assets_unit').addEventListener('input', updatePurchaseCost);

    // Function to calculate and update the Purchase Cost based on Quantity and Unit Price
    function updatePurchaseCost() {
        const quantity = parseFloat(document.getElementById('quantity').value);
        const unitPrice = parseFloat(document.getElementById('assets_unit').value);

        if (!isNaN(quantity) && !isNaN(unitPrice)) {
            const purchaseCost = quantity * unitPrice;
            document.getElementById('purchase_cost').value = purchaseCost.toFixed(2); // Format to 2 decimal places
        }
    }
</script>
