<template>
    <div :class="{ 'input': true, 'focused': focused, 'has-warning': warning }">
    <label :for="name">
        {{label}}
        <b-tooltip v-if="tooltip" :content="tooltip" class="input-tooltip">
            <i class="fa fa-question-circle" aria-hidden="true"></i>
        </b-tooltip>
    </label>

    <input
        type="text"
        :pattern="pattern"
        :id="name"
        :name="name"
        :ref="name"
        :value="value"
        v-focus="autofocus"
        @input="$emit('update', $event.target.value)"
        @focus="focused = true; $emit('focus');"
        @blur="focused = value ? true : false; $emit('blur');"
        :required="required"
        :class="{ 'form-control form-control-lg': true, 'form-control-warning': warning }" />

    </div>
</template>

<script>
    import { focus } from 'vue-focus';

    export default {
        name: 'text-input',

        directives: { focus },

        props: {
            name: String,
            label: String,
            pattern: String,
            icon: String,
            value: String,
            tooltip: String,
            required: Boolean,
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
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/_variables';

    .input {
        position: relative;
        height: 71px;

        label {
            z-index: 10;
            position: absolute;
            margin-bottom: 0;
            color: lighten($gray-light, 25%);
            font-size: 1.2rem;
            top: 1.35rem;
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

            &:focus {
                border-color: $teal;
            }
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
