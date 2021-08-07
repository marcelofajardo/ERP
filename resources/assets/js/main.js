

window.Vue = require('vue');

window.axios = require('axios');

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('instagram-dm-component', require('./components/InstagramDMComponent.vue'));
Vue.component('cold-lead-broadcast-component', require('./components/CLBC.vue'));

const app = new Vue({
    el: '#cold_leads_vue'
});
