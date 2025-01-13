@extends('layouts.main')
@section('page-title')
    {{__('Retainers')}}
@endsection
@section('page-breadcrumb')
    {{ __('Retainers') }}
@endsection

@section('page-action')
    <div>
        @stack('addButtonHook')
        @if (module_is_active('ProductService'))
            <a href="{{ route('category.index') }}"data-size="md"  class="btn btn-sm btn-primary"
               data-bs-toggle="tooltip"data-title="{{__('Setup')}}" title="{{__('Setup')}}"><i class="ti ti-settings"></i></a>
        @endif

        @if ((module_is_active('ProductService') && module_is_active('Account')) || module_is_active('Taskly'))
            @permission('retainer manage')
                <a href="{{ route('retainer.grid') }}" class="btn btn-sm btn-primary btn-icon m-1"
                   data-bs-toggle="tooltip" title="{{ __('Grid View') }}">
                    <i class="ti ti-layout-grid text-white"></i>
                </a>
            @endpermission

            @permission('retainer create')
                <a href="{{ route('retainer.create',0) }}" class="btn btn-sm btn-primary"
                   data-bs-toggle="tooltip" title="{{__('Create')}}">
                    <i class="ti ti-plus"></i>
                </a>
            @endpermission
        @endif
    </div>
@endsection


@push('css-page')

@endpush
@push('script-page')

@endpush
@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class=" multi-collapse mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['retainer.index'], 'method' => 'GET', 'id' => 'frm_submit']) }}

                        <div class="row d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box">
                                    {{ Form::label('issue_date', __('Date'),['class'=>'form-label']) }}
                                    {{ Form::text('issue_date', isset($_GET['issue_date']) ? $_GET['issue_date'] : null, ['class' => 'form-control flatpickr-to-input','placeholder' => 'Select Date']) }}
                                </div>
                            </div>
                            @if (!\Auth::user()->type != 'Client')
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                    <div class="btn-box">
                                        {{ Form::label('customer', __('Customer'),['class'=>'form-label']) }}
                                        {{ Form::select('customer', $customer, isset($_GET['customer']) ? $_GET['customer'] : '', ['class' => 'form-control', 'placeholder' => 'Select Client']) }}
                                    </div>
                                </div>
                            @endif
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                                    {{ Form::select('status', ['' => 'Select Status'] + $status, isset($_GET['status']) ? $_GET['status'] : '', ['class' => 'form-control select']) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                   onclick="document.getElementById('frm_submit').submit(); return false;"
                                   data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                   data-original-title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>


                                @if(\Auth::user()->type == 'company')
                                    <a href="{{ route('retainer.index') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                       title="{{ __('Reset') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>
                                    </a>
                                @endif
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable pc-dt-simple" id="assets">
                            <thead>
                            <tr>
                                <th> {{__('Retainer')}}</th>
                                @if (!\Auth::user()->type != 'Client')
                                    <th> {{__('Customer')}}</th>
                                @endif
                                <th>{{__('Account Type')}}</th>
                                <th> {{__('Issue Date')}}</th>
                                <th>{{ __('Due Amount') }}</th>
                                <th> {{__('Status')}}</th>
                                @if(Laratrust::hasPermission('retainer edit') || Laratrust::hasPermission('retainer delete') || Laratrust::hasPermission('retainer show'))
                                    <th width="10%"> {{__('Action')}}</th>
                                @endif

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($retainers as $retainer)
                                <tr class="font-style">
                                    <td class="Id">
                                        <a href="{{ route('retainer.show',\Crypt::encrypt($retainer->id)) }}" class="btn btn-outline-primary">{{Modules\Retainer\Entities\Retainer::retainerNumberFormat($retainer->retainer_id) }}
                                        </a>
                                    </td>

                                    @if (!\Auth::user()->type != 'Client')
                                        <td> {{!empty($retainer->customer)? $retainer->customer->name:'' }} </td>
                                    @endif
                                    <td>{{$retainer->account_type }}</td>
                                    <td>{{  company_date_formate($retainer->issue_date) }}</td>
                                    <td>{{ currency_format_with_sym($retainer->getDue()) }}</td>
                                    <td>
                                        @if($retainer->status == 0)
                                            <span class="badge fix_badges bg-primary p-2 px-3 rounded">{{ __(Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                        @elseif($retainer->status == 1)
                                            <span class="badge fix_badges bg-info p-2 px-3 rounded">{{ __(Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                        @elseif($retainer->status == 2)
                                            <span class="badge fix_badges bg-secondary p-2 px-3 rounded">{{ __(Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                        @elseif($retainer->status == 3)
                                            <span class="badge fix_badges bg-warning p-2 px-3 rounded">{{ __(Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                        @elseif($retainer->status == 4)
                                            <span class="badge fix_badges bg-danger p-2 px-3 rounded">{{ __(Modules\Retainer\Entities\Retainer::$statues[$retainer->status]) }}</span>
                                        @endif
                                    </td>
                                    @if(Laratrust::hasPermission('retainer edit') || Laratrust::hasPermission('retainer delete') || Laratrust::hasPermission('retainer show'))
                                        <td class="Action">

                                            @if($retainer->is_convert==0 && $retainer->status != 4)

                                                @permission('retainer convert invoice')
                                                    <div class="action-btn bg-success ms-2">
                                                        {!! Form::open([
                                                            'method' => 'get',
                                                            'route' => ['retainer.convert_invoice', $retainer->id],
                                                            'id' => 'retainer-form-' . $retainer->id,
                                                        ]) !!}
                                                        <a href="#"
                                                           class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                           data-bs-toggle="tooltip" title=""
                                                           data-bs-original-title="{{ __('Convert to Invoice') }}"
                                                           aria-label="Delete"
                                                           data-text="{{ __('You want to confirm convert to Invoice. Press Yes to continue or No to go back') }}"
                                                           data-confirm-yes="proposal-form-{{ $retainer->id }}">
                                                            <i class="ti ti-exchange text-white"></i>
                                                        </a>
                                                        {{ Form::close() }}
                                                    </div>
                                                @endpermission
                                            @else
                                                @permission('convert invoice')
                                                    <div class="action-btn bg-success ms-2">
                                                        <a href="{{ route('invoice.show',\Crypt::encrypt($retainer->converted_invoice_id)) }}"
                                                           class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Already convert to Invoice')}}"
                                                           data-original-title="{{__('Already convert to Invoice')}}" data-original-title="{{__('Delete')}}">
                                                            <i class="ti ti-eye text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                            @endif

                                            @permission('retainer duplicate')
                                                <div class="action-btn bg-secondary ms-2">
                                                    {!! Form::open([
                                                        'method' => 'get',
                                                        'route' => ['retainer.duplicate', $retainer->id],
                                                        'id' => 'duplicate-form-' . $retainer->id,
                                                    ]) !!}
                                                    <a href="#"
                                                       class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                       data-bs-toggle="tooltip" title=""
                                                       data-bs-original-title="{{ __('Duplicate') }}"
                                                       aria-label="Delete"
                                                       data-text="{{ __('You want to confirm duplicate this retainer. Press Yes to continue or Cancel to go back') }}"
                                                       data-confirm-yes="duplicate-form-{{ $retainer->id }}">
                                                        <i class="ti ti-copy text-white text-white"></i>
                                                    </a>
                                                    {{ Form::close() }}
                                                </div>
                                            @endpermission

                                            @permission('retainer show')
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{ route('retainer.show',\Crypt::encrypt($retainer->id)) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" data-original-title="{{__('Detail')}}">
                                                        <i class="ti ti-eye text-white text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                            @if (module_is_active('ProductService') && ( ($retainer->retainer_module == 'taskly') ? module_is_active('Taskly') :  module_is_active('Account')))
                                                @permission('retainer edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('retainer.edit',\Crypt::encrypt($retainer->id)) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                            @endif
                                            @permission('retainer delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {{ Form::open(['route' => ['retainer.destroy', $retainer->id], 'class' => 'm-0']) }}
                                                    @method('DELETE')
                                                    <a href="#"
                                                       class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                       data-bs-toggle="tooltip" title=""
                                                       data-bs-original-title="Delete" aria-label="Delete"
                                                       data-confirm="{{ __('Are You Sure?') }}"
                                                       data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                       data-confirm-yes="delete-form-{{ $retainer->id }}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    {{ Form::close() }}
                                                </div>
                                            @endpermission
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
