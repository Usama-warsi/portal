@extends('layouts.main')
@section('page-title')
    {{ __('Manage Commission Receipt') }}
@endsection
@section('page-breadcrumb')
    {{ __('Commission Receipt') }}
@endsection

@section('page-action')
    <div>
        @permission('commission receipt create')
        <a class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Create') }}" data-ajax-popup="true"
            data-url="{{ route('commission-receipt.create') }}" data-size="md"  data-title="{{ __('Create Commission Receipt') }}">
            <i class="ti ti-plus"></i>
        </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Commission Structure') }}</th>
                                    <th>{{ __('Agent') }}</th>
                                    <th>{{ __('Commission type') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($commissionReceipts as $commissionReceipt)
                                    @php
                                        $agent = App\Models\User::where('id', $commissionReceipt->agent)
                                            ->pluck('name')
                                            ->first();
                                        $commissionStr = Modules\Commission\Entities\CommissionModule::where('id', $commissionReceipt->commission_str)
                                            ->pluck('submodule')
                                            ->first();
                                        $commissionPlan = Modules\Commission\Entities\CommissionPlan::where('id', $commissionReceipt->commissionplan_id)->first();
                                    @endphp
                                    <tr class="font-style">
                                        <td>{{ $commissionStr }}</td>
                                        <td>{{ $agent }}</td>
                                        <td>{{ $commissionPlan->commission_type }}</td>
                                        <td>{{ $commissionReceipt->amount }}</td>
                                        <td>
                                            @if ($commissionReceipt->status == 0)
                                                @if(isset($commissionReceipt->status))
                                                <span class="badge bg-danger p-2 px-3 rounded"
                                                    style="width: 91px;">
                                                    {{ __(Modules\Commission\Entities\CommissionReceipt::$status[$commissionReceipt->status]) }}</span>
                                                @else
                                                <span class="badge bg-danger p-2 px-3 rounded"
                                                    style="width: 91px;">
                                                    {{ __('Unpaid') }}</span>
                                                @endif
                                            @elseif($commissionReceipt->status == 1)
                                                 @if(isset($commissionReceipt->status))
                                                <span class="badge bg-primary p-2 px-3 rounded"
                                                    style="width: 91px;">{{ __(Modules\Commission\Entities\CommissionReceipt::$status[$commissionReceipt->status]) }}</span>
                                                @else
                                                <span class="badge bg-primary p-2 px-3 rounded"
                                                style="width: 91px;">{{ __('Paid') }}</span>
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            <a href="#"
                                                data-url="{{ route('commission.receipt', $commissionReceipt->id) }}"
                                                data-size="lg" data-ajax-popup="true" class=" btn-sm btn btn-warning"
                                                data-bs-toggle="tooltip"
                                                title="{{ __('Commission Receipt') }}">{{ __('Receipt') }}</a>


                                            @if ($commissionReceipt->status == 0)
                                                <a href="{{ route('commission.receipt.payment', encrypt($commissionReceipt->id)) }}"
                                                    class="btn-sm btn btn-primary" data-bs-toggle="tooltip"
                                                    title="{{ __('Click To Paid') }}">{{ __('Click To Paid') }}</a>
                                            @endif

                                            @permission('commission receipt delete')
                                                <div class="action-btn bg-danger ms-3">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['commission-receipt.destroy', $commissionReceipt->id],
                                                        'id' => 'delete-form-' . $commissionReceipt->id,
                                                    ]) !!}
                                                    <a class="btn btn-danger btn-sm align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ __('Delete') }}">{{ __('Delete') }}</a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
