@extends('layouts.main')

@section('page-title')
    {{__('Manage Category')}}
@endsection

@section('page-breadcrumb')
   {{__('Category')}}
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
                        <table class="table mb-0 pc-dt-simple" id="wp_category">
                            <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Display type')}}</th>
                                    <th>{{__('Description')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($wp_categorys as $wp_category)
                                    <tr>
                                        <td>
                                            <div>
                                                @if (!empty($wp_category['image']))
                                                    <a href="{{$wp_category['image']['src']}}" target="_blank">
                                                        <img alt="Image placeholder" src="{{$wp_category['image']['src']}}" class="rounded" style="width:70px; height:50px;">
                                                    </a>
                                                @else
                                                    <a href="{{asset('Modules/WordpressWoocommerce/Resources/assets/image/woocommerce.png')}}" target="_blank">
                                                        <img alt="Image placeholder" src="{{asset('Modules/WordpressWoocommerce/Resources/assets/image/woocommerce.png')}}" class="rounded" style="width:70px; height:50px;">
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $wp_category['name'] }}</td>
                                        <td>{{ $wp_category['display'] }}</td>
                                        <td>{{ !empty($wp_category['description'])?$wp_category['description']:'-' }}</td>
                                        <td>
                                            @permission('woocommerce product create')
                                                @if($active == true)
                                                    @if(in_array($wp_category['id'],$wp_conection))
                                                    <a href="{{route('wp-category.edit', $wp_category['id'])}}" class="btn btn-sm btn-primary" data-title="Sync Again">
                                                        <i class="ti ti-refresh " data-bs-toggle="tooltip" title="" data-bs-original-title="Sync Again" aria-label="Sync Again"></i>
                                                    </a>
                                                    @else
                                                        <a href="{{route('wp-category.show',  $wp_category['id'])}}" class="btn btn-sm btn-primary" data-title="Add Category">
                                                            <i class="ti ti-plus" data-bs-toggle="tooltip" title="" data-bs-original-title="Add Category" aria-label="Add Category"></i>
                                                        </a>
                                                    @endif
                                                @endif
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
