import Vue from 'vue'
import VueRouter from 'vue-router'


Vue.use(VueRouter)

const routes = [
    { path: '/', component: () => import(/* webpackChunkName: "js/lux/dashboard" */ '../components/dashboard/analytics') },
    { path: '/call-center', component: () => import(/* webpackChunkName: "js/lux/dashboard" */ '../components/dashboard/callcenter') },
    { path: '/agent-dashboard', component: () => import(/* webpackChunkName: "js/lux/dashboard" */ '../components/dashboard/agent-dashboard') },
    // { path: '/', component: () => import(/* webpackChunkName: "js/lux/dashboard" */ '../components/dashboard/dashboard1') },
    { path: '/users', component: () => import(/* webpackChunkName: "js/lux/users" */ '../components/users/users') },
    { path: '/vendors', component: () => import(/* webpackChunkName: "js/lux/sellers" */ '../components/users/sellers'), name: 'vendors' },
    { path: '/roles', component: () => import(/* webpackChunkName: "js/lux/roles" */ '../components/users/roles') },
    { path: '/products', component: () => import(/* webpackChunkName: "js/lux/products" */ '../components/product'), name: 'products' },
    { path: '/products/:id', component: () => import(/* webpackChunkName: "js/lux/productDetails" */ '../components/product/details'), name: 'productDetails' },
    { path: '/pos', component: () => import(/* webpackChunkName: "js/lux/pos" */ '../components/pos') },
    { path: '/clients', component: () => import(/* webpackChunkName: "js/lux/clients" */ '../components/users/clients') },
    { path: '/groups', component: () => import(/* webpackChunkName: "js/lux/group" */ '../components/group') },
    { path: '/sales', component: () => import(/* webpackChunkName: "js/lux/sales" */ '../components/sales'), name: 'sales' },
    { path: '/drawer', component: () => import(/* webpackChunkName: "js/lux/drawer" */ '../components/drawer') },
    { path: '/receipt', component: () => import(/* webpackChunkName: "js/lux/receipt" */ '../components/pos/inc/Test') },
    { path: '/discount', component: () => import(/* webpackChunkName: "js/lux/discount" */ '../components/discount') },
    { path: '/product/:id', component: () => import(/* webpackChunkName: "js/lux/editProduct" */ '../components/product/edit'), name: 'editProduct' },
    { path: '/product_details/:id', component: () => import(/* webpackChunkName: "js/warehouse/product_details" */ '../warehouse/products/details'), 'name': 'product_details' },
    { path: '/menu', component: () => import(/* webpackChunkName: "js/lux/menu" */ '../components/menu') },
    { path: '/category', component: () => import(/* webpackChunkName: "js/lux/category" */ '../components/menu/category') },
    { path: '/brand', component: () => import(/* webpackChunkName: "js/lux/brand" */ '../components/brand') },
    { path: '/subcategory', component: () => import(/* webpackChunkName: "js/lux/subcategory" */ '../components/menu/category/subcategory') },
    { path: '/currency', component: () => import(/* webpackChunkName: "js/lux/currency" */ '../components/settings/currency') },
    { path: '/product_option', component: () => import(/* webpackChunkName: "js/lux/product_setting" */ '../components/settings/products') },
    { path: '/slider', component: () => import(/* webpackChunkName: "js/lux/slider" */ '../components/settings/slider') },
    { path: '/adjustment', component: () => import(/* webpackChunkName: "js/lux/adjustment" */ '../components/inventory/adjustment') },
    { path: '/neworder', component: () => import(/* webpackChunkName: "js/lux/create_order" */ '../components/sales/create'), name: 'create_order' },
    { path: '/warehouse', component: () => import(/* webpackChunkName: "js/lux/warehouse" */ '../components/warehouse'), name: 'warehouse' },
    { path: '/sales/:id', component: () => import(/* webpackChunkName: "js/lux/saleorder" */ '../components/sales/saleorder'), name: 'saleorder' },
    { path: '/saleorder/:id', component: () => import(/* webpackChunkName: "js/lux/editOrder" */ '../components/sales/edit'), name: 'editOrder' },
    { path: '/status', component: () => import(/* webpackChunkName: "js/lux/status" */ '../components/settings/status'), name: 'status' },
    { path: '/custom', component: () => import(/* webpackChunkName: "js/lux/custom" */ '../components/settings/custom'), name: 'custom' },
    { path: '/custom_create', component: () => import(/* webpackChunkName: "js/lux/custom_create" */ '../components/settings/custom/create'), name: 'custom_create' },
    { path: '/custom/:id', component: () => import(/* webpackChunkName: "js/lux/custom_edit" */ '../components/settings/custom/edit'), name: 'custom_edit' },
    { path: '/subscription', component: () => import(/* webpackChunkName: "js/lux/subscription" */ '../components/settings/subscription'), name: 'subscription' },
    { path: '/returns', component: () => import(/* webpackChunkName: "js/lux/returns" */ '../components/sales/returns'), name: 'returns' },
    { path: '/returns/:id', component: () => import(/* webpackChunkName: "js/lux/edit_returns" */ '../components/sales/returns/edit'), name: 'edit_returns' },

    { path: '/tracking', component: () => import(/* webpackChunkName: "js/lux/tracking" */ '../components/sales/track'), name: 'tracking' },

    { path: '/branch_receive', component: () => import(/* webpackChunkName: "js/lux/branch_receive" */ '../components/sales/branch/branch'), name: 'branch_receive' },
    { path: '/packages', component: () => import(/* webpackChunkName: "js/lux/package" */ '../components/packages/Package') },
    { path: '/report', component: () => import(/* webpackChunkName: "js/lux/reports" */ '../components/settings/reports') },
    { path: '/analysis', component: () => import(/* webpackChunkName: "js/lux/analysis" */ '../components/settings/reports/analysis') },
    { path: '/custom_report', component: () => import(/* webpackChunkName: "js/lux/reports" */ '../components/settings/reports/custom') },
    { path: '/remittance', component: () => import(/* webpackChunkName: "js/lux/reports" */ '../components/settings/reports/remittance') },
 
    { path: '/company', component: () => import(/* webpackChunkName: "js/lux/company" */ '../components/settings/company') },
    { path: '/api', component: () => import(/* webpackChunkName: "js/lux/company" */ '../components/settings/company/apis') },
    { path: '/ou', component: () => import(/* webpackChunkName: "js/lux/ou" */ '../components/settings/ou') },

    { path: '/branches', component: () => import(/* webpackChunkName: "js/lux/branch" */ '../components/users/branches') },
    { path: '/riders', component: () => import(/* webpackChunkName: "js/lux/rider" */ '../components/users/riders') },

    { path: '/invoices', component: () => import(/* webpackChunkName: "js/lux/invoice" */ '../components/sales/invoices') },

    { path: '/dispatch', component: () => import(/* webpackChunkName: "js/lux/invoice" */ '../components/sales/dispatch') },
    { path: '/zones', component: () => import(/* webpackChunkName: "js/lux/zones" */ '../components/settings/charges/zones') },
    { path: '/services', component: () => import(/* webpackChunkName: "js/lux/services" */ '../components/settings/charges/services') },
    { path: '/city', component: () => import(/* webpackChunkName: "js/lux/city" */ '../components/settings/charges/city') },



    { path: '/automation', component: () => import(/* webpackChunkName: "js/lux/automation" */ '../components/settings/automation') },

    { path: '/auto', component: () => import(/* webpackChunkName: "js/lux/automations" */ '../components/settings/automation/create'), name: 'automations' },
    { path: '/automation/:id', component: () => import(/* webpackChunkName: "js/lux/automations" */ '../components/settings/automation/edit'), name: 'automationedit' },

    { path: '/sms_template', component: () => import(/* webpackChunkName: "js/lux/sms_template" */ '../components/settings/automation/sms/index.vue'), name: 'sms_template' },

    { path: '/mails', component: () => import(/* webpackChunkName: "js/lux/mails" */ '../components/settings/automation/mailtemplate/index.vue'), name: 'mails' },



    { path: '/status_update', component: () => import(/* webpackChunkName: "js/lux/status_update" */ '../components/sales/dispatch/status_update'), name: 'status_update' },

    { path: '/dispatch_filter', component: () => import(/* webpackChunkName: "js/lux/status_update" */ '../components/sales/dispatch/filters'), name: 'status_update' },
    { path: '/transactions', component: () => import(/* webpackChunkName: "js/lux/transactions" */ '../components/settings/transactions'), name: 'transactions' },


    { path: '/restore_backup', component: () => import(/* webpackChunkName: "js/lux/restore_backup" */ '../components/admin/settings/database/restore'), name: 'restore_backup' },

    // { path: '/mails', component: () => import(/* webpackChunkName: "js/lux/custom_create" */ '../components/settings/mailtemplate'), name: 'mails' },


    // Payments
    // { path: '/plans', component: () => import(/* webpackChunkName: "js/lux/plans" */ '../components/admin/payment/plan'), name: 'plan' },

    { path: '/shipments', component: () => import(/* webpackChunkName: "js/warehouse/shipments" */ '../warehouse/shipment-request') },


    
    { path: '/marketplace/', component: () => import(/* webpackChunkName: "js/marketplace/dashboard" */ '../marketplace/index.vue') },


    { path: '/shopify/orders', component: () => import(/* webpackChunkName: "js/marketplace/orders" */ '../marketplace/shopifyapp/orders') },
    { path: '/shopify/products', component: () => import(/* webpackChunkName: "js/marketplace/products" */ '../marketplace/shopifyapp/products') },
    { path: '/shopify/sync/:id', component: () => import(/* webpackChunkName: "js/marketplace/sync" */ '../marketplace/shopifyapp/home/sync'), name: 'sync' },
    // { path: '/settings', component: () => import(/* webpackChunkName: "js/marketplace/settings" */ './components/settings') },
    { path: '/woocommerce', component: () => import(/* webpackChunkName: "js/marketplace/sync" */ '../marketplace/woocommerce/home/') },
    { path: '/woocommerce/sync/:id', component: () => import(/* webpackChunkName: "js/marketplace/sync" */ '../marketplace/woocommerce/home/sync'), name: 'woocommerce_sync' },


    { path: '/google/sync/:id', component: () => import(/* webpackChunkName: "js/google/sync" */ '../marketplace/googlesheets/home/sync'), name: 'google_sync' },
    { path: '/zone-sheets', component: () => import(/* webpackChunkName: "js/zoneSheets" */ '../components/settings/charges/zones/sheets'), name: 'google_sync' },


    { path: '/mobile', component: () => import(/* webpackChunkName: "js/lux/status_update" */ '../components/sales/dispatch/filters/mobile'), name: 'mobile' },



    // { path: '/examples', component: () => import(/* webpackChunkName: "js/lux/examples" */ '../components/ExampleComponent.vue') },



    { path: '/callcentre', component: () => import(/* webpackChunkName: "js/lux/sales" */ '../components/callcentre/index.vue'), name: 'sales' },
    { path: '/realtime', component: () => import(/* webpackChunkName: "js/callcentre/realtime" */ '../components/callcentre/realtime.vue'), name: 'realtime' },


    { path: '/warehouse-report', component: () => import(/* webpackChunkName: "js/callcentre/realtime" */ '../components/settings/reports/warehouse.vue'), name: 'warehouse' },


]

export default new VueRouter({ routes })
