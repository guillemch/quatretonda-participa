<link rel="canonical" href="{{ URL::current() }}" />
<meta name="robots" content="index, follow" />
<meta name="keywords" content="@lang('participa.keywords')" />
<meta name="description" content="@lang('participa.description')" />
<link rel="manifest" href="{{ secure_url('manifest.json') }}">
<link rel="shortcut icon" href="{{ secure_asset('images/favicon.png') }}">
<link rel="icon" type="image/png" href="{{ secure_asset('images/favicon.big.png') }}" sizes="310x310">
<link rel="apple-touch-icon" href="{{ secure_asset('images/favicon.big.png') }}">
<meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Participa') }}">
<meta name="msapplication-TileColor" content="{{ config('participa.primary_color', '#2980b9') }}">
<meta name="msapplication-TileImage" content="{{ secure_asset('images/favicon.big.png') }}">
<meta name="application-name" content="{{ config('app.name', 'Participa') }}">
<meta name="theme-color" content="{{ config('participa.primary_color', '#2980b9') }}">
<meta property="fb:app_id" content="{{ config('participa.facebook_app_id', '180444172483336') }}" />
<meta property="og:title" content="@yield('title'){{ config('app.name', 'Participa') }}" />
<meta property="og:image" content="{{ secure_asset('images/thumbnail.png') }}"/>
<meta property="og:site_name" content="{{ config('app.name', 'Participa') }}"/>
<meta property="og:locale" content="@lang('participa.facebook_locale')"/>
<meta property="og:type" content="website"/>
<meta property="og:description" content="@lang('participa.description')"/>
<meta property="og:url" content="{{ URL::current() }}" />
<meta property="twitter:site" content="@{{ config('participa.twitter', 'infoDisedit') }}"/>
<meta property="twitter:card" content="summary_large_image"/>
<meta property="twitter:title" content="@yield('title'){{ config('app.name', 'Participa') }}"/>
<meta property="twitter:description" content="@lang('participa.description')"/>
<meta property="twitter:image" content="{{ secure_asset('images/thumbnail.png') }}"/>
<meta property="twitter:url" content="{{ URL::current() }}"/>
