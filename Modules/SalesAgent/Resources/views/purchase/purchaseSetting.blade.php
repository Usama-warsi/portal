{{-- {{ Form::model(array('route' => 'salesagent.purchase.setting','method' => 'post')) }} --}}
{{Form::open(array('route'=>'salesagent.purchase.setting','method'=>'post'))}}

<div class="modal-body">
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('sales_agent_purchase_order_prefix',__('Purchase Order Prefix'),array('class'=>'form-label')) }}
                {{Form::text('sales_agent_purchase_order_prefix',!empty(company_setting('sales_agent_purchase_order_prefix')) ? company_setting('sales_agent_purchase_order_prefix') :'#PUR',array('class'=>'form-control', 'placeholder' => 'Enter Purchase Order Prefix'))}}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Save'), ['class' => 'btn  btn-primary']) }}
</div>
{{Form::close()}}
