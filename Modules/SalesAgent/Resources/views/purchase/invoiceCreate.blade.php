
    {{ Form::open(['url' => 'invoice']) }}
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        {{Form::label('category',__('Category'),array('class'=>'form-label')) }}
                        <div class="form-icon-user">
                            {{ Form::select('category_id', $category, null, ['class' => 'form-control category_id ', 'required' => 'required', 'placeholder' => 'Select Category...']) }}
                        </div>
                    </div>
                    {!! Form::hidden('agentPurchaseOrderId', $purchaseOrder->id) !!}
                    {!! Form::hidden('invoice_type', 'product') !!}
                    {!! Form::hidden('account_type', 'SalesAgent') !!}
                    {!! Form::hidden('customer_id', $purchaseOrder->salesagent->user_id) !!}
                    {!! Form::hidden('invoice_type_radio', 'product') !!}
                    {!! Form::hidden('issue_date', $purchaseOrder->order_date) !!}
                    {!! Form::hidden('due_date', $purchaseOrder->delivery_date) !!}

                    @foreach($purchaseOrder->items as $index => $item)
                        {!! Form::hidden("items[$index][id]", null) !!}
                        {!! Form::hidden("items[$index][product_type]", $item->product->type) !!}
                        {!! Form::hidden("items[$index][item]", $item['item_id']) !!}
                        {!! Form::hidden("items[$index][quantity]", $item['quantity']) !!}
                        {!! Form::hidden("items[$index][price]", $item['price']) !!}
                        {!! Form::hidden("items[$index][discount]", $item['discount']) !!}
                        {!! Form::hidden("items[$index][tax]", $item['tax']) !!}
                        {!! Form::hidden("items[$index][itemTaxPrice]", $item->itemTaxPrice($item)) !!}
                        {!! Form::hidden("items[$index][itemTaxRate]", $item->totalTaxRate($item['tax'])) !!}
                        {!! Form::hidden("items[$index][description]", $item['description']) !!}
                    @endforeach
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
        </div>
    {!! Form::close() !!}


