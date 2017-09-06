@extends('layouts.public')

@section('content')
<div class="row">
    <div class="col-md-8">
        @php
            $about = $edition->about;
            $about = str_replace("[options]", $options, $about);
        @endphp
        {!! $about !!}
    </div>
    <div class="col-md-4">
        @include('components/sidebar')
    </div>
</div>
@endsection
