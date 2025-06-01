(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/lux/edit_returns~js/lux/returns"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/returns/edit.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/returns/edit.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
      // form: {
      //     returned: 0
      // },
      form: {}
    };
  },
  methods: {
    close: function close() {
      eventBus.$emit('closeReturnSaleEvent');
    },
    save: function save() {
      this.form.sale_id = this.saleorder.id;
      this.form.warehouse_id = this.saleorder.warehouse_id;
      var payload = {
        data: this.sale_return,
        id: this.sale_return.id,
        model: 'returns'
      };
      this.$store.dispatch('patchItems', payload).then(function (response) {});
    },
    getSaleReturn: function getSaleReturn() {
      var _this = this;

      var payload = {
        model: 'returns',
        update: 'updateSaleReturn',
        id: this.$route.params.id
      };
      this.$store.dispatch('showItem', payload).then(function (res) {
        console.log(res);

        _this.getOrder(res.data.sales.id); // this.products_arr = this.saleorder.products

      });
    },
    getOrder: function getOrder(id) {
      var payload = {
        model: 'sales',
        update: 'updateSale',
        id: id
      };
      this.$store.dispatch('showItem', payload).then(function (res) {// this.products_arr = this.saleorder.products
      });
    },
    getWarehouses: function getWarehouses() {
      var payload = {
        model: '/warehouses',
        update: 'updateWarehouseList'
      };
      this.$store.dispatch("getItems", payload);
    }
  },
  mounted: function mounted() {
    this.getSaleReturn();
    this.getWarehouses();
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapState"])(['warehouses', 'saleorder', 'sale_return']))
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/returns/edit.vue?vue&type=template&id=eb3506f8&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/returns/edit.vue?vue&type=template&id=eb3506f8& ***!
  \*********************************************************************************************************************************************************************************************************************/
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
        "v-layout",
        { attrs: { "justify-center": "", "align-center": "", wrap: "" } },
        [
          _c(
            "v-card",
            { staticClass: "mx-auto overflow-hidden" },
            [
              _c(
                "v-card-text",
                [
                  _c(
                    "v-layout",
                    { attrs: { row: "", wrap: "" } },
                    [
                      _c(
                        "v-flex",
                        { attrs: { sm4: "", "offset-sm1": "" } },
                        [
                          _c("label", { attrs: { for: "" } }, [_vm._v("RMA")]),
                          _vm._v(" "),
                          _c("el-input", {
                            attrs: { placeholder: "Please input" },
                            model: {
                              value: _vm.sale_return.rma,
                              callback: function($$v) {
                                _vm.$set(_vm.sale_return, "rma", $$v)
                              },
                              expression: "sale_return.rma"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-flex",
                        { attrs: { sm4: "", "offset-sm1": "" } },
                        [
                          _c("label", { attrs: { for: "" } }, [_vm._v("Date")]),
                          _vm._v(" "),
                          _c("el-date-picker", {
                            staticStyle: { width: "100%" },
                            attrs: {
                              format: "yyyy/MM/dd",
                              "value-format": "yyyy-MM-dd",
                              type: "date",
                              placeholder: "Pick a day"
                            },
                            model: {
                              value: _vm.sale_return.return_date,
                              callback: function($$v) {
                                _vm.$set(_vm.sale_return, "return_date", $$v)
                              },
                              expression: "sale_return.return_date"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-flex",
                        { attrs: { sm4: "", "offset-sm1": "" } },
                        [
                          _c("label", { attrs: { for: "" } }, [
                            _vm._v("Warehouse Name")
                          ]),
                          _vm._v(" "),
                          _c(
                            "el-select",
                            {
                              staticStyle: { width: "100%" },
                              attrs: {
                                filterable: "",
                                clearable: "",
                                placeholder: "Warehouse"
                              },
                              model: {
                                value: _vm.saleorder.warehouse_id,
                                callback: function($$v) {
                                  _vm.$set(_vm.saleorder, "warehouse_id", $$v)
                                },
                                expression: "saleorder.warehouse_id"
                              }
                            },
                            _vm._l(_vm.warehouses, function(item) {
                              return _c("el-option", {
                                key: item.id,
                                attrs: { label: item.name, value: item.id }
                              })
                            }),
                            1
                          )
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-flex",
                        { attrs: { xs4: "", sm4: "", "offset-sm1": "" } },
                        [
                          _c("label", { attrs: { for: "" } }, [
                            _vm._v("Reason")
                          ]),
                          _vm._v(" "),
                          _c("el-input", {
                            attrs: {
                              type: "textarea",
                              autosize: "",
                              placeholder: "Please input"
                            },
                            model: {
                              value: _vm.sale_return.comment,
                              callback: function($$v) {
                                _vm.$set(_vm.sale_return, "comment", $$v)
                              },
                              expression: "sale_return.comment"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-flex",
                        {
                          staticStyle: { "margin-top": "30px" },
                          attrs: { sm10: "", "offset-sm1": "" }
                        },
                        [
                          _c(
                            "table",
                            {
                              staticClass: "table table-striped table-bordered"
                            },
                            [
                              _c("thead", [
                                _c("tr", [
                                  _c("th", [_vm._v("Items & Description")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Shipped")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Returned")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Return Quantity ")])
                                ])
                              ]),
                              _vm._v(" "),
                              _c(
                                "tbody",
                                _vm._l(_vm.saleorder.products, function(item) {
                                  return _c("tr", { key: item.id }, [
                                    _c("td", [
                                      _vm._v(_vm._s(item.product_name))
                                    ]),
                                    _vm._v(" "),
                                    _c("td", [
                                      _vm._v(_vm._s(item.pivot.quantity_sent))
                                    ]),
                                    _vm._v(" "),
                                    _c("td", [
                                      _vm._v(
                                        _vm._s(item.pivot.quantity_returned)
                                      )
                                    ]),
                                    _vm._v(" "),
                                    _c(
                                      "td",
                                      [
                                        _c("el-input", {
                                          attrs: {
                                            placeholder: "Please input"
                                          },
                                          model: {
                                            value: _vm.sale_return.returned,
                                            callback: function($$v) {
                                              _vm.$set(
                                                _vm.sale_return,
                                                "returned",
                                                $$v
                                              )
                                            },
                                            expression: "sale_return.returned"
                                          }
                                        })
                                      ],
                                      1
                                    )
                                  ])
                                }),
                                0
                              )
                            ]
                          )
                        ]
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
                  _c("v-spacer"),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "primary", rounded: "" },
                      on: { click: _vm.save }
                    },
                    [
                      _c("v-icon", [_vm._v("mdi-content-save")]),
                      _vm._v(" "),
                      _c("span", [_vm._v("Save")])
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    { attrs: { color: "primary", rounded: "" } },
                    [
                      _c("v-icon", [_vm._v("mdi-close-circle")]),
                      _vm._v(" "),
                      _c("span", [_vm._v("Cancel")])
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

/***/ "./resources/js/components/sales/returns/edit.vue":
/*!********************************************************!*\
  !*** ./resources/js/components/sales/returns/edit.vue ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _edit_vue_vue_type_template_id_eb3506f8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit.vue?vue&type=template&id=eb3506f8& */ "./resources/js/components/sales/returns/edit.vue?vue&type=template&id=eb3506f8&");
/* harmony import */ var _edit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit.vue?vue&type=script&lang=js& */ "./resources/js/components/sales/returns/edit.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _edit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _edit_vue_vue_type_template_id_eb3506f8___WEBPACK_IMPORTED_MODULE_0__["render"],
  _edit_vue_vue_type_template_id_eb3506f8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/sales/returns/edit.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/sales/returns/edit.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/sales/returns/edit.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./edit.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/returns/edit.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/sales/returns/edit.vue?vue&type=template&id=eb3506f8&":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/sales/returns/edit.vue?vue&type=template&id=eb3506f8& ***!
  \***************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_vue_vue_type_template_id_eb3506f8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./edit.vue?vue&type=template&id=eb3506f8& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/returns/edit.vue?vue&type=template&id=eb3506f8&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_vue_vue_type_template_id_eb3506f8___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_vue_vue_type_template_id_eb3506f8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);