require('./bootstrap');

window.Vue = require('vue');
window.Bus = new Vue();

import Raven from 'raven-js';
import RavenVue from 'raven-js/plugins/vue';

import BootstrapVue from 'bootstrap-vue';

import Participa from './api';

import Admin from './components/Admin';

window.Participa = new Participa();

Vue.use(BootstrapVue);

Raven
  .config('https://1d16265ee614464995c70aa8ff00c816@sentry.io/216036')
  .addPlugin(RavenVue, Vue)
  .install();

const app = new Vue({
  el: '#admin',
  components: { Admin },
  template: '<admin />',
});
