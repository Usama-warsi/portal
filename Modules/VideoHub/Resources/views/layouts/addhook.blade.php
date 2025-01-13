@permission('videohub manage')
        @if($module['filter'] == 'Project')
            <div class="col-sm-auto">
                <a href="{{ route('videos.index',$module) }}" data-bs-original-title="{{ __('VideoHub') }}"  data-bs-toggle="tooltip" class="btn btn-xs btn-primary btn-icon-only width-auto ">
                    {{-- <img src="{{ url('Modules/VideoHub/favicon.png') }}" width="15px" alt=""> --}}
                    <i class="ti ti-video-plus text-white"></i>
                </a>
            </div>
        @else
            <a href="{{ route('videos.index',$module) }}" data-bs-original-title="{{ __('VideoHub') }}" data-title="{{__('VideoHub')}}"  data-bs-toggle="tooltip" id="drive_hook" class="btn btn-sm btn-primary" >
                {{-- <img src="{{ url('Modules/VideoHub/favicon.png') }}" width="15px" alt=""> --}}
                <i class="ti ti-video-plus text-white"></i>
            </a>
        @endif
@endpermission
