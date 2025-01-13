@extends('layouts.main')

@section('page-title')
    {{__('Manage Tax')}}
@endsection

@section('page-breadcrumb')
   {{__('Tax')}}
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
                        <table class="table mb-0 pc-dt-simple" id="wp_tax">
                            <thead>
                                <tr>
                                    <th>{{__('Tax Name')}}</th>
                                    <th>{{__('Rate %')}}</th>
                                    <th>{{__('Country')}}</th>
                                    <th>{{__('State')}}</th>
                                    <th>{{__('City')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($wp_taxs as $wp_tax)
                                    <tr>
                                        <td>{{ !empty($wp_tax['name'])?$wp_tax['name']:'-' }}</td>
                                        <td>{{ !empty($wp_tax['rate'])?$wp_tax['rate']:'0.0' }}</td>
                                        <td>{{ !empty($wp_tax['country'])?$wp_tax['country']:'-' }}</td>
                                        <td>{{ !empty($wp_tax['state'])?$wp_tax['state']:'-' }}</td>
                                        <td>{{ !empty($wp_tax['city'])?$wp_tax['city']:'-' }}</td>
                                        <td>
                                            @if($active == true)
                                                @permission('woocommerce tax create')
                                                @if(in_array($wp_tax['id'],$wp_conection))
                                                    <a href="{{route('wp-tax.edit', $wp_tax['id'])}}" class="btn btn-sm btn-primary" data-title="Sync Again">
                                                        <i class="ti ti-refresh " data-bs-toggle="tooltip" title="" data-bs-original-title="Sync Again" aria-label="Sync Again"></i>
                                                    </a>
                                                @else
                                                        <a href="{{route('wp-tax.show',  $wp_tax['id'])}}" class="btn btn-sm btn-primary" data-title="Add tax">
                                                            <i class="ti ti-plus" data-bs-toggle="tooltip" title="" data-bs-original-title="Add tax" aria-label="Add tax"></i>
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
