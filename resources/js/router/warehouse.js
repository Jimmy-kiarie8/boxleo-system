import Vue from 'vue'
import VueRouter from 'vue-router'


Vue.use(VueRouter)

const routes = [
    { path: '/', component: () => import(/* webpackChunkName: "js/warehouse/dashboard" */ '../warehouse/dashboard/dashboard') },
    { path: '/warehouses', component: () => import(/* webpackChunkName: "js/warehouse/warehouse" */ '../warehouse/warehouse') },
    { path: '/levels', component: () => import(/* webpackChunkName: "js/warehouse/level" */ '../warehouse/warehouse/level') },
    { path: '/bin', component: () => import(/* webpackChunkName: "js/warehouse/bin" */ '../warehouse/warehouse/bin') },
    { path: '/receive', component: () => import(/* webpackChunkName: "js/warehouse/receive" */ '../warehouse/receive') },
    { path: '/asn', component: () => import(/* webpackChunkName: "js/warehouse/asn" */ '../warehouse/asn') },
    { path: '/dispatch', component: () => import(/* webpackChunkName: "js/warehouse/dispatch" */ '../warehouse/dispatch') },
    { path: '/products', component: () => import(/* webpackChunkName: "js/warehouse/products" */ '../components/product') },
    { path: '/product_details/:id', component: () => import(/* webpackChunkName: "js/warehouse/product_details" */ '../warehouse/products/details'), 'name': 'product_details' },
    { path: '/product/:id', component: () => import(/* webpackChunkName: "js/lux/editProduct" */ '../components/product/edit'), name: 'editProduct' },
    { path: '/shipments', component: () => import(/* webpackChunkName: "js/warehouse/shipments" */ '../warehouse/shipment-request') },
    { path: '/transfer', component: () => import(/* webpackChunkName: "js/warehouse/transfer" */ '../warehouse/product-transfer/index.vue') },
]

export default new VueRouter({ routes })
