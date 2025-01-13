@extends('layouts.main')
@section('page-title')
    {{__('Custom Page')}}
@endsection

@section('page-breadcrumb')
    {{__('Custom-Page')}}
@endsection


@section('page-action')
<div class="text-end align-items-end d-flex justify-content-end">
    @permission('custom page create')
        <div class="btn btn-sm btn-primary btn-icon m-1">
            <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create New Page')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create New Page')}}" data-url="{{route('custom-page.create')}}"><i class="ti ti-plus text-white"></i></a>
        </div>
    @endpermission
</div>
@endsection
@section('filter')
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('All Pages')}}</h5>
            </div>

            <!-- Table -->
            <div class="card-body table-border-style">
                <div class="table-responsive overflow_hidden">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">{{__('Name')}}</th>
                                <th scope="col" class="sort" data-sort="name">{{__('Page Slug')}}</th>
                                <th scope="col" class="sort" data-sort="name">{{__('Header')}}</th>
                                @if(Laratrust::hasPermission('custom page edit') ||  Laratrust::hasPermission('custom page delete'))
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        @if(count($pageoptions) > 0 && !empty($pageoptions))
                            <tbody>
                                @foreach($pageoptions as $pageoption)
                                    <tr data-name="{{$pageoption->name}}">
                                        <td class="sorting_1">{{$pageoption->name}}</td>
                                        @if($store && $store->enable_domain == 'on')
                                            <td class="sorting_1">{{$store->domains . '/page/'.$store_settings->name.'/'. $pageoption->slug}}</td>
                                        @elseif($sub_store && $sub_store->enable_subdomain == 'on')
                                            <td class="sorting_1">{{$sub_store->subdomain . '/page/'.$store_settings->name.'/'. $pageoption->slug}}</td>
                                        @else
                                            <td class="sorting_1">{{env('APP_URL') . '/page/'.$store_settings->name.'/'. $pageoption->slug}}</td>
                                        @endif
                                        <td class="sorting_1">{{ucfirst(($pageoption->enable_page_header == 'on')?$pageoption->enable_page_header:'Off')}}</td>
                                        @if(Laratrust::hasPermission('custom page edit') ||  Laratrust::hasPermission('custom page delete'))
                                            <td class="action text-right">
                                                @permission('custom page edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Custom Page')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Custom Page')}}" data-url="{{route('custom-page.edit',$pageoption->id)}}"><i class="ti ti-pencil text-white"></i></a>
                                                    </div>
                                                @endpermission

                                                @permission('custom page delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['custom-page.destroy', $pageoption->id] ]) !!}
                                                            <a href="#!" class="mx-3 btn btn-sm align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endpermission
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <tbody>
                            <tr>
                                <td colspan="7">
                                    <div class="text-center">
                                        <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                        <h2>{{__('Opps')}}...</h2>
                                        <h6>{{__('No data Found')}}. </h6>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@push('script-page')
    <script>
        $(document).ready(function () {
            $(document).on('keyup', '.search-user', function () {
                var value = $(this).val();
                $('.employee_tableese tbody>tr').each(function (index) {
                    var name = $(this).attr('data-name');
                    if (name.includes(value)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endpush

