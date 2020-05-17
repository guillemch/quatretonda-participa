@php
    $docs = (!empty($edition->docs)) ? explode("\n", $edition->docs) : array();
    $places = (!empty($edition->voting_places)) ? explode("\n", $edition->voting_places) : array();
@endphp

<aside class="sidebar">
    <section aria-labelledby="current-poll" class="sidebar__box sidebar__box--main">
        @isset ($isArchive)
            <h3 id="current-poll">@lang('participa.poll')</h3>
        @else
            <h3 id="current-poll">@lang('participa.current_poll')</h3>
        @endif

        <h4>{{ $edition->name }}</h4>
        <p class="sidebar__secondary">@lang('participa.sidebar_dates', ['start_date' => human_date($edition->start_date), 'end_date' => human_date($edition->end_date)])</p>

        <div class="sidebar__social-plugins">
            @component('components.share_buttons', ['share' => 'false'])
                @lang('participa.tweet')
            @endcomponent
        </div>

        <div v-if="docs">
            <hr aria-hidden="true" />

            <ul class="sidebar__list">
                <li class="sidebar__list__item">
                    @if (isset($isArchive) && Request::segment(3) == 'about')
                        {{-- This is the archive and we are in the about page --}}
                        <a href="{{ secure_url('archive/' . $edition->id) }}"><i class="far fa-chart-bar fa-fw" aria-hidden="true"></i> <span>@lang('participa.results')</span></a>
                    @elseif (isset($isArchive) && Request::segment(3) != 'about')
                        {{-- This is the archive and we are in the main (results) page --}}
                        <a href="{{ secure_url('archive/' . $edition->id . '/about') }}"><i class="far fa-info-circle fa-fw" aria-hidden="true"></i> <span>@lang('participa.more_info')</span></a>
                    @elseif (!isset($isArchive) && Request::segment(1) == 'about' && $edition->isOpen())
                        {{-- This is not the archive, edition is open but we are not in the main vote page --}}
                        <a href="{{ secure_url('') }}"><i class="far fa-bullhorn fa-fw" aria-hidden="true"></i> <span>@lang('participa.vote')</span></a>
                    @elseif (!isset($isArchive) && Request::segment(1) != 'about' && !$edition->isPending())
                        {{-- This is not the archive, we are in the main page and it does not contain the about page  --}}
                        <a href="{{ secure_url('about') }}"><i class="far fa-info-circle fa-fw" aria-hidden="true"></i> <span>@lang('participa.more_info')</span></a>
                    @elseif (!isset($isArchive) && Request::segment(1) == 'about' && $edition->resultsPublished())
                        {{-- This is not the archive, we are in the about page and results are published  --}}
                        <a href="{{ secure_url('') }}"><i class="far fa-chart-bar fa-fw" aria-hidden="true"></i> <span>@lang('participa.results')</span></a>
                    @elseif (Request::segment(1) == 'propose')
                        {{-- This is not the archive page and we are in the popose page --}}
                        <a href="{{ secure_url('') }}"><i class="far fa-info-circle fa-fw" aria-hidden="true"></i> <span>@lang('participa.more_info')</span></a>
                    @endif
                </li>

                @if (count($docs) > 0)
                    @forelse ($docs as $doc)
                        @php
                            $part = explode(",", $doc);
                        @endphp
                        <li class="sidebar__list__item">
                            <a href="{{ $var = isset($part[1]) ? $part[1] : '' }}" target="_blank" rel="noopener">
                                @isset ($part[2])
                                    <i class="far fa-{{ $part[2] }} fa-fw" aria-hidden="true"></i>
                                @else
                                    <i class="far fa-file-alt fa-fw" aria-hidden="true"></i>
                                @endif

                                <span>{{ $part[0] }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>

            @if (!isset($isArchive) && $edition->inProposalPhase() && Request::segment(1) != 'propose')
                <div class="sidebar__propose">
                    <a href="{{ secure_url('propose') }}" class="btn btn-secondary btn-lg btn-block"><i class="far fa-pencil-alt" aria-hidden="true"></i> @lang('participa.propose_cta')</a>
                </div>
            @endif
        </div>
    </section>

    @if (count($places) > 0)
        <section aria-labelledby="voting-places" class="sidebar__box" v-if="voting_places">
            <h3 id="voting-places">@lang('participa.voting_places')</h3>
            <p class="sidebar__secondary">@lang('participa.voting_text')</p>
            <hr  aria-hidden="true" />
            <ul class="sidebar__list">
                @foreach ($places as $place)
                    @php
                        $part = explode(",", $place);
                    @endphp

                    <li>
                        <span class="sidebar__list__item">
                            <i class="far fa-map-marker-alt fa-fw" aria-hidden="true"></i>

                            <span>
                                {{ $part[0] }}

                                @isset ($part[1])
                                    <span class="sidebar__secondary">{{ $part[1] }}</span>
                                @endisset
                            </span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    @if (!$edition->isPending())
        @component('components.ballot_lookup', ['in_sidebar' => true])
        @endcomponent
    @endif

    <section aria-labelledby="contact"  class="sidebar__box">
        <h3 id="contact">@lang('participa.contact')</h3>
        <p>@lang('participa.contact_text', ['contact_email' => config('participa.contact_email')])</p>
    </section>

    @isset ($pastEditions)
        @if (count($pastEditions) > 0)
            <section aria-labelledby="past-editions" class="sidebar__box">
                <h3 id="past-editions">@lang('participa.past_editions')</h3>

                <ul class="sidebar__list">
                @foreach ($pastEditions as $edition)
                    <li class="sidebar__list__item">
                        <a href="{{ secure_url('archive/' . $edition->id) }}">
                            <i class="far fa-calendar-alt" aria-hidden="true"></i> <span>{{ human_month($edition->start_date) }}</span>
                        </a>
                    </li>
                @endforeach
                <li class="sidebar__list__item">
                    <a href="https://v2.quatretondaparticipa.com/">
                        <i class="far fa-calendar-alt" aria-hidden="true"></i> <span>abril 2016</span>
                    </a>
                </li>
                <li class="sidebar__list__item">
                    <a href="https://v1.quatretondaparticipa.com/">
                        <i class="far fa-calendar-alt" aria-hidden="true"></i> <span>setembre 2015</span>
                    </a>
                </li>
                </ul>
            </section>
        @endif
    @endisset

    <div>
        {!! $edition->sidebar !!}
    </div>
</aside>
