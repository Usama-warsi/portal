@extends('layouts.main')
@section('page-title')
    {{__('Manage Assets')}}
@endsection
@section("page-breadcrumb")
    {{__('Assets')}}
@endsection

@section('page-action')
    <div>
        @stack('addButtonHook')
        @permission('assets import')
            <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Assets Import')}}" data-url="{{ route('assets.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
            </a>
        @endpermission
        @permission('assets create')
            <a  class="btn btn-sm btn-primary" data-size="lg" data-url="{{ route('asset.create') }}" data-ajax-popup="true" data-title="{{__('Create New Assets')}}"  data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
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
                                <th>{{__('Image')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Category')}}</th>
                                <th>{{__('Location')}}</th>
                                <th>{{__('Purchase Date')}}</th>
                                <th>{{__('Supported Date')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Purchase Cost')}}</th>
                                @if (Laratrust::hasPermission('assets edit') || Laratrust::hasPermission('assets delete'))
                                    <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($assets as $asset)
                                <tr>
                                    @php
                                        $asset_image =  'uploads/assets/asset_image/'.$asset->asset_image ;
                                    @endphp
                                    <td width="200">
                                    @if(!empty($asset_image))
                                        <a href="{{ get_file($asset_image) }}" target="_blank">
                                            <img src="{{ get_file($asset_image) }}"  width="60" height="60"  class="rounded-circle"/>
                                            @endif
                                    </td>
                                    @php
                                        $purchase_cost = $asset->quantity * $asset->assets_unit;
                                    @endphp
                                    <td class="font-style">{{ isset($asset->name) ? $asset->name :''}}</td>
                                    <td class="font-style">{{ isset($asset->categories->name) ? $asset->categories->name :'-'}}</td>
                                    <td class="font-style">{{ isset($asset->location) ? $asset->location :'-'}}</td>
                                    <td class="font-style">{{ company_date_formate($asset->purchase_date) }}</td>
                                    <td class="font-style">{{ company_date_formate($asset->supported_date) }}</td>
                                    <td class="font-style">{{ !empty($asset->quantity) ? $asset->quantity:'-'}}</td>
                                    <td class="font-style">{{ !empty($purchase_cost) ? $purchase_cost: '-' }}</td>
                                    @if (Laratrust::hasPermission('assets edit') || Laratrust::hasPermission('assets delete'))
                                        <td>
                                            <span>
                                                <div class="action-btn bg-success ms-2">
                                                    <a  class="mx-3 btn btn-sm align-items-center" data-size="lg" data-url="{{ route('extra.create',$asset->id) }}" data-ajax-popup="true" data-title="{{__('Create Extra Assets')}}" data-size="lg" data-bs-toggle="tooltip" title="{{__('Extra')}}" data-original-title="{{__('Extra')}}">
                                                        <i class="ti ti-frame"></i>
                                                    </a>
                                                </div>

                                                <div class="action-btn bg-secondary ms-2">
                                                    <a  class="mx-3 btn btn-sm align-items-center" data-size="lg" data-url="{{ route('defective.create',$asset->id) }}" data-ajax-popup="true" data-title="{{__('Create Defective Assets')}}" data-size="lg" data-bs-toggle="tooltip" title="{{__('Defective')}}" data-original-title="{{__('Defective')}}">
                                                        <i class="ti ti-bookmark-off"></i>
                                                    </a>
                                                </div>

                                                <div class="action-btn bg-warning ms-2">
                                                    <a  class="mx-3 btn btn-sm align-items-center" data-size="lg" data-url="{{ route('distribution.create',$asset->id) }}" data-ajax-popup="true" data-title="{{__('Create Distribution Assets')}}" data-size="lg" data-bs-toggle="tooltip" title="{{__('Distribution')}}" data-original-title="{{__('Distribution')}}">
                                                        <i class="ti ti-arrows-maximize"></i>
                                                    </a>
                                                </div>

                                                @permission('assets edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a  class="mx-3 btn btn-sm align-items-center" data-size="lg" data-url="{{ route('asset.edit',$asset->id) }}" data-ajax-popup="true" data-title="{{__('Edit Assets')}}" data-size="lg" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('assets delete')
                                                    <div class="action-btn bg-danger ms-2" data-bs-whatever="{{ __('Delete Asset') }}" data-bs-toggle="tooltip" title="" data-bs-original-title="{{ __('Delete') }}">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['asset.destroy', $asset->id],'id'=>'delete-form-'.$asset->id]) !!}
                                                        <a  class="mx-3 btn btn-sm align-items-center bs-pass-para show_confirm" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$asset->id}}').submit();">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                    {!! Form::close() !!}
                                                    </div>
                                                @endpermission
                                            </span>
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
