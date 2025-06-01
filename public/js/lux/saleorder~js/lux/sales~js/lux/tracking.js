(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/lux/saleorder~js/lux/sales~js/lux/tracking"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/status/Status.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/status/Status.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************/
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

/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'orderStatus',
  data: function data() {
    return {
      loading: false,
      dialog: false,
      form: []
    };
  },
  created: function created() {
    var _this = this;

    eventBus.$on('orderStatusEvent', function (data) {
      _this.dialog = true;
      _this.form = data;

      _this.getBranch();
    });
  },
  methods: {
    getStatus: function getStatus() {
      var payload = {
        model: 'statuses',
        update: 'updateStatusList'
      };
      this.$store.dispatch("getItems", payload);
    },
    getBranch: function getBranch() {
      if (this.branches.length < 1) {
        var payload = {
          model: 'branch/branches',
          update: 'updateBranchesList'
        };
        this.$store.dispatch("getItems", payload);
      }
    },
    updateStatus: function updateStatus() {
      var payload = {
        model: 'status_update',
        id: this.form.id,
        data: this.form
      };
      this.$store.dispatch('patchItems', payload).then(function (response) {
        eventBus.$emit("saleEvent");
      });
    }
  },
  mounted: function mounted() {
    this.getStatus();
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapState"])(['statuses', 'branches', 'errors']))
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/status/Status.vue?vue&type=template&id=40930111&":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/sales/status/Status.vue?vue&type=template&id=40930111& ***!
  \**********************************************************************************************************************************************************************************************************************/
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
          attrs: { persistent: "", "max-width": "400px" },
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
                  { staticClass: "headline", staticStyle: { margin: "auto" } },
                  [_vm._v("Order Details")]
                )
              ]),
              _vm._v(" "),
              _c("VDivider"),
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
                        { attrs: { wrap: "" } },
                        [
                          _c(
                            "v-flex",
                            { attrs: { sm12: "" } },
                            [
                              _c(
                                "label",
                                { staticClass: "col-md-5 col-lg-5" },
                                [_vm._v("Status")]
                              ),
                              _vm._v(" "),
                              _c(
                                "el-select",
                                {
                                  staticStyle: { width: "100%" },
                                  attrs: {
                                    filterable: "",
                                    clearable: "",
                                    placeholder: "Select"
                                  },
                                  model: {
                                    value: _vm.form.delivery_status,
                                    callback: function($$v) {
                                      _vm.$set(_vm.form, "delivery_status", $$v)
                                    },
                                    expression: "form.delivery_status"
                                  }
                                },
                                _vm._l(_vm.statuses, function(item) {
                                  return _c("el-option", {
                                    key: item.id,
                                    attrs: {
                                      label: item.status,
                                      value: item.status
                                    }
                                  })
                                }),
                                1
                              ),
                              _vm._v(" "),
                              _vm.errors.delivery_status
                                ? _c(
                                    "small",
                                    { staticClass: "has-text-danger" },
                                    [
                                      _vm._v(
                                        _vm._s(_vm.errors.delivery_status[0])
                                      )
                                    ]
                                  )
                                : _vm._e()
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _vm.form.delivery_status == "Scheduled"
                            ? _c("v-flex", { attrs: { sm12: "" } }, [
                                _c(
                                  "div",
                                  { staticClass: "block" },
                                  [
                                    _c(
                                      "span",
                                      {
                                        staticClass: "demonstration",
                                        staticStyle: { float: "left" }
                                      },
                                      [_vm._v("Schedule Date")]
                                    ),
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
                                        value: _vm.form.delivery_date,
                                        callback: function($$v) {
                                          _vm.$set(
                                            _vm.form,
                                            "delivery_date",
                                            $$v
                                          )
                                        },
                                        expression: "form.delivery_date"
                                      }
                                    })
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _vm.errors.delivery_date
                                  ? _c(
                                      "small",
                                      { staticClass: "has-text-danger" },
                                      [
                                        _vm._v(
                                          _vm._s(_vm.errors.delivery_date[0])
                                        )
                                      ]
                                    )
                                  : _vm._e()
                              ])
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.form.delivery_status == "Dispatched"
                            ? _c(
                                "v-flex",
                                { attrs: { sm12: "" } },
                                [
                                  _c(
                                    "label",
                                    { staticClass: "col-md-5 col-lg-5" },
                                    [_vm._v("Branch")]
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "el-select",
                                    {
                                      staticStyle: { width: "100%" },
                                      attrs: {
                                        filterable: "",
                                        clearable: "",
                                        placeholder: "Select"
                                      },
                                      model: {
                                        value: _vm.form.branch_id,
                                        callback: function($$v) {
                                          _vm.$set(_vm.form, "branch_id", $$v)
                                        },
                                        expression: "form.branch_id"
                                      }
                                    },
                                    _vm._l(_vm.branches, function(item) {
                                      return _c("el-option", {
                                        key: item.id,
                                        attrs: {
                                          label: item.name,
                                          value: item.id
                                        }
                                      })
                                    }),
                                    1
                                  ),
                                  _vm._v(" "),
                                  _vm.errors.branch_id
                                    ? _c(
                                        "small",
                                        { staticClass: "has-text-danger" },
                                        [
                                          _vm._v(
                                            _vm._s(_vm.errors.branch_id[0])
                                          )
                                        ]
                                      )
                                    : _vm._e()
                                ],
                                1
                              )
                            : _vm._e(),
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
                                [_vm._v("Comments")]
                              ),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: {
                                  type: "textarea",
                                  autosize: { minRows: 2, maxRows: 4 },
                                  placeholder: "comments",
                                  maxlength: "500",
                                  "show-word-limit": ""
                                },
                                model: {
                                  value: _vm.form.comment,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "comment", $$v)
                                  },
                                  expression: "form.comment"
                                }
                              }),
                              _vm._v(" "),
                              _vm.errors.comment
                                ? _c(
                                    "small",
                                    { staticClass: "has-text-danger" },
                                    [_vm._v(_vm._s(_vm.errors.comment[0]))]
                                  )
                                : _vm._e()
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
                      on: {
                        click: function($event) {
                          _vm.dialog = false
                        }
                      }
                    },
                    [_vm._v("Close")]
                  ),
                  _vm._v(" "),
                  _c("VSpacer"),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: {
                        color: "primary",
                        loading: _vm.loading,
                        disabled: _vm.loading
                      },
                      on: { click: _vm.updateStatus }
                    },
                    [_vm._v("Update")]
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

/***/ "./resources/js/components/sales/status/Status.vue":
/*!*********************************************************!*\
  !*** ./resources/js/components/sales/status/Status.vue ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Status_vue_vue_type_template_id_40930111___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Status.vue?vue&type=template&id=40930111& */ "./resources/js/components/sales/status/Status.vue?vue&type=template&id=40930111&");
/* harmony import */ var _Status_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Status.vue?vue&type=script&lang=js& */ "./resources/js/components/sales/status/Status.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Status_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Status_vue_vue_type_template_id_40930111___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Status_vue_vue_type_template_id_40930111___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/sales/status/Status.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/sales/status/Status.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./resources/js/components/sales/status/Status.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Status_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Status.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/status/Status.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Status_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/sales/status/Status.vue?vue&type=template&id=40930111&":
/*!****************************************************************************************!*\
  !*** ./resources/js/components/sales/status/Status.vue?vue&type=template&id=40930111& ***!
  \****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Status_vue_vue_type_template_id_40930111___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Status.vue?vue&type=template&id=40930111& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/sales/status/Status.vue?vue&type=template&id=40930111&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Status_vue_vue_type_template_id_40930111___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Status_vue_vue_type_template_id_40930111___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);