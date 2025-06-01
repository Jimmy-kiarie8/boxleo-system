import Vue from 'vue'
import VueRouter from 'vue-router'


Vue.use(VueRouter)

// import myUser from './components/users/'

// import myUsers from './components/users'
// import myRoles from './components/users/roles/Roles'

// const myDashboard = './components/dashboard'
// const myProducts = './components/product'
// const myPos = './components/pos'
// const myGroup = './components/group'
// const myDrawer = './components/drawer'
// const myDiscount = './components/discount'
// const mySales = './components/sales'
// const EditProduct = './components/product/edit'
// const myMenu = './components/menu'
// const myCategory = './components/menu/category'
// const myBrand = './components/brand'
// const mySubcategory = './components/menu/category/subcategory'
// const myReceipt = './components/pos/inc/Test'

// const myAdjustment = './components/inventory/adjustment'

// const productDetail = './components/product/details'

// // Settings
// const myCurrency = './components/settings/currency'
// const productOptions = './components/settings/products'
// const mySlider = './components/settings/slider'


// const createOrder = './components/sales/create'
// const myWarehouse = './components/warehouse'

// const myOrder = './components/sales/saleorder'

// const myOrderEdit = './components/sales/edit'

// const myClients = './components/users/clients'
// const myUsers = './components/users/users'
// const myRoles = './components/users/roles'
// const myVendors = './components/users/sellers'

// const myStatus = './components/settings/status'
// const myCustom = './components/settings/custom'
// const myCustomCreate = './components/settings/custom/create'
// const myCustomEdit = './components/settings/custom/edit'

// const mySubscription = './components/settings/subscription'

// const myReturn = './components/sales/returns'
// const mySaleReturn = './components/sales/returns/edit'

// const myTracking = './components/sales/track'
// const myBranchReceive = './components/sales/branch/branch'



// const myPackages = './components/packages/Package'
// const myReport = './components/settings/reports'

// const myCompany = './components/settings/company'
// const myOu = './components/settings/ou'
// const myBranches = './components/users/branches'
// const myRiders = './components/users/riders'


// import myInvoice from './components/sales/invoices'

const routes = [
    // {
    //     path: "/",
    //     // name: "dashboard",
    //     component: () => import(/* webpackChunkName: "dashboard" */ "./components/dashboard")
    // },
    // {
    //     path: "/sales",
    //     // name: "orders",
    //     component: () => import(/* webpackChunkName: "orders" */ "./components/sales/saleorder")
    // },
    // {
    //     path: "/invoices",
    //     // name: "invoices",
    //     component: () => import(/* webpackChunkName: "invoice" */ "./components/sales/invoices")
    // },
    // {
    //     path: "/tracking",
    //     // name: "tracking",
    //     component: () => import(/* webpackChunkName: "track" */ "./components/sales/track")
    // },
    // {
    //     path: "/users",
    //     // name: "users",
    //     component: () => import(/* webpackChunkName: "users" */ "./components/users/users")
    // },






    // { path: '/', component: () => './components/dashboard' },
    { path: '/', component: () => import(/* webpackChunkName: "js/lux/dashboard" */ './components/dashboard/dashboard') },
    { path: '/users', component: () => import(/* webpackChunkName: "js/lux/users" */ './components/users/users') },
    { path: '/vendors', component: () => import(/* webpackChunkName: "js/lux/sellers" */ './components/users/sellers') },
    { path: '/roles', component: () => import(/* webpackChunkName: "js/lux/roles" */ './components/users/roles') },
    { path: '/products', component: () => import(/* webpackChunkName: "js/lux/products" */ './components/product'), name: 'products' },
    { path: '/products/:id', component: () => import(/* webpackChunkName: "js/lux/productDetails" */ './components/product/details'), name: 'productDetails' },
    { path: '/pos', component: () => import(/* webpackChunkName: "js/lux/pos" */ './components/pos') },
    { path: '/clients', component: () => import(/* webpackChunkName: "js/lux/clients" */ './components/users/clients') },
    { path: '/groups', component: () => import(/* webpackChunkName: "js/lux/group" */ './components/group') },
    { path: '/sales', component: () => import(/* webpackChunkName: "js/lux/sales" */ './components/sales'), name: 'sales' },
    { path: '/drawer', component: () => import(/* webpackChunkName: "js/lux/drawer" */ './components/drawer') },
    { path: '/receipt', component: () => import(/* webpackChunkName: "js/lux/receipt" */ './components/pos/inc/Test') },
    { path: '/discount', component: () => import(/* webpackChunkName: "js/lux/discount" */ './components/discount') },
    { path: '/product/:id', component: () => import(/* webpackChunkName: "js/lux/editProduct" */ './components/product/edit'), name: 'editProduct' },
    { path: '/menu', component: () => import(/* webpackChunkName: "js/lux/menu" */ './components/menu') },
    { path: '/category', component: () => import(/* webpackChunkName: "js/lux/category" */ './components/menu/category') },
    { path: '/brand', component: () => import(/* webpackChunkName: "js/lux/brand" */ './components/brand') },
    { path: '/subcategory', component: () => import(/* webpackChunkName: "js/lux/subcategory" */ './components/menu/category/subcategory') },
    { path: '/currency', component: () => import(/* webpackChunkName: "js/lux/currency" */ './components/settings/currency') },
    { path: '/product_option', component: () => import(/* webpackChunkName: "js/lux/product_setting" */ './components/settings/products') },
    { path: '/slider', component: () => import(/* webpackChunkName: "js/lux/slider" */ './components/settings/slider') },
    { path: '/adjustment', component: () => import(/* webpackChunkName: "js/lux/adjustment" */ './components/inventory/adjustment') },
    { path: '/neworder', component: () => import(/* webpackChunkName: "js/lux/create_order" */ './components/sales/create'), name: 'create_order' },
    { path: '/warehouse', component: () => import(/* webpackChunkName: "js/lux/warehouse" */ './components/warehouse'), name: 'warehouse' },
    { path: '/sales/:id', component: () => import(/* webpackChunkName: "js/lux/saleorder" */ './components/sales/saleorder'), name: 'saleorder' },
    { path: '/saleorder/:id', component: () => import(/* webpackChunkName: "js/lux/editOrder" */ './components/sales/edit'), name: 'editOrder' },
    { path: '/status', component: () => import(/* webpackChunkName: "js/lux/status" */ './components/settings/status'), name: 'status' },
    { path: '/custom', component: () => import(/* webpackChunkName: "js/lux/custom" */ './components/settings/custom'), name: 'custom' },
    { path: '/custom_create', component: () => import(/* webpackChunkName: "js/lux/custom_create" */ './components/settings/custom/create'), name: 'custom_create' },
    { path: '/custom/:id', component: () => import(/* webpackChunkName: "js/lux/custom_edit" */ './components/settings/custom/edit'), name: 'custom_edit' },
    { path: '/subscription', component: () => import(/* webpackChunkName: "js/lux/subscription" */ './components/settings/subscription'), name: 'subscription' },
    { path: '/returns', component: () => import(/* webpackChunkName: "js/lux/returns" */ './components/sales/returns'), name: 'returns' },
    { path: '/returns/:id', component: () => import(/* webpackChunkName: "js/lux/edit_returns" */ './components/sales/returns/edit'), name: 'edit_returns' },

    { path: '/tracking', component: () => import(/* webpackChunkName: "js/lux/tracking" */ './components/sales/track'), name: 'tracking' },

    { path: '/branch_receive', component: () => import(/* webpackChunkName: "js/lux/branch_receive" */ './components/sales/branch/branch'), name: 'branch_receive' },
    { path: '/packages', component: () => import(/* webpackChunkName: "js/lux/package" */ './components/packages/Package') },
    { path: '/report', component: () => import(/* webpackChunkName: "js/lux/reports" */ './components/settings/reports') },

    { path: '/company', component: () => import(/* webpackChunkName: "js/lux/company" */ './components/settings/company') },
    { path: '/ou', component: () => import(/* webpackChunkName: "js/lux/ou" */ './components/settings/ou') },

    { path: '/branches', component: () => import(/* webpackChunkName: "js/lux/branch" */ './components/users/branches') },
    { path: '/riders', component: () => import(/* webpackChunkName: "js/lux/rider" */ './components/users/riders') },

    { path: '/invoices', component: () => import(/* webpackChunkName: "js/lux/invoice" */ './components/sales/invoices') },

    { path: '/dispatch', component: () => import(/* webpackChunkName: "js/lux/invoice" */ './components/sales/dispatch') },
    { path: '/zones', component: () => import(/* webpackChunkName: "js/lux/zones" */ './components/settings/charges/zones') },
    { path: '/services', component: () => import(/* webpackChunkName: "js/lux/services" */ './components/settings/charges/services') },
    { path: '/city', component: () => import(/* webpackChunkName: "js/lux/city" */ './components/settings/charges/city') },



    { path: '/automation', component: () => import(/* webpackChunkName: "js/lux/automation" */ './components/settings/automation') },

    { path: '/auto', component: () => import(/* webpackChunkName: "js/lux/automations" */ './components/settings/automation/create'), name: 'automations' },

    { path: '/sms_template', component: () => import(/* webpackChunkName: "js/lux/sms_template" */ './components/settings/automation/sms/index.vue'), name: 'sms_template' },

    // { path: '/mails', component: () => import(/* webpackChunkName: "js/lux/custom_create" */ './components/settings/mailtemplate'), name: 'mails' },


    // Payments
    { path: '/plans', component: () => import(/* webpackChunkName: "js/lux/plans" */ './components/admin/payment/plan'), name: 'plan' },



]



export default new VueRouter({ routes })
