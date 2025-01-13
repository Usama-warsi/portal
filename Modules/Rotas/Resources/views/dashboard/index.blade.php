@extends('layouts.main')

@section('page-title')
    {{ __('Dashboard') }}
@endsection
@push('css')
<style>
.calender_location_active{
    font-weight: 600 !important;
    color: #000 !important;
}
</style>
@endpush

@section('page-breadcrumb')
    {{ __('Rotas') }}
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css') }}" />

@endpush

@section('page-action')
    <div>
        @if (Auth::user()->type == 'company')
                <button type="button" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="ti ti-flag" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ __('Filter Role') }}"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end calender_locatin_list">
                    <a class="dropdown-item calender_location_active" data-location='0'
                        onclick="filter_location(0)">{{ __('Select All') }}</a>
                        @foreach ($designations as $designation)
                        <a class="dropdown-item" data-location='{{ $designation['id'] }}'
                        onclick="filter_location({{ $designation['id'] }})">{{ $designation['name'] }}</a>
                    @endforeach
                </div>
        @endif
            <button type="button" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="ti ti-dots-vertical" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('View') }}"></i>
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
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Calendar') }}</h5>
                </div>
                <div class="card-body">
                    <div id='calendar' class='calendar' data-toggle="calendar"></div>

                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Current Month events') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="event-cards list-group list-group-flush w-100">
                        <div class="row align-items-center justify-content-between">
                            <div class=" align-items-center">
                                @forelse ($current_month_rotas as $item)
                                <li class="list-group-item card mb-3">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                <div class="theme-avtar bg-primary"
                                                    style="background-color: {{ !empty($item->getrotarole->color) ? $item->getrotarole->color : '#8492a6' }} !important">
                                                    <i class="ti ti-building-bank"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="m-0">
                                                        {{ $item->getrotauser->name ?? 'Deleted User'}}
                                                        <small class="text-muted text-xs">
                                                        </small>
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ date('Y M d', strtotime($item->rotas_date)) }}
                                                        {{ company_Time_formate($item['start_time']) }} - {{ company_Time_formate($item['end_time']) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item card mb-3">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                {{ __('No Rotas Found.') }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforelse
                            </div>
                            </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('Modules/Rotas/Resources/assets/js/main.min.js') }}"></script>

    <script>
         var feed_calender = {!! $feed_calender !!};
        function filter_location(location_id = 0) {
            var data = {
                location_id: location_id,
            }
            $.ajax({
                url: '{{ route('rota.dashboard.location_filter') }}',
                method: 'post',
                data: data,
                success: function(data) {
                    var feed_calender = data;

                    $('.calender_locatin_list a').removeClass('calender_location_active');
                    $('.calender_locatin_list a[data-location="' + location_id + '"]').addClass(
                        'calender_location_active');
                        $('.callne').html("<div id='calendar' class='calendar' data-toggle='calendar'></div>");

                    calenderrr(feed_calender);
                }
            });
        }

        $(document).ready(function() {
            calenderrr(feed_calender)

            $(this).find('.fc-daygrid-block-event').removeClass("fc-daygrid-event");
        });

        function calenderrr(feed_calender) {
            var etitle;
            var etype;
            var etypeclass;

            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    timeGridDay: "{{__('Day')}}",
                    timeGridWeek: "{{__('Week')}}",
                    dayGridMonth: "{{__('Month')}}"
                },
                themeSystem: 'bootstrap',
                slotDuration: '00:10:00',

                navLinks: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                handleWindowResize: true,
                events: feed_calender,
                eventContent: function(event, element, view) {
                    var customHtml = event.event._def.extendedProps.html;
                    return {
                        html: customHtml
                    }
                }


            });
            calendar.render();
        }
    </script>
@endpush
