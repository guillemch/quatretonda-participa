<template>
    <div class="row ballot-verify">
        <div class="col-md-12">
            <verify-summary :selected="selected" />
        </div>
        <div class="col-md-12">
            <div class="ballot-box ballot-phone">
                <verify-in-person v-if="boothMode" />
                <verify-phone v-else :phone="phone" :country-code="countryCode" :sms-code="smsCode" :sms-requested="smsRequested" />
            </div>
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

        data() {
            return {
                boothMode: false
            }
        },

        created() {
            this.boothMode = window.BoothMode;
        }
    }
</script>

<style scoped lang="scss">
    .ballot-phone {
        max-width: 500px;
        margin: 0 auto;
    }
</style>
