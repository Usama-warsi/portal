<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">

        @permission('rotabranch manage')
            <a href="{{ route('branches.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('branch*') ? 'active' : '' }}">{{ __('Branch') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

         @permission('rotadepartment manage')
            <a href="{{ route('departments.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('department*') ? 'active' : '' }}">{{ __('Department') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('rotadesignation manage')
            <a href="{{ route('designations.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('designation*') ? 'active' : '' }}">{{ __('Designation') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('rotaleavetype manage')
            <a href="{{ route('leavestype.index') }}"
                class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'leavestype.index' ? 'active' : '' }}">{{ __('Leave Type') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

    </div>
</div>
