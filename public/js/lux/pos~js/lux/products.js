(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/lux/pos~js/lux/products"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/create.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/create.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
/* harmony import */ var _users_sellers_create__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../users/sellers/create */ "./resources/js/components/users/sellers/create.vue");
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


/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    myVendor: _users_sellers_create__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  data: function data() {
    return {
      dialog: false,
      form: {
        description: '',
        name: '',
        pos_status: '',
        price: '',
        product_barcode: '',
        quantity: '',
        sku: '',
        tax_category_id: '',
        tax_percent: '',
        type: '',
        weight: ''
      }
    };
  },
  created: function created() {
    var _this = this;

    eventBus.$on("openCreateProduct", function (data) {
      _this.dialog = true;

      _this.getSellers();
    });
    eventBus.$on("sellerEvent", function (data) {
      _this.getSellers();
    });
  },
  methods: {
    save: function save() {
      var payload = {
        model: 'products_store',
        data: this.form
      };
      this.$store.dispatch('postItems', payload).then(function (response) {
        eventBus.$emit("productEvent");
      });
      /*
                  console.log('ddddddddd');
                  eventBus.$emit("LoadingEvent");
                  this.loading = true;
                  axios
                      .post("products", this.form)
                      .then(response => {
                          this.loading = false;
                          eventBus.$emit('productEvent')
                          this.$store.dispatch('alertEvent', 'Client added')
                      })
                      .catch(error => {
                          this.loading = false;
                          if (error.response.status === 500) {
                              eventBus.$emit('errorEvent', error.response.statusText)
                              return
                          } else if (error.response.status === 401 || error.response.status === 409) {
                              eventBus.$emit('reloadRequest', error.response.statusText)
                          } else if (error.response.status === 422) {
                              eventBus.$emit('errorEvent', error.response.data.message + ': ' + error.response.statusText)
                              return
                          }
                      });
                      */
    },
    close: function close() {
      this.dialog = false;
    },
    getSellers: function getSellers() {
      var payload = {
        model: "/seller/sellers",
        update: "updateSellerList"
      };
      this.$store.dispatch("getItems", payload);
    },
    searchSellers: function searchSellers(search) {
      if (search.length > 2) {
        var payload = {
          model: "/seller/seller_search",
          update: "updateSellerList",
          search: search
        };
        this.$store.dispatch("searchItems", payload);
      }
    },
    add_new: function add_new(item) {
      eventBus.$emit('openCreateSeller');
    }
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapState"])(['sellers', 'loading']))
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/create.vue?vue&type=template&id=276a5b27&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/create.vue?vue&type=template&id=276a5b27& ***!
  \*****************************************************************************************************************************************************************************************************************/
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
    "v-layout",
    { attrs: { row: "", "justify-center": "" } },
    [
      _c(
        "v-dialog",
        {
          attrs: { persistent: "", "max-width": "500px" },
          model: {
            value: _vm.dialog,
            callback: function($$v) {
              _vm.dialog = $$v
            },
            expression: "dialog"
          }
        },
        [
          _c(
            "v-card",
            [
              _c("v-card-title", [
                _c(
                  "span",
                  {
                    staticClass: "headline text-center",
                    staticStyle: { margin: "auto" }
                  },
                  [_vm._v("Create Product")]
                )
              ]),
              _vm._v(" "),
              _c("v-divider"),
              _vm._v(" "),
              _c(
                "v-card-text",
                [
                  _c(
                    "v-container",
                    { attrs: { "grid-list-md": "" } },
                    [
                      _c(
                        "v-layout",
                        { attrs: { row: "", wrap: "" } },
                        [
                          _c("v-flex", { attrs: { sm12: "" } }, [
                            _c(
                              "div",
                              [
                                _c("label", { attrs: { for: "" } }, [
                                  _vm._v("Product name")
                                ]),
                                _vm._v(" "),
                                _c("el-input", {
                                  attrs: { placeholder: "Product name" },
                                  model: {
                                    value: _vm.form.product_name,
                                    callback: function($$v) {
                                      _vm.$set(_vm.form, "product_name", $$v)
                                    },
                                    expression: "form.product_name"
                                  }
                                })
                              ],
                              1
                            )
                          ]),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm12: "" } },
                            [
                              _c(
                                "label",
                                {
                                  staticStyle: { color: "#52627d" },
                                  attrs: { for: "" }
                                },
                                [_vm._v("Vendor Name*")]
                              ),
                              _vm._v(" "),
                              _c(
                                "el-select",
                                {
                                  staticStyle: { width: "100%" },
                                  attrs: {
                                    filterable: "",
                                    remote: "",
                                    "reserve-keyword": "",
                                    placeholder: "type at least 3 characters",
                                    "remote-method": _vm.searchSellers,
                                    loading: _vm.loading
                                  },
                                  model: {
                                    value: _vm.form.vendor_id,
                                    callback: function($$v) {
                                      _vm.$set(_vm.form, "vendor_id", $$v)
                                    },
                                    expression: "form.vendor_id"
                                  }
                                },
                                [
                                  _vm._l(_vm.sellers.data, function(item) {
                                    return _c("el-option", {
                                      key: item.id,
                                      attrs: {
                                        label: item.name,
                                        value: item.id
                                      }
                                    })
                                  }),
                                  _vm._v(" "),
                                  _c(
                                    "div",
                                    { attrs: { slot: "empty" }, slot: "empty" },
                                    [
                                      _c(
                                        "el-button",
                                        {
                                          staticStyle: {
                                            "margin-left": "10px"
                                          },
                                          attrs: { type: "text" },
                                          on: { click: _vm.add_new }
                                        },
                                        [_vm._v("Add new")]
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
                "v-card-actions",
                [
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "blue darken-1", text: "" },
                      on: { click: _vm.close }
                    },
                    [_vm._v("Close")]
                  ),
                  _vm._v(" "),
                  _c("v-spacer"),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: {
                        color: "blue darken-1",
                        text: "",
                        loading: _vm.loading,
                        disabled: _vm.loading
                      },
                      on: { click: _vm.save }
                    },
                    [_vm._v("Save")]
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
      _c("myVendor")
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/product/create.vue":
/*!****************************************************!*\
  !*** ./resources/js/components/product/create.vue ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _create_vue_vue_type_template_id_276a5b27___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./create.vue?vue&type=template&id=276a5b27& */ "./resources/js/components/product/create.vue?vue&type=template&id=276a5b27&");
/* harmony import */ var _create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./create.vue?vue&type=script&lang=js& */ "./resources/js/components/product/create.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _create_vue_vue_type_template_id_276a5b27___WEBPACK_IMPORTED_MODULE_0__["render"],
  _create_vue_vue_type_template_id_276a5b27___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/product/create.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/product/create.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/product/create.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./create.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/create.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/product/create.vue?vue&type=template&id=276a5b27&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/product/create.vue?vue&type=template&id=276a5b27& ***!
  \***********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_create_vue_vue_type_template_id_276a5b27___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./create.vue?vue&type=template&id=276a5b27& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/create.vue?vue&type=template&id=276a5b27&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_create_vue_vue_type_template_id_276a5b27___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_create_vue_vue_type_template_id_276a5b27___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);