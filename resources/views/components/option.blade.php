<div>
    {!! $option->description !!}
</div>

@if ($option->motivation)
    <h4>@lang('participa.option_motivation')</h4>
    <div>
        {!! $option->motivation !!}
    </div>
@endif

@if ($option->cost > 0)
    <h4 class="mb-0">@lang('participa.option_cost')</h4>
    <span class="option-cost">{{ number($option->cost, 0) . '€' }}</span>
@endif

@if (!empty($option->attachments))
    <h4>@lang('participa.option_attachments')</h3>

    <ul class="option-attachments">
        @foreach ($option->attachments as $doc)
            @php
                $part = explode(",", $doc);
            @endphp

            <li><a href="{{ $part[1] or '' }}" target="_blank" rel="noopener"><i class="far fa-file-alt" aria-hidden="true" /> {{ $part[0] }}</li></a>
        @endforeach
    </ul>
@endif

@if (!empty($option->pictures))
    <div class="option-pictures">
        @foreach ($option->pictures as $pic)
            @php
                $part = explode(",", $pic);
            @endphp

            <img src="{{ $part[0] }}" alt="{{ $part[1] or 'Imatge' }}" />
        @endforeach
    </div>
@endif
