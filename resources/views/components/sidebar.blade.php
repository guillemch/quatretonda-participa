@php
    $docs = (!empty($edition->docs)) ? explode("\n", $edition->docs) : null;
    $places = (!empty($edition->voting_places)) ? explode("\n", $edition->voting_places) : null;
@endphp

<div class="sidebar">
    <div class="sidebar__box sidebar__box--main">
        @isset($isArchive)
            <h4>@lang('participa.poll')</h4>
        @else
            <h4>@lang('participa.current_poll')</h4>
        @endif

        <h3>{{ $edition->name }}</h3>
        <p class="sidebar__secondary">@lang('participa.sidebar_dates', ['start_date' => human_date($edition->start_date), 'end_date' => human_date($edition->end_date)])</p>

        <div class="sidebar__social-plugins">
            @component('components.share_buttons', ['share' => 'false'])
                @lang('participa.tweet')
            @endcomponent
        </div>

        <div v-if="docs">
            <hr />
            <ul class="sidebar__list">
                <li class="sidebar__list__item">
                    @if(isset($isArchive) && Request::segment(3) == 'about')
                        {{-- This is the archive and we are in the about page --}}
                        <a href="{{ url('archive/' . $edition->id) }}"><i class="fa fa-bar-chart" aria-hidden="true"></i>@lang('participa.results')</a>
                    @elseif(isset($isArchive) && Request::segment(3) != 'about')
                        {{-- This is the archive and we are in the main (results) page --}}
                        <a href="{{ url('archive/' . $edition->id . '/about') }}"><i class="fa fa-info-circle" aria-hidden="true"></i>@lang('participa.more_info')</a>
                    @elseif(!isset($isArchive) && Request::segment(1) == 'about' && $edition->isOpen() )
                        {{-- This is not the archive, edition is open but we are not in the main vote page --}}
                        <a href="{{ url('') }}"><i class="fa fa-bullhorn" aria-hidden="true"></i>@lang('participa.vote')</a>
                    @elseif(!isset($isArchive) && Request::segment(1) != 'about' && !$edition->isPending())
                        {{-- This is not the archive, we are in the main page and it does not contain the about page  --}}
                        <a href="{{ url('about') }}"><i class="fa fa-info-circle" aria-hidden="true"></i>@lang('participa.more_info')</a>
                    @elseif(!isset($isArchive) && Request::segment(1) == 'about' && $edition->resultsPublished())
                        {{-- This is not the archive, we are in the about page and results are published  --}}
                        <a href="{{ url('') }}"><i class="fa fa-bar-chart" aria-hidden="true"></i>@lang('participa.results')</a>
                    @elseif(Request::segment(1) == 'propose')
                        {{-- This is not the archive page and we are in the popose page --}}
                        <a href="{{ url('') }}"><i class="fa fa-info-circle" aria-hidden="true"></i>@lang('participa.more_info')</a>
                    @endif
                </li>

                @if(count($docs) > 0)
                    @forelse($docs as $doc)
                        @php
                            $part = explode(",", $doc);
                        @endphp
                        <li>
                            <a href="{{ $var = isset($part[1]) ? $part[1] : '' }}" target="_blank" rel="noopener" class="sidebar__list__item">
                                @isset($part[2])
                                    <i class="fa fa-{{ $part[2] }}" aria-hidden="true"></i>
                                @else
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                @endif

                                {{ $part[0] }}
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>

            @if(!isset($isArchive) && $edition->inProposalPhase() && Request::segment(1) != 'propose')
                <div class="sidebar__propose">
                    <a href="{{ url('propose') }}" class="btn btn-primary btn-lg btn-block"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('participa.propose_cta')</a>
                </div>
            @endif
        </div>
    </div>

    @if(count($places) > 0)
        <div class="sidebar__box" v-if="voting_places">
            <h4>@lang('participa.voting_places')</h4>
            <p class="sidebar__secondary">@lang('participa.voting_text')</p>
            <hr />
            <ul class="sidebar__list">
                @foreach($places as $place)
                    @php
                        $part = explode(",", $place);
                    @endphp

                    <li>
                        <span class="sidebar__list__item">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>

                            <span>
                                {{ $part[0] }}

                                @isset($part[1])
                                    <span class="sidebar__secondary">{{ $part[1] }}</span>
                                @endisset
                            </span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(!$edition->isPending())
        @component('components.ballot_lookup', ['in_sidebar' => true])
        @endcomponent
    @endif

    <div class="sidebar__box">
        <h4>@lang('participa.contact')</h4>
        <p>@lang('participa.contact_text', ['contact_email' => config('participa.contact_email')])</p>
    </div>

    @isset($pastEditions)
        @if(count($pastEditions) > 0)
            <div class="sidebar__box">
                <h4>@lang('participa.past_editions')</h4>

                <ul class="sidebar__list">
                @foreach($pastEditions as $edition)
                    <li class="sidebar__list__item">
                        <a href="{{ url('archive/' . $edition->id) }}">
                            <i class="fa fa-calendar-check-o" aria-hidden="true"></i> {{ human_month($edition->start_date) }}
                        </a>
                    </li>
                @endforeach
                </ul>
            </div>
        @endif
    @endisset

    <div>
        {!! $edition->sidebar !!}
    </div>
</div>
