@extends('layouts.main')

@section('page-title')
    {{__('Manage Customer')}}
@endsection

@section('page-breadcrumb')
   {{__('Customer')}}
@endsection
@section('page-action')

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="wp_customer">
                            <thead>
                                <tr>
                                    <th>{{__('Avatar')}}</th>
                                    <th>{{__('First Name')}}</th>
                                    <th>{{__('Last Name')}}</th>
                                    <th>{{__('Email')}}</th>
                                    <th>{{__('Phone No')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($wp_customers as $wp_customer)
                                    <tr>
                                        <td>
                                            <div>
                                                <a href="{{$wp_customer['avatar_url']}}" target="_blank">
                                                    <img alt="Image placeholder" src="{{$wp_customer['avatar_url']}}" class="rounded-circle"  width="35">
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $wp_customer['first_name'] }}</td>
                                        <td>{{ $wp_customer['last_name'] }}</td>
                                        <td>{{ $wp_customer['email'] }}</td>
                                        <td>{{!empty($wp_customer['billing']['phone'])?$wp_customer['billing']['phone']:$wp_customer['shipping']['phone'] }}</td>
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
