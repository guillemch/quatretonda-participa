@php
    $inPerson = (isset($inPerson)) ? $inPerson : false;
@endphp

@extends('layouts.public')

@section('content')
<div class="{{ $inPerson ? 'booth-mode' : '' }}">
    <div id="booth"></div>
</div>
@endsection

@push('scripts')
<script>
    window.BoothMode = {{ var_export($inPerson, true) }};
    window.BoothConfig = {
        locale: '@lang('participa.locale')',
        name: '{{ config('participa.municipality', 'Any City') }}',
        contact_email: '{{ config('participa.contact_email', 'participa@disedit.com') }}',
        url: '{{ config('app.url', '') }}',
        council_url: '{{ config('participa.council_url', '') }}',
        twitter: '{{ config('participa.twitter', 'infoDisedit') }}',
        anonymous_voting: {{ var_export(config('participa.anonymous_voting', true), true) }},
        min_age: {{ config('participa.min_age', 16) }},
        sms_max_attempts: {{ config('participa.sms_max_attempts', 3) }},
        max_per_ip: {{ config('participa.max_per_ip', 3) }},
        disable_SMS_verification: {{ var_export(config('participa.disable_SMS_verification', false), true) }}
    }
</script>
<script src="{{ mix('js/app.js') }}"></script>
@endpush
