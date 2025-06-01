(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/lux/examples"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/ExampleComponent.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/ExampleComponent.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var primevue_datatable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! primevue/datatable */ "./node_modules/primevue/datatable/index.js");
/* harmony import */ var primevue_datatable__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(primevue_datatable__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var primevue_column__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! primevue/column */ "./node_modules/primevue/column/index.js");
/* harmony import */ var primevue_column__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(primevue_column__WEBPACK_IMPORTED_MODULE_1__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    DataTable: primevue_datatable__WEBPACK_IMPORTED_MODULE_0___default.a,
    Column: primevue_column__WEBPACK_IMPORTED_MODULE_1___default.a
  },
  data: function data() {
    return {
      products: [{
        "id": "1000",
        "code": "f230fh0g3",
        "name": "Bamboo Watch",
        "description": "Product Description",
        "image": "bamboo-watch.jpg",
        "price": 65,
        "category": "Accessories",
        "quantity": 24,
        "inventoryStatus": "INSTOCK",
        "rating": 5
      }, {
        "id": "1001",
        "code": "nvklal433",
        "name": "Black Watch",
        "description": "Product Description",
        "image": "black-watch.jpg",
        "price": 72,
        "category": "Accessories",
        "quantity": 61,
        "inventoryStatus": "INSTOCK",
        "rating": 4
      }, {
        "id": "1002",
        "code": "zz21cz3c1",
        "name": "Blue Band",
        "description": "Product Description",
        "image": "blue-band.jpg",
        "price": 79,
        "category": "Fitness",
        "quantity": 2,
        "inventoryStatus": "LOWSTOCK",
        "rating": 3
      }, {
        "id": "1003",
        "code": "244wgerg2",
        "name": "Blue T-Shirt",
        "description": "Product Description",
        "image": "blue-t-shirt.jpg",
        "price": 29,
        "category": "Clothing",
        "quantity": 25,
        "inventoryStatus": "INSTOCK",
        "rating": 5
      }, {
        "id": "1004",
        "code": "h456wer53",
        "name": "Bracelet",
        "description": "Product Description",
        "image": "bracelet.jpg",
        "price": 15,
        "category": "Accessories",
        "quantity": 73,
        "inventoryStatus": "INSTOCK",
        "rating": 4
      }, {
        "id": "1005",
        "code": "av2231fwg",
        "name": "Brown Purse",
        "description": "Product Description",
        "image": "brown-purse.jpg",
        "price": 120,
        "category": "Accessories",
        "quantity": 0,
        "inventoryStatus": "OUTOFSTOCK",
        "rating": 4
      }, {
        "id": "1006",
        "code": "bib36pfvm",
        "name": "Chakra Bracelet",
        "description": "Product Description",
        "image": "chakra-bracelet.jpg",
        "price": 32,
        "category": "Accessories",
        "quantity": 5,
        "inventoryStatus": "LOWSTOCK",
        "rating": 3
      }, {
        "id": "1007",
        "code": "mbvjkgip5",
        "name": "Galaxy Earrings",
        "description": "Product Description",
        "image": "galaxy-earrings.jpg",
        "price": 34,
        "category": "Accessories",
        "quantity": 23,
        "inventoryStatus": "INSTOCK",
        "rating": 5
      }, {
        "id": "1008",
        "code": "vbb124btr",
        "name": "Game Controller",
        "description": "Product Description",
        "image": "game-controller.jpg",
        "price": 99,
        "category": "Electronics",
        "quantity": 2,
        "inventoryStatus": "LOWSTOCK",
        "rating": 4
      }, {
        "id": "1009",
        "code": "cm230f032",
        "name": "Gaming Set",
        "description": "Product Description",
        "image": "gaming-set.jpg",
        "price": 299,
        "category": "Electronics",
        "quantity": 63,
        "inventoryStatus": "INSTOCK",
        "rating": 3
      }],
      loading: false,
      selectedCustomer1: null,
      selectedCustomer2: null,
      filters1: {},
      filters2: {},
      representatives: [{
        name: "Amy Elsner",
        image: 'amyelsner.png'
      }, {
        name: "Anna Fali",
        image: 'annafali.png'
      }, {
        name: "Asiya Javayant",
        image: 'asiyajavayant.png'
      }, {
        name: "Bernardo Dominic",
        image: 'bernardodominic.png'
      }, {
        name: "Elwin Sharvill",
        image: 'elwinsharvill.png'
      }, {
        name: "Ioni Bowcher",
        image: 'ionibowcher.png'
      }, {
        name: "Ivan Magalhaes",
        image: 'ivanmagalhaes.png'
      }, {
        name: "Onyama Limba",
        image: 'onyamalimba.png'
      }, {
        name: "Stephen Shaw",
        image: 'stephenshaw.png'
      }, {
        name: "XuXue Feng",
        image: 'xuxuefeng.png'
      }],
      statuses: ['unqualified', 'qualified', 'new', 'negotiation', 'renewal', 'proposal']
    };
  },
  productService: null,
  created: function created() {// this.productService = new ProductService();
  },
  mounted: function mounted() {// this.productService.getProductsSmall().then(data => this.products = data);
  },
  methods: {
    filterDate: function filterDate(value, filter) {
      if (filter === undefined || filter === null || typeof filter === 'string' && filter.trim() === '') {
        return true;
      }

      if (value === undefined || value === null) {
        return false;
      }

      return value === this.formatDate(filter);
    },
    formatDate: function formatDate(date) {
      var month = date.getMonth() + 1;
      var day = date.getDate();

      if (month < 10) {
        month = '0' + month;
      }

      if (day < 10) {
        day = '0' + day;
      }

      return date.getFullYear() + '-' + month + '-' + day;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/ExampleComponent.vue?vue&type=template&id=299e239e&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/ExampleComponent.vue?vue&type=template&id=299e239e& ***!
  \*******************************************************************************************************************************************************************************************************************/
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
  return _c("div", [
    _c(
      "div",
      { staticClass: "card" },
      [
        _c(
          "DataTable",
          {
            staticClass: "p-datatable-customers",
            attrs: {
              value: _vm.products,
              paginator: true,
              rows: 4,
              dataKey: "id",
              filters: _vm.filters2,
              filterDisplay: "row",
              loading: _vm.loading,
              responsiveLayout: "scroll",
              globalFilterFields: [
                "code",
                "description",
                "price",
                "category",
                "quantity",
              ],
            },
            on: {
              "update:filters": function ($event) {
                _vm.filters2 = $event
              },
            },
            scopedSlots: _vm._u([
              {
                key: "empty",
                fn: function () {
                  return [
                    _vm._v(
                      "\n                No customers found.\n            "
                    ),
                  ]
                },
                proxy: true,
              },
              {
                key: "loading",
                fn: function () {
                  return [
                    _vm._v(
                      "\n                Loading customers data. Please wait.\n            "
                    ),
                  ]
                },
                proxy: true,
              },
            ]),
          },
          [
            _vm._v(" "),
            _vm._v(" "),
            _c("Column", {
              attrs: {
                field: "name",
                header: "Name",
                styles: { "min-width": "12rem" },
              },
              scopedSlots: _vm._u([
                {
                  key: "body",
                  fn: function (ref) {
                    var data = ref.data
                    return [
                      _vm._v(
                        "\n                    " +
                          _vm._s(data.name) +
                          "\n                "
                      ),
                    ]
                  },
                },
                {
                  key: "filter",
                  fn: function (ref) {
                    var filterModel = ref.filterModel
                    var filterCallback = ref.filterCallback
                    return [
                      _c("InputText", {
                        directives: [
                          {
                            name: "tooltip",
                            rawName: "v-tooltip.top.focus",
                            value: "Hit enter key to filter",
                            expression: "'Hit enter key to filter'",
                            modifiers: { top: true, focus: true },
                          },
                        ],
                        staticClass: "p-column-filter",
                        attrs: {
                          type: "text",
                          placeholder:
                            "Search by name - " + filterModel.matchMode,
                        },
                        on: {
                          keydown: function ($event) {
                            if (
                              !$event.type.indexOf("key") &&
                              _vm._k(
                                $event.keyCode,
                                "enter",
                                13,
                                $event.key,
                                "Enter"
                              )
                            ) {
                              return null
                            }
                            return filterCallback()
                          },
                        },
                        model: {
                          value: filterModel.value,
                          callback: function ($$v) {
                            _vm.$set(filterModel, "value", $$v)
                          },
                          expression: "filterModel.value",
                        },
                      }),
                    ]
                  },
                },
              ]),
            }),
            _vm._v(" "),
            _c("Column", {
              attrs: {
                header: "Country",
                filterField: "country.name",
                styles: { "min-width": "12rem" },
              },
              scopedSlots: _vm._u([
                {
                  key: "body",
                  fn: function (ref) {
                    var data = ref.data
                    return [
                      _c("img", {
                        class: "flag flag-" + data.country.code,
                        attrs: {
                          src: __webpack_require__(!(function webpackMissingModule() { var e = new Error("Cannot find module '../../assets/images/flag_placeholder.png'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())),
                          width: "30",
                        },
                      }),
                      _vm._v(" "),
                      _c("span", { staticClass: "image-text" }, [
                        _vm._v(_vm._s(data.country.name)),
                      ]),
                    ]
                  },
                },
                {
                  key: "filter",
                  fn: function (ref) {
                    var filterModel = ref.filterModel
                    var filterCallback = ref.filterCallback
                    return [
                      _c("InputText", {
                        directives: [
                          {
                            name: "tooltip",
                            rawName: "v-tooltip.top.focus",
                            value: "Filter as you type",
                            expression: "'Filter as you type'",
                            modifiers: { top: true, focus: true },
                          },
                        ],
                        staticClass: "p-column-filter",
                        attrs: {
                          type: "text",
                          placeholder: "Search by country",
                        },
                        on: {
                          input: function ($event) {
                            return filterCallback()
                          },
                        },
                        model: {
                          value: filterModel.value,
                          callback: function ($$v) {
                            _vm.$set(filterModel, "value", $$v)
                          },
                          expression: "filterModel.value",
                        },
                      }),
                    ]
                  },
                },
              ]),
            }),
            _vm._v(" "),
            _c("Column", {
              attrs: {
                header: "Agent",
                filterField: "representative",
                showFilterMenu: false,
                styles: { "min-width": "14rem" },
              },
              scopedSlots: _vm._u([
                {
                  key: "body",
                  fn: function (ref) {
                    var data = ref.data
                    return [
                      _c("img", {
                        staticStyle: { "vertical-align": "middle" },
                        attrs: {
                          alt: data.representative.name,
                          src:
                            "demo/images/avatar/" + data.representative.image,
                          width: "32",
                        },
                      }),
                      _vm._v(" "),
                      _c("span", { staticClass: "image-text" }, [
                        _vm._v(_vm._s(data.representative.name)),
                      ]),
                    ]
                  },
                },
                {
                  key: "filter",
                  fn: function (ref) {
                    var filterModel = ref.filterModel
                    var filterCallback = ref.filterCallback
                    return [
                      _c("MultiSelect", {
                        staticClass: "p-column-filter",
                        attrs: {
                          options: _vm.representatives,
                          optionLabel: "name",
                          placeholder: "Any",
                        },
                        on: {
                          change: function ($event) {
                            return filterCallback()
                          },
                        },
                        scopedSlots: _vm._u(
                          [
                            {
                              key: "option",
                              fn: function (slotProps) {
                                return [
                                  _c(
                                    "div",
                                    {
                                      staticClass:
                                        "p-multiselect-representative-option",
                                    },
                                    [
                                      _c("img", {
                                        staticStyle: {
                                          "vertical-align": "middle",
                                        },
                                        attrs: {
                                          alt: slotProps.option.name,
                                          src:
                                            "demo/images/avatar/" +
                                            slotProps.option.image,
                                          width: "32",
                                        },
                                      }),
                                      _vm._v(" "),
                                      _c(
                                        "span",
                                        { staticClass: "image-text" },
                                        [_vm._v(_vm._s(slotProps.option.name))]
                                      ),
                                    ]
                                  ),
                                ]
                              },
                            },
                          ],
                          null,
                          true
                        ),
                        model: {
                          value: filterModel.value,
                          callback: function ($$v) {
                            _vm.$set(filterModel, "value", $$v)
                          },
                          expression: "filterModel.value",
                        },
                      }),
                    ]
                  },
                },
              ]),
            }),
            _vm._v(" "),
            _c("Column", {
              attrs: {
                field: "status",
                header: "Status",
                showFilterMenu: false,
                styles: { "min-width": "12rem" },
              },
              scopedSlots: _vm._u([
                {
                  key: "body",
                  fn: function (ref) {
                    var data = ref.data
                    return [
                      _c(
                        "span",
                        { class: "customer-badge status-" + data.status },
                        [_vm._v(_vm._s(data.status))]
                      ),
                    ]
                  },
                },
                {
                  key: "filter",
                  fn: function (ref) {
                    var filterModel = ref.filterModel
                    var filterCallback = ref.filterCallback
                    return [
                      _c("Dropdown", {
                        staticClass: "p-column-filter",
                        attrs: {
                          options: _vm.statuses,
                          placeholder: "Any",
                          showClear: true,
                        },
                        on: {
                          change: function ($event) {
                            return filterCallback()
                          },
                        },
                        scopedSlots: _vm._u(
                          [
                            {
                              key: "value",
                              fn: function (slotProps) {
                                return [
                                  slotProps.value
                                    ? _c(
                                        "span",
                                        {
                                          class:
                                            "customer-badge status-" +
                                            slotProps.value,
                                        },
                                        [_vm._v(_vm._s(slotProps.value))]
                                      )
                                    : _c("span", [
                                        _vm._v(_vm._s(slotProps.placeholder)),
                                      ]),
                                ]
                              },
                            },
                            {
                              key: "option",
                              fn: function (slotProps) {
                                return [
                                  _c(
                                    "span",
                                    {
                                      class:
                                        "customer-badge status-" +
                                        slotProps.option,
                                    },
                                    [_vm._v(_vm._s(slotProps.option))]
                                  ),
                                ]
                              },
                            },
                          ],
                          null,
                          true
                        ),
                        model: {
                          value: filterModel.value,
                          callback: function ($$v) {
                            _vm.$set(filterModel, "value", $$v)
                          },
                          expression: "filterModel.value",
                        },
                      }),
                    ]
                  },
                },
              ]),
            }),
            _vm._v(" "),
            _c("Column", {
              attrs: {
                field: "verified",
                header: "Verified",
                dataType: "boolean",
                styles: { "min-width": "6rem" },
              },
              scopedSlots: _vm._u([
                {
                  key: "body",
                  fn: function (ref) {
                    var data = ref.data
                    return [
                      _c("i", {
                        staticClass: "pi",
                        class: {
                          "true-icon pi-check-circle": data.verified,
                          "false-icon pi-times-circle": !data.verified,
                        },
                      }),
                    ]
                  },
                },
                {
                  key: "filter",
                  fn: function (ref) {
                    var filterModel = ref.filterModel
                    var filterCallback = ref.filterCallback
                    return [
                      _c("TriStateCheckbox", {
                        on: {
                          change: function ($event) {
                            return filterCallback()
                          },
                        },
                        model: {
                          value: filterModel.value,
                          callback: function ($$v) {
                            _vm.$set(filterModel, "value", $$v)
                          },
                          expression: "filterModel.value",
                        },
                      }),
                    ]
                  },
                },
              ]),
            }),
          ],
          1
        ),
      ],
      1
    ),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/ExampleComponent.vue":
/*!******************************************************!*\
  !*** ./resources/js/components/ExampleComponent.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ExampleComponent_vue_vue_type_template_id_299e239e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ExampleComponent.vue?vue&type=template&id=299e239e& */ "./resources/js/components/ExampleComponent.vue?vue&type=template&id=299e239e&");
/* harmony import */ var _ExampleComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ExampleComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/ExampleComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ExampleComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ExampleComponent_vue_vue_type_template_id_299e239e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ExampleComponent_vue_vue_type_template_id_299e239e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/ExampleComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/ExampleComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/ExampleComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ExampleComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./ExampleComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/ExampleComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ExampleComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/ExampleComponent.vue?vue&type=template&id=299e239e&":
/*!*************************************************************************************!*\
  !*** ./resources/js/components/ExampleComponent.vue?vue&type=template&id=299e239e& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ExampleComponent_vue_vue_type_template_id_299e239e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./ExampleComponent.vue?vue&type=template&id=299e239e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/ExampleComponent.vue?vue&type=template&id=299e239e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ExampleComponent_vue_vue_type_template_id_299e239e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ExampleComponent_vue_vue_type_template_id_299e239e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);