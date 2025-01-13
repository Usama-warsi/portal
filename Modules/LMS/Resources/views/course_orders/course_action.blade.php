{{ Form::model($course_order, ['route' => ['course.bank.request.update', $course_order->id], 'method' => 'POST']) }}

<div class="modal-body">
    <div class="table-responsive">
        <table class="table table-bordered ">
            <tr>
                <td>{{__('Order Id')}}</td>
                <td>{{ $course_order->order_id }}</td>
            </tr>
            <tr role="row">
                <td>{{__('Amount')}}</td>
                <td>{{ $course_order->price }}</td>
            </tr>
            <tr>
                <td>{{__('Payment Type')}}</td>
                <td>{{ $course_order->payment_type }}</td>
            </tr>
            <tr>
                <th>{{__('status')}}</th>
                <td>
                    <span class="bg-warning p-1 px-3 rounded text-white">{{ucfirst($course_order->payment_status)}}</span>
                </td>
            </tr>
            <tr>
                <td>{{__('Bank Details')}}</td>
                <td>{!! !empty($company_setting['bank_number'])?$company_setting['bank_number']:'' !!}</td>
            </tr>
            <tr>
                <td>{{__('Payment Recript')}}</td>
                <td>
                    @if (!empty($course_order->receipt) && (check_file($course_order->receipt)))
                        <a href="{{ get_file($course_order->receipt) }}"  title="Invoice" download=""class="btn btn-primary btn-sm action-btn">
                            <i class="ti ti-download"></i>
                        </a>
                    @else
                        {{ __('Not Found')}}
                    @endif
                </td>
            </tr>

        </table>
    </div>
</div>

@if ($course_order->payment_status == 'Pending')
<div class="modal-footer">
    <input type="submit" value="{{ __('Approved') }}" class="btn btn-success rounded" name="status">
    <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="status">
</div>
@endif
{{ Form::close() }}
