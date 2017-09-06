<template>
    <b-modal id="lookUp" title="Troba un identificador" :hide-footer="true" @hidden="clear">
        <form @submit.prevent="Lookup">
            <div class="form-group">
                <label for="SID">El DNI, NIE o Passaport conté els caràcters...</label>
                <input type="text" v-model="SID" v-focus="focused" :class="{ 'form-control': true, 'is-invalid': errors.hasOwnProperty('SID') }" ref="SID" id="SID" autocomplete="off" />
                <div v-if="errors.hasOwnProperty('SID')" v-for="error in errors.SID" class="invalid-feedback">{{ error }}</div>
            </div>
        </form>

        <div v-if="loading" class="alert alert-info"><i class="fa fa-spinner fa-pulse"></i> Carregant...</div>

        <div v-if="results">
            <div v-if="results.length > 0">
                <table class="table table-striped table-sm table-bordered">
                    <tbody>
                        <tr v-for="result in results">
                            <td v-html="$options.filters.highlight(result.SID, SID)"></td>
                        </tr>
                    </tbody>
                </table>
                <small v-if="results.length == 10" class="form-text text-muted">
                    Únicament es mostren els <strong>10 primers identificadors</strong> per seguretat.
                </small>
            </div>
            <div v-else>
                <div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> No s'ha trobat cap identificador</div>
            </div>
        </div>
    </b-modal>
</template>

<script>
    import { focus } from 'vue-focus';
    import debounce from 'lodash/debounce';

    export default {
        name: 'look-up',

        directives: { focus },

        data() {
            return {
                results: null,
                SID: '',
                focused: true,
                errors: {},
                loading: false
            }
        },

        watch: {
            SID: function(value) {
                if(value.length > 0) this.lookUp(value);
                this.errors = {};
            }
        },

        filters: {
            highlight: function(value, keyword) {
                var reg = new RegExp(keyword, 'gi');
                return value.replace(reg, (word) => '<span class="highlight">' + word + '</span>');
            }
        },

        methods: {
            lookUp: debounce(function() {
                this.loading = true;

                Participa.lookUp({
                    SID: this.SID
                }).then(response => {
                    this.results = response;
                    this.loading = false;
                }).catch(errors => {
                    this.errors = errors;
                    this.focused = true;
                    this.loading = false;
                    this.results = null;
                });
            }, 500),

            clear() {
                this.errors = {};
                this.results = null;
                this.SID = '';
                this.loading = false;
            }
        }
    }
</script>

<style lang="scss">
    @import '../../../sass/_variables';

    .highlight {
        background: $yellow;
    }
</style>
