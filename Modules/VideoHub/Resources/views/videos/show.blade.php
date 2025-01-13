@extends('layouts.main')
@section('page-title')
    {{ __('Video Details') }}
@endsection

@push('css')
@endpush

@section('page-breadcrumb')
    {{ __('Video Details') }}
@endsection

@section('page-action')
    <div>
        <a href="{{ route('videos.index') }}" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip"title="{{ __('Back') }}">
            <i class=" ti ti-arrow-back-up"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card table-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>{{ __('Video') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="align-items-center mb-0">
                        <div class="raw">
                            @if (!empty($video->video))
                                @if ($video->type == 'video_file')
                                    @if (check_file($video->video))
                                        <video width="100%" controls>
                                            <source id="videoresource" src="{{ get_file($video->video) }}" type="video/mp4">
                                        </video>
                                    @else
                                        <img src="{{ asset('Modules/VideoHub/Resources/assets/upload/no-video.jpg') }}" alt="noVideo" id="noVideo" class="card-img" style="height: 395px">
                                    @endif
                                @endif

                                @if ($video->type == 'video_url')
                                    @php
                                        $player = 'none';
                                        if (str_contains($video->video, 'youtube') || str_contains($video->video, 'youtu.be')) {
                                            $player = 'youtube';
                                            if (strpos($video->video, 'src') !== false) {
                                                preg_match('/src="([^"]+)"/', $video->video, $match);
                                                $url = $match[1];
                                                $video_url = str_replace('https://www.youtube.com/embed/', '', $url);
                                            } else {
                                                $video_url = str_replace('https://youtu.be/', '', str_replace('https://www.youtube.com/watch?v=', '', $video->video));
                                            }
                                        } elseif (str_contains($video->video, 'vimeo')) {
                                            $player = 'vimeo';
                                            if (strpos($video->video, 'src') !== false) {
                                                preg_match('/src="([^"]+)"/', $video->video, $match);
                                                $url = $match[1];
                                                $video_url = str_replace('https://player.vimeo.com/video/', '', $url);
                                            } else {
                                                $video_url = str_replace('https://vimeo.com/', '', $video->video);
                                            }
                                        } else {
                                            $video_url = $video->video;
                                        }
                                    @endphp
                                    @if ($player == 'youtube')
                                        <iframe width="100%" height="390"
                                            src="{{ 'https://www.youtube.com/embed/' }}{{ $video_url }}"
                                            title="YouTube video player" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                    @elseif ($player == 'vimeo')
                                        <iframe width="100%" height="390"
                                            src="{{ 'https://player.vimeo.com/video/' }}{{ $video_url }}" frameborder="0"
                                            allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                    @else
                                        <iframe width="100%" height="390" src="{{ $video_url }}"
                                            title="YouTube video player" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                    @endif
                                @endif
                            @else
                                <img src="{{ asset('Modules/VideoHub/Resources/assets/upload/no-video.jpg') }}" alt="noVideo" id="noVideo" class="card-img" style="height: 395px">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card table-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>{{ __('Description') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body width-10" style="height: 445px; overflow:auto">
                    <div class="align-items-center mb-0">
                        <div class="raw">
                            <p class="card-text" >{{ $video->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="raw">
        <!--Comments-->
        <div id="useradd-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Comments') }}</h5>
                </div>
                <div class="card-body">
                    @foreach ($comments as $comment)
                        <div class="media mb-2">
                            <a class="pr-2" href="#">
                                <img src="{{ check_file($comment->commentUser->avatar) ? get_file($comment->commentUser->avatar) : get_file('uploads/users-avatar/avatar.png') }}"
                                    class="rounded-circle" alt="" height="32">
                            </a>
                            <div class="media-body">
                                <h6 class="mt-0 ms-2">
                                    {{ !empty($comment->commentUser->name) ? $comment->commentUser->name : '' }}
                                    <small
                                        class="text-muted float-right">{{ $comment->created_at->diffForHumans() }}</small>
                                </h6>
                                <div class="d-flex gap-2 align-items-center">
                                    <p class="text-sm mb-0 ms-2">
                                        {{ $comment->comment }}
                                    </p>
                                    <div class="d-flex">
                                        @if (!empty($comment->file))
                                            @if (check_file('uploads/Video_Hub' . '/' . $comment->file))
                                                <div class="d-flex">
                                                    <a href="#" class="like active" style="margin-bottom: -13px;">
                                                        <i class="ni ni-cloud-download-95"></i>
                                                        <a href="{{ get_file('uploads/Video_Hub') . '/' . $comment->file }}"
                                                            download="" class="m-0 p-1 btn btn-sm d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" title="{{ __('Download') }}">
                                                            <i class="ti ti-download text-primary"></i> </a>
                                                    </a>
                                                </div>
                                                <div class="d-flex">
                                                    <a href="{{ get_file('uploads/Video_Hub') . '/' . $comment->file }}"
                                                        target=_blank
                                                        class="btn btn-sm p-1 d-inline-flex align-items-center text-white "
                                                        data-bs-toggle="tooltip" title="{{ __('Preview') }}">
                                                        <i class="ti ti-crosshair text-primary"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                        @permission('video comment reply')
                                            <div class="d-flex">
                                                <a href="#"
                                                    data-url="{{ route('videos.comment.reply', [$video->id, $comment->id]) }}"
                                                    class="btn btn-sm p-1 d-inline-flex align-items-center text-white "
                                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                                    data-title="{{ __('Create Comment Reply') }}" title="{{ __('Reply') }}">
                                                    <i class="ti ti-send text-primary"></i>
                                                </a>
                                            </div>
                                        @endpermission
                                    </div>
                                </div><br>
                                @foreach ($comment->subComment as $subComment)
                                    @include('videohub::videos.comment', ['subComment' => $subComment])
                                @endforeach
                            </div>

                        </div>
                    @endforeach

                    <div class="border rounded mt-4">

                        {{ Form::open(['route' => ['videos.comment.store', $video->id], 'enctype' => 'multipart/form-data', 'class' => 'd-flex align-items-center gap-3']) }}
                        <textarea rows="3" class="form-control border-0 resize-none project_comment" name="comment"
                            placeholder="Your comment..." required style="flex: 1;"></textarea>
                        <div class="p-2 gap-3 bg-light d-flex justify-content-between align-items-center">
                            <div class="choose-file">
                                <input class="custom-input-file custom-input-file-link  commentFile d-none" onchange="showfilename()"
                                    type="file" name="file" id="file" multiple="">
                                <label for="file">
                                    <button type="button" onclick="selectFile('commentFile')" class="btn btn-primary"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-upload me-2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="3" x2="12" y2="15"></line>
                                        </svg>
                                        {{ __('Choose a file...') }}</button>
                                </label><br>
                                {{-- <span class="uploaded_file_name"></span> --}}
                            </div>
                            <button type="submit" class="btn btn-primary"><i
                                    class='uil uil-message mr-1'></i>{{ __('Post') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('Modules/VideoHub/Resources/assets/custom/js/main.js') }}"></script>
<script>
    function showfilename() {
        var uploaded_file_name = document.getElementById('file');
        $('.uploaded_file_name').text(uploaded_file_name.files.item(0).name);
    };
</script>
@endpush
