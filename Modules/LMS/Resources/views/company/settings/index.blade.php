<div id="lms-store-sidenav" class="card">
    <div class="card-header">
        <h5>{{__('Lms Store Settings')}}</h5>
        <small class="text-muted">{{__('')}}</small>
    </div>
    {{  Form::model($store_settings,['route' => ['lms.store.setting'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="card-body pt-0">
            <div class=" setting-card">
                <div class="row mt-2">
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="small-title">{{ __('Logo') }}</h5>
                            </div>
                            <div class="card-body setting-card setting-logo-box p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="logo-content logo-set-bg  text-center py-2">
                                            <a  @if(!empty($store_settings['logo'])) href="{{get_file($store_settings['logo'])}}" @else  href="{{asset('Modules/LMS/Resources/assets/image/logo.png')}}" @endif target="_blank">
                                                <img  @if(!empty($store_settings['logo'])) src="{{get_file($store_settings['logo'])}}" @else  src="{{asset('Modules/LMS/Resources/assets/image/logo.png')}}" @endif
                                                    id="blah5" width="170px"
                                                    class="img_setting">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="choose-files mt-4">
                                            <label for="logo" class="form-label d-block">
                                                <div class="bg-primary m-auto ">
                                                    <i
                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                    <input type="file" name="logo"
                                                        id="logo" class="form-control file"
                                                        data-filename="company_logo_update"
                                                        onchange="document.getElementById('blah5').src = window.URL.createObjectURL(this.files[0])">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="small-title">{{ __('Invoice Logo') }}</h5>
                            </div>
                            <div class="card-body setting-card setting-logo-box p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="logo-content logo-set-bg  text-center py-2">
                                            <a @if(!empty($store_settings['invoice_logo'])) href="{{get_file($store_settings['invoice_logo'])}}" @else  href="{{ asset('Modules/LMS/Resources/assets/image/invoice_logo.png')  }}" @endif target="_blank">
                                                <img @if(!empty($store_settings['invoice_logo'])) src="{{get_file($store_settings['invoice_logo'])}}" @else  src="{{ asset('Modules/LMS/Resources/assets/image/invoice_logo.png')  }}" @endif
                                                    id="blah10" width="170px"
                                                    class="img_setting">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="choose-files mt-4">
                                            <label for="store_invoice_logo" class="form-label d-block">
                                                <div class=" bg-primary m-auto ">
                                                    <i
                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                    <input type="file" name="invoice_logo"
                                                        id="store_invoice_logo" class="form-control file"
                                                        data-filename="invoice_logo"
                                                        onchange="document.getElementById('blah10').src = window.URL.createObjectURL(this.files[0])">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('name', __('Store Name'), ['class' => 'form-label']) }}
                        {!! Form::text('name', !empty($store_settings['name']) ? $store_settings['name'] :'', ['class' => 'form-control', 'placeholder' => __('Store Name')]) !!}
                        @error('name')
                            <span class="invalid-name" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                        {{ Form::text('email', !empty($store_settings['email']) ? $store_settings['email'] :'', ['class' => 'form-control', 'placeholder' => __('Email')]) }}
                        @error('email')
                            <span class="invalid-email" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6 py-4">
                        <div class="radio-button-group mts">
                            <div class="item">
                                <label
                                    class="btn btn-outline-primary {{ $store_settings['enable_storelink'] == 'on' ? 'active' : '' }}">
                                    <input type="radio" class="domain_click  radio-button"
                                        name="enable_domain" value="enable_storelink"
                                        id="enable_storelink"
                                        {{ $store_settings['enable_storelink'] == 'on' ? 'checked' : '' }}>
                                    {{ __('Store Link') }}
                                </label>
                            </div>
                            {{-- <div class="item">
                                <label
                                    class="btn btn-outline-primary {{ $store_settings['enable_domain'] == 'on' ? 'active' : '' }}">
                                    <input type="radio" class="domain_click radio-button"
                                        name="enable_domain" value="enable_domain"
                                        id="enable_domain"
                                        {{ $store_settings['enable_domain'] == 'on' ? 'checked' : '' }}>
                                    {{ __('Domain') }}
                                </label>
                            </div>
                            <div class="item">
                                <label
                                    class="btn btn-outline-primary {{ $store_settings['enable_subdomain'] == 'on' ? 'active' : '' }}">
                                    <input type="radio" class="domain_click radio-button"
                                        name="enable_domain" value="enable_subdomain"
                                        id="enable_subdomain"
                                        {{ $store_settings['enable_subdomain'] == 'on' ? 'checked' : '' }}>
                                    {{ __('Sub Domain') }}
                                </label>
                            </div> --}}
                        </div>
                        <div class="text-sm mt-2" id="domainnote" style="display: none">
                            {{ __('Note : Before add custom domain, your domain A record is pointing to our server IP :') }}{{ $serverIp }}
                            <br>
                        </div>
                    </div>
                    <div class="form-group col-md-6" id="StoreLink"
                        style="{{ $store_settings['enable_storelink'] == 'on' ? 'display: block' : 'display: none' }}">
                        {{ Form::label('store_link', __('Store Link'), ['class' => 'form-label']) }}
                        <div class="input-group">
                            <input type="text" value="{{ $store_settings['store_url'] }}"
                                id="myInput" class="form-control d-inline-block"
                                aria-label="Recipient's username"
                                aria-describedby="button-addon2" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button"
                                    onclick="myFunction()" id="button-addon2"><i
                                        class="far fa-copy"></i>
                                    {{ __('Copy Link') }}</button>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="form-group col-md-6 domain"
                        style="{{ $store_settings['enable_domain'] == 'on' ? 'display:block' : 'display:none' }}">
                        {{ Form::label('store_domain', __('Custom Domain'), ['class' => 'form-label']) }}
                        {{ Form::text('domains', $store_settings['domains'], ['class' => 'form-control', 'placeholder' => __('xyz.com')]) }}
                    </div>
                    <div class="form-group col-md-6 sundomain"
                        style="{{ $store_settings['enable_subdomain'] == 'on' ? 'display:block' : 'display:none' }}">
                        {{ Form::label('store_subdomain', __('Sub Domain'), ['class' => 'form-label']) }}
                        <div class="input-group">
                            {{ Form::text('subdomain', $store_settings['slug'], ['class' => 'form-control', 'placeholder' => __('Enter Domain'), 'readonly']) }}
                            <div class="input-group-append">
                                <span class="input-group-text"
                                    id="basic-addon2">.{{ $subdomain_name }}</span>
                            </div>
                        </div>
                    </div> --}}

                    <div class="form-group col-md-4">
                        {{ Form::label('tagline', __('Tagline'), ['class' => 'form-label']) }}
                        {{ Form::text('tagline', !empty($store_settings['tagline']) ? $store_settings['tagline'] :null, ['class' => 'form-control', 'placeholder' => __('Tagline')]) }}
                        @error('tagline')
                            <span class="invalid-tagline" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                        {{ Form::text('address', !empty($store_settings['address']) ? $store_settings['address'] :null, ['class' => 'form-control', 'placeholder' => __('Address')]) }}
                        @error('address')
                            <span class="invalid-address" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
                        {{ Form::text('city', !empty($store_settings['city']) ? $store_settings['city'] :null, ['class' => 'form-control', 'placeholder' => __('City')]) }}
                        @error('city')
                            <span class="invalid-city" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::label('state', __('State'), ['class' => 'form-label']) }}
                        {{ Form::text('state', !empty($store_settings['state']) ? $store_settings['state'] :null, ['class' => 'form-control', 'placeholder' => __('State')]) }}
                        @error('state')
                            <span class="invalid-state" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::label('zipcode', __('Zipcode'), ['class' => 'form-label']) }}
                        {{ Form::text('zipcode', !empty($store_settings['zipcode']) ? $store_settings['zipcode'] :null, ['class' => 'form-control', 'placeholder' => __('Zipcode')]) }}
                        @error('zipcode')
                            <span class="invalid-zipcode" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}
                        {{ Form::text('country', !empty($store_settings['country']) ? $store_settings['country'] :null, ['class' => 'form-control', 'placeholder' => __('Country')]) }}
                        @error('country')
                            <span class="invalid-country" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::label('store_default_language', __('Store Default Language'), ['class' => 'form-label']) }}
                        <div class="changeLanguage">
                            <select name="store_default_language" id="store_default_language"
                                class="form-control form-select">
                                @foreach (languages() as $key => $language)
                                    <option @if ($store_lang == $key) selected @endif
                                        value="{{ $key }}">
                                        {{ ucfirst($language) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-check-label" for="enable_rating"></label>
                        <div class="form-check form-check form-switch custom-control-inline mt-3">
                            <input type="checkbox" class="form-check-input" role="switch"
                                name="enable_rating" id="enable_rating"
                                {{ $store_settings['enable_rating'] == 'on' ? 'checked=checked' : '' }}>
                            {{ Form::label('enable_rating', __('Rating Display'), ['class' => 'form-check-label mb-3']) }}
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-check-label" for="blog_enable"></label>
                        <div class="form-check form-check form-switch custom-control-inline mt-3">
                            <input type="checkbox" class="form-check-input" role="switch"
                                name="blog_enable" id="blog_enable"
                                {{$store_settings['blog_enable'] == 'on' ? 'checked=checked' : '' }}>
                            {{ Form::label('blog_enable', __('Blog Menu Dispay'), ['class' => 'form-check-label mb-3']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12 pt-4">
                                <h5 class="h6 mb-0">{{ __('Footer') }}</h5>
                                <hr class="my-4">
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <i class="fas fa-copyright" aria-hidden="true"></i>
                                    {{ Form::label('footer_note', __('Footer'), ['class' => 'form-label']) }}
                                    {{ Form::text('footer_note', !empty($store_settings['footer_note']) ? $store_settings['footer_note'] :null, ['class' => 'form-control', 'placeholder' => __('Footer Note')]) }}
                                    @error('footer_note')
                                        <span class="invalid-footer_note" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('storejs', __('Store Custom JS'), ['class' => 'form-label']) }}
                            {{ Form::textarea('storejs', !empty($store_settings['storejs']) ? $store_settings['storejs'] :null, ['class' => 'form-control', 'rows' => 14, 'placeholder' => __('Store Custom JS')]) }}
                            @error('storejs')
                                <span class="invalid-storejs" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12 px-2">
                <div class="text-end">
                    @permission('delete store')
                        <button type="button" class="btn bs-pass-para btn-secondary btn-light"
                            data-title="{{ __('Delete') }}"
                            data-confirm="{{ __('Are You Sure?') }}"
                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                            data-confirm-yes="delete-form-{{ $store_settings->id }}">
                            <span class="text-black">{{ __('Delete Store') }}</span>
                        </button>
                    @endpermission
                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-primary mx-2']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    {!! Form::close() !!}
</div>

<div id="lms-theme-sidenav" class="card">
    <div class="card-header">
        <h5>{{__('Lms Theme Settings')}}</h5>
    </div>
    {{ Form::open(['route' => ['store.changetheme'], 'method' => 'POST']) }}
        <div class="card-body pt-0">
            <div class=" setting-card">
                <div class="row mt-2">
                    <div class="border border-primary rounded p-3">
                        <div class="row gy-4">
                            @foreach (\Modules\LMS\Entities\LmsUtility::themeOne() as $key => $v)
                            <div class="col-xl-4 col-lg-4 col-md-6 overflow-hidden cc-selector">
                                    <div class="border border-primary rounded">
                                        <div class="theme-card-inner">
                                            <div class="screen theme-image border rounded">
                                                <img src="{{ asset('Modules/LMS/Resources/assets/image/store_theme/' . $key . '/Home.png') }}"
                                                    class="color1 img-center pro_max_width pro_max_height {{ $key }}_img"
                                                    data-id="{{ $key }}">
                                            </div>
                                            <div class="theme-content mt-3">
                                                <div class="row gutters-xs justify-content-center"
                                                    id="{{ $key }}">
                                                    @foreach ($v as $css => $val)
                                                        <div class="col-auto">
                                                            <label class="colorinput">
                                                                <input name="theme_color" type="radio"
                                                                    value="{{ $css }}"
                                                                    data-key="theme{{ $loop->iteration }}"
                                                                    data-theme="{{ $key }}"
                                                                    data-imgpath="{{ $val['img_path'] }}"
                                                                    class="colorinput-input color-{{ $loop->index++ }}"
                                                                    {{ isset($store_settings['store_theme']) && $store_settings['store_theme'] == $css ? 'checked' : '' }}>
                                                                <span class="colorinput-color"
                                                                    style="background:#{{ $val['color'] }}"></span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                    <div class="col-auto">
                                                        @if (isset($store_settings['theme_dir']) && $store_settings['theme_dir'] == $key)
                                                            <a href="{{ route('store.editproducts', [$store_settings->slug, $key]) }}"
                                                                class="btn btn-outline-primary theme_btn" type="button"
                                                                id="button-addon2">{{ __('Edit') }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12 px-2">
                <div class="text-end">
                    <input id="themefile" name="themefile" type="hidden" value="theme1">
                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-primary']) }}
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>

<div id="certificate-sidenav" class="card">
    <div class="card-header">
        <h5>{{ __('Certificate Settings') }}</h5>
    </div>
    <div class="card-body">
        <form id="setting-form" method="post"
            action="{{ route('certificate.template.setting') }}">
            @csrf
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="font-weight-bold">{{ __('Certificate Variable') }}
                                </h6>
                                <div class="col-6 float-left">
                                    <p class="mb-1">{{ __('Store Name') }} : <span
                                            class="pull-right text-primary">{header_name}</span>
                                    </p>
                                    <p class="mb-1">{{ __('Student Name') }} : <span
                                            class="pull-right text-primary">{student_name}</span>
                                    </p>
                                    <p class="mb-1">{{ __('Course Time') }} : <span
                                            class="pull-right text-primary">{course_time}</span>
                                    </p>
                                    <p class="mb-1">{{ __('Course Name') }} : <span
                                            class="pull-right text-primary">{course_name}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="storejs"
                                        class="form-label">{store_name}</label>
                                    {{ Form::text('header_name', $store_settings->header_name, ['class' => 'form-control', 'placeholder' => '{header_name}']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row justify-content-between">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address"
                                            class="form-label">{{ __('Certificate Template') }}</label>
                                        <select class="form-control select2"
                                            name="certificate_template">
                                            @foreach (\Modules\LMS\Entities\LmsUtility::templateData()['templates'] as $key => $template)
                                                <option value="{{ $key }}"
                                                    {{ isset($store_settings->certificate_template) && $store_settings->certificate_template == $key ? 'selected' : '' }}>
                                                    {{ $template }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label
                                            class="form-label form-label">{{ __('Color Input') }}</label>
                                        <div class="row gutters-xs">
                                            @foreach (\Modules\LMS\Entities\LmsUtility::templateData()['colors'] as $key => $color)
                                                <div class="col-auto">
                                                    <label class="colorinput">
                                                        <input name="certificate_color"
                                                            type="radio"
                                                            value="{{ $color['hex'] }}"
                                                            class="colorinput-input"
                                                            {{ isset($store_settings->certificate_color) && $store_settings->certificate_color == $color['hex'] ? 'checked' : '' }}
                                                            data-gradiant='{{ $color['gradiant'] }}'>
                                                        <span class="colorinput-color"
                                                            style="background: #{{ $color['hex'] }}"></span>
                                                    </label>
                                                </div>
                                            @endforeach
                                            <input type="hidden" name="gradiant"
                                                id="gradiant"
                                                value="{{ $color['gradiant'] }} ">
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-md-2 mb-3 text-end align-items-end d-flex justify-content-end">
                                    <button class="btn btn-primary">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <iframe id="certificate_frame" class="certificate_iframe w-100"
                                frameborder="0"
                                src="{{ route('certificate.preview', [isset($store_settings->certificate_template) && !empty($store_settings->certificate_template) ? $store_settings->certificate_template : 'template1', isset($store_settings->certificate_color) && !empty($store_settings->certificate_color) ? $store_settings->certificate_color : 'b10d0d', isset($store_settings->certificate_gradiant) && !empty($store_settings->certificate_gradiant) ? $store_settings->certificate_gradiant : 'color-one']) }}"></iframe>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('Modules/LMS/Resources/assets/css/custom.css') }}">

<script>
    function myFunction() {
        var copyText = document.getElementById("myInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        toastrs('Success', 'Link copied', 'success');
    }
</script>
<script>
    $(document).on('change', '.domain_click#enable_storelink', function (e) {
        $('#StoreLink').show();
        $('.sundomain').hide();
        $('.domain').hide();
        $('#domainnote').hide();
        $( "#enable_storelink" ).parent().addClass('active');
        $( "#enable_domain" ).parent().removeClass('active');
        $( "#enable_subdomain" ).parent().removeClass('active');
    });
    $(document).on('change', '.domain_click#enable_domain', function (e) {
        $('.domain').show();
        $('#StoreLink').hide();
        $('.sundomain').hide();
        $('#domainnote').show();
        $( "#enable_domain" ).parent().addClass('active');
        $( "#enable_storelink" ).parent().removeClass('active');
        $( "#enable_subdomain" ).parent().removeClass('active');
    });
    $(document).on('change', '.domain_click#enable_subdomain', function (e) {
        $('.sundomain').show();
        $('#StoreLink').hide();
        $('.domain').hide();
        $('#domainnote').hide();
        $( "#enable_subdomain" ).parent().addClass('active');
        $( "#enable_domain" ).parent().removeClass('active');
        $( "#enable_domain" ).parent().removeClass('active');
    });
</script>
<script>
    $(document).on('click', 'input[name="theme_color"]', function() {
        var eleParent = $(this).attr('data-theme');
        $('#themefile').val(eleParent);
        // $('#themefile').val($(this).attr('data-key'));
        var imgpath = $(this).attr('data-imgpath');
        $('.' + eleParent + '_img').attr('src', imgpath);
    });

    $(document).ready(function() {
        setTimeout(function(e) {
            var checked = $("input[type=radio][name='theme_color']:checked");
            $('#themefile').val(checked.attr('data-theme'));
            // $('#themefile').val(checked.attr('data-key'));
            $('.' + checked.attr('data-theme') + '_img').attr('src', checked.attr('data-imgpath'));
        }, 300);
    });

    $(".color1").click(function() {
        var dataId = $(this).attr("data-id");
        $('#' + dataId).trigger('click');
        var first_check = $('#' + dataId).find('.color-0').trigger("click");
    });

    $(document).on("change", "select[name='certificate_template'], input[name='certificate_color']", function() {
                var template = $("select[name='certificate_template']").val();
                var color = $("input[name='certificate_color']:checked").val();
                var gradiant = $(this).data('gradiant');
                $('#gradiant').val(gradiant);
                $('#certificate_frame').attr('src', '{{ url('/certificate/preview') }}/' + template + '/' + color +
                    '/' + gradiant);
            });
</script>
