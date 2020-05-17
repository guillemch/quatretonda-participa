<template>
  <div class="ballot-summary">
    <button @click="goBack()" class="btn btn-light btn-sm">
      <i class="far fa-pencil-alt" aria-hidden="true"></i>
      {{ $t('verify_summary.edit') }}
    </button>

    <div :class="{ 'questions': true, 'expanded': expanded }">
      <div v-for="question in selected" :key="question.id">
        <h3>{{ question.question }}</h3>
        <ul class="options">
          <li v-for="(option, i) in question.options" :key="option.id">
            <span class="badge badge-secondary">{{ i + 1 }}</span> {{ option.option }}
          </li>
          <li v-if="question.options.length === 0">
            <em class="blank-vote">{{ $t('verify_summary.blank') }}</em>
          </li>
        </ul>
      </div>

      <a href="#" class="expand" @click.prevent="expand">
        <i :class="'fa fa-chevron-' + arrowDirection" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'verify-summary',

    props: {
      selected: Array
    },

    data() {
      return {
        expanded: false
      }
    },

    computed: {
      arrowDirection () {
        return this.expanded ? 'up' : 'down';
      }
    },

    methods: {
      goBack () {
        Bus.$emit('goToStep', '/');
      },

      expand () {
        this.expanded = !this.expanded;
      }
    }
  }
</script>

<style scoped lang="scss">
  @import '../../../sass/_variables';

  $summary-padding: 1.8rem;

  .ballot-summary {
    position: relative;
    max-width: 500px;
    border: 4px $gray-lighter solid;
    margin: 0 auto;
    padding: $summary-padding;
    @if $enable-rounded {
      border-radius: 0.25rem 0.25rem 0 0;
    }

    h3 {
      font-size: 1.2rem;
      font-weight: 600;
    }

    .options {
      list-style: none;
      padding-left: 0;
      line-height: 175%;

      .far {
        margin-right: 10px;
      }
    }

    .btn {
      position: absolute;
      z-index: 100;
      top: 0;
      right: 0;
      background: $gray-lighter;
      border-color: $gray-lighter;
      cursor: pointer;
      @if $enable-rounded {
        border-radius: 0 0 0 0.25rem;
      }
    }
  }

  .questions {
    margin: -$summary-padding;
    padding: $summary-padding;
    max-height: 200px;
    position: relative;
    transition: 0.25s;
    overflow: hidden;

    .expand {
      display: flex;
      align-items: flex-end;
      justify-content: center;
      position: absolute;
      z-index: 50;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      /* Workaround Safari bug for transparent gradients */
      background: #FFF;
      background: linear-gradient(to bottom, rgba(255,255,255,0.001), #FFF);
      padding: 1rem;

      &:hover, &:active, &:focus {
        text-decoration: none;
      }
    }
  }

  .questions.expanded {
    max-height: 1000px;
    padding-bottom: 3rem;

    .expand {
      background: transparent;
    }
  }

  .blank-vote {
    opacity: 0.5;
  }
</style>
