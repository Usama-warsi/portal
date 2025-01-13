@extends('layouts.main')

@section('page-title')
    {{__('Sales Agent')}}
@endsection

@section('page-breadcrumb')
{{ __('Sales Agent')}} , {{ __('Products List')}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card px-3 p-4">
                    {{ Form::open(['route' => ['salesagent.product.list'], 'method' => 'GET', 'id' => 'product_service']) }}
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                            <div class="btn-box">
                                {{ Form::label('category', __('Category'), ['class' => 'text-type form-label d-none']) }}
                                {{ Form::select('program', $programs, !empty($_GET['program'])? $_GET['program']:null, ['class' => 'form-control program_id ', 'required' => 'required', 'placeholder' => 'Select program...','id' => 'programs_select']) }}
                            </div>
                        </div>
                        <div class="col-auto float-end ms-2">
                            <a  class="btn btn-sm btn-primary"
                               onclick="document.getElementById('product_service').submit(); return false;"
                               data-bs-toggle="tooltip" title="{{ __('apply') }}">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="{{ route('salesagent.product.list') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                               title="{{ __('Reset') }}">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
                            </a>
                        </div>

                    </div>
                    {{ Form::close() }}
                {{-- <div class="text-end d-flex  justify-content-md-end justify-content-center">
                    <div class="col-4">
                        {{ Form::select('programs', $programs, null, ['data-url' => route('salesagent.product.list'). '/' , 'class' => 'form-control program_id ', 'required' => 'required', 'placeholder' => 'Select program...','id' => 'programs_select']) }}
                    </div>
                </div> --}}
            </div>
        </div>  
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="products">
                            <thead>
                            <tr>
                                <th >{{__('Image')}}</th>
                                <th >{{__('Name')}}</th>
                                <th >{{__('Sku')}}</th>
                                <th>{{__('Sale Price')}}</th>
                                <th>{{__('Type')}}</th>
                            </tr>
                            </thead>
                            <tbody id="product_data">
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

@push('scripts')
<script>

</script>
@endpush