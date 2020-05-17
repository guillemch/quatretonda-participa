<template>
  <div>
    <div class="card mt-4">
      <div class="card-body">
        <div class="row">
          <div class="col-sm">
            <h1>{{ edition.name }}</h1>
          </div>
          <div class="col-sm text-right">
            <div v-if="editionIsOpen" class="vote-status vote-status--open">
              <i class="far fa-unlock" aria-hidden="true"></i> Votació oberta
            </div>
            <div v-else class="vote-status vote-status--closed">
              <i class="far fa-lock" aria-hidden="true"></i> Votació tancada
            </div>
          </div>
        </div>
        <hr class="mt-3" />
        <div class="row">
          <div class="col-sm-6">
            <a href="/"
              target="_blank"
              :class="{
                'btn btn-lg btn-block': true,
                'btn-success': editionIsOpen,
                'btn-warning': !editionIsOpen,
                'disabled': !editionIsOpen
              }">
              <i class="far fa-check-square" aria-hidden="true"></i> Emet vots
            </a>
          </div>
          <div class="col-sm">
            <b-btn
              v-if="!anonymousVoting"
              v-b-modal.annulBallot
              :class="{
                'btn btn-danger btn-lg btn-block': true,
                'disabled': !editionIsOpen
              }">
              <i class="far fa-ban" aria-hidden="true"></i> Anul·la vot
            </b-btn>
          </div>
          <div class="col-sm">
            <b-btn v-if="enableIDLookUp" v-b-modal.lookUp class="btn btn-default btn-lg btn-block">
              <i class="far fa-search" aria-hidden="true"></i> Troba per DNI
            </b-btn>
          </div>
        </div>
      </div>
    </div>

    <annul-ballot />
    <look-up />
  </div>
</template>

<script>
  import AnnulBallot from './AnnulBallot';
  import LookUp from './LookUp';

  export default {
    name: 'edition',

    components: {
      AnnulBallot,
      LookUp
    },

    data () {
      return {
        edition: {},
        anonymousVoting: false,
        enableIDLookUp: false,
        editionIsOpen: false,
      }
    },

    mounted () {
      this.loadEdition();
      this.anonymousVoting = window.app.config.anonymous_voting;
      this.enableIDLookUp = (window.app.config.hashed_SIDs) ? false : window.app.config.enable_ID_lookup;
      this.editionIsOpen = window.app.edition_is_open;
    },

    methods: {
      /* Fetch ballot from server */
      loadEdition () {
        Participa.getBallot()
          .then(response => {
            this.edition = response;
          });
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import '../../../sass/_variables';

  .vote-status {
    font-size: 2rem;

    &--open {
      color: $green;
    }

    &--closed {
      color: $gray-light;
    }
  }
</style>
