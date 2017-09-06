<template>
    <div class="phone-input">
        <div class="country-codes">
            <country-codes :value="countryCode" :disabled="disabled" @update="updateCountryCode" />
        </div>

        <div class="phone">
            <div :class="{ 'input': true, 'focused': focused, 'has-warning': warning }">
                <label :for="name">
                    <i v-if="icon" :class="'fa fa-' + icon" aria-hidden="true"></i>
                    {{label}}
                    <b-tooltip v-if="tooltip" :content="tooltip" class="input-tooltip">
                        <i class="fa fa-question-circle" aria-hidden="true"></i>
                    </b-tooltip>
                </label>

                <input
                    ref="phoneField"
                    type="text"
                    pattern="\d*"
                    :id="name"
                    :ref="name"
                    :name="name"
                    :value="value"
                    v-focus="autofocus"
                    @input="$emit('update', $event.target.value)"
                    @focus="focused = true; $emit('focus');"
                    @blur="focused = value ? true : false; $emit('blur');"
                    :required="required"
                    :disabled="disabled"
                    :class="{ 'form-control form-control-lg': true, 'is-invalid': warning }" />

                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
    import CountryCodes from './CountryCodes';
    import { focus } from 'vue-focus';

    export default {
        name: 'phone-input',

        directives: { focus },

        components: {
            CountryCodes
        },

        props: {
            name: String,
            label: String,
            icon: String,
            value: String,
            countryCode: Number,
            tooltip: String,
            required: Boolean,
            disabled: Boolean,
            autofocus: Boolean,
            warning: Boolean
        },

        data() {
            return {
                focused: false
            }
        },

        mounted() {
            this.focused = this.value || this.autofocus ? true : false;
        },

        methods: {
            updateCountryCode(value) {
                this.$emit('updateCountryCode', value);
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/_variables';

    .phone-input {
        display: flex;
        align-content: stretch;
        align-items: stretch;

        .phone {
            width: 100%;
        }
    }

    .input {
        position: relative;
        height: 71px;

        label {
            z-index: 10;
            position: absolute;
            margin-bottom: 0;
            color: lighten($gray-light, 25%);
            font-size: 1.2rem;
            top: 1.4rem;
            left: 1.5rem;
            cursor: text;
            will-change: transform;
            transform: translateZ(0);
            -webkit-font-smoothing: antialiased;
            transition: 0.25s;
            user-select: none;
        }

        input {
            z-index: 1;
            position: absolute;
            padding-top: 2rem;
            padding-left: 1rem;
            padding-bottom: 0.5rem;
            border-width: 0.20rem;
        }

        select {
            position: absolute;
            left: 1.2rem;
            top: 2.2rem;
            width: 4rem;
            z-index: 10;
            background: $gray-lightest;
            border: 0;
            padding: 1px 3px;
            border: 1px #C9CBCB solid;
        }
    }

    .input.focused {
        label {
            top: 0.8rem;
            left: 1.2rem;
            font-size: 1rem;
            color: $gray-light;
        }
    }

    .input-tooltip {
        display: inline;
        font-size: 0.9rem;
    }
</style>
