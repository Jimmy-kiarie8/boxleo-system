(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/lux/company~js/lux/products~js/lux/sales~js/marketplace/dashboard~js/marketplace/products~js/shop~7e569265"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/company/inc/api.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/company/inc/api.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
        file: ""
      },
      headers: {
        '_token': document.querySelector('meta[name="csrf-token"]').getAttribute("content")
      },
      api_data: '',
      jsonstr: "",
      jsonerror: ""
    };
  },
  created: function created() {
    var _this = this;

    eventBus.$on("ApiConnectEvent", function (data) {
      // console.log(data);
      if (data.value == 'google_sheets') {
        _this.get_json();
      } else if (data.value == 'twilio' || data.value == 'africas_talking' || data.value == 'celcomafrica') {
        _this.getApi(0);
      }

      _this.api_data = data;
      _this.dialog = true;
    });
  },
  methods: {
    save: function save() {
      // this.api_data. = 'http://foo.lux.local/callback_url'
      var payload = {
        model: 'api_connect/' + this.api_data.value,
        data: this.form
      };
      this.$store.dispatch('postItems', payload).then(function (response) {// eventBus.$emit("CurrencyEvent")
        // this.$refs.upload.submit();
      });
    },
    getSellers: function getSellers() {
      var payload = {
        model: "/seller/sellers",
        update: "updateSellerList"
      };
      this.$store.dispatch("getItems", payload);
    },
    get_json: function get_json() {
      var _this2 = this;

      var payload = {
        model: 'get_json',
        update: 'updatApi'
      };
      this.$store.dispatch('getItems', payload).then(function (response) {
        console.log(response);

        if (response.data) {
          _this2.form.file = JSON.stringify(response.data, null, 2);
        }
      });
    },
    getApi: function getApi(id) {
      var _this3 = this;

      var payload = {
        model: 'api_exist',
        id: id,
        update: 'updatApi'
      };
      this.$store.dispatch('showItem', payload).then(function (response) {
        console.log(response);

        if (response.data) {
          _this3.form = response.data;
          _this3.form.file = JSON.stringify(response.data.file, null, 2); // this.form = response.data
        } else {
          _this3.form = {
            vendor_id: id,
            file: ""
          };
        }
      });
    },
    submitUpload: function submitUpload() {
      this.$refs.upload.submit();
    },
    close: function close() {
      this.dialog = false;
    },
    get_data: function get_data(data) {
      var _this4 = this;

      console.log(data);
      var payload = {
        model: 'api_exist',
        id: data.site,
        update: 'updatApi'
      };
      this.$store.dispatch('showItem', payload).then(function (response) {
        console.log(response);

        if (response.data) {
          _this4.form = response.data;
        }
      });
    }
  },
  filters: {
    pretty: function pretty(value) {
      return JSON.stringify(JSON.parse(value), null, 2);
    }
  },
  computed: _objectSpread(_objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapState"])(['loading', 'sellers'])), {}, {
    prettyFormat: function prettyFormat() {
      // reset error
      this.jsonerror = "";
      var jsonValue = "";

      try {
        // try to parse
        jsonValue = JSON.parse(this.form.file);
      } catch (e) {
        this.jsonerror = JSON.stringify(e.message);
        var textarea = document.getElementById("jsonText");

        if (this.jsonerror.indexOf('position') > -1) {
          // highlight error position
          var positionStr = this.jsonerror.lastIndexOf('position') + 8;
          var posi = parseInt(this.jsonerror.substr(positionStr, this.jsonerror.lastIndexOf('"')));

          if (posi >= 0) {
            textarea.setSelectionRange(posi, posi + 1);
          }
        }

        return "";
      }

      return JSON.stringify(jsonValue, null, 2);
    }
  }),
  mounted: function mounted() {
    this.getSellers();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/company/inc/api.vue?vue&type=template&id=cd174694&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/settings/company/inc/api.vue?vue&type=template&id=cd174694&scoped=true& ***!
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
  return _c(
    "v-layout",
    { attrs: { row: "", "justify-center": "" } },
    [
      _c(
        "v-dialog",
        {
          attrs: { persistent: "", "max-width": "900px" },
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
            { staticStyle: { padding: "0 20px" } },
            [
              _c("v-card-title", [
                _c(
                  "span",
                  {
                    staticClass: "headline text-center",
                    staticStyle: { margin: "auto" }
                  },
                  [_vm._v(_vm._s(_vm.api_data.site) + " Connection")]
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
                        "v-card-text",
                        [
                          _c(
                            "v-layout",
                            { attrs: { row: "", wrap: "" } },
                            [
                              _vm.api_data.value == "woocommerce" ||
                              _vm.api_data.value == "shopify"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
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
                                            placeholder:
                                              "type at least 3 characters",
                                            loading: _vm.loading
                                          },
                                          on: { change: _vm.getApi },
                                          model: {
                                            value: _vm.form.vendor_id,
                                            callback: function($$v) {
                                              _vm.$set(
                                                _vm.form,
                                                "vendor_id",
                                                $$v
                                              )
                                            },
                                            expression: "form.vendor_id"
                                          }
                                        },
                                        _vm._l(_vm.sellers.data, function(
                                          item
                                        ) {
                                          return _c("el-option", {
                                            key: item.id,
                                            attrs: {
                                              label: item.name,
                                              value: item.id
                                            }
                                          })
                                        }),
                                        1
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "africas_talking"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Africa's Talking Username")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "user name" },
                                        model: {
                                          value:
                                            _vm.form.africas_talkig_username,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "africas_talkig_username",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "form.africas_talkig_username"
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "africas_talking"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Africa's Talking Api Key")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "api key" },
                                        model: {
                                          value:
                                            _vm.form.africas_talkig_api_key,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "africas_talkig_api_key",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "form.africas_talkig_api_key"
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "twilio"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Twilio SID")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "sid" },
                                        model: {
                                          value: _vm.form.twilio_sid,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "twilio_sid",
                                              $$v
                                            )
                                          },
                                          expression: "form.twilio_sid"
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "twilio"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Twilio Auth Token")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "Secret" },
                                        model: {
                                          value: _vm.form.twilio_auth_token,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "twilio_auth_token",
                                              $$v
                                            )
                                          },
                                          expression: "form.twilio_auth_token"
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "twilio"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Twilio Number")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "number" },
                                        model: {
                                          value: _vm.form.twilio_number,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "twilio_number",
                                              $$v
                                            )
                                          },
                                          expression: "form.twilio_number"
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "celcomafrica"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Partner Id")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "Partner id" },
                                        model: {
                                          value:
                                            _vm.form.celcomafrica_partner_id,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "celcomafrica_partner_id",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "form.celcomafrica_partner_id"
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "celcomafrica"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Api Key")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "Api key" },
                                        model: {
                                          value: _vm.form.celcomafrica_api_key,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "celcomafrica_api_key",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "form.celcomafrica_api_key "
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "celcomafrica"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Shortcode/Sender id")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "Shortcode" },
                                        model: {
                                          value:
                                            _vm.form.celcomafrica_short_code,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "celcomafrica_short_code",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "form.celcomafrica_short_code"
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "twilio" ||
                              _vm.api_data.value == "africas_talking" ||
                              _vm.api_data.value == "celcomafrica"
                                ? _c(
                                    "v-flex",
                                    {
                                      staticStyle: { "margin-top": "20px" },
                                      attrs: { sm6: "" }
                                    },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v(
                                          _vm._s(_vm.api_data.site) +
                                            " will be made default to send sms"
                                        )
                                      ]),
                                      _vm._v(" "),
                                      _vm.api_data.value == "twilio"
                                        ? _c("el-radio", {
                                            staticStyle: { width: "100%" },
                                            attrs: {
                                              label: _vm.api_data.value
                                            },
                                            model: {
                                              value: _vm.form.sms_default,
                                              callback: function($$v) {
                                                _vm.$set(
                                                  _vm.form,
                                                  "sms_default",
                                                  $$v
                                                )
                                              },
                                              expression: "form.sms_default"
                                            }
                                          })
                                        : _vm._e(),
                                      _vm._v(" "),
                                      _vm.api_data.value == "africas_talking"
                                        ? _c(
                                            "el-radio",
                                            {
                                              staticStyle: { width: "100%" },
                                              attrs: {
                                                label: _vm.api_data.value
                                              },
                                              model: {
                                                value: _vm.form.sms_default,
                                                callback: function($$v) {
                                                  _vm.$set(
                                                    _vm.form,
                                                    "sms_default",
                                                    $$v
                                                  )
                                                },
                                                expression: "form.sms_default"
                                              }
                                            },
                                            [_vm._v(_vm._s(_vm.api_data.site))]
                                          )
                                        : _vm._e(),
                                      _vm._v(" "),
                                      _vm.api_data.value == "celcomafrica"
                                        ? _c(
                                            "el-radio",
                                            {
                                              staticStyle: { width: "100%" },
                                              attrs: {
                                                label: _vm.api_data.value
                                              },
                                              model: {
                                                value: _vm.form.sms_default,
                                                callback: function($$v) {
                                                  _vm.$set(
                                                    _vm.form,
                                                    "sms_default",
                                                    $$v
                                                  )
                                                },
                                                expression: "form.sms_default"
                                              }
                                            },
                                            [_vm._v(_vm._s(_vm.api_data.site))]
                                          )
                                        : _vm._e()
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "google_sheets"
                                ? _c("v-flex", { attrs: { sm12: "" } }, [
                                    _c(
                                      "label",
                                      { attrs: { for: "" } },
                                      [
                                        _vm._v(
                                          "File content\r\n                                    "
                                        ),
                                        _c(
                                          "el-tooltip",
                                          {
                                            attrs: {
                                              content: "Copy the file here",
                                              placement: "top"
                                            }
                                          },
                                          [
                                            _c(
                                              "v-icon",
                                              {
                                                attrs: {
                                                  small: "",
                                                  color: "black"
                                                }
                                              },
                                              [_vm._v("mdi-help")]
                                            )
                                          ],
                                          1
                                        )
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c("div", { attrs: { id: "vueapp" } }, [
                                      _vm.form.file && _vm.jsonerror
                                        ? _c(
                                            "div",
                                            { staticClass: "text-danger" },
                                            [_vm._v(_vm._s(_vm.jsonerror))]
                                          )
                                        : _vm._e(),
                                      _vm._v(" "),
                                      !_vm.jsonerror
                                        ? _c(
                                            "div",
                                            { staticClass: "text-success" },
                                            [_vm._v("Valid JSON ✔")]
                                          )
                                        : _vm._e(),
                                      _vm._v(" "),
                                      _c("textarea", {
                                        directives: [
                                          {
                                            name: "model",
                                            rawName: "v-model",
                                            value: _vm.form.file,
                                            expression: "form.file"
                                          }
                                        ],
                                        staticClass: "form-control",
                                        attrs: {
                                          rows: "10",
                                          id: "jsonText",
                                          placeholder:
                                            "paste or type JSON here..."
                                        },
                                        domProps: { value: _vm.form.file },
                                        on: {
                                          input: function($event) {
                                            if ($event.target.composing) {
                                              return
                                            }
                                            _vm.$set(
                                              _vm.form,
                                              "file",
                                              $event.target.value
                                            )
                                          }
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("pre", [
                                        _vm._v(_vm._s(_vm.prettyFormat))
                                      ])
                                    ])
                                  ])
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "woocommerce"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Website URL")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "woocommerce" },
                                        model: {
                                          value: _vm.form.woocommerce_url,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "woocommerce_url",
                                              $$v
                                            )
                                          },
                                          expression: "form.woocommerce_url"
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "woocommerce"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Website Consumer Key")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "woocommerce" },
                                        model: {
                                          value:
                                            _vm.form.woocommerce_consumer_key,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "woocommerce_consumer_key",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "form.woocommerce_consumer_key"
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "woocommerce"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Website Consumer Secret")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "woocommerce" },
                                        model: {
                                          value:
                                            _vm.form
                                              .woocommerce_consumer_secret,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "woocommerce_consumer_secret",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "form.woocommerce_consumer_secret"
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "shopify"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Shop name")
                                      ]),
                                      _vm._v(" "),
                                      _c(
                                        "el-input",
                                        {
                                          attrs: { placeholder: "shopify url" },
                                          model: {
                                            value: _vm.form.shopify_url,
                                            callback: function($$v) {
                                              _vm.$set(
                                                _vm.form,
                                                "shopify_url",
                                                $$v
                                              )
                                            },
                                            expression: "form.shopify_url"
                                          }
                                        },
                                        [
                                          _c("template", { slot: "prepend" }, [
                                            _vm._v("https://")
                                          ]),
                                          _vm._v(" "),
                                          _c("template", { slot: "append" }, [
                                            _vm._v(".myshopify.com")
                                          ])
                                        ],
                                        2
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "shopify"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Shopify Api Key")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: { placeholder: "shopify key" },
                                        model: {
                                          value: _vm.form.shopify_key,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "shopify_key",
                                              $$v
                                            )
                                          },
                                          expression: "form.shopify_key"
                                        }
                                      })
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.api_data.value == "shopify"
                                ? _c(
                                    "v-flex",
                                    { attrs: { sm6: "" } },
                                    [
                                      _c("label", { attrs: { for: "" } }, [
                                        _vm._v("Shopify Api Secret")
                                      ]),
                                      _vm._v(" "),
                                      _c("el-input", {
                                        attrs: {
                                          placeholder: "shopify secret"
                                        },
                                        model: {
                                          value: _vm.form.shopify_secret,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.form,
                                              "shopify_secret",
                                              $$v
                                            )
                                          },
                                          expression: "form.shopify_secret"
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
                  _vm.jsonerror == "" && _vm.api_data.value == "google_sheets"
                    ? _c(
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
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.api_data.value != "google_sheets"
                    ? _c(
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
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/settings/company/inc/api.vue":
/*!**************************************************************!*\
  !*** ./resources/js/components/settings/company/inc/api.vue ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _api_vue_vue_type_template_id_cd174694_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./api.vue?vue&type=template&id=cd174694&scoped=true& */ "./resources/js/components/settings/company/inc/api.vue?vue&type=template&id=cd174694&scoped=true&");
/* harmony import */ var _api_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./api.vue?vue&type=script&lang=js& */ "./resources/js/components/settings/company/inc/api.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _api_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _api_vue_vue_type_template_id_cd174694_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _api_vue_vue_type_template_id_cd174694_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "cd174694",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/settings/company/inc/api.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/settings/company/inc/api.vue?vue&type=script&lang=js&":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/settings/company/inc/api.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_api_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./api.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/company/inc/api.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_api_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/settings/company/inc/api.vue?vue&type=template&id=cd174694&scoped=true&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/components/settings/company/inc/api.vue?vue&type=template&id=cd174694&scoped=true& ***!
  \*********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_api_vue_vue_type_template_id_cd174694_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./api.vue?vue&type=template&id=cd174694&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/settings/company/inc/api.vue?vue&type=template&id=cd174694&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_api_vue_vue_type_template_id_cd174694_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_api_vue_vue_type_template_id_cd174694_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);