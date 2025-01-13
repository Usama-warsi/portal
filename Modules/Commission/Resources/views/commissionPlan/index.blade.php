@extends('layouts.main')
@section('page-title')
    {{__('Manage Commission')}}
@endsection
@section('page-breadcrumb')
    {{__('Commission Plan')}}
@endsection

@section('page-action')
    <div>
        @permission('commission plan create')
            <a class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Create') }}" data-ajax-popup="true"
                data-url="{{ route('commission-plan.create') }}" data-size="md" data-title="{{ __('Create Commission Plan') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
<div class="row">
   <div class="col-xl-12">
       <div class="card">
           <div class="card-body table-border-style">
               <div class="table-responsive">
                   <table class="table mb-0 pc-dt-simple" id="products">
                       <thead>
                       <tr>
                           <th >{{__('Name')}}</th>
                           <th >{{__('Start Date')}}</th>
                           <th>{{__('End Date')}}</th>
                           <th>{{__('USer')}}</th>
                           @if (Laratrust::hasPermission('commission plan delete') || Laratrust::hasPermission('commission plan edit'))
                               <th>{{__('Action')}}</th>
                           @endif
                       </tr>
                       </thead>
                       <tbody>

                           @foreach ($commissionPlans as $commissionPlan)
                                @php
                                $user = \App\Models\User::find($commissionPlan->user_id);
                                @endphp
                               <tr class="font-style">
                                   <td>{{ $commissionPlan->name }}</td>
                                   <td>{{ $commissionPlan->start_date }}</td>
                                   <td>{{ $commissionPlan->end_date }}</td>
                                   <td>{{ $commissionPlan->CommissionUser($commissionPlan->user_id)}}</td>
                                   @if (Laratrust::hasPermission('commission plan delete') || Laratrust::hasPermission('commission plan edit'))
                                   <td class="Action">
                                       @permission('commission plan edit')
                                           <div class="action-btn bg-info ms-2">
                                               <a  class="mx-3 btn btn-sm align-items-center" href="{{ route('commission-plan.edit',$commissionPlan->id) }}" data-bs-toggle="tooltip" title="{{__('Edit')}}">
                                                   <i class="ti ti-pencil text-white"></i>
                                               </a>
                                           </div>
                                       @endpermission
                                       @permission('commission plan delete')
                                           <div class="action-btn bg-danger ms-2">
                                               {!! Form::open(['method' => 'DELETE', 'route' => ['commission-plan.destroy', $commissionPlan->id],'id'=>'delete-form-'.$commissionPlan->id]) !!}
                                               <a  class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white text-white"></i></a>
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
</div>
@endsection
