@extends('layouts.public')

@section('content')
<div class="row">
    <div class="col-md-8">
        <iframe src="{{ $edition->proposal_form }}" width="100%" height="1550" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>
    </div>
    <div class="col-md-4">
        @include('components/sidebar')
    </div>
</div>
@endsection
