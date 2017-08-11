
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

import ElementUI from 'element-ui';

Vue.use(ElementUI);

// const dxHeader = r => require.ensure([], () => r(require('./components/parts/header.vue')), 'dxHeader');
// const cardList = r => require.ensure([], () => r(require('./components/card/list.vue')), 'cardList');

Vue.component('dxHeader', require('./components/parts/header.vue'));
Vue.component('ProductList', require('./components/product/list.vue'));
Vue.component('ProductAdd', require('./components/product/add.vue'));
Vue.component('ProductEdit', require('./components/product/edit.vue'));
Vue.component('MerchantList', require('./components/merchant/list.vue'));
Vue.component('MerchantAdd', require('./components/merchant/add.vue'));
Vue.component('MerchantEdit', require('./components/merchant/edit.vue'));
Vue.component('BannerList', require('./components/banner/list.vue'));
Vue.component('BannerAdd', require('./components/banner/add.vue'));
Vue.component('BannerEdit', require('./components/banner/edit.vue'));
Vue.component('ContextList', require('./components/context/list.vue'));
Vue.component('ContextAdd', require('./components/context/add.vue'));
Vue.component('ContextEdit', require('./components/context/edit.vue'));
Vue.component('ActivityList', require('./components/activity/list.vue'));
Vue.component('ActivityAdd', require('./components/activity/add.vue'));
Vue.component('ActivityEdit', require('./components/activity/edit.vue'));
Vue.component('HistoryList', require('./components/history/list.vue'));
Vue.component('HistoryAdd', require('./components/history/add.vue'));
Vue.component('HistoryEdit', require('./components/history/edit.vue'));
Vue.component('MsgList', require('./components/msg/list.vue'));
Vue.component('MsgAdd', require('./components/msg/add.vue'));
Vue.component('MsgEdit', require('./components/msg/edit.vue'));
Vue.component('ManList', require('./components/man/list.vue'));
Vue.component('ManAdd', require('./components/man/add.vue'));
Vue.component('ManEdit', require('./components/man/edit.vue'));

const app = new Vue({
    el: '#app'
});
