<footer class="page-footer media">
        <a href="{{ config('participa.council_url') }}" target="_blank" rel="noopener">
            <img src="{{ secure_asset('images/' . config('participa.council_logo', 'council.png')) }}" alt="{{ config('participa.council_name', 'Any Council') }}" class="d-flex align-self-start mr-3" />
        </a>

        <div class="media-body council-details">
            <address>
                <h5 class="mt-0">{{ config('participa.council_name', 'Any Council') }}</h5>
                <p>
                    @if(config('participa.contact_address'))
                        <span><i class="fa fa-map-marker" aria-hidden="true"></i> {{ config('participa.contact_address') }}</span>
                    @endif

                    @if(config('participa.contact_phone'))
                        <span><i class="fa fa-phone" aria-hidden="true"></i> {{ config('participa.contact_phone') }}</span>
                    @endif

                    @if(config('participa.council_url'))
                        @php
                            $simpleUrl = config('participa.council_url');
                            $simpleUrl = preg_replace('/(https?\:\/\/)(www\.)?/', '', $simpleUrl);
                        @endphp
                        <span>
                            <a href="{{ config('participa.council_url') }}" target="_blank" rel="noopener">
                                <i class="fa fa-globe" aria-hidden="true"></i> {{ $simpleUrl }}
                            </a>
                        </span>
                    @endif
                </p>
                <p>@lang('participa.help'): <a href="mailto:{{ config('participa.contact_email', 'participa@disedit.com') }}">{{ config('participa.contact_email', 'participa@disedit.com') }}</a></p>
            </address>
        </div>
    </div>
</footer>
