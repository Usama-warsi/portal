{{ Form::open(['route' => ['videos.comment.store', $video_id, $comment_id], 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <input type="hidden" name="parent" value="{{ $comment_id }}">
        <div class="form-group  col-md-12">
            {{ Form::label('comment', __('Comment'), ['class' => 'col-form-label']) }}
            {!! Form::textarea('comment', null, ['class' => 'form-control', 'required', 'rows' => '3']) !!}
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('file', __('File'), ['class' => 'col-form-label']) }}
            <div class="choose-file">
                <input class="custom-input-file custom-input-file-link  commentReplayFile d-none" onchange="showReplayFileName()"
                    type="file" name="file" id="fileReplay" multiple="">
                <label for="fileReplay">
                    <button type="button" onclick="selectFile('commentReplayFile')" class="btn btn-primary"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-upload me-2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        {{ __('Choose a file...') }}</button>
                </label><br>
                <span class="uploaded_replay_file_name"></span>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Post'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}

<script src="{{ asset('Modules/VideoHub/Resources/assets/custom/js/main.js') }}"></script>
<script>
    function showReplayFileName() {
        var uploaded_replay_file_name = document.getElementById('fileReplay');
        $('.uploaded_replay_file_name').text(uploaded_replay_file_name.files.item(0).name);
    };
</script>
