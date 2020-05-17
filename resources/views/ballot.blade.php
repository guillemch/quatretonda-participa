@extends('layouts.public')

@section('content')
    @if ($ballot)
        <div class="ballot-receipt">
            <div class="ballot ballot-block">
                <h3 class="ballot__edition">
                    {{ $ballot->edition->name }}
                    <a href="javascript:window.print()" class="pull-right btn-sm btn btn-success d-print-none"><i class="far fa-print" aria-hidden="true"></i> @lang('participa.print')</a>
                </h3>
                <h2 class="ballot__ref">
                    <img src="{{ secure_url('api/ballot/qr/' . $ballot->ref) }}" alt="QR code" width="75" />
                    {{ $ballot->ref }}
                </h2>

                <div class="ballot__contents">
                    @foreach ($ballot->decryptWithOptions() as $questionId => $content)
                        <h4>{{ $content['question']->question }}</h4>
                        <table class="table table-sm table-striped mt-3">
                            @php $i = 0; @endphp
                            @foreach ($content['options_in_order'] as $option)
                                @php $i++ @endphp
                                <tr>
                                    <td width="20"><span class="badge badge-secondary">{{ $i }}</span></td>
                                    <td>{{ $option['option']->option }}</td>
                                    <td class="ballot__points">+{{ $option['points'] }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endforeach
                </div>

                <div class="ballot__signature">
                    {{ $ballot->signature }}
                </div>
            </div>
        </div>
    @else
        <div class="ballot-block ballot-block--error ballot-not-found text-center">
            <h3>@lang('participa.error')</h3>

            <i aria-hidden="true" class="far fa-hand-point-down"></i>
            <h2>@lang('participa.ballot_not_found')</h2>
            <p class="mt-4 mb-0">@lang('participa.ballot_not_found_text', ['ref' => $ballotRef])</p>
            <hr class="my-3" />
            <p class="mb-0">@lang('participa.ballot_not_found_help', ['email' => config('participa.contact_email')])</p>
        </div>
    @endif

    <div class="ballot-lookup-wrapper d-print-none">
        @component('components.ballot_lookup', ['in_sidebar' => false])

        @endcomponent
    </div>

@endsection
