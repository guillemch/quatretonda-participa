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
                            <i class="fa fa-unlock" aria-hidden="true"></i> Votació oberta
                        </div>
                        <div v-else class="vote-status vote-status--closed">
                            <i class="fa fa-lock" aria-hidden="true"></i> Votació tancada
                        </div>
                    </div>
                </div>
                <hr class="mt-3" />
                <div class="row">
                    <div class="col-sm-6">
                        <a href="/" target="_blank" :class="{ 'btn btn-lg btn-block': true, 'btn-success': editionIsOpen, 'btn-warning': !editionIsOpen, 'disabled': !editionIsOpen }">
                            <i class="fa fa-check-square-o" aria-hidden="true"></i> Emet vots
                        </a>
                    </div>
                    <div class="col-sm">
                        <b-btn v-if="!anonymousVoting" v-b-modal.anullBallot :class="{ 'btn btn-danger btn-lg btn-block': true, 'disabled': !editionIsOpen }">
                            <i class="fa fa-ban" aria-hidden="true"></i> Anul·la vot
                        </b-btn>
                    </div>
                    <div class="col-sm">
                        <b-btn v-if="enableIDLookUp" v-b-modal.lookUp class="btn btn-default btn-lg btn-block">
                            <i class="fa fa-search" aria-hidden="true"></i> Troba per DNI
                        </b-btn>
                    </div>
                </div>
            </div>
        </div>

        <anull-ballot />
        <look-up />
    </div>
</template>

<script>
    import AnullBallot from './AnullBallot';
    import LookUp from './LookUp';

    export default {
        name: 'edition',

        components: {
            AnullBallot,
            LookUp
        },

        data() {
            return {
                edition: {},
                anonymousVoting: false,
                enableIDLookUp: false,
                editionIsOpen: false,
            }
        },

        mounted() {
            this.loadEdition();
            this.anonymousVoting = window.app.config.anonymous_voting;
            this.enableIDLookUp = window.app.config.enable_ID_lookup;
            this.editionIsOpen = window.app.edition_is_open;
        },

        methods: {
            /* Fetch ballot from server */
            loadEdition() {
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
    }

    .vote-status--open {
        color: $green;
    }

    .vote-status--closed {
        color: $gray-light;
    }
</style>
