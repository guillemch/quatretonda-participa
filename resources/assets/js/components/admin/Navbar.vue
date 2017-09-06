<template>
    <b-navbar toggleable type="dark" variant="primary" :sticky="true">
        <b-nav-toggle target="user_actions"></b-nav-toggle>

        <a class="navbar-brand" href="/">
            {{ appName }}
            <span class="location">
                <i class="fa fa-map-marker" aria-hidden="true"></i> {{ user.name }}
            </span>
        </a>

        <b-collapse is-nav id="user_actions">
            <b-nav is-nav-bar class="ml-auto">
                <b-nav-item-dropdown :text="user.name" right>
                    <form method="post" action="/logout">
                        <input type="hidden" name="_token" :value="csrfToken" />
                        <button type="submit" class="dropdown-item"><i class="fa fa-sign-out" aria-hidden="true"></i> Tanca sessió</button>
                    </form>
                </b-nav-item-dropdown>
            </b-nav>
        </b-collapse>
    </b-navbar>
</template>

<script>
    export default {
        name: 'navbar',

        data() {
            return {
                appName: '',
                user: { name: '' },
                csrfToken: ''
            }
        },

        mounted() {
            this.appName = window.app.name;
            this.user = window.app.user;
            this.csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
        }
    }
</script>

<style lang="scss" scoped>
    .location {
        margin-left: 1rem;
        opacity: 0.6;
    }
</style>
