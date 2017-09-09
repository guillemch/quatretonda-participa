@php
    $inPerson = (isset($inPerson)) ? $inPerson : false;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @isset($token)
        <meta name="jwt-token" content="{{ $token }}">
    @endisset

    <title>@yield('title'){{ config('app.name', 'Participa') }}</title>

    <link href="https://rsms.me/interface/interface.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @include('components.metatags')
</head>
<body>
    <div class="container main-container">
        @section('header')
            <header class="header row">
                <div class="col-md-5 logo">
                    <a href="/">
                        <h1><img src="{{ secure_asset('images/' . config('participa.logo', 'logo.png')) }}" alt="{{ config('app.name', 'Participa') }}" /></h1>
                    </a>
                </div>
                <div class="col-md-7 links d-flex flex-row d-print-none">
                    @include('components/social')
                    {{--@include('components/languages')--}}
                </div>
            </header>

            @if(!$inPerson)
                <div class="row">
                    <div class="col">
                        @include('components/voteinfo')
                    </div>
                </div>
            @else
                <hr />
            @endif
        @show

        @isset($isArchive)
            <div class="alert alert-info mb-4"><i class="fa fa-archive" aria-hidden="true"></i> @lang('participa.is_archive', ['end_date' => human_date($edition->end_date) . ' ' . date('Y', strtotime($edition->end_date))])</div>
        @endisset

        @yield('content')

        @section('footer')
            <hr />

            @include('components/footer')
        @show
    </div>

    @stack('scripts')

</body>
</html>
