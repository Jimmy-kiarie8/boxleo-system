(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/lux/saleorder~js/lux/sales"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/printing/invoice/Invoice.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/printing/invoice/Invoice.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! moment */ "./node_modules/moment/moment.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _image__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./image */ "./resources/js/components/printing/invoice/image.js");
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = (_defineProperty({
  // props: ['image'],
  data: function data() {
    return {
      items: [{
        description: "Website design",
        quantity: 1,
        price: 300
      }, {
        description: "Website design",
        quantity: 1,
        price: 75
      }, {
        description: "Website design",
        quantity: 1,
        price: 10
      }],
      image: '',
      invoice_data: [],
      loading: false,
      activeName: 'first'
    };
  },
  computed: _objectSpread(_objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_2__["mapState"])(['saleorder'])), {}, {
    total: function total() {
      return this.items.reduce(function (acc, item) {
        return acc + item.price * item.quantity;
      }, 0);
    },
    invoice_total: function invoice_total() {
      var total = 0;

      if (this.invoice_data.length > 0) {
        this.invoice_data.product.forEach(function (element) {
          console.log(element);
          alert(element.price);
          total = parseFloat(total) + parseFloat(element.price) * parseFloat(element.order_qty);
        });
        return total;
      }
    }
  }),
  methods: {
    handleClick: function handleClick(tab, event) {
      var _this = this;

      console.log(tab, event);
      this.loading = true;
      setTimeout(function () {
        _this.loading = false;
      }, 1000);
    },
    addRow: function addRow() {
      this.items.push({
        description: "",
        quantity: 1,
        price: 0
      });
    },
    loadPage: function loadPage() {
      var _this2 = this;

      this.loading = true;
      setTimeout(function () {
        _this2.loading = false;
      }, 1000);
    }
  },
  filters: {
    currency: function currency(value) {
      return value.toFixed(2);
    }
  },
  created: function created() {
    var _this3 = this;

    eventBus.$on('invoiceEvent', function (data) {
      // console.log(data);
      _this3.loading = true;
      setTimeout(function () {
        _this3.loading = false;
      }, 1000);
      _this3.invoice_data = data;
    });
    eventBus.$on('invoiceLoadEvent', function (data) {
      _this3.loading = true;
      setTimeout(function () {
        _this3.loading = false;
      }, 1000);
    }); // eventBus.$on('invoiceLoadEvent', data => {
    //     this.loading = true
    //     setTimeout(() => {
    //         this.loading = false
    //     }, 1000);
    // });
  },
  mounted: function mounted() {
    // console.log(image);
    this.image = _image__WEBPACK_IMPORTED_MODULE_1__["image"];
  }
}, "filters", {
  moment: function moment(date) {
    return moment__WEBPACK_IMPORTED_MODULE_0___default()(date).format('MMMM Do YYYY');
  },
  moment2: function moment2(date) {
    return moment__WEBPACK_IMPORTED_MODULE_0___default()(date).format('MMMM Do YYYY', 'h:mm:ss a');
  }
}));

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['user'],
  data: function data() {
    return {
      toggle_exclusive: undefined,
      form: {}
    };
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapState"])(['invoices', 'packages', 'saleorder'])),
  methods: {
    sale_return: function sale_return() {
      eventBus.$emit('returnSaleEvent');
    },
    invoice: function invoice() {
      this.form.id = this.$route.params.id;
      var payload = {
        data: this.form,
        model: 'invoices'
      };
      this.$store.dispatch('postItems', payload).then(function (response) {
        eventBus.$emit('routerChangeEvent');
      });
    },
    confirm: function confirm() {
      var payload = {
        model: 'confirm',
        data: '',
        id: this.$route.params.id
      };
      this.$store.dispatch('patchItems', payload).then(function (res) {
        eventBus.$emit('routerChangeEvent');
      });
    },
    Saleorderprint: function Saleorderprint() {
      eventBus.$emit('SaleorderprintPage');
    },
    openEdit: function openEdit(data) {
      var id = data.id;
      this.$router.push({
        name: "editOrder",
        params: {
          id: id
        }
      });
      this.getWarehouses();
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/charges.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/charges.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
 // import Show from './Show';

/* harmony default export */ __webpack_exports__["default"] = ({
  components: {// Show
  },
  data: function data() {
    return {
      search: '',
      charges: [],
      headers: [{
        text: 'Order No.',
        value: 'name'
      }, {
        text: 'Amount',
        value: 'pivot.amount'
      }, {
        text: 'Created On',
        value: 'created_at'
      }]
    };
  },
  methods: {},
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapState"])(['saleorder', 'loading'])),
  mounted: function mounted() {
    eventBus.$emit("LoadingEvent"); // this.$store.dispatch('getZones');
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/details.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/details.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
/* harmony import */ var _invoice__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./invoice */ "./resources/js/components/sales/saleorder/invoice.vue");
/* harmony import */ var _charges__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./charges */ "./resources/js/components/sales/saleorder/charges.vue");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

 // import myPackage from './package'


/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    // myPackage,
    myInvoice: _invoice__WEBPACK_IMPORTED_MODULE_1__["default"],
    myCharges: _charges__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  data: function data() {
    return {
      form: {},
      dialog: false,
      activeName: 'comments',
      show_all: false
    };
  },
  methods: {
    addComment: function addComment() {
      this.form.sale_id = this.$route.params.id;
      var payload = {
        model: 'comments',
        data: this.form
      };
      this.$store.dispatch("postItems", payload).then(function (res) {
        eventBus.$emit('routerChangeEvent');
      });
    },
    handleClick: function handleClick(tab, event) {
      console.log(tab, event);
    },
    getInvoice: function getInvoice() {
      var payload = {
        model: 'invoices',
        update: 'updateInvoiceList',
        id: this.$route.params.id
      };
      this.$store.dispatch('showItem', payload);
    },
    confirm: function confirm() {
      var payload = {
        model: 'confirm',
        data: '',
        id: this.$route.params.id
      };
      this.$store.dispatch('patchItems', payload).then(function (res) {
        eventBus.$emit('routerChangeEvent');
      });
    }
  },
  created: function created() {
    var _this = this;

    eventBus('confirmEvent', function (data) {
      _this.confirm();
    });
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapState"])(['invoices', 'packages', 'saleorder']))
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/invoice.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/invoice.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      form: {}
    };
  },
  methods: {
    invoice: function invoice() {
      this.form.id = this.$route.params.id;
      var payload = {
        data: this.form,
        model: 'invoices'
      };
      this.$store.dispatch('postItems', payload).then(function (res) {
        eventBus.$emit('routerChangeEvent');
      });
    }
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapState"])(['saleorder']))
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/printing/invoice/Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/printing/invoice/Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.invoice-box[data-v-61ba94fc] {\r\n    max-width: 800px;\r\n    margin: auto;\r\n    padding: 30px;\r\n    border: 1px solid #eee;\r\n    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);\r\n    font-size: 16px;\r\n    line-height: 24px;\r\n    font-family: \"Helvetica Neue\", \"Helvetica\", Helvetica, Arial, sans-serif;\r\n    color: #555;\n}\n.invoice-box table[data-v-61ba94fc] {\r\n    width: 100%;\r\n    line-height: inherit;\r\n    text-align: left;\n}\n.invoice-box table td[data-v-61ba94fc] {\r\n    padding: 5px;\r\n    vertical-align: top;\n}\n.invoice-box table tr td[data-v-61ba94fc]:nth-child(n + 2) {\r\n    text-align: right;\n}\n.invoice-box table tr.top table td[data-v-61ba94fc] {\r\n    padding-bottom: 20px;\n}\n.invoice-box table tr.top table td.title[data-v-61ba94fc] {\r\n    font-size: 45px;\r\n    line-height: 45px;\r\n    color: #333;\n}\n.invoice-box table tr.information table td[data-v-61ba94fc] {\r\n    padding-bottom: 40px;\n}\n.invoice-box table tr.heading td[data-v-61ba94fc] {\r\n    background: #eee;\r\n    border-bottom: 1px solid #ddd;\r\n    font-weight: bold;\n}\n.invoice-box table tr.details td[data-v-61ba94fc] {\r\n    padding-bottom: 20px;\n}\n.invoice-box table tr.item td[data-v-61ba94fc] {\r\n    border-bottom: 1px solid #eee;\n}\n.invoice-box table tr.item.last td[data-v-61ba94fc] {\r\n    border-bottom: none;\n}\n.invoice-box table tr.item input[data-v-61ba94fc] {\r\n    padding-left: 5px;\n}\n.invoice-box table tr.item td:first-child input[data-v-61ba94fc] {\r\n    margin-left: -5px;\r\n    width: 100%;\n}\n.invoice-box table tr.total td[data-v-61ba94fc]:nth-child(2) {\r\n    border-top: 2px solid #eee;\r\n    font-weight: bold;\n}\n.invoice-box input[type=\"number\"][data-v-61ba94fc] {\r\n    width: 60px;\n}\n@media only screen and (max-width: 600px) {\n.invoice-box table tr.top table td[data-v-61ba94fc] {\r\n        width: 100%;\r\n        display: block;\r\n        text-align: center;\n}\n.invoice-box table tr.information table td[data-v-61ba94fc] {\r\n        width: 100%;\r\n        display: block;\r\n        text-align: center;\n}\n}\r\n\r\n/** RTL **/\n.rtl[data-v-61ba94fc] {\r\n    direction: rtl;\r\n    font-family: Tahoma, \"Helvetica Neue\", \"Helvetica\", Helvetica, Arial,\r\n        sans-serif;\n}\n.rtl table[data-v-61ba94fc] {\r\n    text-align: right;\n}\n.rtl table tr td[data-v-61ba94fc]:nth-child(2) {\r\n    text-align: left;\n}\n.ribbon[data-v-61ba94fc] {\r\n    margin: -27px;\r\n    position: absolute !important;\r\n    top: -5px;\r\n    left: -5px;\r\n    overflow: hidden;\r\n    width: 96px;\r\n    height: 94px;\r\n    border-bottom-right-radius: 92px;\n}\n.ribbon .ribbon-draft[data-v-61ba94fc] {\r\n    background: #94a5a6;\r\n    border-color: #788e8f;\n}\n.ribbon .ribbon-success[data-v-61ba94fc] {\r\n    background: #1fcd6d;\r\n    border-color: #18a155;\n}\n.ribbon .ribbon-open[data-v-61ba94fc] {\r\n    background-color: #2c96dd;\r\n    border-color: #1e7ab8;\n}\n.ribbon .ribbon-inner[data-v-61ba94fc] {\r\n    text-align: center;\r\n    color: #FFF;\r\n    top: 24px;\r\n    left: -31px;\r\n    width: 135px;\r\n    padding: 3px;\r\n    position: relative;\r\n    transform: rotate(-45deg);\n}\n.ribbon .ribbon-inner[data-v-61ba94fc]:before {\r\n    left: 0;\r\n    border-left: 2px solid transparent;\n}\n.ribbon .ribbon-inner[data-v-61ba94fc]:after,\r\n.ribbon .ribbon-inner[data-v-61ba94fc]:before {\r\n    content: \"\";\r\n    border-top: 5px solid transparent;\r\n    border-left: 5px solid;\r\n    border-left-color: inherit;\r\n    border-right: 5px solid transparent;\r\n    border-bottom: 5px solid;\r\n    border-bottom-color: inherit;\r\n    position: absolute;\r\n    top: 20px;\r\n    transform: rotate(-45deg);\n}\n.ribbon .ribbon-inner[data-v-61ba94fc]:after {\r\n    right: -4px;\r\n    top: 22px;\r\n    border-bottom: 3px solid transparent;\n}\n.ribbon .ribbon-inner[data-v-61ba94fc]:after,\r\n.ribbon .ribbon-inner[data-v-61ba94fc]:before {\r\n    content: \"\";\r\n    border-top: 5px solid transparent;\r\n    border-left: 5px solid;\r\n    border-left-color: inherit;\r\n    border-right: 5px solid transparent;\r\n    border-bottom: 5px solid;\r\n    border-bottom-color: inherit;\r\n    position: absolute;\r\n    top: 20px;\r\n    transform: rotate(-45deg);\n}\n.page-break[data-v-61ba94fc] {\r\n    page-break-after: always;\n}\r\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.el-dropdown-link[data-v-3a57c626] {\r\n    cursor: pointer;\r\n    color: #409EFF;\n}\n.el-icon-arrow-down[data-v-3a57c626] {\r\n    font-size: 12px;\n}\n.demonstration[data-v-3a57c626] {\r\n    display: block;\r\n    color: #8492a6;\r\n    font-size: 14px;\r\n    margin-bottom: 20px;\n}\r\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\np[data-v-3caa595c],\r\nsmall[data-v-3caa595c] {\r\n    font-size: 13px;\r\n    color: #555 !important;\n}\nh2[data-v-3caa595c] {\r\n    color: #000 !important;\n}\r\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/printing/invoice/Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/printing/invoice/Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/printing/invoice/Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/printing/invoice/Invoice.vue?vue&type=template&id=61ba94fc&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/printing/invoice/Invoice.vue?vue&type=template&id=61ba94fc&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticStyle: { width: "100%", padding: "0 10px" } }, [
    _c(
      "div",
      {
        directives: [
          {
            name: "loading",
            rawName: "v-loading",
            value: _vm.loading,
            expression: "loading"
          }
        ],
        staticClass: "invoice-box page-break"
      },
      [
        _c(
          "div",
          { staticClass: "container", staticStyle: { width: "100%" } },
          [
            _vm.saleorder.status === "Delivered"
              ? _c("div", { staticClass: "ribbon" }, [
                  _c("div", { staticClass: "ribbon-inner ribbon-success" }, [
                    _vm._v(_vm._s(_vm.saleorder.status))
                  ])
                ])
              : _vm.saleorder.status === "Scheduled" ||
                _vm.saleorder.status === "Dispatched"
              ? _c("div", { staticClass: "ribbon" }, [
                  _c("div", { staticClass: "ribbon-inner ribbon-open" }, [
                    _vm._v(_vm._s(_vm.saleorder.status))
                  ])
                ])
              : _c("div", { staticClass: "ribbon" }, [
                  _c("div", { staticClass: "ribbon-inner ribbon-draft" }, [
                    _vm._v(_vm._s(_vm.saleorder.status))
                  ])
                ]),
            _vm._v(" "),
            _c(
              "table",
              {
                staticClass: "table table-hover",
                attrs: { cellpadding: "0", cellspacing: "0" }
              },
              [
                _c("tr", { staticClass: "top" }, [
                  _c("td", { attrs: { colspan: "4" } }, [
                    _c("table", [
                      _c("tr", [
                        _c("td", { staticClass: "title" }, [
                          _c("img", {
                            staticStyle: {
                              width: "100%",
                              "max-width": "200px"
                            },
                            attrs: { src: _vm.image }
                          })
                        ]),
                        _vm._v(" "),
                        _c("td", [
                          _c(
                            "strong",
                            { staticStyle: { "font-size": "20px" } },
                            [_vm._v("SalesOrder ")]
                          ),
                          _vm._v(
                            "\r\n                                    # " +
                              _vm._s(_vm.saleorder.order_no) +
                              " "
                          ),
                          _c("br"),
                          _vm._v(" "),
                          _c("br"),
                          _vm._v(" "),
                          _c("b", [_vm._v("Balance Due")]),
                          _vm._v(
                            "\r\n                                    KES " +
                              _vm._s(_vm.saleorder.total_price) +
                              "\r\n                                    "
                          ),
                          _c("br"),
                          _vm._v(" "),
                          _c("b", [_vm._v("Order Date :")]),
                          _vm._v(
                            " " +
                              _vm._s(_vm._f("moment")(_vm.saleorder.created_at))
                          ),
                          _c("br"),
                          _vm._v(
                            " Shipment Date : " +
                              _vm._s(_vm.saleorder.pickup_date) +
                              "\r\n                                    "
                          ),
                          _c("br"),
                          _vm._v(" "),
                          _c("b", [_vm._v("Delivery Method : ")]),
                          _vm._v(
                            " " +
                              _vm._s(_vm.saleorder.delivery_method) +
                              "\r\n                                "
                          )
                        ])
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("tr", { staticClass: "information" }, [
                  _c("td", { attrs: { colspan: "4" } }, [
                    _c("table", [
                      _c("tr", [
                        _vm._m(0),
                        _vm._v(" "),
                        _c("td", [
                          _c("b", [_vm._v("Client Details")]),
                          _vm._v(" "),
                          _c("br"),
                          _vm._v(
                            "\r\n\r\n                                    " +
                              _vm._s(_vm.saleorder.client.name)
                          ),
                          _c("br"),
                          _vm._v(" " + _vm._s(_vm.saleorder.client.address)),
                          _c("br"),
                          _vm._v(
                            " " + _vm._s(_vm.saleorder.client.email) + " "
                          ),
                          _c("br"),
                          _vm._v(
                            " " +
                              _vm._s(_vm.saleorder.client.phone) +
                              "\r\n                                "
                          )
                        ])
                      ])
                    ])
                  ])
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "table",
              { staticClass: "table table-striped table-hover table-bordered" },
              [
                _vm._m(1),
                _vm._v(" "),
                _c(
                  "tbody",
                  _vm._l(_vm.saleorder.products, function(item, index) {
                    return _c("tr", { key: index, staticClass: "item" }, [
                      _c("td", { staticStyle: { "text-align": "center" } }, [
                        _vm._v(_vm._s(index + 1))
                      ]),
                      _vm._v(" "),
                      _c("td", { staticStyle: { "text-align": "center" } }, [
                        _vm._v(_vm._s(item.product_name) + " "),
                        _c("br")
                      ]),
                      _vm._v(" "),
                      _c("td", { staticStyle: { "text-align": "center" } }, [
                        _vm._v(
                          "\r\n                            " +
                            _vm._s(item.quantity) +
                            "\r\n                        "
                        )
                      ]),
                      _vm._v(" "),
                      _c("td", { staticStyle: { "text-align": "center" } }, [
                        _vm._v(
                          "\r\n                            " +
                            _vm._s(item.price) +
                            "\r\n                        "
                        )
                      ]),
                      _vm._v(" "),
                      _c("td", { staticStyle: { "text-align": "center" } }, [
                        _vm._v(
                          _vm._s(
                            parseFloat(item.price) * parseFloat(item.quantity)
                          )
                        )
                      ])
                    ])
                  }),
                  0
                ),
                _vm._v(" "),
                _c("tfoot", [
                  _c("td"),
                  _vm._v(" "),
                  _c("td"),
                  _vm._v(" "),
                  _c("td"),
                  _vm._v(" "),
                  _vm._m(2),
                  _vm._v(" "),
                  _c("td", [_c("b", [_vm._v(_vm._s(_vm.saleorder.sub_total))])])
                ])
              ]
            ),
            _vm._v(" "),
            _c("v-divider"),
            _vm._v(" "),
            _vm.saleorder.instructions ? _c("h5", [_vm._v("Notes")]) : _vm._e(),
            _vm._v(" "),
            _c("br"),
            _vm._v(" "),
            _c("small", [_vm._v(_vm._s(_vm.saleorder.instructions))])
          ],
          1
        )
      ]
    )
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("td", [
      _c("b", [_vm._v("Sender Details")]),
      _vm._v(" "),
      _c("br"),
      _vm._v("\r\n                                    Company Name."),
      _c("br"),
      _vm._v(" Address"),
      _c("br"),
      _vm._v(" Email "),
      _c("br"),
      _vm._v(" Phone no.\r\n                                ")
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v("#")]),
        _vm._v(" "),
        _c("th", { attrs: { scope: "col" } }, [_vm._v("Item & Description")]),
        _vm._v(" "),
        _c("th", { attrs: { scope: "col" } }, [_vm._v("Quantity")]),
        _vm._v(" "),
        _c("th", { attrs: { scope: "col" } }, [_vm._v("Price")]),
        _vm._v(" "),
        _c("th", { attrs: { scope: "col" } }, [_vm._v("Total")])
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("td", { staticStyle: { "text-align": "center" } }, [
      _c("b", [_vm._v("Total")])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=template&id=3a57c626&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=template&id=3a57c626&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "v-toolbar",
    { attrs: { dense: "" } },
    [
      _c("v-toolbar-title", [_vm._v(_vm._s(_vm.saleorder.order_no))]),
      _vm._v(" "),
      _c("v-spacer"),
      _vm._v(" "),
      _c(
        "v-btn-toggle",
        {
          attrs: { mandatory: "" },
          model: {
            value: _vm.toggle_exclusive,
            callback: function($$v) {
              _vm.toggle_exclusive = $$v
            },
            expression: "toggle_exclusive"
          }
        },
        [
          _vm.user.can["Order edit"]
            ? _c(
                "v-btn",
                {
                  attrs: { color: "grey", small: "" },
                  on: {
                    click: function($event) {
                      return _vm.openEdit(_vm.saleorder)
                    }
                  }
                },
                [_c("v-icon", [_vm._v("mdi-pencil")])],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _c(
            "v-btn",
            {
              attrs: { color: "grey", small: "" },
              on: { click: _vm.Saleorderprint }
            },
            [_c("v-icon", [_vm._v("mdi-printer")])],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      !_vm.saleorder.confirmed
        ? _c("v-btn", { attrs: { text: "", small: "" } }, [
            _vm._v("Mark as Confirmed")
          ])
        : _vm._e(),
      _vm._v(" "),
      _c(
        "el-dropdown",
        { attrs: { trigger: "click" } },
        [
          _c("span", { staticClass: "el-dropdown-link" }, [
            _vm._v("\r\n            Create"),
            _c("i", { staticClass: "el-icon-arrow-down el-icon--right" })
          ]),
          _vm._v(" "),
          _c(
            "el-dropdown-menu",
            { attrs: { slot: "dropdown" }, slot: "dropdown" },
            [
              _c(
                "el-dropdown-item",
                { attrs: { icon: "el-icon-shopping-bag-1" } },
                [_vm._v("Package")]
              ),
              _vm._v(" "),
              _c(
                "el-dropdown-item",
                { attrs: { icon: "el-icon-circle-check" } },
                [_vm._v("Shipment")]
              ),
              _vm._v(" "),
              !_vm.saleorder.invoiced
                ? _c(
                    "el-dropdown-item",
                    { attrs: { icon: "el-icon-printer" } },
                    [
                      _c(
                        "el-button",
                        { attrs: { type: "text" }, on: { click: _vm.invoice } },
                        [_vm._v("Instant Invoice")]
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _c(
                "el-dropdown-item",
                { attrs: { icon: "el-icon-printer" } },
                [
                  _c(
                    "el-button",
                    { attrs: { type: "text" }, on: { click: _vm.sale_return } },
                    [_vm._v("Sale Return")]
                  )
                ],
                1
              )
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "el-dropdown",
        { attrs: { trigger: "click" } },
        [
          _c("span", { staticClass: "el-dropdown-link" }, [
            _vm._v("\r\n            More"),
            _c("i", { staticClass: "el-icon-arrow-down el-icon--right" })
          ]),
          _vm._v(" "),
          _c(
            "el-dropdown-menu",
            { attrs: { slot: "dropdown" }, slot: "dropdown" },
            [
              _c("el-dropdown-item", { attrs: { icon: "el-icon-plus" } }, [
                _vm._v("Package")
              ]),
              _vm._v(" "),
              _c(
                "el-dropdown-item",
                { attrs: { icon: "el-icon-circle-plus" } },
                [_vm._v("Shipment")]
              ),
              _vm._v(" "),
              _c(
                "el-dropdown-item",
                { attrs: { icon: "el-icon-circle-plus" } },
                [_vm._v("Instant Invoice")]
              )
            ],
            1
          )
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/charges.vue?vue&type=template&id=1879564e&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/charges.vue?vue&type=template&id=1879564e& ***!
  \**************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c(
        "v-container",
        { attrs: { fluid: "", "fill-height": "" } },
        [
          _c(
            "v-layout",
            { attrs: { "justify-center": "", "align-center": "" } },
            [
              _c(
                "v-flex",
                { attrs: { sm12: "" } },
                [
                  _c(
                    "v-card",
                    [
                      _c("h3", { staticClass: "text-center" }, [
                        _vm._v("Charges")
                      ]),
                      _vm._v(" "),
                      _c("v-divider"),
                      _vm._v(" "),
                      _c("v-card-title", [_c("v-spacer")], 1),
                      _vm._v(" "),
                      _c("v-data-table", {
                        attrs: {
                          headers: _vm.headers,
                          items: _vm.saleorder.services,
                          search: _vm.search
                        }
                      })
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/details.vue?vue&type=template&id=3caa595c&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/details.vue?vue&type=template&id=3caa595c&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "el-tabs",
    {
      staticStyle: { width: "90%", margin: "auto" },
      on: { "tab-click": _vm.handleClick },
      model: {
        value: _vm.activeName,
        callback: function($$v) {
          _vm.activeName = $$v
        },
        expression: "activeName"
      }
    },
    [
      _c(
        "el-tab-pane",
        { attrs: { label: "what's Next", name: "next" } },
        [
          _c(
            "v-layout",
            { attrs: { row: "", wrap: "" } },
            [
              _c("v-flex", { attrs: { sm6: "" } }, [
                _c(
                  "div",
                  {
                    staticStyle: {
                      "padding-bottom": "30px",
                      "margin-left": "30px"
                    }
                  },
                  [
                    _c(
                      "v-layout",
                      { attrs: { row: "", wrap: "" } },
                      [
                        _c("v-flex", { attrs: { sm2: "" } }, [
                          _c(
                            "svg",
                            {
                              staticClass: "icon icon-xxxlg",
                              staticStyle: { width: "50px" },
                              attrs: {
                                xmlns: "http://www.w3.org/2000/svg",
                                viewBox: "0 0 499.54 499.54"
                              }
                            },
                            [
                              _c(
                                "g",
                                {
                                  attrs: {
                                    id: "Layer_2",
                                    "data-name": "Layer 2"
                                  }
                                },
                                [
                                  _c(
                                    "g",
                                    {
                                      attrs: {
                                        id: "Layer_1-2",
                                        "data-name": "Layer 1"
                                      }
                                    },
                                    [
                                      _c("circle", {
                                        attrs: {
                                          cx: "249.77",
                                          cy: "249.77",
                                          r: "249.77",
                                          fill: "#ffefdd"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("path", {
                                        attrs: {
                                          fill: "#f9a13c",
                                          d:
                                            "M299.85 371.97l-.54.48-.5.43-2.94 2.61-2.33 2.06-.14.12-.75.67-2.15 1.9-1.3 1.15-.57.5-2.66 2.36-.54.47-.69.61-2.21 1.96-.38.33-1.98 1.75-.13.11-.02.02-.72.64-.63.55-2.82 2.49-2.74 2.43-.09.07-.4.35-.57.51-2.87 2.54-.07.07-1.63 1.43-.94.83-.58.52-1.48 1.31-1.97 1.74-3.23 2.85-.61.55-1.24 1.1-.83.72-.77.68-.1.09-1.51 1.34-1.18 1.03-.43.38-.87.78-.84.74-.66.58-.06.05-.2-.18-7.31-6.19-1.14-.98-1.36-1.15-4.3-3.65-1.37-1.17-2.07-1.76-2.36-2-.98-.84-3.88-3.29-.75-.64-5.42-4.62-1.29-1.08-1.98-1.69-9.46-8.04-4.12-3.5-3.52-3h29.69v-32.98l.01-3.42v-32.71l1.32.63 2.27 1.07.89.41 1.68.79 2.36 1.11 23.93 11.25 2.4 1.13 6.88 3.24v49.36h29.75z"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("path", {
                                        attrs: {
                                          d:
                                            "M134.73 127.66l26.38 26.38m203.71-28.61l-28.61 28.61m-87.2-36.86V83.67",
                                          fill: "none",
                                          stroke: "#f9ac3e",
                                          "stroke-linecap": "round",
                                          "stroke-linejoin": "round",
                                          "stroke-width": "8"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("path", {
                                        attrs: {
                                          d:
                                            "M310.85 175.88a58.46 58.46 0 00-14-19.5 63 63 0 00-16.8-11l-1.62-.71h-.06c-1-.42-2-.82-3-1.2a79 79 0 00-27.66-4.67h-1.82a72.87 72.87 0 00-13.89 1.58 58.52 58.52 0 00-9.76 3c-1.19.47-2.34 1-3.47 1.48a69.28 69.28 0 00-8.57 4.62 62.6 62.6 0 00-7.61 5.66c-.4.34-.79.7-1.18 1a70.75 70.75 0 00-6 6.14 68.52 68.52 0 00-6 8.11l-.6 1c-1.43 2.34-2.61 4.34-3.6 6.24a49.19 49.19 0 00-1.67 3.55 45.19 45.19 0 00-2 5.94l35.45 17.8a40.2 40.2 0 013.92-10 36.21 36.21 0 016.76-8.72 31.13 31.13 0 019.43-6.11 30.33 30.33 0 0111.94-2.27 31.19 31.19 0 019.52 1.36 22.78 22.78 0 019.82 6.06 24.86 24.86 0 016.36 11.19 24.36 24.36 0 01.71 5.89 31.75 31.75 0 01-.16 3.28 25.11 25.11 0 01-3.13 10.3 20.93 20.93 0 01-1.43 2.22c-1 1.33-2.06 2.7-3.27 4.11q-3.19 3.72-7.51 7.83c-.78.74-1.58 1.49-2.42 2.25a193.68 193.68 0 00-3.78 3.36q-4.33 3.94-7.78 7.58c-.74.76-1.44 1.52-2.11 2.27-1.17 1.3-2.25 2.59-3.27 3.89a58.33 58.33 0 00-5.53 8.37l-.14.27a50.33 50.33 0 00-3.24 7.4 49 49 0 00-1.49 5.06c-.41 1.71-.73 3.46-1 5.28a67 67 0 00-.58 8.8v.26L264 302.44l5.72 2.84v-15a51.33 51.33 0 01.94-10.31 34.32 34.32 0 013-8.55 41.21 41.21 0 015.65-8.22q3.62-4.17 9-9.35 5.65-5.46 10.69-11.12a75.65 75.65 0 008.8-12.08 63.25 63.25 0 006-13.86 62.26 62.26 0 00-2.84-41z",
                                          fill: "#fbb124"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("path", {
                                        attrs: {
                                          d:
                                            "M270.1 322.61v8.83l-41.73-19.19v-9.18l4.48 2.02 1.68.79 2.36 1.11 23.93 11.25 2.4 1.13 6.88 3.24zm0 20.27v9.52l-41.73-19.18v-9.19l41.73 18.85zm25.77 32.61l-2.33 2.06-.14.12-.75.67-2.15 1.9-1.3 1.15-.57.5-60.27-27.71V345l41.74 18.85v8.12h17.98l7.79 3.52zm-15.7 13.88l-.13.11-.02.02-.72.64-.63.55-2.82 2.49-2.74 2.43-.09.07-51.29-23.59h6.63v-6.13l51.81 23.41zm-15.69 13.87l-1.97 1.74-3.23 2.85-.61.55-1.24 1.1-32.22-14.82-.75-.64-5.42-4.62-1.29-1.08-1.98-1.69-9.46-8.04-4.12-3.5 62.29 28.15z",
                                          fill: "#f2f3f9"
                                        }
                                      })
                                    ]
                                  )
                                ]
                              )
                            ]
                          )
                        ]),
                        _vm._v(" "),
                        _c("v-flex", { attrs: { sm10: "" } }, [
                          _c("span", [
                            _c("h2", [_vm._v("Send the Sales Order")]),
                            _vm._v(" "),
                            _c("small", [
                              _vm._v(
                                "Sales order has been created. You can email the Sales Order to your customer or mark it as Confirmed."
                              )
                            ])
                          ])
                        ])
                      ],
                      1
                    )
                  ],
                  1
                )
              ]),
              _vm._v(" "),
              !_vm.saleorder.confirmed
                ? _c(
                    "v-flex",
                    { attrs: { sm5: "", "offset-sm1": "" } },
                    [
                      _c(
                        "v-btn",
                        {
                          attrs: { color: "primary", small: "", text: "" },
                          on: { click: _vm.confirm }
                        },
                        [_vm._v("Mark as Confirmed")]
                      ),
                      _vm._v(" "),
                      _c(
                        "v-btn",
                        { attrs: { color: "primary", small: "", text: "" } },
                        [_vm._v("Send Sale Order")]
                      )
                    ],
                    1
                  )
                : _vm._e()
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "el-tab-pane",
        { attrs: { label: "Comment&History", name: "comments" } },
        [
          _c(
            "el-collapse",
            { attrs: { accordion: "" } },
            [
              _c(
                "el-collapse-item",
                { attrs: { name: "1" } },
                [
                  _c("template", { slot: "title" }, [
                    _vm._v("\r\n                    Comments "),
                    _c("i", { staticClass: "header-icon el-icon-info" })
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    [
                      _c(
                        "el-timeline",
                        _vm._l(_vm.saleorder.order_history, function(
                          item,
                          index
                        ) {
                          return _c(
                            "el-timeline-item",
                            {
                              key: index,
                              attrs: {
                                icon: "el-icon-comment",
                                type: item.type,
                                color: item.color,
                                size: item.size,
                                timestamp: item.timestamp
                              }
                            },
                            [
                              _c("p", {
                                domProps: {
                                  innerHTML: _vm._s(
                                    item.show_comment + " | " + item.created_at
                                  )
                                }
                              }),
                              _vm._v(" "),
                              item.comment
                                ? _c("small", [
                                    _vm._v("Comment: "),
                                    _c("b", [_vm._v(_vm._s(item.comment))])
                                  ])
                                : _vm._e(),
                              _vm._v(" "),
                              item.updated_fields["original"]
                                ? _c(
                                    "el-collapse",
                                    { attrs: { accordion: "" } },
                                    [
                                      _c(
                                        "el-collapse-item",
                                        { attrs: { name: "1" } },
                                        [
                                          _c("template", { slot: "title" }, [
                                            _vm._v(
                                              "\r\n                                        Updated fields"
                                            ),
                                            _c("i", {
                                              staticClass:
                                                "header-icon el-icon-s-operation"
                                            })
                                          ]),
                                          _vm._v(" "),
                                          _c(
                                            "v-layout",
                                            {
                                              staticStyle: {
                                                "padding-top": "20px"
                                              },
                                              attrs: { row: "", wrap: "" }
                                            },
                                            [
                                              _c(
                                                "v-flex",
                                                {
                                                  staticStyle: {},
                                                  attrs: { sm6: "" }
                                                },
                                                [
                                                  _c("b", [_vm._v("Original")]),
                                                  _vm._v(" "),
                                                  _c("v-divider"),
                                                  _vm._v(" "),
                                                  _vm._l(
                                                    item.updated_fields[
                                                      "original"
                                                    ],
                                                    function(update, o_key) {
                                                      return _c(
                                                        "v-list-item",
                                                        {
                                                          key: o_key,
                                                          attrs: {
                                                            "two-line": ""
                                                          }
                                                        },
                                                        [
                                                          _c(
                                                            "v-list-item-content",
                                                            [
                                                              _c(
                                                                "v-list-item-subtitle",
                                                                [
                                                                  _vm._v(
                                                                    " " +
                                                                      _vm._s(
                                                                        o_key
                                                                      ) +
                                                                      ": "
                                                                  ),
                                                                  _c("b", [
                                                                    _vm._v(
                                                                      _vm._s(
                                                                        update
                                                                      )
                                                                    )
                                                                  ])
                                                                ]
                                                              )
                                                            ],
                                                            1
                                                          )
                                                        ],
                                                        1
                                                      )
                                                    }
                                                  )
                                                ],
                                                2
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "v-flex",
                                                { attrs: { sm6: "" } },
                                                [
                                                  _c("b", [
                                                    _vm._v("Updated to")
                                                  ]),
                                                  _vm._v(" "),
                                                  _c("v-divider"),
                                                  _vm._v(" "),
                                                  _vm._l(
                                                    item.updated_fields[
                                                      "updated"
                                                    ],
                                                    function(update, u_key) {
                                                      return _c(
                                                        "v-list-item",
                                                        {
                                                          key: u_key,
                                                          attrs: {
                                                            "two-line": ""
                                                          }
                                                        },
                                                        [
                                                          _c(
                                                            "v-list-item-content",
                                                            [
                                                              _c(
                                                                "v-list-item-subtitle",
                                                                [
                                                                  _vm._v(
                                                                    " " +
                                                                      _vm._s(
                                                                        u_key
                                                                      ) +
                                                                      ": "
                                                                  ),
                                                                  _c("b", [
                                                                    _vm._v(
                                                                      _vm._s(
                                                                        update
                                                                      )
                                                                    )
                                                                  ])
                                                                ]
                                                              )
                                                            ],
                                                            1
                                                          )
                                                        ],
                                                        1
                                                      )
                                                    }
                                                  )
                                                ],
                                                2
                                              )
                                            ],
                                            1
                                          )
                                        ],
                                        2
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e()
                            ],
                            1
                          )
                        }),
                        1
                      )
                    ],
                    1
                  )
                ],
                2
              )
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "el-dialog",
            {
              attrs: { title: "Comment", visible: _vm.dialog, width: "30%" },
              on: {
                "update:visible": function($event) {
                  _vm.dialog = $event
                }
              }
            },
            [
              _c("el-input", {
                attrs: { placeholder: "Please input" },
                model: {
                  value: _vm.form.comment,
                  callback: function($$v) {
                    _vm.$set(_vm.form, "comment", $$v)
                  },
                  expression: "form.comment"
                }
              }),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticClass: "dialog-footer",
                  attrs: { slot: "footer" },
                  slot: "footer"
                },
                [
                  _c(
                    "v-btn",
                    {
                      staticStyle: { "text-transform": "none" },
                      attrs: { color: "primary", text: "" },
                      on: { click: _vm.addComment }
                    },
                    [
                      _c("v-icon", [_vm._v("mdi-comment")]),
                      _vm._v(" "),
                      _c("span", [_vm._v("Comment")])
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "v-btn",
            {
              staticStyle: { "text-transform": "none" },
              attrs: { small: "", color: "primary", rounded: "" },
              on: {
                click: function($event) {
                  _vm.dialog = true
                }
              }
            },
            [
              _c("v-icon", [_vm._v("mdi-comment")]),
              _vm._v(" "),
              _c("span", [_vm._v("Comment")])
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "el-tab-pane",
        { attrs: { label: "Invoice", name: "invoice" } },
        [_c("myInvoice")],
        1
      ),
      _vm._v(" "),
      _c(
        "el-tab-pane",
        { attrs: { label: "Charges", name: "charges" } },
        [_c("myCharges")],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/invoice.vue?vue&type=template&id=fb218672&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/saleorder/invoice.vue?vue&type=template&id=fb218672& ***!
  \**************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _vm.saleorder.invoiced
      ? _c("table", { staticClass: "table table-striped table-bordered" }, [
          _c("tbody", [
            _c("tr", { staticClass: "item" }, [
              _c("td", { staticStyle: { "text-align": "center" } }, [
                _c("p", [_vm._v(_vm._s(_vm.saleorder.invoice.invoice_no))]),
                _vm._v(" "),
                _c("p", [_vm._v(_vm._s(_vm.saleorder.invoice.created_at))])
              ]),
              _vm._v(" "),
              _vm._m(0),
              _vm._v(" "),
              _c("td", { staticStyle: { "text-align": "center" } }, [
                _c("p", [_vm._v("Balance")]),
                _vm._v(" "),
                _c("p", [
                  _vm._v(
                    "KES " +
                      _vm._s(_vm.saleorder.invoice.balance) +
                      " (Due On " +
                      _vm._s(_vm.saleorder.invoice.due_date) +
                      ")"
                  )
                ])
              ]),
              _vm._v(" "),
              _c("td", { staticStyle: { "text-align": "center" } }, [
                _c("p", [
                  _vm._v("KES " + _vm._s(_vm.saleorder.invoice.total) + " ")
                ])
              ])
            ])
          ])
        ])
      : _c("div", { staticStyle: { "text-align": "center" } }, [
          _c("p", [_vm._v("No Invoices created so far ")]),
          _vm._v(" "),
          _c(
            "span",
            [
              _c(
                "v-btn",
                { attrs: { text: "", small: "", color: "primary" } },
                [_vm._v("Convert to Invoice")]
              ),
              _vm._v(" "),
              _c(
                "v-btn",
                {
                  attrs: { text: "", small: "", color: "primary" },
                  on: { click: _vm.invoice }
                },
                [_vm._v("Instant Invoice")]
              )
            ],
            1
          )
        ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("td", { staticStyle: { "text-align": "center" } }, [
      _c("p", [_vm._v("Draft")])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/printing/invoice/Invoice.vue":
/*!**************************************************************!*\
  !*** ./resources/js/components/printing/invoice/Invoice.vue ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Invoice_vue_vue_type_template_id_61ba94fc_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Invoice.vue?vue&type=template&id=61ba94fc&scoped=true& */ "./resources/js/components/printing/invoice/Invoice.vue?vue&type=template&id=61ba94fc&scoped=true&");
/* harmony import */ var _Invoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Invoice.vue?vue&type=script&lang=js& */ "./resources/js/components/printing/invoice/Invoice.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Invoice_vue_vue_type_style_index_0_id_61ba94fc_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css& */ "./resources/js/components/printing/invoice/Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Invoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Invoice_vue_vue_type_template_id_61ba94fc_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Invoice_vue_vue_type_template_id_61ba94fc_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "61ba94fc",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/printing/invoice/Invoice.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/printing/invoice/Invoice.vue?vue&type=script&lang=js&":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/printing/invoice/Invoice.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Invoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Invoice.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/printing/invoice/Invoice.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Invoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/printing/invoice/Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css&":
/*!***********************************************************************************************************************!*\
  !*** ./resources/js/components/printing/invoice/Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css& ***!
  \***********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Invoice_vue_vue_type_style_index_0_id_61ba94fc_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/printing/invoice/Invoice.vue?vue&type=style&index=0&id=61ba94fc&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Invoice_vue_vue_type_style_index_0_id_61ba94fc_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Invoice_vue_vue_type_style_index_0_id_61ba94fc_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Invoice_vue_vue_type_style_index_0_id_61ba94fc_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Invoice_vue_vue_type_style_index_0_id_61ba94fc_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/components/printing/invoice/Invoice.vue?vue&type=template&id=61ba94fc&scoped=true&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/components/printing/invoice/Invoice.vue?vue&type=template&id=61ba94fc&scoped=true& ***!
  \*********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Invoice_vue_vue_type_template_id_61ba94fc_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Invoice.vue?vue&type=template&id=61ba94fc&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/printing/invoice/Invoice.vue?vue&type=template&id=61ba94fc&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Invoice_vue_vue_type_template_id_61ba94fc_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Invoice_vue_vue_type_template_id_61ba94fc_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/printing/invoice/image.js":
/*!***********************************************************!*\
  !*** ./resources/js/components/printing/invoice/image.js ***!
  \***********************************************************/
/*! exports provided: image */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "image", function() { return image; });
var image = 'data:image/jpeg;base64,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';

/***/ }),

/***/ "./resources/js/components/sales/saleorder/Tab_header.vue":
/*!****************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/Tab_header.vue ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Tab_header_vue_vue_type_template_id_3a57c626_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Tab_header.vue?vue&type=template&id=3a57c626&scoped=true& */ "./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=template&id=3a57c626&scoped=true&");
/* harmony import */ var _Tab_header_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Tab_header.vue?vue&type=script&lang=js& */ "./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Tab_header_vue_vue_type_style_index_0_id_3a57c626_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css& */ "./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Tab_header_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Tab_header_vue_vue_type_template_id_3a57c626_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Tab_header_vue_vue_type_template_id_3a57c626_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "3a57c626",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/sales/saleorder/Tab_header.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Tab_header_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Tab_header.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Tab_header_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css&":
/*!*************************************************************************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css& ***!
  \*************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Tab_header_vue_vue_type_style_index_0_id_3a57c626_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=style&index=0&id=3a57c626&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Tab_header_vue_vue_type_style_index_0_id_3a57c626_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Tab_header_vue_vue_type_style_index_0_id_3a57c626_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Tab_header_vue_vue_type_style_index_0_id_3a57c626_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Tab_header_vue_vue_type_style_index_0_id_3a57c626_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=template&id=3a57c626&scoped=true&":
/*!***********************************************************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=template&id=3a57c626&scoped=true& ***!
  \***********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Tab_header_vue_vue_type_template_id_3a57c626_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Tab_header.vue?vue&type=template&id=3a57c626&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/Tab_header.vue?vue&type=template&id=3a57c626&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Tab_header_vue_vue_type_template_id_3a57c626_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Tab_header_vue_vue_type_template_id_3a57c626_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/sales/saleorder/charges.vue":
/*!*************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/charges.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _charges_vue_vue_type_template_id_1879564e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./charges.vue?vue&type=template&id=1879564e& */ "./resources/js/components/sales/saleorder/charges.vue?vue&type=template&id=1879564e&");
/* harmony import */ var _charges_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./charges.vue?vue&type=script&lang=js& */ "./resources/js/components/sales/saleorder/charges.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _charges_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _charges_vue_vue_type_template_id_1879564e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _charges_vue_vue_type_template_id_1879564e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/sales/saleorder/charges.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/sales/saleorder/charges.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/charges.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_charges_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./charges.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/charges.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_charges_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/sales/saleorder/charges.vue?vue&type=template&id=1879564e&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/charges.vue?vue&type=template&id=1879564e& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_charges_vue_vue_type_template_id_1879564e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./charges.vue?vue&type=template&id=1879564e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/charges.vue?vue&type=template&id=1879564e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_charges_vue_vue_type_template_id_1879564e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_charges_vue_vue_type_template_id_1879564e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/sales/saleorder/details.vue":
/*!*************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/details.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _details_vue_vue_type_template_id_3caa595c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./details.vue?vue&type=template&id=3caa595c&scoped=true& */ "./resources/js/components/sales/saleorder/details.vue?vue&type=template&id=3caa595c&scoped=true&");
/* harmony import */ var _details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./details.vue?vue&type=script&lang=js& */ "./resources/js/components/sales/saleorder/details.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _details_vue_vue_type_style_index_0_id_3caa595c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css& */ "./resources/js/components/sales/saleorder/details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _details_vue_vue_type_template_id_3caa595c_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _details_vue_vue_type_template_id_3caa595c_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "3caa595c",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/sales/saleorder/details.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/sales/saleorder/details.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/details.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./details.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/details.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/sales/saleorder/details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css&":
/*!**********************************************************************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css& ***!
  \**********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_style_index_0_id_3caa595c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/details.vue?vue&type=style&index=0&id=3caa595c&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_style_index_0_id_3caa595c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_style_index_0_id_3caa595c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_style_index_0_id_3caa595c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_style_index_0_id_3caa595c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/components/sales/saleorder/details.vue?vue&type=template&id=3caa595c&scoped=true&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/details.vue?vue&type=template&id=3caa595c&scoped=true& ***!
  \********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_template_id_3caa595c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./details.vue?vue&type=template&id=3caa595c&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/details.vue?vue&type=template&id=3caa595c&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_template_id_3caa595c_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_template_id_3caa595c_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/sales/saleorder/invoice.vue":
/*!*************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/invoice.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _invoice_vue_vue_type_template_id_fb218672___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./invoice.vue?vue&type=template&id=fb218672& */ "./resources/js/components/sales/saleorder/invoice.vue?vue&type=template&id=fb218672&");
/* harmony import */ var _invoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./invoice.vue?vue&type=script&lang=js& */ "./resources/js/components/sales/saleorder/invoice.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _invoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _invoice_vue_vue_type_template_id_fb218672___WEBPACK_IMPORTED_MODULE_0__["render"],
  _invoice_vue_vue_type_template_id_fb218672___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/sales/saleorder/invoice.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/sales/saleorder/invoice.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/invoice.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_invoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./invoice.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/invoice.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_invoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/sales/saleorder/invoice.vue?vue&type=template&id=fb218672&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/sales/saleorder/invoice.vue?vue&type=template&id=fb218672& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_invoice_vue_vue_type_template_id_fb218672___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./invoice.vue?vue&type=template&id=fb218672& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/saleorder/invoice.vue?vue&type=template&id=fb218672&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_invoice_vue_vue_type_template_id_fb218672___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_invoice_vue_vue_type_template_id_fb218672___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);