
@php
    $color =  !empty($admin_settings['color'])?$admin_settings['color']:'theme-1';

@endphp
@extends('supportticket::layouts.master')
@section('page-title')
{{ __('Search Your Ticket') }}
@endsection
@section('content')
<div class="custom-login">
    <div class="login-bg-img">
        <img src="{{ asset('images/'.$color.'.svg') }}" class="login-bg-1">
        <img src="{{ asset('images/common.svg') }}" class="login-bg-2">
    </div>
    <div class="bg-login bg-primary"></div>
    <div class="custom-login-inner">
        <header class="dash-header">

            <nav class="navbar navbar-expand-md navbar-light default">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <img src="{{ get_file(sidebar_logo()) }}{{'?'.time()}}" alt="" class="logo logo-lg" style="max-width: 110px;"/>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01" style="flex-grow: 0;">
                        <ul class="navbar-nav ms-auto me-auto mb-2 mb-lg-0">

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('support-ticket',$workspace->slug) }}">{{ __('Create Ticket') }}</a>
                            </li>

                        </ul>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main class="custom-wrapper">
            <div class="custom-row">

                    <div class="card">

                        <div class="card-body">
                            <div>
                                <h2 class="mb-3 f-w-600">{{ __('Search Your Ticket') }}</h2>
                            </div>
                            <form method="POST"  action="{{route('ticket.search',$workspace->slug)}}">

                                @csrf
                                @if (session()->has('info'))
                                    <div class="alert alert-success">
                                        {{ session()->get('info') }}
                                    </div>
                                @endif
                                @if (session()->has('status'))
                                    <div class="alert alert-info">
                                        {{ session()->get('status') }}
                                    </div>
                                @endif

                                <div class="custom-login-form">
                                    <div class="">
                                        <div class="form-group mb-3">
                                            <label for="ticket_id" class="form-label">{{ __('Ticket Number') }}</label>
                                            <input type="number"
                                                class="form-control {{ $errors->has('ticket_id') ? 'is-invalid' : '' }}"
                                                min="0" id="ticket_id" name="ticket_id"
                                                placeholder="{{ __('Enter Ticket Number') }}" required=""
                                                value="{{ old('ticket_id') }}" autofocus>
                                            <div class="invalid-feedback d-block">
                                                {{ $errors->first('ticket_id') }}
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label">{{ __('Email') }}</label>
                                            <input type="email"
                                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                id="email" name="email" placeholder="{{ __('Email address') }}"
                                                reuired="" value="{{ old('email') }}">
                                            <div class="invalid-feedback d-block">
                                                {{ $errors->first('email') }}
                                            </div>
                                        </div>
                                        <div class="d-grid">
                                            <button
                                                class="btn btn-primary btn-submit btn-block mt-2">{{ __('Search') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

            </div>
        </main>
        <footer>
            <div class="auth-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <span>
                                @if (!empty($admin_settings['footer_text'])) {{$admin_settings['footer_text']}} @else{{__('Copyright')}} &copy; {{ config('app.name', 'WorkDo') }}@endif{{date('Y')}}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

@endsection
@push('scripts')
    <script>
        // for Choose file
        $(document).on('change', 'input[type=file]', function () {
            var names = '';
            var files = $('input[type=file]')[0].files;

            for (var i = 0; i < files.length; i++) {
                names += files[i].name + '<br>';
            }
            $('.' + $(this).attr('data-filename')).html(names);
        });
    </script>
@endpush
