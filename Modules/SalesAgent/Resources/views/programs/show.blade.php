
@extends('layouts.main')
@section('page-title')
    {{ __('Program Details') }}
@endsection
@section('page-breadcrumb')
    {{ __('Sales Agent') }} , {{ __('Program') }}, {{ __($program->name) }}
@endsection

@section('page-action')
    <div>
    	@stack('addButtonHook')
        @permission('programs create')
            <a  href="{{ route('salesagent.program.request.list',$program['id']) }}" class="btn btn-sm btn-primary"  
                data-title="{{ __('Join Requests') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Join Requests') }}">
                {{ __('Join Requests') }} {{'('. $totalJoinRequests.')' }}
            </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <h4 class="p-3 pb-0">{{ __('General information') }}</h4>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <p class="font-style">
                                        <span class="font-bold">{{ __('From date:') }}</span>
                                        <span>{{ $program['from_date'] }}</span>
                                    </p>
                                    <p class="font-style">
                                        <span class="font-bold">{{ __('To date:') }}</span>
                                        <span>{{ $program['to_date'] }}</span>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p class="font-style">
                                        <span class="font-bold">{{ __('Created at:') }}</span>
                                        <span>{{ $program['created_at'] }}</span>
                                    </p>
                                    <p class="font-style">
                                        <span class="font-bold">{{ __('Created by:') }}</span>
                                        <span>{{ $program['to_date'] }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="font-style">
                                        <span class="font-bold"> {{ __('Description:') }}</span>
                                        <span> {{ $program['description'] }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        @if(\Auth::user()->type != 'salesagent')
            <div class="col-6">
                <h4 class="pb-3">{{ __('Approved Program Participants') }}</h4>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table mb-0 pc-dt-simple" id="assets">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Contact') }}</th>
                                            <th>{{ __('Email') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($program->getSalesAgentAll() as $k => $Agent)
                                            <tr class="font-style">
                                                <td>{{ $Agent['name'] }}</td>
                                                <td>{{ $Agent['contact'] }}</td>
                                                <td>{{ $Agent['email'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <h4 class="pb-3 mx-3">{{ __('Discount Information') }}</h4>
                <div class="card ">
                    <div class="card-body">
                        <div class="row table-responsive" >
                            <table class="table mb-0 pc-dt-simple" id="discounts">
                                <thead>
                                    <tr>
                                        <th>{{ __('From amount') }}</th>
                                        <th>{{ __('To amount') }}</th>
                                        <th>{{ __('Discount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (is_array(($program->program_details)) || is_object(($program->program_details)))
                                        @foreach (($program->program_details) as $detail)
                                            <tr class="font-style">
                                                <td>{{ currency_format_with_sym($detail->from_amount) }}</td>
                                                <td>{{ currency_format_with_sym($detail->to_amount) }}</td>
                                                <td>{{ $detail->discount }} {{ $program->discount_type == 'percentage' ? '%' : company_setting('defult_currancy_symbol') }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="font-style">
                                            <td>--</td>
                                            <td>--</td>
                                            <td>--</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-12">
                <h4 class="pb-3 mx-3">{{ __('Discount Information') }}</h4>
                <div class="card ">
                    <div class="card-body">
                        <div class="row table-responsive" >
                            <table class="table mb-0 pc-dt-simple" id="discounts">
                                <thead>
                                    <tr>
                                        <th>{{ __('From amount') }}</th>
                                        <th>{{ __('To amount') }}</th>
                                        <th>{{ __('Discount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (is_array(($program->program_details)) || is_object(($program->program_details)))
                                        @foreach (($program->program_details) as $detail)
                                            <tr class="font-style">
                                                <td>{{ currency_format_with_sym($detail->from_amount) }}</td>
                                                <td>{{ currency_format_with_sym($detail->to_amount) }}</td>
                                                <td>{{ $detail->discount }} {{ $program->discount_type == 'percentage' ? '%' : company_setting('defult_currancy_symbol') }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="font-style">
                                            <td>--</td>
                                            <td>--</td>
                                            <td>--</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif 
        
        <div class="col-xl-12">
            <h4 class="pb-3">{{ __('Items Included in Program') }}</h4>
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="products">
                            <thead>
                            <tr>
                                <th >{{__('Image')}}</th>
                                <th >{{__('Name')}}</th>
                                <th >{{__('Sku')}}</th>
                                <th>{{__('Sale Price')}}</th>
                                <th>{{__('Type')}}</th>
                                {{-- <th>{{__('Discount')}}</th> --}}
                            </tr>
                            </thead>
                            <tbody>
                                @if (is_array($productServices) || is_object($productServices))
                                    @foreach ($productServices as $productService)
                                        <?php
                                            if(check_file($productService->image) == false){
                                                $path = asset('Modules/ProductService/Resources/assets/image/img01.jpg');
                                            }else{
                                                $path = get_file($productService->image);
                                            }
                                        ?>
                                        <tr class="font-style">
                                            <td>
                                                <a href="{{ $path }}" target="_blank">
                                                    <img src=" {{ $path }} " class="wid-75 rounded me-3">
                                                </a>
                                            </td>
                                            <td class="text-center">{{ $productService->name}}</td>
                                            <td class="text-center">{{ $productService->sku }}</td>
                                            <td>{{ currency_format_with_sym($productService->sale_price) }}</td>
                                            <td>{{ $productService->type }}</td>
                                            {{-- <td>{{ '' }}</td> --}}
                                        </tr>
                                    @endforeach
                                @endif    
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
