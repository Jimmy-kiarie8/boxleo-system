require('./bootstrap');

require('alpinejs');


window.Vue = require('vue');
 
window.eventBus = new Vue()

import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en'
import 'element-ui/lib/theme-chalk/index.css';
// import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader
import vuetify from './vuetify'

import store from './vuex'

import router from './router/shopify'

Vue.use(ElementUI, { locale });


// import myHeader from './components/inc/header'
import myApp from './shopify/app'

const app = new Vue({
    el: '#shopify',
    // token: csrf_token,
    vuetify,
    store,
    router,
    components: {
         myApp
    },
    data() {
        return {
            open: true,
            progress: false
        }
    },
    methods: {
        setupTheme() {
            // alert('Theme configured')
            axios.post('settings').then((response) => {
                console.log(response);

            }).catch((error) => {
                console.log(error);
            })
        }
    },
});
