@extends('layouts.main')
@section('page-title')
    {{ __('Assets') }}
@endsection
@section('page-breadcrumb')
    {{ __('Defective Manage') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Name')}}</th>
                                    <th>{{ __('Reason') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    @if(Laratrust::hasPermission('assets defective status'))
                                        <th>{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assetdefectives as $index => $assetdefective)
                                    <tr>

                                        <th scope="row">{{ ++$index }}</th>
                                        <td class="font-style">{{ !empty($assetdefective->module) ?  $assetdefective->module->name: '-' }}</td>
                                        <td class="font-style">{{ !empty($assetdefective->reason) ? $assetdefective->reason: '-' }}</td>
                                        <td class="font-style">{{ !empty($assetdefective->quantity) ? $assetdefective->quantity: '-' }}</td>
                                        <td class="font-style">{{ !empty($assetdefective->code) ? $assetdefective->code: '-' }}</td>
                                        <td class="font-style">{{ !empty($assetdefective->status) ? $assetdefective->status: '-' }}</td>

                                        @if(Laratrust::hasPermission('assets defective status'))
                                            <td>
                                                @if($assetdefective->status != 'Fail')
                                                    <span>
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Status')}}" data-bs-toggle="tooltip" data-bs-placement="top" data-url="{{ route('assets.withdraw.status',$assetdefective->id) }}" data-ajax-popup="true" data-title="{{__('Status')}}" data-size="md"><span class="text-white"><i class="ti ti-caret-right text-white"></i></span></a>
                                                        </div>
                                                    </span> 
                                                @endif
                                            </td>
                                        @endif
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
