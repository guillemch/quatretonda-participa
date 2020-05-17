<template>
  <div class="sidebar">
    <div v-html="sidebar"></div>
  </div>
</template>

<script>
  export default {
    name: 'sidebar',

    data() {
      return {
        sidebar: ''
      }
    },

    mounted() {
      this.loadSidebar();
    },

    methods: {
      loadSidebar () {
        Participa.getSidebar()
          .then(response => {
            this.sidebar = response;
          });
      }
    }
  }
</script>
