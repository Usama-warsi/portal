@extends('layouts.main')
@section('page-title')
    {{ __('Spreadsheet') }}
@endsection
@section('page-breadcrumb')
    {{ __('Edit') }}
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('Modules/Spreadsheet/Resources/assets/css/xspreadsheet.css') }}">
@endpush

@section('page-action')
    <div class="">
        <button class="btn-submit btn btn-sm btn-primary" id="save-button" type="submit">{{ __('Save') }}</button>
        <a href="{{ route('spreadsheet.index') }}" class="btn-submit btn btn-sm btn-primary " data-toggle="tooltip" title="" data-bs-original-title="Back">
            <i class=" ti ti-arrow-back-up"></i>
        </a>
    </div>
@endsection

@section('content')
    <div id="spreadsheet-container"></div>
@endsection

@push('scripts')
<script src="{{ asset('Modules/Spreadsheet/Resources/assets/js/xspreadsheet.js')}}"></script>
    <script>
        const spreadsheetData = @json($spreadsheetData);
        const sheetDataArray = [];
        $.each(spreadsheetData, function(indexInArray, valueOfElement) {
            const spreadsheetDat = valueOfElement;
            const newObj = {};
            spreadsheetDat.forEach((arr, index) => {
                newObj[index] = {
                    cells: arr.map((value, i) => ({
                        text: value !== null ? value : ''
                    }))
                };
            });
            newObj.len = 100;
            sheetDataArray.push({
                name: indexInArray,
                rows: newObj
            });
        });

            const container = document.getElementById('spreadsheet-container');
            const hot = x_spreadsheet(container, {
                showToolbar: true,
                showGrid: true,

            }).loadData(sheetDataArray).change((cdata) => {
                console.log('>>>', hot.getData());
            });
        const saveButton = document.getElementById('save-button');

        saveButton.addEventListener('click', function() {
            // Disable the button to prevent multiple clicks
            saveButton.disabled = true;

            var updatedData = hot.getData(); // Get updated data from the editor

            $.ajax({
                url: '{{ route('spreadsheet.file.update') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "parent_id": "{{ $sheet->parent_id }}",
                    "id": "{{ $sheet->id }}",
                    "updatedData": updatedData,
                },

                success: function(response) {
                    if (response !== false) {
                        toastrs('success', 'Spreadsheet data saved successfully', 'success');
                        window.location.href = '{{ route('spreadsheet.index') }}';
                    } else {
                        toastrs('Error', 'Something went wrong, please try again!', 'error');
                    }
                },
                error: function() {
                    // Handle errors here as needed
                }
            });

        });
    </script>
@endpush
