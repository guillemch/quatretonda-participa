<template>
  <div class="dashboard">
    <navbar />
    <div class="container mb-5">
      <edition />
      <div v-if="is_superadmin" class="row">
        <div class="col-md-8">
          <results />
        </div>
        <div class="col-md-4">
          <reports />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Navbar from './admin/Navbar';
  import Edition from './admin/Edition';
  import Results from './admin/Results';
  import Reports from './admin/Reports';

  export default {
    name: 'admin',

    components: {
      Navbar,
      Edition,
      Results,
      Reports
    },

    data () {
      return {
        is_superadmin: false
      }
    },

    created () {
      if (window.app.user.is_superadmin)
        this.is_superadmin = true;
    }
  }
</script>
