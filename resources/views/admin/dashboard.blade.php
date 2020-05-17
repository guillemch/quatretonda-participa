@extends('layouts.admin')

@section('content')
    <div id="admin"></div>
@endsection

@push('scripts')
<script>
    window.app = {
        name: '{{ config('app.name', 'Wildcard Participa') }}',
        config: {!! json_encode(config('participa')) !!},
        user: {!! $user !!},
        edition_is_open: {{ var_export($editionIsOpen, true) }},
        ip: '{{ request()->ip() }}'
    }
</script>
<script src="{{ mix('js/admin.js') }}"></script>
@endpush
