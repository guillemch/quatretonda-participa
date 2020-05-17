@extends('layouts.public')

@section('content')
    <div class="ballot-block ballot-block--error ballot-not-found text-center">
        <h3>@lang('participa.error')</h3>

        <i aria-hidden="true" class="far fa-hand-point-down"></i>
        <h2>@lang('participa.error_404')</h2>
        <p class="mt-4 mb-0">@lang('participa.error_404_text')</p>
        <hr class="my-3" />
        <p class="mb-0">@lang('participa.ballot_not_found_help', ['email' => config('participa.contact_email')])</p>
    </div>
@endsection
