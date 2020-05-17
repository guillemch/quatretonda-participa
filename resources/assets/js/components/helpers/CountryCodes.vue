<template>
  <div>
    <label for="dialCode" class="sr-only">{{ $t('verify_phone.country_code') }}</label>
    <select
      id="dialCode"
      :value="value"
      @change="$emit('update', $event.target.value)"
      @focus="$emit('focus')"
      :disabled="disabled"
      class="custom-select country-code-select">
      <option v-for="(code, key) in countryCodes" :value="code.dialCode" :key="key">
        +{{ code.dialCode }} - {{ code.name }}
      </option>
    </select>
  </div>
</template>

<script>
  import country_codes from './country_codes';

  export default {
    name: 'country-codes',

    props: {
      value: Number,
      disabled: Boolean
    },

    data () {
      return {
        countryCodes: country_codes
      }
    },
  }
</script>

<style lang="scss" scoped>
  @import '../../../sass/_variables';

  .country-code-select {
    background-color: rgba(0, 0, 0, 0.15);
    border-width: 0;
    width: 5rem;
    height: 4.5rem;
    @if $enable-rounded {
      border-radius: 0.25rem 0 0 0.25rem;
    } @else {
      border-radius: 0;
    }
  }

  .country-code-select:disabled {
    background-color: rgba(0, 0, 0, 0.15);
  }
</style>
