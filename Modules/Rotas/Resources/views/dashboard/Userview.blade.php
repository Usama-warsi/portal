@extends('layouts.main')
@push('css')
    <script src="{{ asset('Modules/Rotas/Resources/js/moment.min.js') }}"></script>
@endpush
@section('page-title')
    {{ __('User View') }}
@endsection
@section('page-breadcrumb')
    {{ __('User View') }}
@endsection
@section('content')
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-8">
                            <h5 class="fullcalendar-title h4 d-inline-block">{{ $cur_year . '-' . $cur_month }}

                            </h5> &nbsp;&nbsp;
                            <div class="btn-group" role="group" aria-label="Basic example" data-month="{{ $cur_month }}"
                                data-year="{{ $cur_year }}">
                                <a class="btn btn-sm btn-primary date_sub m-1 date_click">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                                <a class="btn btn-sm btn-primary date_add m-1 date_click">
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
            </div>
        </div>

        <div class="card day_view_filter" style="display: none;">
            <div class="card-body p-3 m-0 mt-2">
                <div class="row">
                    <div class="form-group col-md-3 mb-0">
                        {{ Form::label('', __('Date'), ['class' => 'form-control-label']) }}
                        <input type="month" class="rota_date form-control h_40i" style="height: 40px;" id="datepicker"
                            value="{{ $cur_year . '-' . $cur_month }}">
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


        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive day_view_tbl">
                        <table class="table mb-0 pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Date') }}</th>
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
                                                    <div class="media-body ms-3">
                                                        <a href="#"
                                                            class="text-dark">{{ !empty($rota->getrotauser) ? $rota->getrotauser->name : '-' }}</a>
                                                    </div>
                                                </div>
                                            </th>
                                            <td> {{ $rota->rotas_date }} </td>
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
@endsection

@push('scripts')
    <script src="{{ asset('Modules/Rotas/Resources/js/custom.js') }}"></script>
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

        $(document).on('click', '.date_click', function(e) {
            var rota_date = $('.rota_date').val() + '-01';
            var rota_date12 = $('.rota_date').val();
            var futureMonth = rota_date12;

            if ($(this).hasClass('date_sub')) {
                var futureMonth = moment(rota_date).subtract(1, 'month').format("YYYY-MM");
            }
            if ($(this).hasClass('date_add')) {
                var futureMonth = moment(rota_date).add(1, 'month').format("YYYY-MM");
            }

            $('.rota_date').val(futureMonth);
            $('.fullcalendar-title').html(futureMonth);
            $('.rota_date').trigger('change');
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
            var date_type = 'date';
            dayviewfilter(date_type);
        });

        function dayviewfilter(date_type = 'date') {
            var date = $('.rota_date').val();
            var emp_name = $('.emp_name').val();
            var loaction_name = $('.loaction_name').val();

            var data = {
                date: date,
                date_type: date_type,
                emp_name: emp_name,
                loaction_name: loaction_name,
            }

            $.ajax({
                url: '{{ route('rota.dashboard.userviewfilter') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    $('.day_view_tbl').html(data.returnHTML);
                }
            });
        }
    </script>
@endpush
