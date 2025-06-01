(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/lux/pos~js/lux/receipt"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pos/inc/Receipt.vue?vue&type=script&lang=js":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pos/inc/Receipt.vue?vue&type=script&lang=js ***!
  \*************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _toConsumableArray(r) { return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _iterableToArray(r) { if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r); }
function _arrayWithoutHoles(r) { if (Array.isArray(r)) return _arrayLikeToArray(r); }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['cart'],
  data: function data() {
    return {
      logo: "https://jimkiarie8.nyc3.digitaloceanspaces.com/swap/site/logo.jpg",
      date: new Date().toLocaleString(),
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
      }]
    };
  },
  created: function created() {
    eventBus.$on('printReceiptEvent', function (data) {});
  },
  computed: {
    total: function total() {
      return this.cart.cart.reduce(function (acc, item) {
        return acc + item.price * item.ordered;
      }, 0);
    }
  },
  filters: {
    currency: function currency(value) {
      return value.toFixed(2);
    }
  },
  methods: {
    addRow: function addRow() {
      this.items.push({
        description: "",
        quantity: 1,
        price: 0
      });
    },
    printPage: function printPage() {
      // Get HTML to print from element
      var prtHtml = document.getElementById('print').innerHTML;

      // Get all stylesheets HTML
      var stylesHtml = '';
      for (var _i = 0, _arr = _toConsumableArray(document.querySelectorAll('link[rel="stylesheet"], style')); _i < _arr.length; _i++) {
        var node = _arr[_i];
        stylesHtml += node.outerHTML;
      }

      // Open the print window
      var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
      WinPrint.document.write("<!DOCTYPE html>\n            <html>\n            <head>\n                ".concat(stylesHtml, "\n            </head>\n            <body>\n                ").concat(prtHtml, "\n            </body>\n            </html>"));
      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      WinPrint.close();
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pos/inc/Receipt.vue?vue&type=template&id=a8523f9e":
/*!***********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib/loaders/templateLoader.js??ref--6!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pos/inc/Receipt.vue?vue&type=template&id=a8523f9e ***!
  \***********************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "invoice-box"
  }, [_c("table", {
    attrs: {
      cellpadding: "0",
      cellspacing: "0"
    }
  }, [_c("tr", {
    staticClass: "top"
  }, [_c("td", {
    attrs: {
      colspan: "4"
    }
  }, [_c("table", {
    staticClass: "table table-hover table-striped"
  }, [_c("tr", [_c("td", {
    staticClass: "title"
  }, [_c("img", {
    staticStyle: {
      width: "100%",
      "max-width": "250px"
    },
    attrs: {
      src: _vm.logo
    }
  })]), _vm._v(" "), _c("td", [_vm._v("\n                            Receipt #: 123"), _c("br"), _vm._v(" Created: " + _vm._s(_vm.date)), _c("br")])])])])]), _vm._v(" "), _vm._m(0), _vm._v(" "), _vm._m(1), _vm._v(" "), _vm._l(_vm.cart.cart, function (item, index) {
    return _c("tr", {
      key: index,
      staticClass: "item"
    }, [_c("td", [_vm._v(_vm._s(item.product_name))]), _vm._v(" "), _c("td", [_vm._v("KES " + _vm._s(item.price))]), _vm._v(" "), _c("td", [_vm._v(_vm._s(item.ordered))]), _vm._v(" "), _c("td", [_vm._v("KES " + _vm._s(_vm._f("currency")(item.price * item.ordered)))])]);
  }), _vm._v(" "), _c("tr", {
    staticClass: "total"
  }, [_c("td", {
    attrs: {
      colspan: "3"
    }
  }), _vm._v(" "), _c("td", [_vm._v("Total: KES " + _vm._s(_vm._f("currency")(_vm.total)))])])], 2)]);
};
var staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("tr", {
    staticClass: "information"
  }, [_c("td", {
    attrs: {
      colspan: "4"
    }
  }, [_c("table", {
    staticClass: "table table-hover table-striped"
  }, [_c("tr", [_c("td", [_vm._v("\n                            Sparksuite, Inc."), _c("br"), _vm._v(" 12345 Sunny Road"), _c("br"), _vm._v(" Sunnyville, CA 12345\n                        ")]), _vm._v(" "), _c("td", [_vm._v("\n                            Acme Corp."), _c("br"), _vm._v(" John Doe"), _c("br"), _vm._v(" john@example.com\n                        ")])])])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("tr", {
    staticClass: "heading"
  }, [_c("td", [_vm._v("Item")]), _vm._v(" "), _c("td", [_vm._v("Unit Cost")]), _vm._v(" "), _c("td", [_vm._v("Quantity")]), _vm._v(" "), _c("td", [_vm._v("Price")])]);
}];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pos/inc/Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css":
/*!********************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pos/inc/Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.invoice-box {\n    max-width: 800px;\n    margin: auto;\n    padding: 30px;\n    border: 1px solid #eee;\n    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);\n    font-size: 16px;\n    line-height: 24px;\n    font-family: \"Helvetica Neue\", \"Helvetica\", Helvetica, Arial, sans-serif;\n    color: #555;\n}\n.invoice-box table {\n    width: 100%;\n    line-height: inherit;\n    text-align: left;\n}\n.invoice-box table td {\n    padding: 5px;\n    vertical-align: top;\n}\n.invoice-box table tr td:nth-child(n + 2) {\n    text-align: right;\n}\n.invoice-box table tr.top table td {\n    padding-bottom: 20px;\n}\n.invoice-box table tr.top table td.title {\n    font-size: 45px;\n    line-height: 45px;\n    color: #333;\n}\n.invoice-box table tr.information table td {\n    padding-bottom: 40px;\n}\n.invoice-box table tr.heading td {\n    background: #eee;\n    border-bottom: 1px solid #ddd;\n    font-weight: bold;\n}\n.invoice-box table tr.details td {\n    padding-bottom: 20px;\n}\n.invoice-box table tr.item td {\n    border-bottom: 1px solid #eee;\n}\n.invoice-box table tr.item.last td {\n    border-bottom: none;\n}\n.invoice-box table tr.item input {\n    padding-left: 5px;\n}\n.invoice-box table tr.item td:first-child input {\n    margin-left: -5px;\n    width: 100%;\n}\n.invoice-box table tr.total td:nth-child(2) {\n    border-top: 2px solid #eee;\n    font-weight: bold;\n}\n.invoice-box input[type=\"number\"] {\n    width: 60px;\n}\n@media only screen and (max-width: 600px) {\n.invoice-box table tr.top table td {\n        width: 100%;\n        display: block;\n        text-align: center;\n}\n.invoice-box table tr.information table td {\n        width: 100%;\n        display: block;\n        text-align: center;\n}\n}\n\n/** RTL **/\n.rtl {\n    direction: rtl;\n    font-family: Tahoma, \"Helvetica Neue\", \"Helvetica\", Helvetica, Arial,\n        sans-serif;\n}\n.rtl table {\n    text-align: right;\n}\n.rtl table tr td:nth-child(2) {\n    text-align: left;\n}\n.table {\n    width: 100%;\n    margin-bottom: 1rem;\n    color: #212529;\n}\n.table th,\n.table td {\n    padding: 0.75rem;\n    vertical-align: top;\n    border-top: 1px solid #dee2e6;\n}\n.table thead th {\n    vertical-align: bottom;\n    border-bottom: 2px solid #dee2e6;\n}\n.table tbody+tbody {\n    border-top: 2px solid #dee2e6;\n}\n.table-sm th,\n.table-sm td {\n    padding: 0.3rem;\n}\n.table-bordered {\n    border: 1px solid #dee2e6;\n}\n.table-bordered th,\n.table-bordered td {\n    border: 1px solid #dee2e6;\n}\n.table-bordered thead th,\n.table-bordered thead td {\n    border-bottom-width: 2px;\n}\n.table-borderless th,\n.table-borderless td,\n.table-borderless thead th,\n.table-borderless tbody+tbody {\n    border: 0;\n}\n.table-striped tbody tr:nth-of-type(odd) {\n    background-color: rgba(0, 0, 0, 0.05);\n}\n.table-hover tbody tr:hover {\n    color: #212529;\n    background-color: rgba(0, 0, 0, 0.075);\n}\n.table-primary,\n.table-primary>th,\n.table-primary>td {\n    background-color: #b8daff;\n}\n.table-primary th,\n.table-primary td,\n.table-primary thead th,\n.table-primary tbody+tbody {\n    border-color: #7abaff;\n}\n.table-hover .table-primary:hover {\n    background-color: #9fcdff;\n}\n.table-hover .table-primary:hover>td,\n.table-hover .table-primary:hover>th {\n    background-color: #9fcdff;\n}\n.table-secondary,\n.table-secondary>th,\n.table-secondary>td {\n    background-color: #d6d8db;\n}\n.table-secondary th,\n.table-secondary td,\n.table-secondary thead th,\n.table-secondary tbody+tbody {\n    border-color: #b3b7bb;\n}\n.table-hover .table-secondary:hover {\n    background-color: #c8cbcf;\n}\n.table-hover .table-secondary:hover>td,\n.table-hover .table-secondary:hover>th {\n    background-color: #c8cbcf;\n}\n.table-success,\n.table-success>th,\n.table-success>td {\n    background-color: #c3e6cb;\n}\n.table-success th,\n.table-success td,\n.table-success thead th,\n.table-success tbody+tbody {\n    border-color: #8fd19e;\n}\n.table-hover .table-success:hover {\n    background-color: #b1dfbb;\n}\n.table-hover .table-success:hover>td,\n.table-hover .table-success:hover>th {\n    background-color: #b1dfbb;\n}\n.table-info,\n.table-info>th,\n.table-info>td {\n    background-color: #bee5eb;\n}\n.table-info th,\n.table-info td,\n.table-info thead th,\n.table-info tbody+tbody {\n    border-color: #86cfda;\n}\n.table-hover .table-info:hover {\n    background-color: #abdde5;\n}\n.table-hover .table-info:hover>td,\n.table-hover .table-info:hover>th {\n    background-color: #abdde5;\n}\n.table-warning,\n.table-warning>th,\n.table-warning>td {\n    background-color: #ffeeba;\n}\n.table-warning th,\n.table-warning td,\n.table-warning thead th,\n.table-warning tbody+tbody {\n    border-color: #ffdf7e;\n}\n.table-hover .table-warning:hover {\n    background-color: #ffe8a1;\n}\n.table-hover .table-warning:hover>td,\n.table-hover .table-warning:hover>th {\n    background-color: #ffe8a1;\n}\n.table-danger,\n.table-danger>th,\n.table-danger>td {\n    background-color: #f5c6cb;\n}\n.table-danger th,\n.table-danger td,\n.table-danger thead th,\n.table-danger tbody+tbody {\n    border-color: #ed969e;\n}\n.table-hover .table-danger:hover {\n    background-color: #f1b0b7;\n}\n.table-hover .table-danger:hover>td,\n.table-hover .table-danger:hover>th {\n    background-color: #f1b0b7;\n}\n.table-light,\n.table-light>th,\n.table-light>td {\n    background-color: #fdfdfe;\n}\n.table-light th,\n.table-light td,\n.table-light thead th,\n.table-light tbody+tbody {\n    border-color: #fbfcfc;\n}\n.table-hover .table-light:hover {\n    background-color: #ececf6;\n}\n.table-hover .table-light:hover>td,\n.table-hover .table-light:hover>th {\n    background-color: #ececf6;\n}\n.table-dark,\n.table-dark>th,\n.table-dark>td {\n    background-color: #c6c8ca;\n}\n.table-dark th,\n.table-dark td,\n.table-dark thead th,\n.table-dark tbody+tbody {\n    border-color: #95999c;\n}\n.table-hover .table-dark:hover {\n    background-color: #b9bbbe;\n}\n.table-hover .table-dark:hover>td,\n.table-hover .table-dark:hover>th {\n    background-color: #b9bbbe;\n}\n.table-active,\n.table-active>th,\n.table-active>td {\n    background-color: rgba(0, 0, 0, 0.075);\n}\n.table-hover .table-active:hover {\n    background-color: rgba(0, 0, 0, 0.075);\n}\n.table-hover .table-active:hover>td,\n.table-hover .table-active:hover>th {\n    background-color: rgba(0, 0, 0, 0.075);\n}\n.table .thead-dark th {\n    color: #fff;\n    background-color: #343a40;\n    border-color: #454d55;\n}\n.table .thead-light th {\n    color: #495057;\n    background-color: #e9ecef;\n    border-color: #dee2e6;\n}\n.table-dark {\n    color: #fff;\n    background-color: #343a40;\n}\n.table-dark th,\n.table-dark td,\n.table-dark thead th {\n    border-color: #454d55;\n}\n.table-dark.table-bordered {\n    border: 0;\n}\n.table-dark.table-striped tbody tr:nth-of-type(odd) {\n    background-color: rgba(255, 255, 255, 0.05);\n}\n.table-dark.table-hover tbody tr:hover {\n    color: #fff;\n    background-color: rgba(255, 255, 255, 0.075);\n}\n@media (max-width: 575.98px) {\n.table-responsive-sm {\n        display: block;\n        width: 100%;\n        overflow-x: auto;\n        -webkit-overflow-scrolling: touch;\n}\n.table-responsive-sm>.table-bordered {\n        border: 0;\n}\n}\n@media (max-width: 767.98px) {\n.table-responsive-md {\n        display: block;\n        width: 100%;\n        overflow-x: auto;\n        -webkit-overflow-scrolling: touch;\n}\n.table-responsive-md>.table-bordered {\n        border: 0;\n}\n}\n@media (max-width: 991.98px) {\n.table-responsive-lg {\n        display: block;\n        width: 100%;\n        overflow-x: auto;\n        -webkit-overflow-scrolling: touch;\n}\n.table-responsive-lg>.table-bordered {\n        border: 0;\n}\n}\n@media (max-width: 1199.98px) {\n.table-responsive-xl {\n        display: block;\n        width: 100%;\n        overflow-x: auto;\n        -webkit-overflow-scrolling: touch;\n}\n.table-responsive-xl>.table-bordered {\n        border: 0;\n}\n}\n.table-responsive {\n    display: block;\n    width: 100%;\n    overflow-x: auto;\n    -webkit-overflow-scrolling: touch;\n}\n.table-responsive>.table-bordered {\n    border: 0;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pos/inc/Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pos/inc/Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pos/inc/Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css");

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

/***/ "./resources/js/components/pos/inc/Receipt.vue":
/*!*****************************************************!*\
  !*** ./resources/js/components/pos/inc/Receipt.vue ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Receipt_vue_vue_type_template_id_a8523f9e__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Receipt.vue?vue&type=template&id=a8523f9e */ "./resources/js/components/pos/inc/Receipt.vue?vue&type=template&id=a8523f9e");
/* harmony import */ var _Receipt_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Receipt.vue?vue&type=script&lang=js */ "./resources/js/components/pos/inc/Receipt.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _Receipt_vue_vue_type_style_index_0_id_a8523f9e_lang_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css */ "./resources/js/components/pos/inc/Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Receipt_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _Receipt_vue_vue_type_template_id_a8523f9e__WEBPACK_IMPORTED_MODULE_0__["render"],
  _Receipt_vue_vue_type_template_id_a8523f9e__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/pos/inc/Receipt.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/pos/inc/Receipt.vue?vue&type=script&lang=js":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/pos/inc/Receipt.vue?vue&type=script&lang=js ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Receipt_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Receipt.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pos/inc/Receipt.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Receipt_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/pos/inc/Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/pos/inc/Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css ***!
  \*************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Receipt_vue_vue_type_style_index_0_id_a8523f9e_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pos/inc/Receipt.vue?vue&type=style&index=0&id=a8523f9e&lang=css");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Receipt_vue_vue_type_style_index_0_id_a8523f9e_lang_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Receipt_vue_vue_type_style_index_0_id_a8523f9e_lang_css__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Receipt_vue_vue_type_style_index_0_id_a8523f9e_lang_css__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Receipt_vue_vue_type_style_index_0_id_a8523f9e_lang_css__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/components/pos/inc/Receipt.vue?vue&type=template&id=a8523f9e":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/pos/inc/Receipt.vue?vue&type=template&id=a8523f9e ***!
  \***********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_Receipt_vue_vue_type_template_id_a8523f9e__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ref--6!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Receipt.vue?vue&type=template&id=a8523f9e */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pos/inc/Receipt.vue?vue&type=template&id=a8523f9e");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_Receipt_vue_vue_type_template_id_a8523f9e__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_Receipt_vue_vue_type_template_id_a8523f9e__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);