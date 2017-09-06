<template>
    <div class="row">
        <div :class="{'col-md-8': !boothMode, 'col-md-12': boothMode}">
            <form @submit.prevent="submitBallot">
                <div v-for="question in ballot.questions">
                    <ballot-question :question="question" :selected="selected" />
                </div>

                <ballot-identification :identifier="identifier" :loading="isLoading" />
            </form>
        </div>
        <div class="col-md-4" v-if="!boothMode">
            <sidebar :edition="ballot" />
        </div>
    </div>
</template>

<script>
    import BallotQuestion from './ballot/BallotQuestion';
    import BallotIdentification from './ballot/BallotIdentification';
    import Sidebar from './Sidebar';

    export default {
        name: 'booth-ballot',

        components: {
            BallotQuestion,
            BallotIdentification,
            Sidebar
        },

        data() {
            return {
                isLoading: false,
                boothMode: false,
            }
        },

        props: {
            ballot: Object,
            selected: Array,
            identifier: String,
        },

        created() {
            this.boothMode = window.BoothMode;
            Bus.$on('BoothBallotLoading', (isLoading) => this.isLoading = isLoading);
        },

        methods: {
            submitBallot() {
                this.isLoading = true;
                Bus.$emit('submitBallotForVerification');
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
