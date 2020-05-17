<template>
  <div class="card mt-4">
    <div class="card-body">
      <div class="d-flex align-items-center">
        <h3>Resultats</h3>
        <div class="ml-auto results__refresh">
          <a href="#" @click.prevent="loadResults(true)">
            <i class="far fa-redo" aria-hidden="true"></i> Refresca
          </a>
        </div>
      </div>

      <hr class="mt-2 mb-3" />

      <div v-if="!loading" key="results-loaded" class="results-wrapper">
        <div v-if="turnout > 0 || !integrity">
          <div v-if="integrity" key="results-passed-integrity" class="alert alert-info">
            <i class="far fa-check" aria-hidden="true"></i>
            Test d'integritat passat correctament. Resultats generats el <strong>{{ time }}</strong>
          </div>
          <div v-else key="results-failed-integrity" class="alert alert-danger">
            <i class="far fa-minus-circle" aria-hidden="true"></i>
            <strong>Error:</strong> El test d'integritat ha fallat. Resultats generats el <strong>{{ time }}</strong>
          </div>
        </div>

        <div v-if="turnout > 0" key="results-with-turnout">
          <table class="table table-bordered">
            <tr>
              <th width="25%" class="text-right">Cens</th>
              <td width="25%">{{ census | formatNumber }}</td>
              <th width="25%" class="text-right">Participació</th>
              <td width="25%">{{ turnout | formatNumber }} <span v-if="turnout > 0">({{ turnoutPercentage }})</span></td>
            </tr>
          </table>

          <div v-for="result in results" :key="result.id">
            <h4>{{ result.question }}</h4>
            <table class="table table-sm">
              <colgroup>
                <col width="50%" />
                <col width="40%" />
                <col width="10%" />
              </colgroup>

              <tbody>
                <tr v-for="option in result.options" :key="option.id">
                  <td>{{ option.option }}</td>
                  <td style="vertical-align: middle">
                    <div class="progress">
                      <div class="progress-bar"
                         role="progressbar"
                         :style="'width: ' + option.relative + '%'"
                         :aria-valuenow="option.relative"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        <span v-if="option.percentage > 0">
                          {{ option.percentage | formatNumber }}%
                        </span>
                      </div>
                    </div>
                  </td>
                  <td class="text-right">
                    <span>
                      {{ option.points | formatNumber }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-else key="results-no-turnout" class="text-center results__empty">
          <i class="far fa-envelope-open fa-3x fa-fw mt-3"></i>
          <h4 class="mt-2">Cap vot encara</h4>
          <p>Encara no s'ha emés cap papereta a aquesta votació</p>
        </div>
      </div>
      <div v-else key="results-loading" class="text-center">
        <i class="far fa-spinner-third fa-spin fa-3x fa-fw mt-3"></i>
        <h4 class="mt-2">Carregant resultats...</h4>
        <p>Aquest procés pot tardar uns minuts mentres es comprova la validesa de cada papereta</p>
      </div>
    </div>
  </div>
</template>

<script>
  import format from 'format-number';

  export default {
    name: 'results',

    data () {
      return {
        results: {},
        census: 0,
        turnout: 0,
        integrity: true,
        time: '',
        loading: true
      }
    },

    mounted () {
      this.loadResults();
      Bus.$on('refreshResults', () => this.loadResults(true));
    },

    computed: {
      turnoutPercentage: function () {
        const percentage = (this.turnout * 100) / this.census;
        return format({ decimal: ',', suffix: '%', round: 2 })(percentage);
      }
    },

    filters: {
      formatNumber (number) {
        return format({integerSeparator: '.', round: 0})(number);
      }
    },

    methods: {
      /* Fetch results from server */
      loadResults (force) {
        this.loading = true;

        Participa.getResults(force)
          .then(response => {
            this.results = response.results;
            this.census = response.census;
            this.turnout = response.turnout;
            this.integrity = response.integrity;
            this.time = response.time;
            this.loading = false;
          });
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import '../../../sass/_variables';

  .results {
    &__refresh a:hover {
      text-decoration: none;
    }

    &__empty {
      color: $gray-light;
    }
  }
</style>
