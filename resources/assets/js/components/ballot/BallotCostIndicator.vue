<template>
  <div class="progress-holder d-flex align-items-center">
    <div class="progress" style="height: 30px;width: 100%;">
      <div :class="{ 'progress-bar bg-primary': true, 'progress-bar-striped progress-bar-animated': !doneSelecting }" role="progressbar" :style="{ width }" :aria-valuenow="sum" aria-valuemin="0" :aria-valuemax="outOf">
        {{ sum | formatCurrency }}
      </div>
    </div>
    <div class="out-of">{{ outOf | formatCurrency }}</div>
  </div>
</template>

<script>
  import format from 'format-number';

  export default {
    name: 'ballot-cost-indicator',

    props: {
      sum: Number,
      outOf: Number,
      doneSelecting: Boolean
    },

    computed: {
      width () {
        return this.sum * 100 / this.outOf + '%';
      }
    },

    filters: {
      formatCurrency: function (value) {
        if(window.BoothConfig.locale == 'es'
        || window.BoothConfig.locale == 'ca') {
          return format({ suffix: '€', integerSeparator: '.', round: 0 })(value);
        }

        return format({ prefix: '€', integerSeparator: ',', round: 0 })(value);
      }
    },
  }
</script>

<style lang="scss" scoped>
  @import '../../../sass/_variables';

  .out-of {
    color: rgba(0, 0, 0, .5);
    margin-left: .5rem;
  }

  .progress-holder {
    position: sticky;
    top: 50px;
    z-index: 1000;
    background: $white;
    padding: 1rem 0;
    border-bottom: 1px rgba(0, 0, 0, .15) solid;

    &::before,
    &::after {
      content: '';
      position: absolute;
      left: -10px;
      top: 0;
      bottom: 0;
      width: 10px;
      background: $white;
    }

    &::after {
      left: auto;
      right: -10px;
      top: 0;
      bottom: 0;
      width: 10px;
      background: $white;
    }
  }
</style>