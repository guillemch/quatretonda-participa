<template>
  <div class="verify-in-person">
    <form @submit.prevent="castBallot">
      <button :class="'btn btn-cast btn-success btn-block btn-lg' + disabled" type="submit">
        <spinner icon="check" :loading="isLoading" />
        {{ $t('verify_in_person.button') }}
      </button>
    </form>
  </div>
</template>

<script>
  import Spinner from '../helpers/Spinner';

  export default {
    name: 'verify-in-person',

    components: {
      Spinner
    },

    data () {
      return {
        isLoading: false,
      }
    },

    computed: {
      disabled: function () {
        return this.isLoading ? ' disabled' : ''
      }
    },

    created () {
      Bus.$on('VerifyPhoneLoading', (isLoading) => this.isLoading = isLoading);
    },

    methods: {
      castBallot () {
        Bus.$emit('castBallot');
      }
    }
  }
</script>
