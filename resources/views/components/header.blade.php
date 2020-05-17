@php
    $logoUrl = (config('participa.navbar') == 'colorful')
        ? config('participa.logo_dark')
        : config('participa.logo');
@endphp

<nav class="navbar fixed-top navbar-expand-lg {{ (config('participa.navbar') == 'colorful') ? 'navbar-dark' : 'navbar-light' }}">
    <h1>
        <a class="navbar-brand" href="/">
            @if ($logoUrl)
                <img src="{{ secure_asset('images/' . $logoUrl) }}" alt="{{ config('app.name', 'Participa') }}" height="40" />
            @else
                <i class="far fa-bullhorn"></i> {{ config('app.name', 'Participa') }}
            @endif
        </a>
    </h1>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav navbar-social ml-auto">
            @include('components/social')
        </ul>

        <nav id="languages" aria-label="@lang('participa.select_language')" class="navbar-nav navbar-languages">
            @include('components/languages')
        </nav>
    </div>
</nav>
