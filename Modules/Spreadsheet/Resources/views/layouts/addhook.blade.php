@permission('spreadsheet manage')
    @if(isset($module) && $module == 'Project')
        <div class="col-md-auto col-sm-4 pb-3">
            <a href="{{ route('spreadsheet.index', ['related' => $related->id, 'related_id' => $id]) }}"
                class="btn btn-xs btn-primary btn-icon-only col-12" data-toggle="tooltip" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Spreadsheet') }}">
                <span class="btn-inner--text text-white">
                    <i class="ti ti-file"></i></span>
            </a>
        </div>
    @else
        <a href="{{ route('spreadsheet.index', ['related' => $related->id, 'related_id' => $id]) }}"
            class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Spreadsheet') }}">
            <i class="ti ti-file"></i>
        </a>
    @endif
@endpermission
