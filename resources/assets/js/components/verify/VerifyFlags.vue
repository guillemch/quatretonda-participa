<template>
  <div class="flag">
    <div
      v-if="flag.name === 'SMS_already_sent'"
      v-html="$t('verify_flags.SMS_already_sent', { time: flag.info.time })"
      class="alert alert-info">
    </div>

    <div
      v-if="flag.name === 'SMS_exceeded'"
      v-html="$t('verify_flags.SMS_exceeded', { sms_max_attempts, last_number: flag.info.last_number, time: flag.info.time })"
      class="alert alert-warning">
    </div>

    <p v-if="!flag" class="smalltext">{{ $t('verify_phone.code_smalltext') }}</p>
  </div>
</template>

<script>
  export default {
    name: 'verify-flags',

    props: {
      flag: [Boolean, Object]
    },

    data () {
      return {
        sms_max_attempts: window.BoothConfig.sms_max_attempts
      };
    }
  }
</script>

<style scoped lang="scss">
  .alert {
    margin-bottom: 0;
  }

  .smalltext {
    font-size: 0.75rem;
    opacity: 0.5;
    margin-top: 1rem;
  }
</style>
