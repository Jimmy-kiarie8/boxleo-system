import Vue from 'vue'
import VueRouter from 'vue-router'


Vue.use(VueRouter)


// import myDashboard from '../shopifyapp/home';
// import myOrders from '../shopifyapp/orders';
// import myProducts from '../shopifyapp/products';

const routes = [
    // { path: '/', component: myDashboard },
    // { path: '/orders', component: myOrders },
    // { path: '/products', component: myProducts },

    { path: '/shopify/', component: () => import(/* webpackChunkName: "js/shopifyapp/dashboard" */ '../marketplace/shopifyapp/home') },
    { path: '/shopify/orders', component: () => import(/* webpackChunkName: "js/shopifyapp/orders" */ '../marketplace/shopifyapp/orders') },
    { path: '/shopify/products', component: () => import(/* webpackChunkName: "js/shopifyapp/products" */ '../marketplace/shopifyapp/products') },
    { path: '/shopify/sync/:id', component: () => import(/* webpackChunkName: "js/shopifyapp/sync" */ '../marketplace/shopifyapp/home/sync'), name: 'sync' },
    // { path: '/settings', component: () => import(/* webpackChunkName: "js/shopifyapp/settings" */ './components/settings') },
    { path: '/woocommerce', component: () => import(/* webpackChunkName: "js/woocommerce/sync" */ '../marketplace/woocommerce/home/') },
    { path: '/woocommerce/sync/:id', component: () => import(/* webpackChunkName: "js/woocommerce/sync" */ '../marketplace/woocommerce/home/sync'), name: 'woocommerce_sync' },
    { path: '/google', component: () => import(/* webpackChunkName: "js/google/sync" */ '../marketplace/googlesheets/home') },
    { path: '/google/sync/:id', component: () => import(/* webpackChunkName: "js/google/sync" */ '../marketplace/googlesheets/home/sync'), name: 'google_sync' },


];


export default new VueRouter({ routes })
