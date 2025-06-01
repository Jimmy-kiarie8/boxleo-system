(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/marketplace/dashboard~js/shopifyapp/dashboard"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _stores_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./stores.vue */ "./resources/js/marketplace/shopifyapp/home/stores.vue");
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
    myStores: _stores_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {};
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************/
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

/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      dialog: false,
      form: {},
      intervals: [{
        value: 0.5,
        label: '30 Minutes'
      }, {
        value: 1,
        label: '1 Hour'
      }, {
        value: 2,
        label: '2 Hours'
      }, {
        value: 4,
        label: '4 Hours'
      }, {
        value: 24,
        label: '1 Day'
      }]
    };
  },
  methods: {
    close: function close() {
      this.dialog = false;
    },
    getOu: function getOu() {
      var payload = {
        model: 'ous',
        update: 'updateOu'
      };
      this.$store.dispatch('getItems', payload);
    },
    change_sync: function change_sync() {
      if (this.form.sync_option == 'Auto') {
        this.form.auto_sync = true;
        this.form.product_webhook = false;
        this.form.order_webhook = false;
      } else if (this.form.sync_option == 'Webhook') {
        this.form.product_webhook = true;
        this.form.order_webhook = true;
        this.form.auto_sync = false;
      }
    },
    update: function update() {
      var payload = {
        model: 'shopify_settings',
        id: this.form.id,
        data: this.form
      };
      this.$store.dispatch('patchItems', payload);
    }
  },
  created: function created() {
    var _this = this;

    eventBus.$on('settingsEvent', function (data) {
      if (_this.ous.length < 1) {
        _this.getOu();
      }

      _this.form = data;
      _this.dialog = true;
    });
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapState"])(['ous']))
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
/* harmony import */ var _components_settings_company_inc_api_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../components/settings/company/inc/api.vue */ "./resources/js/components/settings/company/inc/api.vue");
/* harmony import */ var _settings_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./settings.vue */ "./resources/js/marketplace/shopifyapp/home/settings.vue");
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



/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    createStore: _components_settings_company_inc_api_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
    settings: _settings_vue__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  data: function data() {
    return {
      s_color: '#95bf47'
    };
  },
  methods: {
    openApi: function openApi() {
      var data = {
        site: 'Shopify',
        value: 'shopify',
        icon: 'fab fa-shopify'
      };
      eventBus.$emit('ApiConnectEvent', data);
    },
    getStores: function getStores() {
      var payload = {
        model: '/shopify_stores',
        update: 'updateShopifyStore'
      };
      this.$store.dispatch("getItems", payload);
    },
    openStore: function openStore(data) {
      this.$router.push({
        name: "sync",
        params: {
          id: data.id
        }
      });
    },
    changeStatus: function changeStatus(data) {
      var _this = this;

      var payload = {
        model: 'shopify_status',
        id: data.id,
        data: data
      };
      this.$store.dispatch('patchItems', payload).then(function (response) {
        _this.getStores();
      });
    },
    openSettings: function openSettings(data) {
      eventBus.$emit('settingsEvent', data);
    }
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapState"])(['shopify_stores', 'loading'])),
  mounted: function mounted() {
    this.getStores();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=template&id=6bdcb9c4&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=template&id=6bdcb9c4& ***!
  \*************************************************************************************************************************************************************************************************************************/
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
        "v-card",
        { staticStyle: { margin: "auto" }, attrs: { width: "90%" } },
        [_c("myStores")],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=template&id=1a2697a1&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=template&id=1a2697a1& ***!
  \****************************************************************************************************************************************************************************************************************************/
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
    "v-dialog",
    {
      attrs: {
        persistent: "",
        "max-width": "600px",
        transition: "dialog-transition"
      },
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
        { attrs: { elevation: "3" } },
        [
          _c(
            "v-card-title",
            { attrs: { "grey-title": "" } },
            [
              _vm._v(
                "\r\n            " +
                  _vm._s(_vm.form.shopify_name) +
                  " settings\r\n            "
              ),
              _c("v-spacer"),
              _vm._v(" "),
              _c(
                "v-btn",
                {
                  attrs: { text: "", icon: "", color: "primary" },
                  on: { click: _vm.close }
                },
                [_c("v-icon", [_vm._v("mdi-close")])],
                1
              )
            ],
            1
          ),
          _vm._v(" "),
          _c("v-divider"),
          _vm._v(" "),
          _c(
            "v-card-text",
            [
              _c("label", { attrs: { for: "" } }, [_vm._v("Operating unit")]),
              _vm._v(" "),
              _vm.form.auto_sync
                ? _c(
                    "el-select",
                    {
                      staticStyle: { width: "100%" },
                      attrs: {
                        filterable: "",
                        "reserve-keyword": "",
                        placeholder: "Operating unit"
                      },
                      model: {
                        value: _vm.form.ou_id,
                        callback: function($$v) {
                          _vm.$set(_vm.form, "ou_id", $$v)
                        },
                        expression: "form.ou_id"
                      }
                    },
                    _vm._l(_vm.ous, function(item) {
                      return _c("el-option", {
                        key: item.id,
                        attrs: { label: item.ou, value: item.value }
                      })
                    }),
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _c(
                "el-radio-group",
                {
                  staticStyle: { width: "100%" },
                  on: { change: _vm.change_sync },
                  model: {
                    value: _vm.form.sync_option,
                    callback: function($$v) {
                      _vm.$set(_vm.form, "sync_option", $$v)
                    },
                    expression: "form.sync_option"
                  }
                },
                [
                  _c(
                    "el-tooltip",
                    {
                      attrs: {
                        content:
                          "Automatically synchronize orders at a given time interval",
                        placement: "top"
                      }
                    },
                    [
                      _c("el-radio", { attrs: { label: "Auto" } }, [
                        _vm._v("Auto Sync")
                      ])
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "el-tooltip",
                    {
                      attrs: {
                        content:
                          "Automatically create an order&products after it is created on shopify",
                        placement: "top"
                      }
                    },
                    [
                      _c("el-radio", { attrs: { label: "Webhook" } }, [
                        _vm._v("Webhooks")
                      ])
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c("label", { attrs: { for: "" } }, [_vm._v("Time interval")]),
              _vm._v(" "),
              _vm.form.auto_sync
                ? _c(
                    "el-select",
                    {
                      staticStyle: { width: "100%" },
                      attrs: {
                        filterable: "",
                        "reserve-keyword": "",
                        placeholder: "Time interval"
                      },
                      model: {
                        value: _vm.form.sync_interval,
                        callback: function($$v) {
                          _vm.$set(_vm.form, "sync_interval", $$v)
                        },
                        expression: "form.sync_interval"
                      }
                    },
                    _vm._l(_vm.intervals, function(item) {
                      return _c("el-option", {
                        key: item.value,
                        attrs: { label: item.label, value: item.value }
                      })
                    }),
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _c("label", { attrs: { for: "" } }, [
                _vm._v("Order number prefix")
              ]),
              _vm._v(" "),
              _c("el-input", {
                attrs: { placeholder: "eg SHP_" },
                model: {
                  value: _vm.form.order_prefix,
                  callback: function($$v) {
                    _vm.$set(_vm.form, "order_prefix", $$v)
                  },
                  expression: "form.order_prefix"
                }
              })
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
                  attrs: { color: "primary", text: "" },
                  on: { click: _vm.update }
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
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=template&id=1dd10130&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=template&id=1dd10130& ***!
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
        "v-card",
        {
          staticClass: "mx-auto",
          staticStyle: { "padding-bottom": "30px" },
          attrs: { "max-width": "100%", outlined: "" }
        },
        [
          _c(
            "v-list-item",
            { attrs: { "three-line": "" } },
            [
              _c(
                "v-list-item-content",
                [
                  _c("div", { staticClass: "text-overline mb-4" }, [
                    _vm._v(
                      "\r\n                    Logixsaas\r\n                "
                    )
                  ]),
                  _vm._v(" "),
                  _c("v-list-item-title", { staticClass: "text-h5 mb-1" }, [
                    _vm._v(
                      "\r\n                    Shopify\r\n                "
                    )
                  ]),
                  _vm._v(" "),
                  _c(
                    "v-list-item-subtitle",
                    { staticStyle: { overflow: "visible" } },
                    [
                      _vm._v(
                        "Integrating your Shopify store with Logixsaas bridges the gap between your sales channel and inventory, giving you the ability to tackle any number of online orders with ease while keeping the stock in continuous sync in both platforms."
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "v-list-item-avatar",
                {
                  staticStyle: {
                    width: "200px !important",
                    height: "200px !important",
                    margin: "0 !important"
                  },
                  attrs: { tile: "", size: "200" }
                },
                [
                  _c(
                    "v-icon",
                    {
                      staticStyle: { "font-size": "190px" },
                      attrs: { color: _vm.s_color }
                    },
                    [_vm._v("fab fa-shopify")]
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
                  attrs: { outlined: "", rounded: "", text: "" },
                  on: { click: _vm.openApi }
                },
                [_vm._v("\r\n                Add Store\r\n            ")]
              )
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c("v-divider"),
      _vm._v(" "),
      _c("h3", [_vm._v("Connected Stores")]),
      _vm._v(" "),
      _c(
        "v-btn",
        {
          attrs: { color: "primary", text: "", icon: "" },
          on: { click: _vm.getStores }
        },
        [_c("v-icon", { attrs: { small: "" } }, [_vm._v("mdi-refresh")])],
        1
      ),
      _vm._v(" "),
      _c(
        "el-table",
        {
          directives: [
            {
              name: "loading",
              rawName: "v-loading",
              value: _vm.loading,
              expression: "loading"
            }
          ],
          staticStyle: { width: "100%" },
          attrs: { data: _vm.shopify_stores }
        },
        [
          _c("el-table-column", {
            attrs: { prop: "shopify_name", label: "Store Name", width: "180" }
          }),
          _vm._v(" "),
          _c("el-table-column", {
            attrs: { prop: "active", label: "Status", width: "180" },
            scopedSlots: _vm._u([
              {
                key: "default",
                fn: function(scope) {
                  return [
                    _c(
                      "v-btn",
                      {
                        staticClass: "mx-0",
                        attrs: { slot: "activator", icon: "" },
                        slot: "activator"
                      },
                      [
                        _c("v-icon", {
                          attrs: {
                            small: "",
                            color: scope.row.active ? "success" : "red"
                          },
                          domProps: {
                            textContent: _vm._s(
                              scope.row.active
                                ? "mdi-check-circle"
                                : "mdi-cancel"
                            )
                          }
                        })
                      ],
                      1
                    )
                  ]
                }
              }
            ])
          }),
          _vm._v(" "),
          _c("el-table-column", {
            attrs: { prop: "created_at", label: "Created on", width: "180" }
          }),
          _vm._v(" "),
          _c("el-table-column", {
            attrs: { label: "Actions" },
            scopedSlots: _vm._u([
              {
                key: "default",
                fn: function(scope) {
                  return [
                    scope.row.active
                      ? _c(
                          "v-tooltip",
                          {
                            attrs: { bottom: "" },
                            scopedSlots: _vm._u(
                              [
                                {
                                  key: "activator",
                                  fn: function(ref) {
                                    var on = ref.on
                                    return [
                                      _c(
                                        "v-btn",
                                        _vm._g(
                                          {
                                            staticClass: "mx-0",
                                            attrs: {
                                              slot: "activator",
                                              icon: ""
                                            },
                                            on: {
                                              click: function($event) {
                                                return _vm.openStore(scope.row)
                                              }
                                            },
                                            slot: "activator"
                                          },
                                          on
                                        ),
                                        [
                                          _c(
                                            "v-icon",
                                            {
                                              attrs: {
                                                small: "",
                                                color: "blue darken-2"
                                              }
                                            },
                                            [_vm._v("mdi-eye")]
                                          )
                                        ],
                                        1
                                      )
                                    ]
                                  }
                                }
                              ],
                              null,
                              true
                            )
                          },
                          [_vm._v(" "), _c("span", [_vm._v("Open store")])]
                        )
                      : _vm._e(),
                    _vm._v(" "),
                    scope.row.active
                      ? _c(
                          "v-tooltip",
                          {
                            attrs: { bottom: "" },
                            scopedSlots: _vm._u(
                              [
                                {
                                  key: "activator",
                                  fn: function(ref) {
                                    var on = ref.on
                                    return [
                                      _c(
                                        "v-btn",
                                        _vm._g(
                                          {
                                            staticClass: "mx-0",
                                            attrs: {
                                              slot: "activator",
                                              icon: ""
                                            },
                                            on: {
                                              click: function($event) {
                                                return _vm.openSettings(
                                                  scope.row
                                                )
                                              }
                                            },
                                            slot: "activator"
                                          },
                                          on
                                        ),
                                        [
                                          _c(
                                            "v-icon",
                                            {
                                              attrs: {
                                                small: "",
                                                color: "blue darken-2"
                                              }
                                            },
                                            [_vm._v("mdi-cogs")]
                                          )
                                        ],
                                        1
                                      )
                                    ]
                                  }
                                }
                              ],
                              null,
                              true
                            )
                          },
                          [_vm._v(" "), _c("span", [_vm._v("Store settings")])]
                        )
                      : _vm._e(),
                    _vm._v(" "),
                    _c(
                      "v-tooltip",
                      {
                        attrs: { bottom: "" },
                        scopedSlots: _vm._u(
                          [
                            {
                              key: "activator",
                              fn: function(ref) {
                                var on = ref.on
                                return [
                                  _c(
                                    "v-btn",
                                    _vm._g(
                                      {
                                        staticClass: "mx-0",
                                        attrs: { slot: "activator", icon: "" },
                                        on: {
                                          click: function($event) {
                                            return _vm.changeStatus(scope.row)
                                          }
                                        },
                                        slot: "activator"
                                      },
                                      on
                                    ),
                                    [
                                      _c("v-icon", {
                                        attrs: {
                                          small: "",
                                          color: scope.row.active
                                            ? "success"
                                            : "red"
                                        },
                                        domProps: {
                                          textContent: _vm._s(
                                            scope.row.active
                                              ? "mdi-check-circle"
                                              : "mdi-cancel"
                                          )
                                        }
                                      })
                                    ],
                                    1
                                  )
                                ]
                              }
                            }
                          ],
                          null,
                          true
                        )
                      },
                      [
                        _vm._v(" "),
                        scope.row.active
                          ? _c("span", [_vm._v("Deactivate")])
                          : _c("span", [_vm._v("Activate")])
                      ]
                    )
                  ]
                }
              }
            ])
          })
        ],
        1
      ),
      _vm._v(" "),
      _c("createStore"),
      _vm._v(" "),
      _c("settings")
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/home/index.vue":
/*!************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/home/index.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_vue_vue_type_template_id_6bdcb9c4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.vue?vue&type=template&id=6bdcb9c4& */ "./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=template&id=6bdcb9c4&");
/* harmony import */ var _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.vue?vue&type=script&lang=js& */ "./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _index_vue_vue_type_template_id_6bdcb9c4___WEBPACK_IMPORTED_MODULE_0__["render"],
  _index_vue_vue_type_template_id_6bdcb9c4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/marketplace/shopifyapp/home/index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=template&id=6bdcb9c4&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=template&id=6bdcb9c4& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_6bdcb9c4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=template&id=6bdcb9c4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/index.vue?vue&type=template&id=6bdcb9c4&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_6bdcb9c4___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_6bdcb9c4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/home/settings.vue":
/*!***************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/home/settings.vue ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _settings_vue_vue_type_template_id_1a2697a1___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./settings.vue?vue&type=template&id=1a2697a1& */ "./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=template&id=1a2697a1&");
/* harmony import */ var _settings_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./settings.vue?vue&type=script&lang=js& */ "./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _settings_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _settings_vue_vue_type_template_id_1a2697a1___WEBPACK_IMPORTED_MODULE_0__["render"],
  _settings_vue_vue_type_template_id_1a2697a1___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/marketplace/shopifyapp/home/settings.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=script&lang=js&":
/*!****************************************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_settings_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./settings.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_settings_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=template&id=1a2697a1&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=template&id=1a2697a1& ***!
  \**********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_settings_vue_vue_type_template_id_1a2697a1___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./settings.vue?vue&type=template&id=1a2697a1& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/settings.vue?vue&type=template&id=1a2697a1&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_settings_vue_vue_type_template_id_1a2697a1___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_settings_vue_vue_type_template_id_1a2697a1___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/home/stores.vue":
/*!*************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/home/stores.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _stores_vue_vue_type_template_id_1dd10130___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./stores.vue?vue&type=template&id=1dd10130& */ "./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=template&id=1dd10130&");
/* harmony import */ var _stores_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./stores.vue?vue&type=script&lang=js& */ "./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _stores_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _stores_vue_vue_type_template_id_1dd10130___WEBPACK_IMPORTED_MODULE_0__["render"],
  _stores_vue_vue_type_template_id_1dd10130___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/marketplace/shopifyapp/home/stores.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_stores_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./stores.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_stores_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=template&id=1dd10130&":
/*!********************************************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=template&id=1dd10130& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_stores_vue_vue_type_template_id_1dd10130___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./stores.vue?vue&type=template&id=1dd10130& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/home/stores.vue?vue&type=template&id=1dd10130&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_stores_vue_vue_type_template_id_1dd10130___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_stores_vue_vue_type_template_id_1dd10130___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);