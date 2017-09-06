<template>
    <div class="dashboard">
        <navbar />
        <div class="container mb-5">
            <edition />
            <results v-if="display_results" />
        </div>
    </div>
</template>

<script>
    import Navbar from './admin/Navbar';
    import Edition from './admin/Edition';
    import Results from './admin/Results';

    export default {
        name: 'admin',

        components: {
            Navbar,
            Edition,
            Results
        },

        data() {
            return {
                display_results: false
            }
        },

        created() {
            if(window.app.user.is_superadmin)
                this.display_results = true;
        }
    }
</script>
