<!--Retainer Setting-->
@php
    $retainer_template = isset($settings['retainer_template']) ? $settings['retainer_template'] : '';
    $retainer_color = isset($settings['retainer_color']) ? $settings['retainer_color'] : '';
@endphp
<div id="retainer-print-sidenav" class="card">
    <div class="card-header">
        <h5>{{ __('Retainer Print Setting') }}</h5>
        <small class="text-muted">{{ __('Edit your Company Retainer details') }}</small>
    </div>
    <div class="bg-none">
        <div class="row company-setting">
            <form id="setting-form" method="post" action="{{ route('retainer.template.setting') }}"
                enctype ="multipart/form-data">
                @csrf
                <div class="card-header card-body ">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('retainer_prefix', __('Prefix'), ['class' => 'form-label']) }}
                                {{ Form::text('retainer_prefix', isset($settings['retainer_prefix']) ? $settings['retainer_prefix'] : '#RETAINER', ['class' => 'form-control', 'placeholder' => 'Enter Retaine Prefix']) }}
                            </div>

                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('retainer_starting_number', __('Starting Number'), ['class' => 'form-label']) }}
                                {{ Form::number('retainer_starting_number', isset($settings['retainer_starting_number']) ? $settings['retainer_starting_number'] : 1, ['class' => 'form-control', 'placeholder' => 'Enter Retaine Starting Number']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('retainer_footer_title', __('Footer Title'), ['class' => 'form-label']) }}
                                {{ Form::text('retainer_footer_title', isset($settings['retainer_footer_title']) ? $settings['retainer_footer_title'] : '', ['class' => 'form-control', 'placeholder' => 'Enter Footer Title']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('retainer_footer_notes', __('Footer Notes'), ['class' => 'form-label']) }}
                                {{ Form::textarea('retainer_footer_notes', isset($settings['retainer_footer_notes']) ? $settings['retainer_footer_notes'] : '', ['class' => 'form-control', 'rows' => '1', 'placeholder' => 'Enter Retaine Footer Notes']) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mt-2">
                                {{ Form::label('retainer_shipping_display', __('Shipping Display?'), ['class' => 'form-label']) }}
                                <div class=" form-switch form-switch-left">
                                    <input type="checkbox" class="form-check-input" name="retainer_shipping_display"
                                        id="retainer_shipping_display"
                                        {{ (isset($settings['retainer_shipping_display']) ? $settings['retainer_shipping_display'] : 'off') == 'on' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="retainer_shipping_display"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-header card-body">
                            <div class="form-group">
                                <label for="retainer_template" class="col-form-label">{{ __('Template') }}</label>
                                <select class="form-control" name="retainer_template" id="retainer_template">
                                    @foreach (templateData()['templates'] as $key => $template)
                                        <option value="{{ $key }}"
                                            {{ $retainer_template == $key ? 'selected' : '' }}>
                                            {{ $template }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{ __('Color Input') }}</label>
                                <div class="row gutters-xs">
                                    @foreach (templateData()['colors'] as $key => $color)
                                        <div class="col-auto">
                                            <label class="colorinput">
                                                <input name="retainer_color" type="radio" value="{{ $color }}"
                                                    class="colorinput-input"
                                                    {{ $retainer_color == $color ? 'checked' : '' }}>
                                                <span class="colorinput-color"
                                                    style="background: #{{ $color }}"></span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{ __('Retainer Logo') }}</label>
                                <div class="choose-files mt-5 ">
                                    <label for="retainer_logo">
                                        <div class=" bg-primary "> <i
                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                        <img id="blah7" class="mt-3" src="" width="70%" />
                                        <input type="file" class="form-control file" name="retainer_logo"
                                            id="retainer_logo" data-filename="retainer_logo_update"
                                            onchange="document.getElementById('blah7').src = window.URL.createObjectURL(this.files[0])">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mt-2 text-end">
                                <input type="submit" value="{{ __('Save Changes') }}"
                                    class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @if (!empty($retainer_template) && !empty($retainer_color))
                            <iframe id="retainer_frame" class="w-100 h-100" frameborder="0"
                                src="{{ route('retainer.preview', [$retainer_template, $retainer_color]) }}"></iframe>
                        @else
                            <iframe id="retainer_frame" class="w-100 h-100" frameborder="0"
                                src="{{ route('retainer.preview', ['template1', 'fffff']) }}"></iframe>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on("change", "select[name='retainer_template'], input[name='retainer_color']", function() {
        var template = $("select[name='retainer_template']").val();
        var color = $("input[name='retainer_color']:checked").val();
        $('#retainer_frame').attr('src', '{{ url('/retainer/preview') }}/' + template + '/' + color);
    });
</script>
