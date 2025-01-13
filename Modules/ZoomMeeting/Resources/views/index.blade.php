@extends('layouts.main')
@section('page-title')
    {{ __('Zoom Meeting') }}
@endsection
@section('page-breadcrumb')
    {{ __('Zoom Meeting') }}
@endsection
@push('css')
@endpush
@section('page-action')
    <div>
        <a href="{{ route('zoom-meeting.calender') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Calendar View') }}">
            <i class="ti ti-calendar"></i>
        </a>
        @permission('zoommeeting create')
            <a href="#" class="btn btn-sm btn-primary" data-size="lg" data-url="{{ route('zoom-meeting.create') }}"
                data-ajax-popup="true" data-title="{{ __('Create Zoom Meeting') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}">
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
                        <table class="table mb-0 pc-dt-simple" id="d">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Invitees') }}</th>
                                    <th>{{ __('Meeting Date/Time') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('URL') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    @if (Laratrust::hasPermission('zoommeeting show') || Laratrust::hasPermission('zoommeeting delete'))
                                        <th>{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($meetings as $m)
                                    <tr>
                                        <td>{{ $m->title }}</td>
                                        <td>
                                            <div class="user-group">
                                                @if (!empty($m->users))
                                                    @foreach ($m->users as $user)
                                                        <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ $user->name }}"
                                                            @if ($user->avatar) src="{{ get_file($user->avatar) }}" @else src="{{ get_file('avatar.png') }}" @endif
                                                            class="rounded-circle " width="25" height="25">
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $m->start_date }}</td>
                                        <td>{{ $m->duration }} {{ __('minute') }}</td>
                                        <td>
                                            @if (\Auth::user()->id == $m->created_by)
                                                <a target="_blank" href="{{ $m->start_url }}" class="d-block mb-1">
                                                    <span class="d-inline-block">{{ __('Start URL') }}</span>
                                                    <i data-feather="external-link"> </i>
                                                </a>
                                            @endif
                                            <a target="_blank" href="{{ $m->join_url }}" class="d-block mb-1">
                                                <span class="pl-3">{{ __('Join URL') }}</span>
                                                <i data-feather="external-link" class="pr-2"> </i>
                                            </a>
                                        </td>
                                        <td>
                                            @if ($m->status == 'waiting')
                                                <span
                                                    class="badge fix_badges bg-warning p-2 px-3 rounded">{{ __('Waiting') }}</span>
                                            @elseif ($m->status == 'start')
                                                <span
                                                    class="badge fix_badges bg-success p-2 px-3 rounded">{{ __('Start') }}</span>
                                            @else
                                                <span
                                                    class="badge fix_badges bg-danger p-2 px-3 rounded">{{ __($m->status) }}</span>
                                            @endif
                                        </td>
                                        @if (Laratrust::hasPermission('zoommeeting show') || Laratrust::hasPermission('zoommeeting delete'))
                                            <td>
                                                <span>
                                                    @permission('zoommeeting show')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="#"
                                                                data-url="{{ route('zoom-meeting.show', $m->id) }}"
                                                                data-size="md" data-ajax-popup="true"
                                                                data-title="{{ __('Meeting Info') }}"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                data-bs-whatever="{{ __('View Meeting') }}"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="{{ __('View Meeting') }}">
                                                                <span class="text-white">
                                                                    <i class="ti ti-eye"></i>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    @endpermission

                                                    @permission('zoommeeting delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['zoom-meeting.destory', $m->id],
                                                                'id' => 'delete-form-' . $m->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endpermission
                                                </span>
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
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
@endpush
