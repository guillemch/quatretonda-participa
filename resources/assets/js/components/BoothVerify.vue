<template>
  <div class="ballot-verify">
    <verify-summary :selected="selected" />

    <div class="ballot-box ballot-phone">
      <verify-in-person
        v-if="boothMode || disableSMSVerification"
        key="verify-in-person" />
      <verify-phone
        v-else
        key="verify-phone"
        :phone="phone"
        :country-code="countryCode"
        :sms-code="smsCode"
        :sms-requested="smsRequested" />
    </div>
  </div>
</template>

<script>
  import VerifyPhone from './verify/VerifyPhone';
  import VerifyInPerson from './verify/VerifyInPerson';
  import VerifySummary from './verify/VerifySummary';

  export default {
    name: 'booth-verify',

    components: {
      VerifyPhone,
      VerifyInPerson,
      VerifySummary
    },

    props: {
      phone: String,
      countryCode: Number,
      selected: Array,
      smsCode: String,
      smsRequested: Boolean
    },

    data () {
      return {
        boothMode: false,
        disableSMSVerification: false
      }
    },

    created () {
      this.boothMode = window.BoothMode;
      this.disableSMSVerification = window.BoothConfig.disable_SMS_verification;
      document.title = this.$t('verify_phone.heading') + ' - ' + window.BoothConfig.app_name;
    }
  }
</script>

<style scoped lang="scss">
  .ballot-phone {
    max-width: 500px;
    margin: 0 auto;
  }
</style>
