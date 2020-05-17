<template>
  <b-modal
    id="lookUp"
    title="Troba un identificador"
    :hide-footer="true"
    @hidden="clear"
    @shown="focused = true">
    <form @submit.prevent="lookUp">
      <div class="form-group">
        <label for="SID">El DNI, NIE o Passaport conté els caràcters...</label>
        <input
          type="text"
          ref="SID"
          id="SID"
          v-model="SID"
          v-focus="focused"
          @blur="focused = false"
          :class="{
            'form-control': true,
            'is-invalid': errors.hasOwnProperty('SID')
          }"
          autocomplete="off" />
        <div v-if="errors.hasOwnProperty('SID')" class="invalid-feedback">
          <div
            v-for="(error, key) in errors.SID"
            :key="key">
               {{ error }}
          </div>
        </div>
      </div>
    </form>

    <div v-if="loading" class="alert alert-info">
      <i class="far fa-spinner-third fa-spin"></i> Carregant...
    </div>

    <div v-if="results">
      <div v-if="results.length > 0" key="lookup-with-results">
        <table class="table table-striped table-sm table-bordered">
          <tbody>
            <tr :key="key" v-for="(result, key) in results">
              <td v-html="$options.filters.highlight(result.SID, SID)"></td>
            </tr>
          </tbody>
        </table>
        <small v-if="results.length === 10" class="form-text text-muted">
          Únicament es mostren els <strong>10 primers identificadors</strong> per seguretat.
        </small>
      </div>
      <div v-else key="lookup-no-results">
        <div class="alert alert-danger">
          <i class="far fa-times" aria-hidden="true"></i>
          No s'ha trobat cap identificador
        </div>
      </div>
    </div>
  </b-modal>
</template>

<script>
  import { focus } from 'vue-focus';
  import debounce from 'lodash/debounce';

  export default {
    name: 'look-up',

    directives: {
      focus
    },

    data() {
      return {
        results: null,
        SID: '',
        focused: false,
        errors: {},
        loading: false
      }
    },

    watch: {
      SID: function (value) {
        if (value.length > 0) this.lookUp(value);
        this.errors = {};
      }
    },

    filters: {
      highlight: function (value, keyword) {
        var reg = new RegExp(keyword, 'gi');
        return value.replace(reg, (word) => '<span class="highlight">' + word + '</span>');
      }
    },

    methods: {
      lookUp: debounce(function () {
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

      clear () {
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
