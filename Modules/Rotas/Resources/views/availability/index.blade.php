@extends('layouts.main')
@section('page-title')
    {{ __('Manage availability') }}
@endsection
@section('page-breadcrumb')
    {{ __('availability') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Rotas/Resources/js/jquery-schedule-master/dist/jquery.schedule.css') }}">
@endpush
@section('page-action')
    <div>

        @permission('availability create')
            <a class="btn btn-sm btn-primary" data-ajax-avalibility="true" data-size="lg"
                data-title="{{ __('Create New Availability') }}" data-url="{{ route('availabilitie.create') }}"
                data-toggle="tooltip" title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
       @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    @if (Auth::user()->type == 'company')
                                        <th scope="sort">{{ __('Name') }}</th>
                                    @endif
                                    <th scope="sort">{{ __('Title') }}</th>
                                    <th scope="sort">{{ _('Effective Dates') }}</th>
                                    {{-- <th scope="sort">{{ __('Action') }}</th> --}}
                                    @if (Laratrust::hasPermission('availability edit') || Laratrust::hasPermission('availability delete'))
                                        <th scope="sort"> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($availabilitys as $availability)
                                    <tr data-id="{{ $availability->employee_id }}">
                                        @if (Auth::user()->type == 'company')
                                            <td>{{ $availability->getUserInfo->name }}</td>
                                        @endif
                                        <td> {{ $availability->name }}</td>
                                        <td> {{ $availability->getAvailabilityDate() }} </td>
                                        <td class="Action">
                                            <span>
                                                @permission('availability edit')
                                                    <button type="button"
                                                        class="btn-white rounded-circle border-0 edit_schedule bg-transparent"
                                                        data-availability-json="{{ $availability->availability_json }}">
                                                        <div class="action-btn bg-info ms-2">
                                                            <a data-url="{{ route('availabilitie.edit', $availability->id) }}"
                                                                data-size="lg" data-ajax-avalibility="true"
                                                                data-title="{{ __('Edit Availability') }}"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center">
                                                                <i class="ti ti-pencil text-white" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" title="{{ __('Edit') }}"></i>
                                                            </a>
                                                        </div>
                                                    </button>
                                               @endpermission
                                                @permission('availability delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {{ Form::open(['route' => ['availabilitie.destroy', $availability->id], 'class' => 'm-0']) }}
                                                        @method('DELETE')
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $availability->id }}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        {{ Form::close() }}
                                                    </div>
                                               @endpermission

                                            </span>
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

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script id="add_schedule"
        src="{{ asset('Modules/Rotas/Resources/js/jquery-schedule-master/dist/jquery.schedule.js') }}"
        data-src="{{ asset('Modules/Rotas/Resources/js/jquery-schedule-master/dist/jquery.schedule.js') }}"></script>
    <script id="edit_schedule"
        src="{{ asset('Modules/Rotas/Resources/js/jquery-schedule-master/dist/jquery.scheduleedit.js') }}"
        data-src="{{ asset('Modules/Rotas/Resources/js/jquery-schedule-master/dist/jquery.scheduleedit.js') }}"></script>
    <script src="{{ asset('Modules/Rotas/Resources/js/custom.js') }}"></script>
    <script>
        function availabilitytablejs() {
            $('#schedule4').jqs({
                periodColors: [
                    ['rgba(0, 200, 0, 0.5)', '#0f0', '#000'],
                    ['rgba(200, 0, 0, 0.5)', '#f00', '#000'],
                ],
                periodTitle: '',
                periodBackgroundColor: 'rgba(0, 200, 0, 0.5)',
                periodBorderColor: '#000',
                periodTextColor: '#fff',
                periodRemoveButton: 'Remove please !',
                onRemovePeriod: function(period, jqs) {},
                onAddPeriod: function(period, jqs) {},
                onClickPeriod: function(period, jqs) {},
                onDuplicatePeriod: function(event, period, jqs) {},
                onPeriodClicked: function(event, period, jqs) {}
            });
        }

        function editavailabilitytablejs(data = []) {
            $('#schedule5').jqs({
                data: data,
                days: 7,
                periodColors: [
                    ['rgba(0, 200, 0, 0.5)', '#0f0', '#000'],
                    ['rgba(200, 0, 0, 0.5)', '#f00', '#000'],
                ],
                periodTitle: '',
                periodBackgroundColor: 'rgba(0, 200, 0, 0.5)',
                periodBorderColor: '#000',
                periodTextColor: '#fff',
                periodRemoveButton: 'Remove please !',
                onRemovePeriod: function(period, jqs) {},
                onAddPeriod: function(period, jqs) {},
                onClickPeriod: function(period, jqs) {},
                onDuplicatePeriod: function(event, period, jqs) {},
                onPeriodClicked: function(event, period, jqs) {}
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            availabilitytablejs();
            editavailabilitytablejs();
            $(document).on('change', '.search-user-ava', function() {
                var value = $(this).val();
                $('.avalabilty_table tbody>tr').hide();
                if (value == 'all0') {
                    $('.avalabilty_table tbody>tr').show();
                } else {
                    $('.avalabilty_table tbody>tr[data-id="' + value + '"]').show();
                }
            });
        });
    </script>
@endpush
