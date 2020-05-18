<template>
  <section :class="'question template-' + question.template"
    :aria-labelledby="'question-' + question.id"
    role="group">
    <div :class="{ 'heading': true, 'has-number': displayNumber }">
      <span v-if="displayNumber" class="number">{{ number }}</span>
      <h2 :id="'question-' + question.id">{{ question.question }}</h2>
      <p class="description">{{ question.description }}</p>
    </div>
    <ballot-cost-indicator :sum="sumCost" :out-of="question.max_options" :done-selecting="doneSelecting" />
    <div
      :class="{
        'option-group': true,
        'list-group': question.template != 'cards'
      }">
      <label
        v-for="option in question.options"
        :key="option.id"
        :class="{
          'option': true,
          'list-group-item list-group-item-action': question.template !== 'cards',
          'disabled' : isDisabled(option),
          'selected' : isSelected(option)
        }">
        <ballot-option
          :type="questionType"
          :option="option"
          :selected="isSelected(option)"
          :context="selected[0].options"
          :disabled="isDisabled(option)"
          :display-cost="displayCost" />
      </label>
    </div>

    <hr  aria-hidden="true" />
  </section>
</template>

<script>
  import BallotOption from './BallotOption';
  import BallotCostIndicator from './BallotCostIndicator';

  export default {
    name: 'ballot-question',

    components: {
      BallotOption,
      BallotCostIndicator
    },

    props: {
      question: Object,
      selected: Array,
      number: Number,
      displayNumber: Boolean
    },

    computed: {
      questionType () {
        return this.question.max_options === 1 ? 'radio' : 'checkbox';
      },
      displayCost () {
        return this.question.display_cost === 1;
      },
      questionIndex () {
        return this.selected.findIndex((q) => q.id === this.question.id);
      },
      sumCost () {
        return this.selected[this.questionIndex].options.reduce((a, b) => a + b.cost, 0);
      },
      doneSelecting () {
        const remainingOptions = this.question.options.filter(option => {
          return this.selected[this.questionIndex].options.findIndex(o => o.id == option.id) === -1
        });

        if (!remainingOptions.length) return true;

        remainingOptions.sort((a, b) => a.cost - b.cost);
        return (this.sumCost + remainingOptions[0].cost) > this.question.max_options;
      }
    },

    watch: {
      doneSelecting (value) {
        this.$emit('doneSelecting', this.question.id, value);
      }
    },

    methods: {
      isSelected (option) {
        return this.selected[this.questionIndex].options.findIndex((o) => o.id === option.id) === -1 ? false : true;
      },

      isDisabled (option) {
        if (!this.doneSelecting) return option.cost + this.sumCost > this.question.max_options && !this.isSelected(option);
        return !this.isSelected(option);
      }
    }

  }
</script>

<style scoped lang="scss">
  @import '../../../sass/_variables';

  .question {
    h2 {
      font-size: 1.65rem;
      font-weight: 600;
    }

    .description {
      color: $gray-light;
    }

    .heading {
      position: relative;

      .number {
        color: darken($gray-lighter, 20%);
        font-size: 3rem;
        position: absolute;
        left: -50px;
        top: -15px;
      }
    }
  }
</style>
