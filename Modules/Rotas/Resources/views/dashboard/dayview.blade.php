@extends('layouts.main')

@section('page-title')
    {{ __('Day View') }}
@endsection
@section('page-breadcrumb')
    {{ __('Day View') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-body">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-8">
                            <h5 class="fullcalendar-title h4 d-inline-block font-weight-400 mb-0">{{ $today }}
                            </h5> &nbsp;&nbsp;
                            <div class="btn-group" role="group" aria-label="Basic example" data-date="{{ $today }}">
                                <a class="btn btn-sm btn-primary date_sub m-1">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                                <a class="btn btn-sm btn-primary date_add m-1">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4 d-flex justify-content-end text-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn btn-sm btn-primary btn-icon m-1 day_view_filter_btn"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-filter" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ __('Filter') }}"></i>
                                </button>
                            </div>
                            <div class="btn-group card-option">
                                <button type="button" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ __('Filter Role') }}"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('rotas.dashboard') }}"
                                        class="dropdown-item {{ Request::segment(1) == 'rotas.dashboard' ? 'calender_active' : '' }}"
                                        onclick="window.location.href=this;">{{ __('Calendar View') }}</a>
                                    <a href="{{ route('rota.dashboard.day') }}"
                                        class="dropdown-item {{ Request::segment(1) == 'rota.dashboard.day' ? 'calender_active' : '' }}"
                                        onclick="window.location.href=this;">{{ __('Daily View') }}</a>
                                    <a href="{{ route('rota.dashboard.user-view') }}"
                                        class="dropdown-item {{ Request::segment(1) == 'user' ? 'calender_active' : '' }}"
                                        onclick="window.location.href=this;">{{ __('User View') }}</a>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="card day_view_filter" style="display: none;">
                    <div class="card-body p-4 m-0 mt-2">
                        <div class="row">
                            <div class="form-group col-md-3 mb-0">
                                {{ Form::label('', __('Date'), ['class' => 'form-control-label']) }}
                                <input type="date" class="rota_date form-control" style="height: 40px;">
                            </div>
                            <div class="form-group col-md-3 mb-0 cus_select_h_40">
                                {{ Form::label('', __('Employee'), ['class' => 'form-control-label']) }}
                                {{ Form::select('emp_name[]', $employee_data, null, ['id' => 'emp_name', 'class' => 'emp_name choices', 'multiple' => '']) }}
                            </div>
                            <div class="form-group col-md-3 mb-0 cus_select_h_40">
                                {{ Form::label('', __('Designation'), ['class' => 'form-control-label']) }}
                                {{ Form::select('loaction_name[]', $location_option, null, ['id' => 'loaction_name', 'class' => 'loaction_name choices', 'multiple' => '', 'searchEnabled' => 'true']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive day_view_tbl">
                            <table class="table mb-0 pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Time') }}</th>
                                        <th>{{ __('Break') }}</th>
                                        <th>{{ __('Designation') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if (!empty($rotas) && count($rotas) != 0)
                                        @foreach ($rotas as $rota)
                                            <tr>
                                                <th>
                                                    <div class="media align-items-center">
                                                        <div>
                                                            <div class="avatar-parent-child">
                                                                <img src="{{ asset($rota->userprofile($rota->user_id)) }}"
                                                                    class="avatar rounded-circle" style="width: 40px;">
                                                            </div>
                                                        </div>
                                                        <div style="margin: 10px;">
                                                            <a href="#"
                                                                class="text-dark ms-3">{{ !empty($rota->getrotauser) ? $rota->getrotauser->name : '-' }}</a>
                                                            <small
                                                                class="d-inline-block font-weight-bold">{{ $rota->name }}</small>
                                                        </div>
                                                    </div>
                                                </th>
                                                <td> {{ company_Time_formate($rota['start_time']) }} -
                                                    {{ company_Time_formate($rota['end_time']) }} </td>
                                                <td> {{ $rota->break_time . __('Min') }}</td>
                                                <td> {{ !empty($rota->designation->name) ? $rota->designation->name : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">
                                                <div class="text-center">
                                                    <i class="fas fa-calendar-times text-primary fs-40"></i>
                                                    <h2>{{ __('Opps...') }}</h2>
                                                    <h6> {!! __('No rotas found.') !!} </h6>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        if ($(".choices").length > 0) {
            $($(".choices")).each(function(index, element) {
                var id = $(element).attr('id');
                var searchEnabled = $(element).attr('searchEnabled');
                if (searchEnabled == undefined) {
                    searchEnabled = false;
                } else if (searchEnabled == 'true') {
                    searchEnabled = true;
                } else {
                    searchEnabled = false;
                }
                if (id !== undefined) {
                    var multipleCancelButton = new Choices(
                        '#' + id, {
                            loadingText: 'Loading...',
                            searchEnabled: searchEnabled,
                            removeItemButton: true,
                        }
                    );
                }
            });
        }
    </script>
    <script>
        $(document).on('click', '.day_view_filter_btn', function(e) {
            $('.day_view_filter').slideToggle(500);
        });

        $(document).on('click', '.date_sub', function(e) {
            var date_type = 'sub_date';
            dayviewfilter(date_type);
        });

        $(document).on('click', '.date_add', function(e) {
            var date_type = 'add_date';
            dayviewfilter(date_type);
        });

        $(document).on('change', '.emp_name', function(e) {
            var date_type = 'date';
            dayviewfilter(date_type);
        });

        $(document).on('change', '.loaction_name', function(e) {
            var date_type = 'date';
            dayviewfilter(date_type);
        });

        $(document).on('change', '.role_name', function(e) {
            var date_type = 'date';
            dayviewfilter(date_type);
        });

        $(document).on('change', '.rota_date', function(e) {
            var date_type = 'date_input';
            dayviewfilter(date_type);
        });

        function dayviewfilter(date_type = 'date') {
            var date = $('.date_add').parent().attr('data-date');
            var emp_name = $('.emp_name').val();
            var loaction_name = $('.loaction_name').val();
            var role_name = $('.role_name').val();

            if (date_type == 'date_input') {
                var date = $('.rota_date').val();
            }

            var data = {
                date: date,
                date_type: date_type,
                emp_name: emp_name,
                loaction_name: loaction_name,
                role_name: role_name,
            }

            $.ajax({
                url: '{{ route('rota.dashboard.dayview_filter') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    $('.day_view_tbl').html(data.returnHTML);
                    $('.fullcalendar-title').html(data.date);
                    $('.date_add').parent().attr('data-date', data.date);
                }
            });
        }
    </script>
@endpush
