@php
    $inPerson = (isset($inPerson)) ? $inPerson : false;
@endphp
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @isset($token)
        <meta name="jwt-token" content="{{ $token }}">
    @endisset

    <title>@yield('title'){{ config('app.name', 'Participa') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @if (file_exists(public_path('css/fontawesome.css')))
        <link href="{{ mix('css/fontawesome.css') }}" rel="stylesheet">
    @endif

    @include('components.metatags')
</head>
<body class="{{ 'lang-' . config('app.locale') }}{{ ($inPerson) ? ' booth-mode' : '' }}">
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', '{{ config('participa.google_analytics_ID', 'UA-106217417-1') }}', 'auto');
      ga('send', 'pageview');
    </script>

    <a href="#content" class="sr-only sr-only-focusable">@lang('participa.skip_to_content')</a>
    <a href="#languages" class="sr-only sr-only-focusable">@lang('participa.select_language')</a>

    @section('header')
        @include('components/header')
        @include('components/voteinfo', ['inPerson' => $inPerson])
    @show

    <main class="main-background" id="content">
        <div class="container main-container">
            @isset($isArchive)
                <div class="alert alert-primary mb-4"><i class="far fa-archive" aria-hidden="true"></i> @lang('participa.is_archive', ['end_date' => human_date($edition->end_date) . ' ' . date('Y', strtotime($edition->end_date))])</div>
            @endisset

            @yield('content')
        </div>
    </main>

    @section('footer')
        <div class="footer-background">
            <div class="container">
                @include('components/footer')
            </div>
        </div>
    @show

    <script src="{{ mix('js/common.js') }}"></script>
    @stack('scripts')
</body>
</html>
