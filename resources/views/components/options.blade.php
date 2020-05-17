@foreach ($edition->questions as $question)
    <div class="options-widget">
        <h4>{{ $question->question }}</h4>
        <div class="card-columns">
            @foreach ($question->options as $option)
                <div class="card option">
                    <a  href="#" data-toggle="modal" data-target="#optionModal" data-option-id="{{ $option->id }}" data-option-title="{{ $option->option }}" class="option__card">
                        <h5>{{ $option->option }}</h5>

                        @if ($question->display_cost)
                            <div class="option__cost">{{ number($option->cost, 0) . '€' }}</div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>

        @if ($question->random_order)
            <div class="options-widget__random">@lang('participa.ordered_randomly')</div>
        @endif
    </div>
@endforeach

<!-- Modal -->
<div class="modal option-modal fade" id="optionModal" tabindex="-1" role="dialog" aria-labelledby="optionModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="optionModalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('participa.close')</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            $('#optionModal').on('show.bs.modal', function (e) {
                var option_id = e.relatedTarget.dataset.optionId,
                    option_title = e.relatedTarget.dataset.optionTitle;

                $(".modal-title", this).text(option_title);
                $(".modal-body").load('/api/option/' + option_id);
            });
        });
    </script>
@endpush
