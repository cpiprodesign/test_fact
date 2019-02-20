
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue'
import ElementUI from 'element-ui'
import Axios from 'axios'

import lang from 'element-ui/lib/locale/lang/es'
import locale from 'element-ui/lib/locale'
locale.use(lang)

//Vue.use(ElementUI)
Vue.use(ElementUI, {size: 'small'})
Vue.prototype.$eventHub = new Vue()
Vue.prototype.$http = Axios

// import VueCharts from 'vue-charts'
// Vue.use(VueCharts);
// import { TableComponent, TableColumn } from 'vue-table-component';
//
// Vue.component('table-component', TableComponent);
// Vue.component('table-column', TableColumn);

Vue.component('tenant-companies-form', require('./views/tenant/companies/form.vue'));
Vue.component('tenant-companies-logo', require('./views/tenant/companies/logo.vue'));
Vue.component('tenant-certificates-index', require('./views/tenant/certificates/index.vue'));
Vue.component('tenant-certificates-form', require('./views/tenant/certificates/form.vue'));
Vue.component('tenant-configurations-form', require('./views/tenant/configurations/form.vue'));
// Vue.component('tenant-establishments-form', require('./views/tenant/establishments/form.vue'));
// Vue.component('tenant-series-form', require('./views/tenant/series/form.vue'));
Vue.component('tenant-bank_accounts-index', require('./views/tenant/bank_accounts/index.vue'));
Vue.component('tenant-items-index', require('./views/tenant/items/index.vue'));
Vue.component('tenant-persons-index', require('./views/tenant/persons/index.vue'));
Vue.component('tenant-customers-index', require('./views/tenant/customers/index.vue'));
Vue.component('tenant-suppliers-index', require('./views/tenant/suppliers/index.vue'));
Vue.component('tenant-users-form', require('./views/tenant/users/form.vue'));
Vue.component('tenant-documents-index', require('./views/tenant/documents/index.vue'));
Vue.component('tenant-documents-invoice', require('./views/tenant/documents/invoice.vue'));
Vue.component('tenant-documents-note', require('./views/tenant/documents/note.vue'));
Vue.component('tenant-summaries-index', require('./views/tenant/summaries/index.vue'));
Vue.component('tenant-voided-index', require('./views/tenant/voided/index.vue'));
Vue.component('tenant-search-index', require('./views/tenant/search/index.vue'));
Vue.component('tenant-options-form', require('./views/tenant/options/form.vue'));
Vue.component('tenant-unit_types-index', require('./views/tenant/unit_types/index.vue'));
Vue.component('tenant-users-index', require('./views/tenant/users/index.vue'));
Vue.component('tenant-establishments-index', require('./views/tenant/establishments/index.vue'));
Vue.component('tenant-charge_discounts-index', require('./views/tenant/charge_discounts/index.vue'));
Vue.component('tenant-banks-index', require('./views/tenant/banks/index.vue'));
Vue.component('tenant-exchange_rates-index', require('./views/tenant/exchange_rates/index.vue'));
Vue.component('tenant-currency-types-index', require('./views/tenant/currency_types/index.vue'));
Vue.component('tenant-retentions-index', require('./views/tenant/retentions/index.vue'));
Vue.component('tenant-retentions-form', require('./views/tenant/retentions/form.vue'));
Vue.component('tenant-perceptions-index', require('./views/tenant/perceptions/index.vue'));
Vue.component('tenant-perceptions-form', require('./views/tenant/perceptions/form.vue'));
Vue.component('tenant-dispatches-index', require('./views/tenant/dispatches/index.vue'));
Vue.component('tenant-dispatches-form', require('./views/tenant/dispatches/form.vue'));
Vue.component('tenant-purchases-index', require('./views/tenant/purchases/index.vue'));
Vue.component('tenant-purchases-form', require('./views/tenant/purchases/form.vue'));
Vue.component('tenant-purchases-items', require('./views/tenant/dispatches/items.vue'));

Vue.component('tenant-attribute_types-index', require('./views/tenant/attribute_types/index.vue'));
Vue.component('tenant-calendar', require('./views/tenant/components/calendar.vue'));

// System
Vue.component('system-clients-index', require('./views/system/clients/index.vue'));
Vue.component('system-clients-form', require('./views/system/clients/form.vue'));
Vue.component('system-users-form', require('./views/system/users/form.vue'));

Vue.component('system-plans-index', require('./views/system/plans/index.vue'));
Vue.component('system-plans-form', require('./views/system/plans/form.vue'));

const app = new Vue({
    el: '#main-wrapper'
});
