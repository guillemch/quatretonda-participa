@php
    $languages = config('participa.languages');
    $current_language = config('app.locale', 'ca');
@endphp

<ul class="languages">
    <li class="languages__current">
        <a href="/">
            {{ $languages[$current_language] }}
            <i class="fa fa-caret-down" aria-hidden="true"></i>
         </a>

        <ul class="languages__menu">
            @foreach($languages as $code => $language)
                @unless($current_language == $code)
                    <li><a href="{{ url('lang/' . $code) }}">{{ $language }}</a></li>
                @endunless
            @endforeach
        </ul>
    </li>
</ul>
