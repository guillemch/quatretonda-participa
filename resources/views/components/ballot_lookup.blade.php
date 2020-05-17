<section aria-labelledby="ballot-lookup" class="sidebar__box ballot-lookup">
    <h3 id="ballot-lookup">@lang('participa.ballot_lookup')</h3>
    <p class="{{ ($in_sidebar) ? 'sidebar__secondary' : '' }}">@lang('participa.ballot_lookup_help')</p>

    <form method="get" action="{{ secure_url('ballot/lookup') }}" aria-labelledby="ballot-lookup-form">
        <label class="sr-only" id="ballot-lookup-form">@lang('participa.ballot_ref')</label>
        <div class="input-group">
            <input type="search" name="ref" class="form-control ballot-lookup__input" placeholder="@lang('participa.ballot_ref')" aria-label="@lang('participa.ballot_ref')" required="required">
            <span class="input-group-append">
                <button class="btn btn-secondary" type="submit" title="@lang('participa.ballot_lookup')"><i class="far fa-search" aria-hidden="true"></i></button>
            </span>
        </div>
    </form>
</section>
