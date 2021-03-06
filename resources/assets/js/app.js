/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    'clients-table',
    require('./components/ClientsTable.vue')
);

Vue.component(
    'client-row',
    require('./components/ClientRow.vue')
);

Vue.component(
    'google-plus-accounts-table',
    require('./components/GooglePlusAccountsTable.vue')
);

Vue.component(
    'google-plus-posting-panels',
    require('./components/GooglePlusPostingPanels.vue')
);

const app = new Vue({
    el: '#app'
});
