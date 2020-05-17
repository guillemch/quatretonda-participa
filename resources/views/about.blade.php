@extends('layouts.public')

@section('content')
<div class="row">
    <div class="col-lg-8">
        @php
            $about = $edition->about;
            $about = str_replace("[template]", $page['template'], $about);
            $about = str_replace("[options]", $page['options'], $about);
        @endphp
        {!! $about !!}
    </div>
    <div class="col-lg-4">
        @include('components/sidebar')
    </div>
</div>
@endsection
