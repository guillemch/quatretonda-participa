<template>
    <b-modal id="errorsModal" ref="errorsModal" @hidden="close" size="lg" :hide-header="true" :visible="anyErrors">

        <div class="error">
            <div class="header">
                <i class="fa fa-hand-o-down" aria-hidden="true"></i>
                <h2>{{ $t('error.heading') }}</h2>
            </div>

            <div v-for="errorField in errors">
                <div v-for="error in errorField" class="message">
                    {{ error }}
                </div>
            </div>

            <div class="message message--contest">
                {{ $t('error.challenge') }} <a :href="'mailto:' + contact_email + '?subject=Error+votació'">{{ contact_email }}</a>
            </div>
        </div>

        <div slot="modal-footer" class="footer">
            <button class="btn btn-primary" @click="$refs.errorsModal.hide();">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ $t('error.back') }}
            </button>
        </div>

    </b-modal>
</template>

<script>
    export default {
        name: 'error-modal',

        props: {
            errors: Object
        },

        data() {
            return {
                contact_email: window.BoothConfig.contact_email
            }
        },

        computed: {
            anyErrors: function() { return Object.keys(this.errors).length > 0; }
        },

        methods: {
            close() {
                Bus.$emit('fieldUpdated', 'errors', {});
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/_variables';

    .header {
        text-align: center;
        margin-top: 20px;

        i {
            font-size: 68px;
            color: $gray;
            animation: thumbs-down 1s;
        }

        h2 {
            font-size: 46px;
            color: $gray;
        }
    }

    .message {
        text-align: center;
        width: 100%;
        max-width: 500px;
        margin: 15px auto;
        border: 2px $brand-danger solid;
        color: $brand-danger;
        border-radius: 5px;
        padding: 12px;
        font-size: 18px;
    }

    .message--contest {
        background: #FFF;
        color: $gray-light;
        font-size: 14px;
        border: 2px $gray-light solid;
    }

    .footer {
        width: 100%;
        display: block;
        text-align: center;
    }
</style>
