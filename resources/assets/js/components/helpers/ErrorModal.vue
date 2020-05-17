<template>
  <b-modal id="errorsModal" ref="errorsModal" @hidden="close" size="lg" :hide-header="true" :visible="anyErrors">
    <div class="error">
      <div class="header">
        <i class="far fa-hand-point-down" aria-hidden="true"></i>
        <h2>{{ $t('error.heading') }}</h2>
      </div>

      <div v-for="errorField in errors" :key="errorField">
        <div v-for="(error, key) in errorField" class="message" :key="key">
          {{ error }}
        </div>
      </div>

      <div class="message message--contest">
        {{ $t('error.challenge') }} <a :href="'mailto:' + contact_email + '?subject=Error+votació'">{{ contact_email }}</a>
      </div>
    </div>

    <div slot="modal-footer" class="footer">
      <div class="btn-wrapper">
        <button class="btn btn-light btn-block btn-lg" @click="$refs.errorsModal.hide();">
          <i class="far fa-arrow-circle-left" aria-hidden="true"></i> {{ $t('error.back') }}
        </button>
      </div>
    </div>
  </b-modal>
</template>

<script>
  export default {
    name: 'error-modal',

    props: {
      errors: Object
    },

    data () {
      return {
        contact_email: window.BoothConfig.contact_email
      }
    },

    computed: {
      anyErrors: function () { return Object.keys(this.errors).length > 0; }
    },

    methods: {
      close () {
        Bus.$emit('fieldUpdated', 'errors', {});
        Bus.$emit('focusMainButton', this.errors);
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import '../../../sass/_variables';

  .header {
    text-align: center;
    margin-top: 1.25rem;

    i {
      font-size: 4.25rem;
      color: $gray;
      animation: thumbs-down 1s;
    }

    h2 {
      font-size: 2.85rem;
      color: $gray;
    }
  }

  .message {
    text-align: center;
    width: 100%;
    max-width: 500px;
    margin: 1rem auto;
    border: 2px $brand-danger solid;
    color: $brand-danger;
    border-radius: 0.25rem;
    padding: 1rem;
    font-size: 1.15rem;

    &--contest {
      background: #FFF;
      color: $gray-light;
      font-size: 0.85rem;
      border: 2px $gray-light solid;
    }
  }

  .footer {
    width: 100%;
    display: block;
    text-align: center;

    .btn-wrapper {
      margin: -15px;
    }

    .btn-light {
      color: $brand-primary;
      padding: 1rem;
    }
  }
</style>
