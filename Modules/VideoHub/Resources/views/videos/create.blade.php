{{ Form::open(['route' => ['videos.store'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }}
            {{ Form::text('title', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Enter Video Title']) }}
        </div>
        <div class="form-group">
            <div class="btn-box">
                <label for="module_create"><b>{{ __('Module') }}</b></label>
                <select class="form-control module_create " name="module" id="module_create" tabindex="-1"
                    aria-hidden="true">
                    <option value="">{{ __('Select Module') }}</option>
                    @foreach ($modules as $module)
                        @foreach ($active_modules as $active_module)
                            @if (Module_Alias_Name($active_module) == $module)
                                @php
                                    if ($module == 'Product Service') {
                                        $module = 'Items';
                                    } elseif ($module == 'Insurance Management') {
                                        $module = 'Insurance';
                                    } elseif ($module == 'Property Management') {
                                        $module = 'Property Manage';
                                    } elseif ($module == 'Rental Management') {
                                        $module = 'Rental';
                                    } elseif ($module == 'Business Process Mapping') {
                                        $module = 'Business Mapping';
                                    }
                                @endphp
                                <option value="{{ $module }}">
                                    {{ $module }}
                                </option>
                            @endif
                        @endforeach
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12" id="getfields"></div>
        <div class="col-12" id="relatedfields"></div>

        <div class="form-group mt-3">
            <span>{{ __('Things you want to upload?') }}</span><br>
            <div class="form-check form-check-inline mt-1">
                <input class="form-check-input" type="radio" name="video_type" value="video_file" id="video_file"
                    data-name="video_file">
                <label class="form-check-label" for="video_file">
                    {{ 'Upload Video' }}
                </label>
            </div>
            <div class="form-check form-check-inline mt-1">
                <input class="form-check-input" type="radio" name="video_type" value="video_url" id="video_url"
                    data-name="video_url">
                <label class="form-check-label" for="video_url">
                    {{ 'Custom Video Link' }}
                </label>
            </div>
        </div>
        <div class="col-6 form-group">
            {{ Form::label('thumbnail', __('Thumbnail Image'), ['class' => 'form-label']) }}
            <div class="choose-file">
                <input class="custom-input-file custom-input-file-link  thumbnail1 d-none" onchange="showThumbnailImage()"
                    type="file" name="thumbnail" id="thumbnailImage" multiple="">
                <label for="thumbnailImage">
                    <button type="button" onclick="selectFile('thumbnail1')" class="btn btn-primary"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-upload me-2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        {{ __('Choose a image...') }}</button>
                </label>
                <img src="{{ asset('Modules/VideoHub/Resources/assets/upload/thumbnail-not-found.png') }}" alt="thumbnail_create" id="thumbnail_create" class="img-fluid mt-2" style="max-width: 100%;" width="190px">
            </div>
        </div>
        <div class="form-group col-6 video_file">
            {{ Form::label('video', __('Upload Video'), ['class' => 'form-label']) }}
            <div class="choose-file">
                <input class="custom-input-file custom-input-file-link video d-none" type="file" name="video" id="videoFile" onchange="showVideo(this)" multiple="">
                <label for="videoFile">
                    <button type="button" onclick="selectFile('video')" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload me-2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        {{ __('Choose a video...') }}
                    </button>
                </label>
                @if (!empty($mp4_msg))
                    <br>
                    <span style="color: red;">{{ __($mp4_msg) }}</span>
                @else
                    <video class="mt-2" id="videoPlayer" width="190px" style="max-width: 100%;height: auto;" controls>
                        <source id="videoresource" src="" type="video/mp4">
                    </video>
                    <span id="notFoundVideo" class="text-muted">{{ __('Please select video.') }}</span>
                @endif
            </div>
        </div>
        <div class="form-group col-md-12 video_url d-none">
            {{ Form::label('video', __('Custom Video Link'), ['class' => 'form-label']) }}
            {{ Form::text('video', null, ['class' => 'form-control font-style', 'placeholder' => __('Enter Video Link')]) }}
        </div>
        <div class="form-group">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('')]) }}
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}

{{-- Start Video & Thumbnail --}}
<script src="{{ asset('Modules/VideoHub/Resources/assets/custom/js/main.js') }}"></script>

<script>
    function showThumbnailImage() {
        var uploadedThumbnailImage = document.getElementById('thumbnailImage');
        var selectedFile = uploadedThumbnailImage.files[0];
        var objectURL = window.URL.createObjectURL(selectedFile);
        document.getElementById('thumbnail_create').src = objectURL;
    };

    function showVideo(input)  {
        var uploadedVideo = input.files[0];
        var videoPlayer = document.getElementById('videoPlayer');
        var source = document.getElementById('videoresource');

        var objectURL = window.URL.createObjectURL(uploadedVideo);
        source.src = objectURL;
        if (source.src) {
            $("#notFoundVideo").hide();
        }
        videoPlayer.load();
    };
</script>
{{-- End Video & Thumbnail --}}

{{-- Start Module Selection --}}
<script>
    $(document).ready(function(){
        $(document).on("change", "#module_create", function() {
            if ($(this).val() == 'Items') {
                var modules = 'Product Service';
            } else if ($(this).val() == 'Insurance') {
                var modules = 'Insurance Management';
            } else if ($(this).val() == 'Property Manage') {
                var modules = 'Property Management';
            } else if ($(this).val() == 'Rental') {
                var modules = 'Rental Management';
            } else if ($(this).val() == 'Business Mapping') {
                var modules = 'Business Process Mapping';
            } else {
                var modules = $(this).val();
            }
            $.ajax({
                url: '{{ route('videos.modules') }}',
                type: 'POST',
                data: {
                    "module": modules,
                },
                success: function(data) {
                    $('#getfields').empty();
                    $('.sub_module_create').empty();
                    $('#relatedfields').empty();
                    var emp_selct = `
                        <label class='form-label'><b>${(data.module == 'Product Service') ? 'Item Type' : 'Sub Module'}</b></label>
                        <select class="form-control sub_module_create" name="sub_module_create" id="sub_module_create" placeholder="Select Sub Module"></select>
                    `;
                    $('#getfields').html(emp_selct);
    
                    if (data.module == 'Product Service') {
                            $('.sub_module_create').append(
                                '<option value="0"> {{ __('Select Item Type') }} </option>');
                        } else {
                            $('.sub_module_create').append(
                                '<option value="0"> {{ __('Select Sub Module') }} </option>');
                        }
                    $.each(data.video_modules, function(key, value) {
                        $('.sub_module_create').append('<option value="' + key + '">' + value +
                            '</option>');
                        if (!value) {
                            $('#getfields').empty();
                            field(key, 'nonSubModule');
                        }
    
                    });
    
                },
            });
        });
    
        $(document).on("change", ".sub_module_create", function() {
            field($(this), 'subModule');
        });
    
        function field(sub_module, type) {
            (type == 'subModule') ? sub_module = sub_module.val(): sub_module = sub_module;
    
            $.ajax({
                url: '{{ route('videos.getfield') }}',
                type: 'POST',
                data: {
                    "module": sub_module,
                },
                success: function(data) {
                    $('#relatedfields').empty();
                    $('#relatedfields').append(data.html)
    
                },
            });
        }
    });
</script>
{{-- End Module Selection --}}
{{-- Start Video $ Url Select --}}
<script>
    $(document).ready(function() {
        $('input[name="video_type"][id="video_file"]').prop('checked', true);
        $('input[name="video_type"]').trigger("change");
    });
    $('input[name="video_type"]').change(function() {
        var radioValue = $('input[name="video_type"]:checked').val();
        // var video_file = $('.video_file');

        if (radioValue === "video_file") {
            $('.video_file').removeClass('d-none');
            $('.video_url').addClass('d-none');
        } else {
            $('.video_file').addClass('d-none');
            $('.video_url').removeClass('d-none');
        }
    });
</script>
{{-- End Video $ Url Select --}}
