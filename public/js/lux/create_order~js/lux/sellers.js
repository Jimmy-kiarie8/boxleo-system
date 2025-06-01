(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/lux/create_order~js/lux/sellers"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/sellers/create.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/users/sellers/create.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
      loading: false,
      form: {
        name: '',
        email: '',
        phone: '',
        address: '',
        company_name: '',
        company_email: '',
        company_phone: '',
        company_address: '',
        business_no: '',
        company_website: '',
        account_no: '',
        account_name: '',
        mpesa_name: '',
        mpesa_phone: '',
        payment_mode: 'bank',
        generate: 'input'
      },
      errors: {},
      gender: [{
        value: 'Male',
        lable: 'Male'
      }, {
        value: 'Female',
        lable: 'Female'
      }]
    };
  },
  created: function created() {
    var _this = this;

    eventBus.$on("openCreateSeller", function (data) {
      _this.dialog = true;
    });
  },
  methods: {
    save: function save() {
      var payload = {
        data: this.form,
        model: '/seller/sellers'
      };
      this.$store.dispatch('postItems', payload).then(function (response) {
        eventBus.$emit("sellerEvent");
      });
    },
    close: function close() {
      this.dialog = false;
    }
  },
  computed: {
    suppliers: function suppliers() {
      return this.$store.dispatch('suppliers');
    },
    groups: function groups() {
      return this.$store.getters.groups;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/sellers/create.vue?vue&type=template&id=cac4e96e&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/users/sellers/create.vue?vue&type=template&id=cac4e96e& ***!
  \***********************************************************************************************************************************************************************************************************************/
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
          attrs: { persistent: "", "max-width": "800px" },
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
                  [_vm._v("Create Vendor")]
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
                          _c(
                            "v-flex",
                            { attrs: { sm6: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Full Name")
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "John Doe" },
                                model: {
                                  value: _vm.form.name,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "name", $$v)
                                  },
                                  expression: "form.name"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm6: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Email Address")
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "john@gmail.com" },
                                model: {
                                  value: _vm.form.email,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "email", $$v)
                                  },
                                  expression: "form.email"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm6: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Phone Number")
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "+254..." },
                                model: {
                                  value: _vm.form.phone,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "phone", $$v)
                                  },
                                  expression: "form.phone"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm6: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Address")
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "123 main st" },
                                model: {
                                  value: _vm.form.address,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "address", $$v)
                                  },
                                  expression: "form.address"
                                }
                              })
                            ],
                            1
                          )
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c("v-divider"),
                      _vm._v(" "),
                      _c("h3", { staticClass: "text-center" }, [
                        _vm._v("Company details")
                      ]),
                      _vm._v(" "),
                      _c("v-divider"),
                      _vm._v(" "),
                      _c(
                        "v-layout",
                        { attrs: { row: "", wrap: "" } },
                        [
                          _c(
                            "v-flex",
                            { attrs: { sm6: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Company name")
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "jane's store" },
                                model: {
                                  value: _vm.form.company_name,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "company_name", $$v)
                                  },
                                  expression: "form.company_name"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm6: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Address")
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "123 mainst" },
                                model: {
                                  value: _vm.form.company_address,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "company_address", $$v)
                                  },
                                  expression: "form.company_address"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm6: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Address 2")
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "123 mainst" },
                                model: {
                                  value: _vm.form.address_2,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "address_2", $$v)
                                  },
                                  expression: "form.address_2"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm6: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Company phone no.")
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "+254..." },
                                model: {
                                  value: _vm.form.company_phone,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "company_phone", $$v)
                                  },
                                  expression: "form.company_phone"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm6: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Company email")
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "jane@store.com" },
                                model: {
                                  value: _vm.form.company_email,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "company_email", $$v)
                                  },
                                  expression: "form.company_email"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm6: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Company website")
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "https://123.com" },
                                model: {
                                  value: _vm.form.company_website,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "company_website", $$v)
                                  },
                                  expression: "form.company_website"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm6: "" } },
                            [
                              _c("label", { attrs: { for: "" } }, [
                                _vm._v("Postal Code")
                              ]),
                              _vm._v(" "),
                              _c("el-input", {
                                attrs: { placeholder: "000192" },
                                model: {
                                  value: _vm.form.postal_code,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "postal_code", $$v)
                                  },
                                  expression: "form.postal_code"
                                }
                              })
                            ],
                            1
                          )
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-layout",
                        { attrs: { row: "", wrap: "" } },
                        [
                          _c(
                            "v-flex",
                            { attrs: { sm4: "" } },
                            [
                              _c(
                                "el-radio",
                                {
                                  attrs: { label: "input" },
                                  model: {
                                    value: _vm.form.generate,
                                    callback: function($$v) {
                                      _vm.$set(_vm.form, "generate", $$v)
                                    },
                                    expression: "form.generate"
                                  }
                                },
                                [_vm._v("Input client order no")]
                              ),
                              _vm._v(" "),
                              _c(
                                "el-radio",
                                {
                                  attrs: { label: "generate" },
                                  model: {
                                    value: _vm.form.generate,
                                    callback: function($$v) {
                                      _vm.$set(_vm.form, "generate", $$v)
                                    },
                                    expression: "form.generate"
                                  }
                                },
                                [_vm._v("Auto generate order no.")]
                              )
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _vm.form.generate == "generate"
                            ? _c(
                                "v-flex",
                                { attrs: { sm4: "" } },
                                [
                                  _c("label", { attrs: { for: "" } }, [
                                    _vm._v("Order number Start")
                                  ]),
                                  _vm._v(" "),
                                  _c("el-input", {
                                    attrs: { placeholder: "000192" },
                                    model: {
                                      value: _vm.form.order_no_start,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.form,
                                          "order_no_start",
                                          $$v
                                        )
                                      },
                                      expression: "form.order_no_start"
                                    }
                                  })
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.form.generate == "generate"
                            ? _c(
                                "v-flex",
                                { attrs: { sm4: "" } },
                                [
                                  _c("label", { attrs: { for: "" } }, [
                                    _vm._v("Order number End")
                                  ]),
                                  _vm._v(" "),
                                  _c("el-input", {
                                    attrs: { placeholder: "000192" },
                                    model: {
                                      value: _vm.form.order_no_end,
                                      callback: function($$v) {
                                        _vm.$set(_vm.form, "order_no_end", $$v)
                                      },
                                      expression: "form.order_no_end"
                                    }
                                  })
                                ],
                                1
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
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/users/sellers/create.vue":
/*!**********************************************************!*\
  !*** ./resources/js/components/users/sellers/create.vue ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _create_vue_vue_type_template_id_cac4e96e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./create.vue?vue&type=template&id=cac4e96e& */ "./resources/js/components/users/sellers/create.vue?vue&type=template&id=cac4e96e&");
/* harmony import */ var _create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./create.vue?vue&type=script&lang=js& */ "./resources/js/components/users/sellers/create.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _create_vue_vue_type_template_id_cac4e96e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _create_vue_vue_type_template_id_cac4e96e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/users/sellers/create.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/users/sellers/create.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/users/sellers/create.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./create.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/sellers/create.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/users/sellers/create.vue?vue&type=template&id=cac4e96e&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/users/sellers/create.vue?vue&type=template&id=cac4e96e& ***!
  \*****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_create_vue_vue_type_template_id_cac4e96e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./create.vue?vue&type=template&id=cac4e96e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/sellers/create.vue?vue&type=template&id=cac4e96e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_create_vue_vue_type_template_id_cac4e96e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_create_vue_vue_type_template_id_cac4e96e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);