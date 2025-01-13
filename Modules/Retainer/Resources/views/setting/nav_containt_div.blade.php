<div class="tab-pane fade" id="customer-retainer" role="tabpanel" aria-labelledby="pills-user-tab-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable pc-dt-simple" id="customer_retainers">
                            <thead>
                                <tr>
                                    <th> {{ __('Retainer') }}</th>
                                    @if (!\Auth::user()->type != 'Client')
                                        <th> {{ __('Customer') }}</th>
                                    @endif
                                    <th> {{ __('Issue Date') }}</th>
                                    <th>{{ __('Due Amount') }}</th>
                                    <th> {{ __('Status') }}</th>
                                    @if (Laratrust::hasPermission('retainer edit') || Laratrust::hasPermission('retainer delete') || Laratrust::hasPermission('retainer show'))
                                        <th width="10%"> {{ __('Action') }}</th>
                                    @endif

                                </tr>
                            </thead>

                            <tbody>
                                @forelse (\Modules\Retainer\Entities\Retainer::customerRetainer($customer->id) as $customer_retainer)
                                    <tr class="font-style">
                                        <td class="Id">
                                            <a href="{{ route('retainer.show', \Crypt::encrypt($customer_retainer->id)) }}"
                                                class="btn btn-outline-primary">{{ Modules\Retainer\Entities\Retainer::retainerNumberFormat($customer_retainer->retainer_id) }}
                                            </a>
                                        </td>
                                        @if (!\Auth::user()->type != 'Client')
                                            <td> {{ !empty($customer_retainer->customer) ? $customer_retainer->customer->name : '' }}
                                            </td>
                                        @endif
                                        <td>{{ company_date_formate($customer_retainer->issue_date) }}</td>
                                        <td>{{ currency_format_with_sym($customer_retainer->getDue()) }}</td>
                                        <td>
                                            @if($customer_retainer->status == 0)
                                                <span class="badge fix_badges bg-primary p-2 px-3 rounded">{{ __(Modules\Retainer\Entities\Retainer::$statues[$customer_retainer->status]) }}</span>
                                            @elseif($customer_retainer->status == 1)
                                                <span class="badge fix_badges bg-info p-2 px-3 rounded">{{ __(Modules\Retainer\Entities\Retainer::$statues[$customer_retainer->status]) }}</span>
                                            @elseif($customer_retainer->status == 2)
                                                <span class="badge fix_badges bg-secondary p-2 px-3 rounded">{{ __(Modules\Retainer\Entities\Retainer::$statues[$customer_retainer->status]) }}</span>
                                            @elseif($customer_retainer->status == 3)
                                                <span class="badge fix_badges bg-warning p-2 px-3 rounded">{{ __(Modules\Retainer\Entities\Retainer::$statues[$customer_retainer->status]) }}</span>
                                            @elseif($customer_retainer->status == 4)
                                                <span class="badge fix_badges bg-danger p-2 px-3 rounded">{{ __(Modules\Retainer\Entities\Retainer::$statues[$customer_retainer->status]) }}</span>
                                            @endif
                                        </td>
                                        @if(Laratrust::hasPermission('retainer edit') || Laratrust::hasPermission('retainer delete') || Laratrust::hasPermission('retainer show'))
                                        <td class="Action">
                                            @if($customer_retainer->is_convert==0 && $customer_retainer->status != 4)
                                                @permission('retainer convert invoice')
                                                    <div class="action-btn bg-success ms-2">
                                                        {!! Form::open([
                                                            'method' => 'get',
                                                            'route' => ['retainer.convert_invoice', $customer_retainer->id],
                                                            'id' => 'retainer-form-' . $customer_retainer->id,
                                                        ]) !!}
                                                        <a href="#"
                                                           class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                           data-bs-toggle="tooltip" title=""
                                                           data-bs-original-title="{{ __('Convert to Invoice') }}"
                                                           aria-label="Delete"
                                                           data-text="{{ __('You want to confirm convert to Invoice. Press Yes to continue or No to go back') }}"
                                                           data-confirm-yes="proposal-form-{{ $customer_retainer->id }}">
                                                            <i class="ti ti-exchange text-white"></i>
                                                        </a>
                                                        {{ Form::close() }}
                                                    </div>
                                                @endpermission
                                            @else
                                                @permission('convert invoice')
                                                    <div class="action-btn bg-success ms-2">
                                                        <a href="{{ route('invoice.show',\Crypt::encrypt($customer_retainer->converted_invoice_id)) }}"
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
                                                        'route' => ['retainer.duplicate', $customer_retainer->id],
                                                        'id' => 'duplicate-form-' . $customer_retainer->id,
                                                    ]) !!}
                                                    <a href="#"
                                                       class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                       data-bs-toggle="tooltip" title=""
                                                       data-bs-original-title="{{ __('Duplicate') }}"
                                                       aria-label="Delete"
                                                       data-text="{{ __('You want to confirm duplicate this retainer. Press Yes to continue or Cancel to go back') }}"
                                                       data-confirm-yes="duplicate-form-{{ $customer_retainer->id }}">
                                                        <i class="ti ti-copy text-white text-white"></i>
                                                    </a>
                                                    {{ Form::close() }}
                                                </div>
                                            @endpermission

                                            @permission('retainer show')
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{ route('retainer.show',\Crypt::encrypt($customer_retainer->id)) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" data-original-title="{{__('Detail')}}">
                                                        <i class="ti ti-eye text-white text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                            @if (module_is_active('ProductService') && ( ($customer_retainer->retainer_module == 'taskly') ? module_is_active('Taskly') :  module_is_active('Account')))
                                                @permission('retainer edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('retainer.edit',\Crypt::encrypt($customer_retainer->id)) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                            @endif
                                            @permission('retainer delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {{ Form::open(['route' => ['retainer.destroy', $customer_retainer->id], 'class' => 'm-0']) }}
                                                    @method('DELETE')
                                                    <a href="#"
                                                       class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                       data-bs-toggle="tooltip" title=""
                                                       data-bs-original-title="Delete" aria-label="Delete"
                                                       data-confirm="{{ __('Are You Sure?') }}"
                                                       data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                       data-confirm-yes="delete-form-{{ $customer_retainer->id }}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    {{ Form::close() }}
                                                </div>
                                            @endpermission
                                        </td>
                                    @endif
                                    </tr>
                                    @empty
                                    @include('layouts.nodatafound')
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
