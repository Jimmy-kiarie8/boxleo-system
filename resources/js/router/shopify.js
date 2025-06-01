import Vue from 'vue'
import VueRouter from 'vue-router'


Vue.use(VueRouter)


import myDashboard from '../shopify/dashboard';
import myOrders from '../shopify/orders';
import myProducts from '../shopify/products';


const routes = [
    { path: '/', component: myDashboard },
    { path: '/orders', component: myOrders },
    { path: '/products', component: myProducts },

    // { path: '/', component: () => import(/* webpackChunkName: "js/lux/dashboard" */ '../shopify/dashboard') },
    // { path: '/orders', component: () => import(/* webpackChunkName: "js/lux/orders" */ '../shopify/orders') },
    // { path: '/settings', component: () => import(/* webpackChunkName: "js/lux/settings" */ './components/settings') },
];


export default new VueRouter({ routes })
