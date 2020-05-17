<template>
  <div :class="'fake-ballot question template-' + template">
    <div class="heading">
      <span class="filler"></span>
    </div>
    <div class="subheading">
      <span class="filler"></span>
      <span class="filler" style="width: 60%"></span>
    </div>

    <div :class="{ 'option-group': true, 'list-group': template !== 'cards' }">
      <label
        v-for="index in 6"
        :key="index"
        :class="{
          'option': true,
          'list-group-item list-group-item-action': template !== 'cards'
        }">
        <div class="option-wrapper">
          <span class="box-filler"></span>
          <span class="filler"></span>
          <span class="filler" style="width: 60%"></span>
        </div>
      </label>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'ballot-loading',

    data () {
      return {
        template: 'cards'
      }
    },

    mounted () {
      this.template = window.BoothConfig.loading_template;
    }
  }
</script>

<style scoped lang="scss">
  @import '../../../sass/_variables';

  .fake-ballot {
    margin-bottom: 2rem;
  }

  .filler {
    display: block;
    height: 1rem;
    animation-duration: 1.5s;
    animation-fill-mode: forwards;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
    animation-name: placeHolder;
    background: $gray-lighter;
    background: linear-gradient(to right, $gray-lighter 8%, darken($gray-lighter, 5%) 18%, $gray-lighter 33%);
    background-size: 1200px 100px;
    position: relative;
  }

  .heading {
    margin-bottom: 1rem;

    .far {
      color: $gray-lighter;
      font-size: 1.5rem;
      margin-right: 0.5rem;
    }

    .filler {
      display: inline-block;
      width: 80%;
      height: 1.5rem;
      position: relative;
      top: 4px;
    }
  }

  .subheading {
    margin-bottom: 1rem;

    .filler {
      margin-bottom: 0.5rem;
    }
  }

  .option-wrapper {
    .filler {
      margin-bottom: 0.5rem;
    }
  }

  .list-group-item:hover {
    background: #fff;
  }

  .option:hover {
    background: #FFF;
    transform: none;
    border-color: darken($gray-lighter, 10%);
    box-shadow: $card-shadow;
  }

  @keyframes placeHolder {
    0% { background-position: -600px 0; }
    100% { background-position: 600px 0; }
  }
</style>
