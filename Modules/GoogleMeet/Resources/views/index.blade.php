@extends('layouts.main')
@section('page-title')
    {{ __('Google Meet') }}
@endsection
@section('page-breadcrumb')
    {{ __('Google Meet') }}
@endsection
@push('css')
@endpush
@section('page-action')
    @if(company_setting('google_meet_token') != null && company_setting('google_meet_refresh_token') != null && check_file(company_setting('google_meet_json_file')) && !empty(company_setting('google_meet_json_file')))
        <div>
            <a href="{{ route('googlemeet.calender') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Calendar View') }}">
                <i class="ti ti-calendar"></i>
            </a>
            @permission('googlemeet create')
                <a href="#" class="btn btn-sm btn-primary" data-size="lg" data-url="{{ route('googlemeet.create') }}"
                    data-ajax-popup="true" data-title="{{ __('Create Google Meet') }}" data-bs-toggle="tooltip"
                    data-bs-original-title="{{ __('Create') }}">
                    <i class="ti ti-plus"></i>
                </a>
            @endpermission
        </div>
    @endif    

@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">

            @if(company_setting('google_meet_token') != null && company_setting('google_meet_refresh_token') != null)
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0 pc-dt-simple" id="d">
                                <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Invitees') }}</th>
                                        <th>{{ __('Meeting Date / Time') }}</th>
                                        <th>{{ __('Duration') }}</th>
                                        <th>{{ __('URL') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        @if (Laratrust::hasPermission('googlemeet show') || Laratrust::hasPermission('googlemeet delete'))
                                            <th class="text-center">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($meetings as $m)
                                        <tr>
                                            <td>{{ $m->title }}</td>
                                            <td>
                                                <div class="user-group">
                                                    @if (!empty($m->getMembers($m->id)))
                                                        @foreach ($m->getMembers($m->id) as $user)
                                                            <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ $user->name }}"
                                                                @if ($user->avatar) src="{{ get_file($user->avatar) }}" @else src="{{ get_file('avatar.png') }}" @endif
                                                                class="rounded-circle " width="25" height="25">
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{  company_date_formate($m->start_date) }} / {{ company_Time_formate($m->start_date)  }}</td>
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
                                                <span class="badge fix_badges bg-success p-2 px-3 rounded">{{ __($m->status) }}</span>
                                            </td>
                                            @if (Laratrust::hasPermission('googlemeet show') || Laratrust::hasPermission('googlemeet delete'))
                                                <td class="text-center">
                                                    <span>
                                                        @permission('googlemeet show')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="#"
                                                                    data-url="{{ route('googlemeet.show', $m->id) }}"
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

                                                        @permission('googlemeet delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['googlemeet.destory', $m->id],
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
            @else       
                <div class="card">
                    <div class="card-header pb-3">
                        <i class="ti ti-info-circle pointer h2 text-primary"></i>
                        <span class="h4">{{ __('Info') }}</span>
                    </div>
                    <div class="card-body">
                        @if(check_file(company_setting('google_meet_json_file')) && !empty(company_setting('google_meet_json_file')))
                            <div class="row">
                                <div class="col-auto">
                                    <p class="text-danger">{{ __('You haven\'t authorized your google account to Create Google Meeting. Click ') }} <a href="{{ route('auth.googlemeet') }}">{{ __('here') }}</a>{{ __(' to authorize.') }}</p>
                                </div>
                            </div>
                        @else   
                            <div class="row">
                                <div class="col-auto">
                                    <p class="text-danger">{{ __('You haven\'t uploaded your Google Meet Credentials JSON file. Please upload it by opening the') }} <a href="{{ url('settings#googlemeet-sidenav') }}">{{ __(' Settings!') }}</a></p>
                                </div>
                            </div> 
                        @endif    
                    <div>
                </div>
            @endif    

        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
@endpush
