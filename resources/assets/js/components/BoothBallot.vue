<template>
  <div class="row flex-column flex-sm-row">
    <div :class="{'col-md-8': !boothMode, 'col-md-12': boothMode}">
      <form @submit.prevent="submitBallot">
        <div v-if="Object.keys(ballot).length > 0" key="ballot-loaded">
          <div :key="item" v-for="(question, item) in ballot.questions">
            <ballot-question
              :question="question"
              :selected="selected"
              :number="item + 1"
              :display-number="ballot.questions.length > 1"
              @doneSelecting="(id, state) => $emit('doneSelecting', id, state)" />
          </div>
        </div>
        <div v-else key="ballot-loading">
          <ballot-loading />
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
  import BallotLoading from './ballot/BallotLoading';
  import Sidebar from './Sidebar';

  export default {
    name: 'booth-ballot',

    components: {
      BallotQuestion,
      BallotIdentification,
      BallotLoading,
      Sidebar
    },

    data () {
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

    created () {
      this.boothMode = window.BoothMode;
      Bus.$on('BoothBallotLoading', (isLoading) => this.isLoading = isLoading);
      document.title = window.BoothConfig.app_name;
    },

    methods: {
      submitBallot() {
        this.isLoading = true;
        Bus.$emit('submitBallotForVerification');
      }
    }
  }
</script>
