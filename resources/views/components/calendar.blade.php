@isset($edition)
    @php
        $startTime  = strtotime($edition->start_date);
        $endTime    = strtotime($edition->end_date);
        $startDay   = date('j', $startTime);
        $endDay     = date('j', $endTime);
        $startMonth = date('n', $startTime) - 1;
        $endMonth   = date('n', $endTime) - 1;
    @endphp
    <div class="calendar">
        <div class="calendar__heading">@lang('participa.calendar_heading')</div>
        <div class="calendar__dates">
            @if ($startMonth != $endMonth)
                <div class="d-flex calendar__date-columns">
                    <div class="col">
                        <div class="calendar__days"><strong>{{ $startDay }}</strong></div>
                        <div class="calendar__month">
                            @lang('participa.months_short.' . $startMonth)
                        </div>
                    </div>
                    <div class="col">
                        <div class="calendar__days"><strong>{{ $endDay }}</strong></div>
                        <div class="calendar__month">
                            @lang('participa.months_short.' . $endMonth)
                        </div>
                    </div>
                </div>
            @else
                <div class="calendar__days"><strong>{{ $startDay }}</strong> - <strong>{{ $endDay }}</strong></div>
                <div class="calendar__month">
                    @lang('participa.months_long.' . $startMonth)
                </div>
            @endif
        </div>
    </div>
@endisset
