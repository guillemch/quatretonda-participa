@extends('layouts.public')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="placeholder">
            <i class="fa fa-commenting-o" aria-hidden="true"></i>
            <h2>@lang('participa.voting_closed')</h2>
            <p>@lang('participa.awaiting_results', ['publish_date' => human_date($edition->publish_results), 'publish_time' => date('H:i', strtotime($edition->publish_results))])</h2>
        </div>
    </div>
    <div class="col-md-4">
        @include('components/sidebar')
    </div>
</div>
@endsection
