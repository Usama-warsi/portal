@php
    // $logo_img=\App\Models\Utility::getValByName('logo');
    $company_logo = get_file(sidebar_logo());

@endphp
<style>
    .qrcode canvas {
        width: 100%;
        height: 100%;
        padding: 12px 25px;
    }

    .qrcode img {
        width: 100%;
        height: 100%;
    }
    .qrlogo
    {
        width: 200px
    }
</style>
<div class=" modal-body-section text-center">

    <img src="{{(isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
        alt="{{ config('app.name', 'WorkDo Dash') }}" class="qrlogo logo-lg">
</div>
<div class="modal-body border-0">
    <div class="modal-body-section text-center">
        <div class="qr-main-image">
            <div class="qr-code-border">
                <div class="qrcode"></div>
            </div>
        </div>
        <div class="text mt-3">
            <p class="text-black">
                {{ __('Point your camera at the QR code, or visit') }}<br> <span
                    class="qr-link text-center mr-2"></span>
            </p>
        </div>
    </div>
    <img src="{{ isset($qr_detail->image) ? get_file($qr_detail->image) : '' }}" id="image-buffers"
                style="display: none">
</div>
<script src="{{ asset('Modules/LMS/Resources/assets/js/jquery.qrcode.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var url_link = '{{ $store['store_url'] }}';
        $(`.qr-link`).text(url_link);

        var foreground_color =
            `{{ isset($qr_detail->foreground_color) ? $qr_detail->foreground_color : '#000000' }}`;
        var background_color =
            `{{ isset($qr_detail->background_color) ? $qr_detail->background_color : '#ffffff' }}`;
        var radius = `{{ isset($qr_detail->radius) ? $qr_detail->radius : 26 }}`;
        var qr_type = `{{ isset($qr_detail->qr_type) ? $qr_detail->qr_type : 0 }}`;
        var qr_font = `{{ isset($qr_detail->qr_text) ? $qr_detail->qr_text : 'VCard' }}`;
        var qr_font_color = `{{ isset($qr_detail->qr_text_color) ? $qr_detail->qr_text_color : '#f50a0a' }}`;
        var size = `{{ isset($qr_detail->size) ? $qr_detail->size : 9 }}`;

        $('.qrcode').empty().qrcode({
            render: 'image',
            size: 400,
            ecLevel: 'H',
            minVersion: 3,
            quiet: 1,
            text: url_link,
            fill: foreground_color,
            background: background_color,
            radius: .01 * parseInt(radius, 10),
            mode: parseInt(qr_type, 10),
            label: qr_font,
            fontcolor: qr_font_color,
            image: $("#image-buffers")[0],
            mSize: .01 * parseInt(size, 10)
        });

    });
</script>
