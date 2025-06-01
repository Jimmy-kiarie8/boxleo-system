/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

require("alpinejs");

window.Vue = require("vue");

window.eventBus = new Vue();

// import Vuetify from 'vuetify';
// import 'vuetify/dist/vuetify.min.css';
// import '@mdi/font/css/materialdesignicons.css'; // Ensure you are using css-loader
// Vue.use(Vuetify)

import vuetify from "./vuetify";
import router from "./router";
import store from "./vuex";

import ElementUI from "element-ui";
import locale from "element-ui/lib/locale/lang/en";
import "element-ui/lib/theme-chalk/index.css";

// import 'material-design-icons-iconfont/dist/material-design-icons.css' // Ensure you are using css-loader

import Receipt from "vue-receipt-component";
import splitPane from "vue-splitpane";

import * as XLSX from "xlsx/xlsx.mjs";

/* load 'fs' for readFile and writeFile support */
// import  fs from 'fs';
// XLSX.set_fs(fs);

/* load 'stream' for stream support */
import { Readable } from "stream";
XLSX.stream.set_readable(Readable);

/* load the codepage support library for extended support with older formats  */
import * as cpexcel from "xlsx/dist/cpexcel.full.mjs";
XLSX.set_cptable(cpexcel);

Vue.component("split-pane", splitPane);
Vue.use(Receipt);

//
import VuePlaceAutocomplete from "vue-place-autocomplete";
Vue.use(VuePlaceAutocomplete);

import VueTelInput from "vue-tel-input";
Vue.use(VueTelInput);

// import FileManager from 'laravel-file-manager'
// Vue.use(FileManager, {store})

// import VueGoodTablePlugin from 'vue-good-table';
// Vue.use(VueGoodTablePlugin);
// import 'vue-good-table/dist/vue-good-table.css'

import VueFuse from "vue-fuse";
Vue.use(VueFuse);

import VueHtmlToPaper from "vue-html-to-paper";
Vue.use(VueHtmlToPaper, options);

// import the styles

// import 'material-design-icons-iconfont/dist/material-design-icons.css'
// import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader
// import '@fortawesome/fontawesome-free/css/all.css' // Ensure you are using css-loader
// import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader

import JsonExcel from "vue-json-excel";

Vue.component("downloadExcel", JsonExcel);

// import DashboardPlugin from '@/plugins/blackDashboard'

import vueCountryRegionSelect from "vue-country-region-select";
Vue.use(vueCountryRegionSelect);

const options = {
  name: "_blank",
  specs: ["fullscreen=yes", "titlebar=yes", "scrollbars=yes"],
  styles: [
    "/css/receipt.css",
    "/css/app.css",
    // 'https://unpkg.com/kidlat-css/css/kidlat.css'
  ],
};

// import Builder from 'vuse';

// Vue.use(Builder);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.use(ElementUI, { locale });

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
import myHeader from "./components/include/Header.vue";
import myApp from "./components/app.vue";

// import myMaps from './components/maps'
// import myExample from './components/ExampleComponent'

import myAccount from "./components/admin/account";

import mySetup from "./components/admin/setup/company";

// import tinymce from 'vue-tinymce-editor'

// Vue.use('tinymce', tinymce)

import myMail from "./components/settings/automation/mailtemplate";
// import myPaypal from './components/admin/paypal'

import Chartkick from "vue-chartkick";
import Chart from "chart.js";
Vue.use(Chartkick.use(Chart));

import PrimeVue from "primevue/config";

Vue.use(PrimeVue);

import tinymce from "vue-tinymce-editor";
Vue.component("tinymce", tinymce);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');
Vue.prototype.$tenant = document.querySelector("meta[name='tenant-id']").getAttribute('content');

// router.beforeEach((to, from, next) => {
//     // if (to.name !== 'Login' && !isAuthenticated) next({ name: 'Login' })
//     store.dispatch('errorReset')
//     next()
//   })

const app = new Vue({
  el: "#app",
  // token: csrf_token,
  // vuetify: new Vuetify(),

  store,
  vuetify,
  router,
  components: {
    myHeader,
    myApp,
    myAccount,
    mySetup,
    myMail,
    // myMaps,myExample
    // , myProducts, myPos, myClients, myGroup, myDrawer, myDiscount
  },


  data() {
    return {
      userId: this.$userId,
      tenant: this.$tenant,
    };
  },

  mounted() {
    Echo.join(`calls`)
    .here((users) => {
        // eventBus.$emit('userOnlineEvent', user)
      })
    .joining((user) => {
        console.log(user);
        eventBus.$emit('userOnlineEvent', user)
    })
    .leaving((user) => {
        console.log(user);
        eventBus.$emit('userOfflineEvent', user)
      })
    .error((error) => {
        console.error(error);
    });



    window.Echo.private("order").listen("SaleEvent", (e) => {
    });
    // this.listen();

    Echo.channel('order-' + this.tenant) //Should be Channel Name
        .listen('SaleEvent', (e) => {
            eventBus.$emit('statusChangeEvent', e.sale)
        });

    // Echo.channel('private-websockets-dashboard-api-message') //Should be Channel Name
    //     .listen('SaleEvent', (e) => {
    //         alert(1)
    //         console.log(e.sale);
    //         eventBus.$emit('statusChangeEvent', e.sale)
    //     });

    Echo.channel('order-upload-' + this.tenant) //Should be Channel Name
        .listen('OrderUploadEvent', (e) => {
            eventBus.$emit('orderUploadEvent', e)
            // eventBus.$emit('playSoundEvent')
        });


    Echo.channel('mobile-' + this.tenant) //Should be Channel Name
    .listen('MobileEvent', (e) => {
        eventBus.$emit('MobileEventChange', e)
        // eventBus.$emit('playSoundEvent')
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
  methods: {


    listen() {
      Echo.join('calls')
          .joining((user) => {
              // alert(1)
              // alert('online')
              eventBus.$emit('userOnlineEvent', user)
              // axios.put('/api/user/' + user.id + '/online');
          })
          .leaving((user) => {
              eventBus.$emit('userOfflineEvent', user)
              // alert(2)
              // console.log(user);
              // axios.put('/api/user/' + user.id + '/offline');
          })
          .listen('UserOnline', (e) => {
              eventBus.$emit('userOfflineEvent')
              // console.log(e);
          })
          .listen('UserOffline', (e) => {
              // alert(4)
              // console.log(e);
              // eventBus.$emit('userOfflineEvent')
              // this.friend = e.user;
          });
  },
  },
});
