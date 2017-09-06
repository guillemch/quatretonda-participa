require('./bootstrap');

window.Vue = require('vue');
window.Bus = new Vue();

import BootstrapVue from 'bootstrap-vue';

import Participa from './api';

import Admin from './components/Admin';

window.Participa = new Participa();

Vue.use(BootstrapVue);

const app = new Vue({
    el: '#admin',
    components: { Admin },
    template: '<admin />',
});
