
<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        <a href="{{route('course-category.index')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('course-category*') ? 'active' : '')}}">{{__('Category')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('course-subcategory.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('course-subcategory*') ? 'active' : '')}}">{{__('Sub Category')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
    </div>
</div>
