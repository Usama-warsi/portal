@extends('layouts.main')
@section('page-title')
    {{ __('Spreadsheet') }}
@endsection
@section('page-breadcrumb')
    {{ __('View') }}
@endsection

@section('page-action')
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
        <a href="{{ route('spreadsheet.index') }}" class="btn-submit btn btn-sm btn-primary" data-toggle="tooltip" title="" data-bs-original-title="Back">
            <i class=" ti ti-arrow-back-up"></i>
        </a>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Spreadsheet/Resources/assets/css/xspreadsheet.css') }}">
@endpush
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

    </script>
@endpush

