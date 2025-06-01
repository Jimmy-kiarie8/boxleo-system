(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/lux/mar"],{

/***/ "./resources/js/marketplace.js":
/*!*************************************!*\
  !*** ./resources/js/marketplace.js ***!
  \*************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var element_ui__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! element-ui */ "./node_modules/element-ui/lib/element-ui.common.js");
/* harmony import */ var element_ui__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(element_ui__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var element_ui_lib_locale_lang_en__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! element-ui/lib/locale/lang/en */ "./node_modules/element-ui/lib/locale/lang/en.js");
/* harmony import */ var element_ui_lib_locale_lang_en__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(element_ui_lib_locale_lang_en__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var element_ui_lib_theme_chalk_index_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! element-ui/lib/theme-chalk/index.css */ "./node_modules/element-ui/lib/theme-chalk/index.css");
/* harmony import */ var element_ui_lib_theme_chalk_index_css__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(element_ui_lib_theme_chalk_index_css__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _vuetify__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./vuetify */ "./resources/js/vuetify.js");
/* harmony import */ var _vuex__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./vuex */ "./resources/js/vuex.js");
/* harmony import */ var _router_marketplace__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./router/marketplace */ "./resources/js/router/marketplace.js");
__webpack_require__(/*! ./bootstrap */ "./resources/js/bootstrap.js");

__webpack_require__(/*! alpinejs */ "./node_modules/alpinejs/dist/alpine.js");

window.Vue = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
window.eventBus = new Vue();


 // import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader




Vue.use(element_ui__WEBPACK_IMPORTED_MODULE_0___default.a, {
  locale: element_ui_lib_locale_lang_en__WEBPACK_IMPORTED_MODULE_1___default.a
}); // import myHeader from './components/inc/header'
// import myShopify from './marketplace/shopifyapp/app'

var app = new Vue({
  el: '#marketplace',
  // token: csrf_token,
  vuetify: _vuetify__WEBPACK_IMPORTED_MODULE_3__["default"],
  store: _vuex__WEBPACK_IMPORTED_MODULE_4__["default"],
  router: _router_marketplace__WEBPACK_IMPORTED_MODULE_5__["default"],
  components: {//  myShopify
  },
  data: function data() {
    return {
      open: true,
      progress: false
    };
  },
  methods: {
    setupTheme: function setupTheme() {
      // alert('Theme configured')
      axios.post('settings').then(function (response) {
        console.log(response);
      })["catch"](function (error) {
        console.log(error);
      });
    }
  }
});

/***/ }),

/***/ "./resources/js/router/marketplace.js":
/*!********************************************!*\
  !*** ./resources/js/router/marketplace.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_router__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-router */ "./node_modules/vue-router/dist/vue-router.esm.js");


vue__WEBPACK_IMPORTED_MODULE_0___default.a.use(vue_router__WEBPACK_IMPORTED_MODULE_1__["default"]); // import myDashboard from '../shopifyapp/home';
// import myOrders from '../shopifyapp/orders';
// import myProducts from '../shopifyapp/products';

var routes = [// { path: '/', component: myDashboard },
// { path: '/orders', component: myOrders },
// { path: '/products', component: myProducts },
{
  path: '/shopify/',
  component: function component() {
    return Promise.all(/*! import() | js/shopifyapp/dashboard */[__webpack_require__.e("js/lux/company~js/lux/products~js/lux/sales~js/shopifyapp/dashboard~js/shopifyapp/products"), __webpack_require__.e("js/shopifyapp/dashboard")]).then(__webpack_require__.bind(null, /*! ../marketplace/shopifyapp/home */ "./resources/js/marketplace/shopifyapp/home/index.vue"));
  }
}, {
  path: '/shopify/orders',
  component: function component() {
    return Promise.all(/*! import() | js/shopifyapp/orders */[__webpack_require__.e("js/lux/sales~js/lux/tracking~js/shopifyapp/orders"), __webpack_require__.e("js/lux/sales~js/shopifyapp/orders"), __webpack_require__.e("js/shopifyapp/orders")]).then(__webpack_require__.bind(null, /*! ../marketplace/shopifyapp/orders */ "./resources/js/marketplace/shopifyapp/orders/index.vue"));
  }
}, {
  path: '/shopify/products',
  component: function component() {
    return Promise.all(/*! import() | js/shopifyapp/products */[__webpack_require__.e("js/lux/company~js/lux/products~js/lux/sales~js/shopifyapp/dashboard~js/shopifyapp/products"), __webpack_require__.e("js/lux/create_order~js/lux/pos~js/lux/products~js/lux/sellers~js/shopifyapp/products"), __webpack_require__.e("js/lux/pos~js/lux/products~js/shopifyapp/products"), __webpack_require__.e("js/lux/products"), __webpack_require__.e("js/shopifyapp/products")]).then(__webpack_require__.bind(null, /*! ../marketplace/shopifyapp/products */ "./resources/js/marketplace/shopifyapp/products/index.vue"));
  }
}, {
  path: '/shopify/sync/:id',
  component: function component() {
    return __webpack_require__.e(/*! import() | js/shopifyapp/sync */ "js/shopifyapp/sync").then(__webpack_require__.bind(null, /*! ../marketplace/shopifyapp/home/sync */ "./resources/js/marketplace/shopifyapp/home/sync.vue"));
  },
  name: 'sync'
}, // { path: '/settings', component: () => import(/* webpackChunkName: "js/shopifyapp/settings" */ './components/settings') },
{
  path: '/woocommerce',
  component: function component() {
    return Promise.all(/*! import() | js/woocommerce/sync */[__webpack_require__.e("vendors~js/woocommerce/sync"), __webpack_require__.e("js/woocommerce/sync")]).then(__webpack_require__.bind(null, /*! ../marketplace/woocommerce/home/ */ "./resources/js/marketplace/woocommerce/home/index.vue"));
  }
}, {
  path: '/woocommerce/sync/:id',
  component: function component() {
    return Promise.all(/*! import() | js/woocommerce/sync */[__webpack_require__.e("vendors~js/woocommerce/sync"), __webpack_require__.e("js/woocommerce/sync")]).then(__webpack_require__.bind(null, /*! ../marketplace/woocommerce/home/sync */ "./resources/js/marketplace/woocommerce/home/sync.vue"));
  },
  name: 'woocommerce_sync'
}];
/* harmony default export */ __webpack_exports__["default"] = (new vue_router__WEBPACK_IMPORTED_MODULE_1__["default"]({
  routes: routes
}));

/***/ })

}]);