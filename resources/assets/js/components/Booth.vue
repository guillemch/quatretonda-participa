<template>
    <div class="booth" id="boothView">
        <transition :name="transitionName" mode="out-in">
            <router-view
                class="child-view"
                :identifier="ID"
                :ballot="ballot"
                :selected="selected"
                :phone="phone"
                :country-code="countryCode"
                :sms-code="smsCode"
                :sms-requested="smsRequested"
                :receipt="receipt" />
        </transition>

        <error-modal :errors="errors" />
        <option-modal />
    </div>
</template>

<script>
    import jump from 'jump.js';
    import ErrorModal from './helpers/ErrorModal';
    import OptionModal from './helpers/OptionModal';

    export default {
        name: 'booth',

        components: {
            ErrorModal,
            OptionModal
        },

        data() {
          return {
            ballot: {},
            selected: [],
            errors: {},
            receipt: {},
            ID: '',
            phone: '',
            countryCode: 34,
            smsCode: '',
            smsRequested: false,
            transitionName: 'slide-left'
          }
        },

        beforeRouteUpdate (to, from, next) {
            let transitionName = 'slide-left';

            if(from.path == '/booth/verify' && to.path == '/') transitionName = 'slide-right';

            if(from.path == '/booth/receipt' && to.path == '/booth/verify'){
                // Should not be allowed. Redirect to first step
                this.clearBooth();
                this.$router.push({ path: '/' });
            }

            if(from.path == '/booth/receipt' && to.path == '/'){
                // If going from receipt to first step, clear the form
                this.clearBooth();
            }

            this.transitionName = transitionName;
            next();
        },

        created() {
            this.loadBallot();
            Bus.$on('optionSelected', (option, type) => this.handleOptionChange(option, type));
            Bus.$on('clearQuestion', (option) => this.clearQuestion(option));
            Bus.$on('fieldUpdated', (field, value) => this[field] = value);
            Bus.$on('submitBallotForVerification', () => this.submitBallotForVerification());
            Bus.$on('requestSMS', () => this.requestSMS());
            Bus.$on('castBallot', () => this.castBallot());
            Bus.$on('goToStep', (path) => this.$router.push({ path }));
        },

        watch: {
            selected: function(){
                this.doneSelecting();
            }
        },

        methods: {

            /* Fetch ballot from server */
            loadBallot() {
                Participa.getBallot()
                    .then(response => {
                        this.ballot = response;
                        this.initialSelected();
                    });
            },

            /* Load an emtpy ballot onto 'selected' */
            initialSelected() {
                /* Hack: Prevent original ballot value from updating */
                let ballot = JSON.parse( JSON.stringify( this.ballot.questions ) );

                ballot.forEach(function(question, index) {
                    ballot[index].options = new Array();
                });

                this.selected = ballot;
            },

            /* When user selects an option */
            handleOptionChange(option, type) {
                if(type == 'radio') {
                    this.radioOptions(option);
                } else {
                    this.checkboxOptions(option);
                }
            },

            /* Handles option selection for single-choice questions */
            radioOptions(option) {
                let selected = this.selected;
                const questionIndex = selected.findIndex((q) => q.id == option.question_id);

                selected[questionIndex].options = new Array(option);

                this.$set(this.selected, questionIndex, selected[questionIndex]);

            },

            /* Handles option selection for multiple-choice questions */
            checkboxOptions(option) {
                let selected = this.selected;
                const questionIndex = selected.findIndex((q) => q.id == option.question_id);
                let optionIndex = selected[questionIndex].options.findIndex((o) => o.id == option.id);

                if(optionIndex >= 0){
                    selected[questionIndex].options.splice(optionIndex, 1);
                } else {
                    selected[questionIndex].options.push(option);
                }

                this.$set(this.selected, questionIndex, selected[questionIndex]);
            },

            /* Provides a method to clear a radio question */
            clearQuestion(option) {
                const questionIndex = this.selected.findIndex((q) => q.id == option.question_id);
                let questions = this.selected[questionIndex];
                questions.options = [];
                this.$set(this.selected, questionIndex, questions);
            },

            /* Scroll to ID field, if all questions have been fully answered */
            doneSelecting() {
                let completed = [];

                this.selected.forEach((question) => completed.push(question.max_options == question.options.length));

                const shouldScroll = completed.every((value) => value === true);

                if(shouldScroll) {
                    jump('.ballot-identification', {
                        offset: -50,
                        duration: 500,
                        callback: () => Bus.$emit('doneSelecting')
                    });
                }
            },

            /* Precheck before step 2. Checks if ID exists or has been used */
            submitBallotForVerification() {
                Bus.$emit('BoothBallotLoading', true);

                Participa.precheck({
                    ballot: this.selected,
                    SID: this.ID
                }).then(response => {
                    this.$router.push({ path: '/booth/verify' });
                }).catch(errors => {
                    this.errors = errors
                }).then(() => Bus.$emit('BoothBallotLoading', false));
            },

            /* Request SMS code to verify ballot */
            requestSMS() {

                Bus.$emit('VerifyPhoneLoading', true);

                Participa.requestSMS({
                    ballot: this.selected,
                    SID: this.ID,
                    phone: this.phone,
                    country_code: this.countryCode
                }).then(response => {
                    jump('.ballot-phone', { offset: -50, duration: 500 });
                    this.smsRequested = true;
                    if(response.flag){
                        Bus.$emit('setFlag', response.flag);
                        if(response.flag.name == 'SMS_exceeded'){
                            this.phone = response.flag.info.last_number;
                            this.countryCode = parseInt(response.flag.info.last_country_code);
                        }
                    }
                }).catch(errors => {
                    this.errors = errors
                }).then(() => Bus.$emit('VerifyPhoneLoading', false));
            },

            /* Submit SMS code to register ballot */
            castBallot() {
                Bus.$emit('VerifyPhoneLoading', true);

                Participa.castBallot({
                    ballot: this.selected,
                    SID: this.ID,
                    phone: this.phone,
                    country_code: this.countryCode,
                    SMS_code: this.smsCode
                }).then(response => {
                    this.receipt = response.ballot;
                    this.$router.push({ path: '/booth/receipt' });
                }).catch(errors => {
                    this.errors = errors
                }).then(() => Bus.$emit('VerifyPhoneLoading', false));
            },

            clearBooth() {
                this.initialSelected();

                this.receipt = {};
                this.ID = '';
                this.phone = '';
                this.countryCode = 34;
                this.smsCode = '';
                this.smsRequested = false;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .booth {
        padding-top: 2rem;
        margin-top: -2rem;
    }
</style>
