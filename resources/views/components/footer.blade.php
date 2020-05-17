<footer class="main-footer">
    <div class="footer-language" aria-hidden="true">
        @include('components/languages')
    </div>

    <div class="media">
        <a href="{{ config('participa.council_url') }}" target="_blank" rel="noopener" title="{{ config('participa.council_name', 'Any Council') }}">
            <img src="{{ '/images/' . config('participa.council_logo', 'council.png') }}" alt="{{ config('participa.council_name', 'Any Council') }}" width="60" class="d-flex align-self-start mr-3" />
        </a>

        <div class="media-body council-details">
            <address role="presentation">
                <h3 class="mt-0">{{ config('participa.council_name', 'Any Council') }}</h3>
                <p>
                    @if (config('participa.contact_address'))
                        <span><i class="far fa-map-marker-alt faw-fw" aria-hidden="true"></i> {{ config('participa.contact_address') }}</span>
                    @endif

                    @if (config('participa.contact_phone'))
                        <span><i class="far fa-phone fa-fw" aria-hidden="true"></i> {{ config('participa.contact_phone') }}</span>
                    @endif

                    @if (config('participa.council_url'))
                        @php
                            $simpleUrl = config('participa.council_url');
                            $simpleUrl = preg_replace('/(https?\:\/\/)(www\.)?/', '', $simpleUrl);
                        @endphp
                        <span>
                            <a href="{{ config('participa.council_url') }}" target="_blank" rel="noopener" title="{{ config('participa.council_name', 'Any Council') }}">
                                <i class="far fa-globe fa-fw" aria-hidden="true"></i> {{ $simpleUrl }}
                            </a>
                        </span>
                    @endif
                </p>
            </address>

            <p>@lang('participa.help'): <a href="mailto:{{ config('participa.contact_email', 'participa@disedit.com') }}">{{ config('participa.contact_email', 'participa@disedit.com') }}</a></p>

            <div class="wai-logo">
                <a href="https://www.w3.org/WAI/WCAG2AA-Conformance" title="Explanation of WCAG 2.0 Level Double-A Conformance">
                    <img height="32" width="88" src="https://www.w3.org/WAI/wcag21/wcag2.1AA-blue-v.png" alt="Level Double-A conformance, W3C WAI Web Content Accessibility Guidelines 2.0">
                </a>
            </div>
        </div>
    </div>
</footer>
