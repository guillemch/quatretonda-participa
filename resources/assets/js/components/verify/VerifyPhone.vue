<template>
  <div class="verify-phone">
    <form @submit.prevent="requestSMS">
      <h3>
        <i class="fa fa-mobile-alt" aria-hidden="true"></i> {{ $t('verify_phone.heading') }}
      </h3>

      <p class="subheading" id="phoneDescription">{{ $t('verify_phone.phone_subheading') }}</p>

      <phone-input
        name="phone"
        ref="phone"
        :label="$t('verify_phone.phone_label')"
        :required="true"
        :value="phone"
        :country-code="countryCode"
        :disabled="smsRequested"
        :autofocus="phoneFocused"
        :warning="smsRequested && !canBeModified"
        @update="updatePhone"
        @updateCountryCode="updateCountryCode"
        @focus="phoneFocused = true"
        @blur="phoneFocused = false"
        aria-describedby="codeDescription">
          <button v-show="canBeModified" @click="modifyPhone" class="btn btn-edit btn-light btn-sm" type="button">
            <i class="far fa-pencil-alt" aria-hidden="true"></i>
            <span class="sr-only">{{ $t('verify_phone.modify_phone') }}</span>
          </button>
      </phone-input>

      <transition name="slide">
        <button v-show="!smsRequested" :class="'btn btn-primary btn-request btn-block btn-lg' + disabled" type="submit">
          <spinner icon="paper-plane" :loading="isLoading" />
          {{ $t('verify_phone.request_sms_button') }}
        </button>
      </transition>
    </form>


    <transition name="slide">
      <form v-if="smsRequested" @submit.prevent="castBallot">
        <hr aria-hidden="true" />

        <p class="subheading" id="codeDescription">{{ $t('verify_phone.code_subheading') }}</p>

        <text-input
          name="sms_code"
          ref="sms_code"
          pattern="\d*"
          :label="$t('verify_phone.code_label')"
          :tooltip="$t('verify_phone.code_tooltip')"
          :required="true"
          :value="smsCode"
          :autofocus="smsCodeFocused"
          @update="updateSMSCode"
          @focus="smsCodeFocused = true"
          @blur="smsCodeFocused = false"
          aria-describedby="codeDescription" />

        <verify-flags :flag="flag" />

        <button :class="'btn btn-success btn-cast btn-block btn-lg' + disabled" type="submit">
          <spinner icon="check" :loading="isLoading" />
          {{ $t('verify_phone.cast_ballot_button') }}
        </button>
      </form>
    </transition>
  </div>
</template>

<script>
  import Spinner from '../helpers/Spinner';
  import VerifyFlags from './VerifyFlags';
  import PhoneInput from '../helpers/PhoneInput';
  import TextInput from '../helpers/TextInput';

  export default {
    name: 'verify-phone',

    components: {
      Spinner,
      VerifyFlags,
      PhoneInput,
      TextInput
    },

    props: {
      phone: String,
      countryCode: Number,
      smsCode: String,
      smsRequested: Boolean,
    },

    data () {
      return {
        isLoading: false,
        phoneFocused: false,
        smsCodeFocused: false,
        flag: false
      }
    },

    computed: {
      disabled: function () {
        return this.isLoading ? ' disabled' : ''
      },
      canBeModified: function () {
        let SMS_exceeded = false;
        if (typeof this.flag === 'object') {
          if (this.flag.name === 'SMS_exceeded') SMS_exceeded = true;
        }
        return this.smsRequested === true && !SMS_exceeded;
      }
    },

    created () {
      Bus.$on('VerifyPhoneLoading', (isLoading) => this.isLoading = isLoading);
      Bus.$on('setFlag', (flag) => this.flag = flag);
      Bus.$on('focusMainButton', this.handleErrorModalClose);
    },

    mounted () {
      this.phoneFocused = true;
    },

    methods: {
      updatePhone (value) {
        Bus.$emit('fieldUpdated', 'phone', value);
      },

      updateSMSCode (value) {
        Bus.$emit('fieldUpdated', 'smsCode', value);
      },

      updateCountryCode (value) {
        Bus.$emit('fieldUpdated', 'countryCode', Number(value));
        this.phoneFocused = true;
      },

      modifyPhone (){
        Bus.$emit('fieldUpdated', 'smsRequested', false);
        Bus.$emit('fieldUpdated', 'smsCode', '');
        this.flag = false;
        this.phoneFocused = true;
      },

      requestSMS () {
        Bus.$emit('requestSMS');
        this.smsCodeFocused = true;
      },

      castBallot () {
        Bus.$emit('castBallot');
      },

      handleErrorModalClose(errors) {
        if(errors.hasOwnProperty('SMS_code')) {
          this.smsCodeFocused = true;
        } else {
          this.phoneFocused = true;
        }
      }
    }
  }
</script>

<style scoped lang="scss">
  .btn-edit {
    position: absolute;
    z-index: 150;
    right: 1rem;
    top: 1.3rem;
  }

  .btn-cast, .btn-request {
    margin-top: 1rem;
  }
</style>
