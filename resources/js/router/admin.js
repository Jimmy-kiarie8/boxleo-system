import Vue from 'vue'
import VueRouter from 'vue-router'


Vue.use(VueRouter)

const routes = [
    // Payments
    { path: '/', component: () => import(/* webpackChunkName: "js/lux/plans" */ '../components/admin/payment/plan'), name: 'plan' },
    // { path: '/', component: () => import(/* webpackChunkName: "js/lux/plans" */ '../components/settings/tracking'), name: 'tracking' },
]



export default new VueRouter({ routes })
