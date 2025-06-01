/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('alpinejs');

window.Vue = require('vue');

window.eventBus = new Vue()

// import Vuetify from 'vuetify';
// import 'vuetify/dist/vuetify.min.css';
// import '@mdi/font/css/materialdesignicons.css'; // Ensure you are using css-loader
// Vue.use(Vuetify)

import vuetify from './vuetify'
import router from './router/admin'
import store from './vuex'

import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en'
import 'element-ui/lib/theme-chalk/index.css';

// import 'material-design-icons-iconfont/dist/material-design-icons.css' // Ensure you are using css-loader






// import the styles

// import 'material-design-icons-iconfont/dist/material-design-icons.css'
// import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader
// import '@fortawesome/fontawesome-free/css/all.css' // Ensure you are using css-loader
// import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader

// import JsonExcel from 'vue-json-excel'

// Vue.component('downloadExcel', JsonExcel)

// import Builder from 'vuse';

// Vue.use(Builder);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

 import vueCountryRegionSelect from 'vue-country-region-select'
 Vue.use(vueCountryRegionSelect)
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.use(ElementUI, { locale });

import myPaypal from './components/admin/paypal'

import myAccount from './components/admin/signup/index'


import accountSetup from './components/admin/setup'


// import myExample from './components/ExampleComponent'
import myTracking from './components/settings/tracking'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');

const app = new Vue({
    el: '#admin',
    // token: csrf_token,
    // vuetify: new Vuetify(),

    store,
    vuetify,
    router,
    components: {
        // myExample,
        myPaypal, myAccount, accountSetup, myTracking
    },

    data() {
        return {
            userId: this.$userId
        }
    },

    mounted() {
        Echo.channel('order') //Should be Channel Name
            .listen('SaleEvent', (e) => {
                // console.log(e.sale);
                eventBus.$emit('statusChangeEvent', e.sale)
            });


        Echo.channel('order-upload') //Should be Channel Name
            .listen('OrderUploadEvent', (e) => {
                // console.log(e);
                eventBus.$emit('orderUploadEvent', e)
                eventBus.$emit('playSoundEvent')
            });


        Echo.channel('account') //Should be Channel Name
            .listen('CreatingAccountEvent', (e) => {
                // console.log(e);
                eventBus.$emit('CreatingAccountEvent', e)
                eventBus.$emit('playSoundEvent')
            });

        // Echo.channel('location') //Should be Channel Name
        //     .listen('SendPosition', (e) => {
        //         console.log(e);
        //         // eventBus.$emit('orderUploadEvent', e)
        //         eventBus.$emit('playSoundEvent')
        //     });

        // Echo.private('App.User.' + this.userId)
        //     .notification((notification) => {
        //         console.log(notification.type);
        //         eventBus.$emit('orderUploadEvent')
        //     });
    },
});
