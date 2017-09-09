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
            loadSidebar() {
                Participa.getSidebar()
                    .then(response => {
                        this.sidebar = response;

                        !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
                    });
            }
        }
    }
</script>
