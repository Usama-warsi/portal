@extends('layouts.main')
@section('page-title')
    {{ __('Assets') }}
@endsection
@section('page-breadcrumb')
    {{ __('History') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Purchase Date') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Unit Price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assets as $index => $asset)
                                    <tr>
                                        <th scope="row">{{ ++$index }}</th>
                                        <td class="font-style">{{ !empty($asset->modules) ?  $asset->modules->name: '-' }}</td>
                                        <td class="font-style">{{ company_date_formate($asset->date) }}</td>
                                        <td class="font-style">{{ !empty($asset->quantity) ? $asset->quantity :'-' }}</td>
                                        <td class="font-style">{{ !empty($asset->type) ? $asset->type :'-' }}</td>
                                        <td class="font-style">{{ !empty($asset->modules) ? $asset->modules->assets_unit :'-' }}</td>
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
