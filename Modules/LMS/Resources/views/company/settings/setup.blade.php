<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        <a href="{{route('store.editproducts',[$store->slug, $store->theme_dir])}}" class=" list-group-item list-group-item-action border-0 {{ (request()->is('*edit-products*') ? 'active' : '')}}">{{__('Detail')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('change.blocks',[$store->slug, $store->theme_dir]) }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('*change-blocks*') ? 'active' : '')}}">{{__('Change Blocks')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('lms.seo.setting',[$store->slug, $store->theme_dir]) }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('*lms-seo-setting*') ? 'active' : '')}}">{{__('SEO')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('lms.pwa.setting',[$store->slug, $store->theme_dir]) }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('*lms-pwa-setting*') ? 'active' : '')}}">{{__('PWA')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('lms.qrcode.setting',[$store->slug, $store->theme_dir]) }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('*lms-qrcode-setting*') ? 'active' : '')}}">{{__('QR Code')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    </div>
</div>
