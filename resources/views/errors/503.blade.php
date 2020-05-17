@extends('layouts.public')

@section('content')
    <div class="ballot-block ballot-block--error ballot-not-found text-center">
        <i aria-hidden="true" class="far fa-hand-point-down"></i>
        @if (isset($message))
            <p class="mt-4 mb-0">{!! $message !!}</p>
        @else
            <h2>@lang('participa.error_503')</h2>
            <p class="mt-4 mb-0">@lang('participa.error_503_text')</p>
        @endif
        <hr class="my-3" />
        <p class="mb-0">@lang('participa.ballot_not_found_help', ['email' => config('participa.contact_email')])</p>
    </div>
@endsection
