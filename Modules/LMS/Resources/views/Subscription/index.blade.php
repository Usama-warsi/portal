@extends('layouts.main')
@section('page-title')
    {{__('Subscriber')}}
@endsection
@section('page-breadcrumb')
    {{__('Subscriber')}}
@endsection
@section('page-action')
<div class="text-end align-items-end d-flex justify-content-end">
    @permission('subscriber create')
        <div class="btn btn-sm btn-primary btn-icon m-1">
            <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Email')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Email')}}" data-url="{{route('subscriptions.create')}}"><i class="ti ti-plus text-white"></i></a>
        </div>
    @endpermission
</div>
@endsection
@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <!-- Table -->
            <div class="card-header">
                <h5>{{__('Subscriber Email')}}</h5>
            </div>
            <!-- Table -->
            <div class="card-body table-border-style">
                <div class="table-responsive overflow_hidden">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">{{__('Email')}}</th>
                                @if(Laratrust::hasPermission('subscriber delete'))
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subs as $sub)
                                <tr data-name="{{$sub->email}}">
                                    <td class="sorting_1">{{$sub->email}}</td>
                                    @if(Laratrust::hasPermission('subscriber delete'))
                                        <td class="action text-right">
                                            @permission('subscriber delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['subscriptions.destroy', $sub->id]]) !!}
                                                        <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
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
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-page')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script src="{{ asset('js/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/datatable/buttons.html5.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.dataTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'subscriber'
                    }, {
                        extend: 'csvHtml5',
                        title: 'subscriber'
                    }, {
                        extend: 'pdfHtml5',
                        title: 'subscriber'
                    },
                ],
            });
        });

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
