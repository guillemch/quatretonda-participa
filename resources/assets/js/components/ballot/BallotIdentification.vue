<template>
  <div class="ballot-identification ballot-box">
    <h3><i class="fa fa-user" aria-hidden="true"></i> {{ $t('booth_identification.heading') }}</h3>
    <p class="subheading" v-html="$t('booth_identification.subheading', { min_age, municipality })"></p>
    <text-input
      name="identification"
      ref="identificationInput"
      :label="$t('booth_identification.label')"
      :tooltip="$t('booth_identification.tooltip')"
      :required="true"
      :value="identifier"
      :autofocus="autofocus"
      @update="updateID"
      @focus="autofocus = true"
      @blur="autofocus = false" />

    <hr aria-hidden="true" />

    <button :class="'btn btn-vote btn-primary btn-lg btn-block' + disabled" type="submit">
      <i v-if="disabled" class="fa fa-spinner-third fa-spin" aria-hidden="true"></i>
      <i v-else class="fa fa-bullhorn" aria-hidden="true"></i>
      {{ $t('booth_identification.button') }}
    </button>

    <p v-if="anonymous_voting" class="anonymous">
      <i class="far fa-lock" aria-hidden="true"></i>
      {{ $t('booth_identification.anonymous_voting') }}
    </p>
  </div>
</template>

<script>
  import TextInput from '../helpers/TextInput';

  export default {
    name: 'ballot-identification',

    components: {
      TextInput
    },

    props: {
      loading: Boolean,
      identifier: String
    },

    data () {
      return {
        municipality: '',
        min_age: '16',
        anonymous_voting: false,
        autofocus: false
      }
    },

    created () {
      this.anonymous_voting = window.BoothConfig.anonymous_voting;
      this.municipality = window.BoothConfig.name;
      this.min_age = window.BoothConfig.min_age;
    },

    mounted () {
      Bus.$on('doneSelecting', this.focusID);
      Bus.$on('focusMainButton', this.focusID);
    },

    computed: {
      disabled: function () {
        return this.loading ? ' disabled' : ''
      }
    },

    methods: {
      updateID (value) {
        Bus.$emit('fieldUpdated', 'ID', value);

        // Reset phone, country code and SMS request
        // in case user came from a previous step
        // and has changed their ID for whetever reason
        Bus.$emit('fieldUpdated', 'phone', '');
        Bus.$emit('fieldUpdated', 'countryCode', 34);
        Bus.$emit('fieldUpdated', 'smsRequested', false);
        Bus.$emit('fieldUpdated', 'smsCode', '');
      },

      focusID () {
        this.autofocus = true;
      }
    }

  }
</script>

<style lang="scss">
  @import '../../../sass/_variables';

  .anonymous {
    text-align: center;
    color: $gray-light;
    font-size: 1rem;
    margin-bottom: 0;
    margin-top: 1rem;
  }

  .form-control:focus {
    border-color: $brand-primary;
  }

</style>
