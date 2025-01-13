@extends('layouts.main')

@section('page-title')
    {{__('Sales Agent')}}
@endsection

@section('page-breadcrumb')
    {{ __('Sales Agent')}} ,{{ __('Programs')}}
@endsection

@section('page-action')
    <div>
        @permission('programs create')
            <a  href="{{ route('programs.create') }}" class="btn btn-sm btn-primary"  
                data-title="{{ __('Create New Program') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    @if(\Auth::user()->type != 'salesagent')    
                                        <th>{{ __('Sales Agents') }}</th>
                                    @endif
                                    <th>{{ __('From') }}</th>
                                    <th>{{ __('To') }}</th>
                                    @if (Laratrust::hasPermission('salesagent programs show') )
                                        <th>{{ __('Status') }}</th>
                                    @endif
                                    @if (Laratrust::hasPermission('salesagent programs show') ||Laratrust::hasPermission('programs edit') || Laratrust::hasPermission('salesagent delete') || Laratrust::hasPermission('salesagent show'))
                                        <th width="10%"> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $getUsersData = \Modules\SalesAgent\Entities\Program::getUsersData()
                                @endphp
                                @foreach ($programs as $k => $program)
                                    <tr class="font-style">
                                        <td><a href="{{ route('programs.show', \Crypt::encrypt($program['id'])) }}" class="">{{ $program['name'] }}</a></td>
                                        @if(\Auth::user()->type != 'salesagent')    
                                            <td class="user-group">
                                                {{-- @foreach ($program->getSalesAgent($program->id) as $user)
                                                        <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ $user->name }}"
                                                            @if ($user->avatar) src="{{ get_file($user->avatar) }}" @else src="{{ get_file('avatar.png') }}" @endif
                                                            class="rounded-circle " width="25" height="25">
                                                @endforeach --}}
                                                @foreach (explode(',' , $program->sales_agents_applicable) as $user)
                                                    <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ $getUsersData[$user]['name'] }}"
                                                        @if ($getUsersData[$user]['avatar']) src="{{ get_file($getUsersData[$user]['avatar']) }}" @else src="{{ get_file('avatar.png') }}" @endif
                                                        class="rounded-circle " width="25" height="25">
                                                @endforeach
                                            </td>
                                        @endif
                                        <td>{{ company_date_formate($program['from_date']) }}</td>
                                        <td>
                                            @if ($program['to_date'] < date('Y-m-d'))
                                                <p class="text-danger">
                                                    {{ company_date_formate($program['to_date']) }}</p>
                                            @else
                                                {{ company_date_formate($program['to_date']) }}
                                            @endif
                                        </td>
                                        @if (Laratrust::hasPermission('salesagent programs show') )
                                            <td>
                                                @if (in_array(\Auth::user()->id, explode(',', $program->sales_agents_applicable)))
                                                    <span
                                                        class="badge fix_badges bg-primary  p-2 px-3 rounded bill_status">{{ __('Joined') }}</span>
                                                @elseif(in_array(\Auth::user()->id, explode(',', $program->requests_to_join)))
                                                    <span
                                                        class="badge fix_badges bg-info p-2 px-3 rounded bill_status">{{ __('Requested') }}</span>
                                                @else
                                                    <span
                                                        class="badge fix_badges bg-secondary p-2 px-3 rounded bill_status">{{ __('Not yet participated') }}</span>
                                                @endif
                                            </td>
                                        @endif
                                        @if (Laratrust::hasPermission('salesagent programs show') || Laratrust::hasPermission('programs edit') || Laratrust::hasPermission('programs delete') || Laratrust::hasPermission('programs show'))
                                            <td class="Action">
                                                <span>
                                                    @if (Laratrust::hasPermission('salesagent programs show') || Laratrust::hasPermission('programs show'))
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="{{ route('programs.show', \Crypt::encrypt($program['id'])) }}"
                                                                class="mx-3 btn btn-sm align-items-center"
                                                                data-bs-toggle="tooltip" title="{{ __('View') }}">
                                                                <i class="ti ti-eye text-white text-white"></i>
                                                            </a>
                                                        </div>
                                                        @if((Laratrust::hasPermission('salesagent programs show')) && (!in_array(\Auth::user()->id, explode(',', $program->sales_agents_applicable))) && (!in_array(\Auth::user()->id, explode(',', $program->requests_to_join))))
                                                            
                                                            <div class="action-btn bg-primary ms-2">
                                                            <a href="{{ route('salesagent.program.send.request', [$program['id']]) }}"
                                                                    class="mx-3 btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip" title="{{ __('Send Request') }}">
                                                                    <i class="ti ti-arrow-forward-up text-white text-white"></i>
                                                                </a>
                                                            </div>
                                                            
                                                        @endif
                                                    @endif
                                                    @permission('programs edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a  href="{{ route('programs.edit', $program['id']) }}" 
                                                                class="mx-3 btn btn-sm  align-items-center"
                                                                data-size="lg" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Sales Agent') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @if (!empty($program['id']))
                                                        @permission('programs delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {{ Form::open(['route' => ['programs.destroy', $program['id']], 'class' => 'm-0']) }}
                                                                @method('DELETE')
                                                                <a
                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="Delete" aria-label="Delete"
                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                    data-confirm-yes="delete-form-{{ $program['id'] }}"><i
                                                                        class="ti ti-trash text-white text-white"></i></a>
                                                                {{ Form::close() }}
                                                            </div>
                                                        @endpermission
                                                    @endif
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
