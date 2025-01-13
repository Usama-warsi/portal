@extends('layouts.main')

@section('page-title')
    {{__('Manage Product')}}
@endsection

@section('page-breadcrumb')
   {{__('Product')}}
@endsection
@section('page-action')
@endsection
@php
    $active = module_is_active('ProductService');
@endphp

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="wp_product">
                            <thead>
                                <tr>
                                    <th>{{__('Product Image')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('SKU')}}</th>
                                    <th>{{__('Stock')}}</th>
                                    <th>{{__('Price')}}</th>
                                    <th>{{__('Category')}}</th>
                                    <th>{{__('Type')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($wp_products as $wp_product)
                                    @php
                                        $nameValues = array_column($wp_product['categories'], 'name');
                                        $category_name = implode(',', $nameValues);
                                    @endphp
                                    <tr>
                                        <td>
                                            <div>
                                                @if($wp_product['images'])
                                                <a href="{{$wp_product['images'][0]['src']}}" target="_blank">
                                                    <img alt="Image placeholder" src="{{$wp_product['images'][0]['src']}}" class="wid-75 rounded me-3">
                                                </a>
                                                @else
                                                <a href="{{asset('Modules/WordpressWoocommerce/Resources/assets/image/woocommerce.png')}}" target="_blank">
                                                    <img alt="Image placeholder" src="{{asset('Modules/WordpressWoocommerce/Resources/assets/image/woocommerce.png')}}" class="rounded" style="width:70px; height:50px;">
                                                </a>
                                            @endif
                                            </div>
                                        </td>
                                        <td>{{ $wp_product['name'] }}</td>
                                        <td>{{ !empty($wp_product['sku'])?$wp_product['sku']:'-' }}</td>
                                        <td>{{ $wp_product['stock_status'] }}</td>
                                        <td>{{ $wp_product['price'] }}</td>
                                        <td>{{ $category_name }}</td>
                                        <td>{{ $wp_product['type'] }}</td>
                                        <td>
                                            @if($active == true)
                                                @permission('woocommerce category create')
                                                    @if(in_array($wp_product['id'],$wp_conection))
                                                        <a href="{{route('wp-product.edit', $wp_product['id'])}}" class="btn btn-sm btn-primary" data-title="Sync Again">
                                                            <i class="ti ti-refresh " data-bs-toggle="tooltip" title="" data-bs-original-title="Sync Again" aria-label="Sync Again"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{route('wp-product.show',  $wp_product['id'])}}" class="btn btn-sm btn-primary" data-title="Add Category">
                                                            <i class="ti ti-plus" data-bs-toggle="tooltip" title="" data-bs-original-title="Add Category" aria-label="Add Category"></i>
                                                        </a>
                                                    @endif
                                                @endpermission
                                           @endif
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
