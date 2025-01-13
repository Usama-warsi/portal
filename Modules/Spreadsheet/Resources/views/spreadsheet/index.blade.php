@extends('layouts.main')
@section('page-title')
    {{ __('Spreadsheet') }}
@endsection
@section('page-breadcrumb')
    {{ __('Manage Spreadsheet') }}
@endsection

@section('page-action')
    <div>
        @permission('spreadsheet create')
            <a href="{{ route('spreadsheet.file.create', $parent_id) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create File') }}">
                <i class="ti ti-files"></i>
            </a>

            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Folders') }}"
                data-url="{{ route('spreadsheets.folder.create', $parent_id) }}" data-toggle="tooltip"
                title="{{ __('Create Folder') }}">
                <i class="ti ti-folder"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            @if (\Auth::user()->type == 'company')
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['spreadsheet.index'], 'method' => 'GET', 'id' => 'customer_submit']) }}
                    <div class="row d-flex align-items-center justify-content-end">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                            <div class="btn-box">
                                {{ Form::label('modal_name', __('model name'), ['class' => 'form-label']) }}
                                <select name="related" class='form-control font-style' id="related-to">
                                    <option value="0" selected disabled>{{ __('Related To') }}</option>
                                    @foreach ($relateds as $key => $relate)
                                        <option value="{{ $key }}" {{ $key == $related ? 'selected' : '' }}>{{ $relate }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                            <div class="btn-box">
                                {{ Form::label('value',(count($relateds) > 0) ? (array_key_exists($related,$relateds->toArray())) ? ($relateds->toArray()[$related]): __('Value')  : __('Value'), ['class' => 'form-label']) }}
                                <div id="value_id">
                                    <input type="text" name="related_id[]" id="values" class="form-control font-style" value="{{ $related_id }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-auto float-end ms-2 mt-4">
                            <a href="#" class="btn btn-sm btn-primary"
                                onclick="document.getElementById('customer_submit').submit(); return false;"
                                data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                data-original-title="{{ __('apply') }}">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="{{ route('spreadsheet.index') }}" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                data-original-title="{{ __('Reset') }}">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
                            </a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="products">
                            <thead>
                                <tr>
                                    <th>{{ __('File / Folder Name') }}</th>
                                    <th>{{ __('Related') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($spreadsheets as $spreadsheet)
                                    <tr>
                                        @if ($spreadsheet->type == 'folder')
                                            <td><a href="{{ route('spreadsheet.index', [$spreadsheet->id]) }}"><img
                                                    src="{{ asset('images/folder.png') }}" alt=""
                                                    class=" rounded-circle" width="50px"
                                                    height="50px">{{ $spreadsheet->folder_name }}</a></td>
                                        @else
                                            <td><a href="{{ route('spreadsheets.file.show',$spreadsheet->id) }}"><img
                                                    src="{{ asset('images/file.png') }}" alt=""
                                                    class=" rounded-circle" width="50px"
                                                    height="50px">{{ $spreadsheet->folder_name }}</a></td>
                                        @endif

                                        <td>{{ !empty($spreadsheet->relatedGet) ? $spreadsheet->relatedGet->related : '-' }}</td>
                                            <td>
                                                @php
                                                    $permission = json_decode($spreadsheet->user_and_per,true);
                                                    $filtered_array = [];
                                                    if (is_array($permission)) {
                                                        $user = Auth::user()->id;
                                                        foreach ($permission as $item) {
                                                            if ($item['user_id'] == $user) {
                                                                $filtered_array[] = $item;
                                                            }
                                                        }
                                                    } else {
                                                        $category_name = '';
                                                    }
                                                @endphp
                                                <span>
                                                    @if(Auth::user()->type == 'company' || !empty($filtered_array) && $filtered_array[0]['permission'] == 'View' && $filtered_array[0]['user_id'] == $user )
                                                        @if ($spreadsheet->type != 'folder')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('spreadsheets.file.show', $spreadsheet->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('View') }}">
                                                                    <i class="ti ti-eye text-white"></i>
                                                                </a>

                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if (Auth::user()->type == 'company')
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a class="mx-3 btn btn-sm align-items-center"
                                                                data-ajax-popup="true" data-size="lg"
                                                                data-title="{{ __('Add Share') }}"
                                                                data-url="{{ route('spreadsheets.folder.share', $spreadsheet->id) }}"
                                                                data-toggle="tooltip" title="{{ __('Share Folder') }}">
                                                                <i class="ti ti-share text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if (Auth::user()->type == 'company')
                                                        <div class="action-btn bg-secondary ms-2">
                                                            <a class="mx-3 btn btn-sm align-items-center"
                                                                data-ajax-popup="true" data-size="lg"
                                                                data-title="{{ __('Add Related') }}"
                                                                data-url="{{ route('spreadsheets.related.create', $spreadsheet->id) }}"
                                                                data-toggle="tooltip" title="{{ __('Related Folder') }}">
                                                                <i class="ti ti-file-symlink"></i>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if(Auth::user()->type == 'company' || !empty($filtered_array) && $filtered_array[0]['permission'] == 'Edit' && $filtered_array[0]['user_id'] == $user )

                                                        @if ($spreadsheet->type == 'folder')
                                                            <div class="action-btn bg-info ms-2 ">
                                                                <a class="mx-3 btn btn-sm align-items-center"
                                                                    data-url="{{ route('spreadsheets.folder.edit', $spreadsheet->id) }}"
                                                                    data-ajax-popup="true" data-title="{{ __('Edit Folder') }}"
                                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                    data-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('spreadsheets.file.edit', $spreadsheet->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Edit File') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endif

                                                    @endif

                                                    @permission('spreadsheet delete')
                                                        <div class="action-btn bg-danger ms-2 ">
                                                            <form method="POST"
                                                                action="{{ route('spreadsheets.folder.destroy', $spreadsheet->id) }}"
                                                                id="user-form-{{ $spreadsheet->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input name="_method" type="hidden" value="DELETE">
                                                                <button type="button"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                                    data-bs-toggle="tooltip" title='Delete'>
                                                                    <span class="text-white"> <i
                                                                            class="ti ti-trash"></i></span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endpermission
                                                </span>
                                            </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#related-to').trigger('change');
    });

    $(document).on("change", "#related-to", function() {
        var relatedId = $(this).val();
        $.ajax({
            url: '{{ route('spreadsheets.relateds.get') }}',
            type: 'POST',
            data: {
                "related_id": relatedId,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data)
            {

                var preselected = $('#values').val();
                var preselected_arr = [];

                if (typeof preselected === 'string' && preselected.trim() !== '') {
                    preselected_arr = preselected.split(',');
                }

                $('#value_id').empty();

                var option = '<select class="form-control choices" name="related_id[]" id="values" placeholder="{{__('Select Item')}}"  multiple>';
                    // option += '<option value="" disabled>{{__('Select Item')}}</option>';

                    $.each(data, function(key, value) {

                        var selected = preselected_arr.includes(key) ? 'selected' : '';
                        option += '<option '+selected+' value="' + key + '">' + value + '</option>';
                    });
                    option += '</select>';

                    $("#value_id").append(option);

                    if ('{{$related_id}}' == 0 && '{{!empty($related)}}' ) {
                        $("#value_id").find('option:first').prop('selected', true);
                    }
                    var multipleCancelButton = new Choices('#values', {
                        removeItemButton: true,
                    });

            },
        });
    });
</script>
@endpush
