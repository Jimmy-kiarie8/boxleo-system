(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/google/sync~js/lux/sellers~js/marketplace/dashboard"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/sellers/sheets.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/users/sellers/sheets.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

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

/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      dialog: false,
      form: {
        vendor_id: '',
        sheet_name: '',
        post_spreadsheet_id: ''
      },
      select_vendor: false
    };
  },
  created: function created() {
    var _this = this;

    eventBus.$on("openSheet", function (data) {
      _this.dialog = true;

      if (data == 0) {
        _this.select_vendor = true;

        if (_this.sellers.length < 1) {
          _this.getSellers();
        }
      } else {
        _this.form.vendor_id = data;

        _this.getSheet(data);
      }
    });
  },
  methods: {
    save: function save() {
      var payload = {
        data: this.form,
        model: 'sheets'
      };
      this.$store.dispatch('postItems', payload).then(function () {// eventBus.$emit("sellerEvent")
      });
    },
    getSellers: function getSellers() {
      var payload = {
        model: "/seller/sellers",
        update: "updateSellerList"
      };
      this.$store.dispatch("getItems", payload);
    },
    getSheet: function getSheet(id) {
      var _this2 = this;

      var payload = {
        model: 'sheets',
        update: 'updateSheet',
        id: id
      };
      this.$store.dispatch('showItem', payload).then(function (response) {
        if (response.data.length > 0) {
          _this2.form.sheet_name = response.data.sheet_name;
          _this2.form.post_spreadsheet_id = response.data.post_spreadsheet_id;
        } else {
          _this2.form.sheet_name = '';
          _this2.form.post_spreadsheet_id = '';
        }
      });
    },
    close: function close() {
      this.dialog = false;
    }
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapState"])(['errors', 'loading', 'sheets', 'sellers']))
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/sellers/sheets.vue?vue&type=template&id=2e33057e&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/users/sellers/sheets.vue?vue&type=template&id=2e33057e& ***!
  \***********************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
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
            callback: function ($$v) {
              _vm.dialog = $$v
            },
            expression: "dialog",
          },
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
                    staticStyle: { margin: "auto" },
                  },
                  [_vm._v("Google Sheets")]
                ),
              ]),
              _vm._v(" "),
              _c(
                "v-card-text",
                [
                  _c(
                    "v-container",
                    { attrs: { "grid-list-md": "" } },
                    [
                      _c("v-divider"),
                      _vm._v(" "),
                      _c(
                        "v-layout",
                        { attrs: { row: "", wrap: "" } },
                        [
                          _vm.select_vendor
                            ? _c(
                                "v-flex",
                                { attrs: { sm12: "" } },
                                [
                                  _c("label", { attrs: { for: "" } }, [
                                    _vm._v("Vendor"),
                                  ]),
                                  _vm._v(" "),
                                  _c(
                                    "el-select",
                                    {
                                      staticStyle: { width: "100%" },
                                      attrs: {
                                        name: "client",
                                        filterable: "",
                                        placeholder:
                                          "type at least 3 characters",
                                        loading: _vm.loading,
                                      },
                                      model: {
                                        value: _vm.form.vendor_id,
                                        callback: function ($$v) {
                                          _vm.$set(_vm.form, "vendor_id", $$v)
                                        },
                                        expression: "form.vendor_id",
                                      },
                                    },
                                    _vm._l(_vm.sellers.data, function (item) {
                                      return _c("el-option", {
                                        key: item.id,
                                        attrs: {
                                          label: item.name,
                                          value: item.id,
                                        },
                                      })
                                    }),
                                    1
                                  ),
                                  _vm._v(" "),
                                  _vm.errors.vendor_id
                                    ? _c(
                                        "small",
                                        { staticClass: "has-text-danger" },
                                        [
                                          _vm._v(
                                            _vm._s(_vm.errors.vendor_id[0])
                                          ),
                                        ]
                                      )
                                    : _vm._e(),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm12: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Sheet name"),
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "Sheet1" },
                                model: {
                                  value: _vm.form.sheet_name,
                                  callback: function ($$v) {
                                    _vm.$set(_vm.form, "sheet_name", $$v)
                                  },
                                  expression: "form.sheet_name",
                                },
                              }),
                              _vm._v(" "),
                              _vm.errors.sheet_name
                                ? _c(
                                    "small",
                                    { staticClass: "has-text-danger" },
                                    [_vm._v(_vm._s(_vm.errors.sheet_name[0]))]
                                  )
                                : _vm._e(),
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm12: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Spreadsheet id"),
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "123 mainst" },
                                model: {
                                  value: _vm.form.post_spreadsheet_id,
                                  callback: function ($$v) {
                                    _vm.$set(
                                      _vm.form,
                                      "post_spreadsheet_id",
                                      $$v
                                    )
                                  },
                                  expression: "form.post_spreadsheet_id",
                                },
                              }),
                              _vm._v(" "),
                              _vm.errors.post_spreadsheet_id
                                ? _c(
                                    "small",
                                    { staticClass: "has-text-danger" },
                                    [
                                      _vm._v(
                                        _vm._s(
                                          _vm.errors.post_spreadsheet_id[0]
                                        )
                                      ),
                                    ]
                                  )
                                : _vm._e(),
                            ],
                            1
                          ),
                        ],
                        1
                      ),
                    ],
                    1
                  ),
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
                      on: { click: _vm.close },
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
                        disabled: _vm.loading,
                      },
                      on: { click: _vm.save },
                    },
                    [_vm._v("Save")]
                  ),
                ],
                1
              ),
            ],
            1
          ),
        ],
        1
      ),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/users/sellers/sheets.vue":
/*!**********************************************************!*\
  !*** ./resources/js/components/users/sellers/sheets.vue ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _sheets_vue_vue_type_template_id_2e33057e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./sheets.vue?vue&type=template&id=2e33057e& */ "./resources/js/components/users/sellers/sheets.vue?vue&type=template&id=2e33057e&");
/* harmony import */ var _sheets_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./sheets.vue?vue&type=script&lang=js& */ "./resources/js/components/users/sellers/sheets.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _sheets_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _sheets_vue_vue_type_template_id_2e33057e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _sheets_vue_vue_type_template_id_2e33057e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/users/sellers/sheets.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/users/sellers/sheets.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/users/sellers/sheets.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_sheets_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./sheets.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/sellers/sheets.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_sheets_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/users/sellers/sheets.vue?vue&type=template&id=2e33057e&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/users/sellers/sheets.vue?vue&type=template&id=2e33057e& ***!
  \*****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_sheets_vue_vue_type_template_id_2e33057e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./sheets.vue?vue&type=template&id=2e33057e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/sellers/sheets.vue?vue&type=template&id=2e33057e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_sheets_vue_vue_type_template_id_2e33057e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_sheets_vue_vue_type_template_id_2e33057e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);