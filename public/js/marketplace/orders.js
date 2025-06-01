(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/marketplace/orders"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_sales_Show__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../components/sales/Show */ "./resources/js/components/sales/Show.vue");
/* harmony import */ var _components_sales_status_Status__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../components/sales/status/Status */ "./resources/js/components/sales/status/Status.vue");
/* harmony import */ var _components_sales_filter_filter__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../components/sales/filter/filter */ "./resources/js/components/sales/filter/filter.vue");
/* harmony import */ var _components_sales_setup__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../components/sales/setup */ "./resources/js/components/sales/setup.vue");
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  props: ['user', 'tenant'],
  components: {
    myShow: _components_sales_Show__WEBPACK_IMPORTED_MODULE_0__["default"],
    myStatus: _components_sales_status_Status__WEBPACK_IMPORTED_MODULE_1__["default"],
    myFilter: _components_sales_filter_filter__WEBPACK_IMPORTED_MODULE_2__["default"],
    mySetup: _components_sales_setup__WEBPACK_IMPORTED_MODULE_3__["default"]
  },
  inject: ['theme'],
  data: function data() {
    return {
      singleSelect: false,
      selected: [],
      order_item: {
        search: ''
      },
      filter_form: {
        app_custom_id: null
      },
      form: {},
      ready: false,
      deleted_orders: false,
      search: "",
      checkedRows: [],
      columns: [{
        label: "Created On",
        field: "created_at"
      }, {
        label: "Client Name",
        field: "client_name"
      }, {
        label: "Created By",
        field: "user_name"
      }, {
        label: "Sub total",
        field: "sub_total"
      }, {
        label: "Discount",
        field: "discount"
      }, {
        label: "Total",
        field: "total_price"
      }, {
        label: "Charges",
        field: "shipping_charges"
      }, {
        label: "Printed",
        field: "printed"
      }, {
        label: "Actions",
        field: "actions",
        sortable: false
      }],
      snack: false,
      setup_loaded: false,
      default_filter: true
    };
  },
  filters: {
    columnHead: function columnHead(value) {
      return value.split('_').join(' ').toUpperCase();
    }
  },
  methods: {
    update_data: function update_data(data) {
      // console.log(data);
      var payload = {
        data: data,
        model: 'order_update',
        id: data.id
      };
      this.$store.dispatch('patchItems', payload).then(function (response) {// this.goToSales()
      });
    },
    cancel: function cancel() {// this.snack = true
      // this.snackColor = 'error'
      // this.snackText = 'Canceled'
    },
    open_dialog: function open_dialog() {// this.snack = true
      // this.snackColor = 'info'
      // this.snackText = 'Dialog opened'
    },
    close: function close() {
      console.log('Dialog closed');
    },
    open: function open(printed, id) {
      var _this = this;

      this.$prompt('Please enter reason for re-printing', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel'
      }).then(function (_ref) {
        var value = _ref.value;

        _this.print_change(printed, id, value);
      })["catch"](function () {// this.$message({
        //     type: 'error',
        //     message: 'error'
        // });
      });
    },
    print_change: function print_change(printed, id, comment) {
      var _this2 = this;

      // console.log(printed, id);
      var payload = {
        model: '/print_change',
        id: id,
        data: {
          printed: printed,
          comment: comment
        }
      };
      this.$store.dispatch("patchItems", payload).then(function (response) {
        _this2.refreshSales();
      });
    },
    search_order: function search_order() {
      this.default_filter = false;
      var payload = {
        model: 'order_search',
        update: 'updateSaleList',
        search: this.order_item.search
      };
      this.$store.dispatch('searchItems', payload);
    },
    printOrder: function printOrder() {},
    openOrder: function openOrder(data) {
      console.log(data);
      var id = data.id;
      this.$router.push({
        name: "saleorder",
        params: {
          id: id
        }
      });
      this.getWarehouses();
    },
    openShow: function openShow(data) {
      eventBus.$emit("openShowSale", data);
    },
    googleUpload: function googleUpload() {
      eventBus.$emit("GoogleUploadEvent");
    },
    // shopifyUpload() {
    //     eventBus.$emit("ShopifyUploadEvent");
    // },
    updateStatus: function updateStatus(data) {
      eventBus.$emit("orderStatusEvent", data);
    },
    assign: function assign(data, event) {
      eventBus.$emit(event, data);
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
    },
    get_orders: function get_orders(data) {
      var _this3 = this;

      console.log(data);

      if (data) {
        var payload = {
          model: '/app_custom',
          id: data,
          update: 'updateSaleList'
        };
        this.$store.dispatch("showItem", payload).then(function (response) {
          _this3.columns = response.data.columns;
        });
      } else {
        this.getSales();
      }
    },
    confirm_delete: function confirm_delete(item) {
      var _this4 = this;

      this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning'
      }).then(function () {
        _this4.deleteItem(item);
      })["catch"](function () {
        _this4.$message({
          type: 'error',
          message: 'Delete canceled'
        });
      });
    },
    restore_order: function restore_order(item) {
      var _this5 = this;

      this.$confirm('This will restore this order. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning'
      }).then(function () {
        _this5.restoreItem(item);
      })["catch"](function () {
        _this5.$message({
          type: 'error',
          message: 'Restore canceled'
        });
      });
    },
    restoreItem: function restoreItem(item) {
      var _this6 = this;

      var payload = {
        model: 'sales_restore',
        data: {},
        id: item.id
      };
      this.$store.dispatch("patchItems", payload).then(function (response) {
        // this.$message({
        //     type: 'success',
        //     message: 'Order restored'
        // });
        _this6.deletedOrders();
      });
    },
    customView: function customView() {
      eventBus.$emit("openCustomViewEvent");
    },
    uploadOrder: function uploadOrder(data) {
      eventBus.$emit("openOrderUploadEvent", data.row);
    },
    createOrder: function createOrder(data) {
      // eventBus.$emit("openEditProduct", data.row);
      this.$router.push({
        name: "create_order"
      });
    },
    deleteItem: function deleteItem(item) {
      var _this7 = this;

      this.$store.dispatch("deleteItem", "sales/" + item.id).then(function (response) {
        _this7.$message({
          type: 'success',
          message: 'Delete completed'
        });

        _this7.get_orders();
      });
    },
    deleteItems: function deleteItems() {
      var _this8 = this;

      this.$confirm('This will delete the Order. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning'
      }).then(function () {
        // this.deleteItem(item)
        _this8.get_orders();

        var payload = {
          model: '/sales_delete',
          data: _this8.selected
        };

        _this8.$store.dispatch("postItems", payload).then(function (response) {
          // this.$message({
          //     type: 'success',
          //     message: 'Delete completed'
          // });
          _this8.get_orders();
        });
      })["catch"](function () {
        _this8.$message({
          type: 'error',
          message: 'Delete canceled'
        });
      });
    },
    getSales: function getSales() {
      var _this9 = this;

      var payload = {
        model: 'sale_filter',
        update: 'updateSaleList',
        data: this.form
      };
      this.$store.dispatch('filterItems', payload).then(function (res) {
        _this9.deleted_orders = false;
        _this9.ready = true;
      }); // var payload = {
      //     model: 'sales',
      //     update: 'updateSaleList'
      // }
      // this.$store.dispatch("getItems", payload).then((res) => {
      //     this.deleted_orders = false
      //     this.ready = true
      // })
    },
    refreshSales: function refreshSales() {
      if (!this.default_filter) {
        this.default_filter = true;
        eventBus.$emit('filterItemsEvent');
      } else {
        this.deleted_orders = false;
        var data = {
          path: this.sales.sales.path,
          page: this.sales.sales.current_page
        };
        console.log(data);
        eventBus.$emit('refreshEvent', data);
      }
    },
    deletedOrders: function deletedOrders() {
      var _this10 = this;

      var payload = {
        model: 'deleted_sales',
        update: 'updateSaleList'
      };
      this.$store.dispatch("getItems", payload).then(function (res) {
        _this10.deleted_orders = true;
        _this10.ready = true;
      });
    },
    get_filter: function get_filter(data) {
      var _this11 = this;

      // console.log(data);
      var payload = {
        model: '/columns',
        id: this.user.id,
        update: 'updateFilterList'
      };
      this.$store.dispatch("showItem", payload).then(function (response) {
        _this11.deleted_orders = false;
        _this11.form.app_custom_id = response.data.id;
        _this11.filter_form.app_custom_id = response.data.id; // console.log(response.data);

        if (response.data > 0) {
          _this11.get_orders(response.data.id);
        }
      });
    },
    getWarehouses: function getWarehouses() {
      var payload = {
        model: '/warehouses',
        update: 'updateWarehouseList'
      };
      this.$store.dispatch("getItems", payload);
    },
    getCustom: function getCustom() {
      var payload = {
        model: '/app_custom',
        update: 'updateAppCustomList'
      };
      this.$store.dispatch("getItems", payload);
    },
    next_page: function next_page(path, page) {
      var data = {
        path: path,
        page: page
      };
      eventBus.$emit('refreshNextEvent', data);
    },
    getSetup: function getSetup() {
      var _this12 = this;

      var payload = {
        model: '/setup',
        update: 'updateSetup'
      };
      this.$store.dispatch("getItems", payload).then(function (res) {
        _this12.setup_loaded = true;
      });
    }
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_4__["mapState"])(['app_custom', 'sales', 'loading', 'custom_sale', 'setup'])),
  mounted: function mounted() {
    // this.$store.dispatch('getSales');
    eventBus.$emit("LoadingEvent");
    this.getSales();
    this.getCustom();
    this.getSetup();
  },
  created: function created() {
    var _this13 = this;

    // this.getSales();
    this.getCustom();
    this.get_filter();
    eventBus.$on("saleEvent", function (data) {
      _this13.refreshSales(); // this.getSales();

    });
    eventBus.$on("statusChangeEvent", function (data) {
      for (var _index = 0; _index < _this13.sales.sales.data.length; _index++) {
        var element = _this13.sales.sales.data[_index];

        if (element.id == data.id) {
          var order = element;
        }
      }

      var index = _this13.sales.sales.data.indexOf(order);

      var payload = {
        data: data,
        index: index
      };

      _this13.$store.dispatch("updateStatus", payload); // this.sales.sales.index.set(index, data)

    });
    eventBus.$on("responseChooseEvent", function (data) {
      console.log(data);

      if (data == "Excel") {
        eventBus.$emit("openEditSale");
      } else {
        eventBus.$emit("openCreateSale");
      }
    });
  } //   beforeRouteEnter(to, from, next) {
  //     next(vm => {
  //       if (vm.user.can["view sales"]) {
  //         next();
  //       } else {
  //         next("/");
  //       }
  //     });
  //   }

});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=style&index=0&lang=css&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=style&index=0&lang=css& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.el-input--prefix .el-input__inner {\r\n    border-radius: 50px !important;\n}\n.v-toolbar__content,\r\n.v-toolbar__extension {\r\n    height: auto !important;\n}\n.v-avatar {\r\n    height: 10px !important;\r\n    width: 10px !important;\r\n    margin-left: 40% !important;\n}\n.theme--light.v-data-table>.v-data-table__wrapper>table>tbody>tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {\r\n    cursor: pointer !important;\n}\n#orders td {\r\n    padding: 0 0 0 15px !important;\n}\n#orders .v-data-table>.v-data-table__wrapper>table {\r\n    width: 150% !important;\n}\n#address_tab span {\r\n    font-style: inherit;\r\n    font-weight: inherit;\r\n    white-space: nowrap;\r\n    text-overflow: ellipsis;\r\n    max-width: 180px;\r\n    overflow: hidden;\r\n    display: block;\r\n    -webkit-line-clamp: 3;\r\n    -webkit-box-orient: vertical;\n}\r\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=style&index=0&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=style&index=0&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=style&index=0&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=style&index=0&lang=css&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=template&id=a464446c&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=template&id=a464446c& ***!
  \***************************************************************************************************************************************************************************************************************************/
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
        { attrs: { fluid: "", "fill-height": "", id: "orders" } },
        [
          _vm.ready
            ? _c(
                "v-card",
                {
                  staticStyle: { padding: "20px 20px" },
                  attrs: { width: "100%" }
                },
                [
                  _vm.setup_loaded &&
                  (!_vm.setup.products || !_vm.setup.vendors)
                    ? _c("div", [_c("mySetup")], 1)
                    : _c(
                        "v-layout",
                        {
                          attrs: {
                            "justify-center": "",
                            "align-center": "",
                            wrap: ""
                          }
                        },
                        [
                          _c(
                            "v-flex",
                            { attrs: { sm12: "" } },
                            [
                              _c(
                                "el-breadcrumb",
                                {
                                  attrs: {
                                    "separator-class": "el-icon-arrow-right"
                                  }
                                },
                                [
                                  _c(
                                    "el-breadcrumb-item",
                                    { attrs: { to: { path: "/" } } },
                                    [_vm._v("Dashboard")]
                                  ),
                                  _vm._v(" "),
                                  _c("el-breadcrumb-item", [_vm._v("Sales")])
                                ],
                                1
                              )
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c("v-divider"),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm9: "" } },
                            [
                              _c("v-text-field", {
                                attrs: {
                                  label: "Track waybill",
                                  required: "",
                                  "prepend-icon": "mdi-magnify"
                                },
                                on: {
                                  keyup: function($event) {
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
                                    return _vm.search_order($event)
                                  }
                                },
                                model: {
                                  value: _vm.order_item.search,
                                  callback: function($$v) {
                                    _vm.$set(_vm.order_item, "search", $$v)
                                  },
                                  expression: "order_item.search"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c("v-flex", {
                            attrs: { sm2: "", "offset-sm1": "" }
                          }),
                          _vm._v(" "),
                          _c("myFilter", {
                            attrs: { form: _vm.filter_form, user: _vm.user }
                          }),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            {
                              staticStyle: { "margin-top": "30px" },
                              attrs: { sm12: "" }
                            },
                            [
                              _vm.sales.sales.last_page > 1
                                ? _c("v-pagination", {
                                    attrs: {
                                      length: _vm.sales.sales.last_page,
                                      "total-visible": "5",
                                      circle: ""
                                    },
                                    on: {
                                      input: function($event) {
                                        return _vm.next_page(
                                          _vm.sales.sales.path,
                                          _vm.sales.sales.current_page
                                        )
                                      }
                                    },
                                    model: {
                                      value: _vm.sales.sales.current_page,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.sales.sales,
                                          "current_page",
                                          $$v
                                        )
                                      },
                                      expression: "sales.sales.current_page"
                                    }
                                  })
                                : _vm._e()
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-flex",
                            { attrs: { sm12: "" } },
                            [
                              _c("v-text-field", {
                                attrs: {
                                  "append-icon": "mdi-magnify",
                                  label: "Quick Search",
                                  "single-line": "",
                                  "hide-details": ""
                                },
                                model: {
                                  value: _vm.search,
                                  callback: function($$v) {
                                    _vm.search = $$v
                                  },
                                  expression: "search"
                                }
                              }),
                              _vm._v(" "),
                              _c("v-data-table", {
                                staticClass: "elevation-1",
                                staticStyle: { "font-size": "10px !important" },
                                attrs: {
                                  headers: _vm.sales.columns,
                                  items: _vm.sales.sales.data,
                                  search: _vm.search,
                                  "single-select": _vm.singleSelect,
                                  "item-key": "id",
                                  "show-select": "",
                                  loading: _vm.loading
                                },
                                scopedSlots: _vm._u(
                                  [
                                    {
                                      key: "top",
                                      fn: function() {
                                        return [
                                          _c(
                                            "v-tooltip",
                                            {
                                              attrs: { right: "" },
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
                                                              staticClass:
                                                                "mx-0",
                                                              attrs: {
                                                                slot:
                                                                  "activator",
                                                                icon: ""
                                                              },
                                                              on: {
                                                                click:
                                                                  _vm.refreshSales
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
                                                                  color:
                                                                    "blue darken-2",
                                                                  small: ""
                                                                }
                                                              },
                                                              [
                                                                _vm._v(
                                                                  "mdi-refresh"
                                                                )
                                                              ]
                                                            )
                                                          ],
                                                          1
                                                        )
                                                      ]
                                                    }
                                                  }
                                                ],
                                                null,
                                                false,
                                                3244470444
                                              )
                                            },
                                            [
                                              _vm._v(" "),
                                              _c("span", [_vm._v("Refresh")])
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-tooltip",
                                            {
                                              attrs: { right: "" },
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
                                                              staticClass:
                                                                "mx-0",
                                                              attrs: {
                                                                slot:
                                                                  "activator",
                                                                icon: ""
                                                              },
                                                              on: {
                                                                click:
                                                                  _vm.deletedOrders
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
                                                                  color:
                                                                    "blue darken-2",
                                                                  small: ""
                                                                }
                                                              },
                                                              [
                                                                _vm._v(
                                                                  "mdi-delete-sweep-outline"
                                                                )
                                                              ]
                                                            )
                                                          ],
                                                          1
                                                        )
                                                      ]
                                                    }
                                                  }
                                                ],
                                                null,
                                                false,
                                                2220292649
                                              )
                                            },
                                            [
                                              _vm._v(" "),
                                              _c("span", [
                                                _vm._v("Deleted Orders")
                                              ])
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _vm.selected.length > 0 &&
                                          _vm.user.can["Order delete"] &&
                                          !_vm.deleted_orders
                                            ? _c(
                                                "v-tooltip",
                                                {
                                                  attrs: { right: "" },
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
                                                                  staticClass:
                                                                    "mx-0",
                                                                  attrs: {
                                                                    slot:
                                                                      "activator",
                                                                    icon: ""
                                                                  },
                                                                  on: {
                                                                    click:
                                                                      _vm.deleteItems
                                                                  },
                                                                  slot:
                                                                    "activator"
                                                                },
                                                                on
                                                              ),
                                                              [
                                                                _c(
                                                                  "v-icon",
                                                                  {
                                                                    attrs: {
                                                                      color:
                                                                        "red darken-2",
                                                                      small: ""
                                                                    }
                                                                  },
                                                                  [
                                                                    _vm._v(
                                                                      "mdi-delete"
                                                                    )
                                                                  ]
                                                                )
                                                              ],
                                                              1
                                                            )
                                                          ]
                                                        }
                                                      }
                                                    ],
                                                    null,
                                                    false,
                                                    656046575
                                                  )
                                                },
                                                [
                                                  _vm._v(" "),
                                                  _c("span", [
                                                    _vm._v(
                                                      "Delete selected orders"
                                                    )
                                                  ])
                                                ]
                                              )
                                            : _vm._e()
                                        ]
                                      },
                                      proxy: true
                                    },
                                    {
                                      key: "item.created_at",
                                      fn: function(ref) {
                                        var item = ref.item
                                        return [
                                          _c(
                                            "el-tag",
                                            { attrs: { type: "warning" } },
                                            [_vm._v(_vm._s(item.created_at))]
                                          )
                                        ]
                                      }
                                    },
                                    {
                                      key: "item.delivery_date",
                                      fn: function(ref) {
                                        var item = ref.item
                                        return [
                                          _c(
                                            "el-tag",
                                            { attrs: { type: "info" } },
                                            [_vm._v(_vm._s(item.delivery_date))]
                                          )
                                        ]
                                      }
                                    },
                                    {
                                      key: "item.sub_total",
                                      fn: function(ref) {
                                        var item = ref.item
                                        return [
                                          _c(
                                            "el-tag",
                                            { attrs: { type: "info" } },
                                            [_vm._v(_vm._s(item.sub_total))]
                                          )
                                        ]
                                      }
                                    },
                                    {
                                      key: "item.total",
                                      fn: function(ref) {
                                        var item = ref.item
                                        return [
                                          _c(
                                            "el-tag",
                                            { attrs: { type: "error" } },
                                            [_vm._v(_vm._s(item.total))]
                                          )
                                        ]
                                      }
                                    },
                                    {
                                      key: "item.delivery_status",
                                      fn: function(ref) {
                                        var item = ref.item
                                        return [
                                          item.delivery_status == "Delivered"
                                            ? _c(
                                                "el-tag",
                                                { attrs: { type: "success" } },
                                                [
                                                  _vm._v(
                                                    _vm._s(item.delivery_status)
                                                  )
                                                ]
                                              )
                                            : item.delivery_status ==
                                              "Dispatched"
                                            ? _c(
                                                "el-tag",
                                                { attrs: { type: "info" } },
                                                [
                                                  _vm._v(
                                                    _vm._s(item.delivery_status)
                                                  )
                                                ]
                                              )
                                            : item.delivery_status ==
                                              "Scheduled"
                                            ? _c(
                                                "el-tag",
                                                { attrs: { type: "info" } },
                                                [
                                                  _vm._v(
                                                    _vm._s(item.delivery_status)
                                                  )
                                                ]
                                              )
                                            : item.delivery_status ==
                                                "Returned" ||
                                              item.delivery_status ==
                                                "Cancelled"
                                            ? _c(
                                                "el-tag",
                                                { attrs: { type: "danger" } },
                                                [
                                                  _vm._v(
                                                    _vm._s(item.delivery_status)
                                                  )
                                                ]
                                              )
                                            : _c(
                                                "el-tag",
                                                { attrs: { type: "warning" } },
                                                [
                                                  _vm._v(
                                                    _vm._s(item.delivery_status)
                                                  )
                                                ]
                                              )
                                        ]
                                      }
                                    },
                                    {
                                      key: "item.paid",
                                      fn: function(ref) {
                                        var item = ref.item
                                        return [
                                          _c(
                                            "el-tooltip",
                                            {
                                              attrs: {
                                                content: item.paid
                                                  ? "Paid"
                                                  : "Not paid",
                                                placement: "top"
                                              }
                                            },
                                            [
                                              _c("v-avatar", {
                                                staticStyle: {
                                                  cursor: "pointer"
                                                },
                                                attrs: {
                                                  color: item.paid
                                                    ? "green"
                                                    : "red",
                                                  small: ""
                                                }
                                              })
                                            ],
                                            1
                                          )
                                        ]
                                      }
                                    },
                                    {
                                      key: "item.printed",
                                      fn: function(ref) {
                                        var item = ref.item
                                        return [
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
                                                        _vm.user.can[
                                                          "Order print edit"
                                                        ]
                                                          ? _c(
                                                              "v-btn",
                                                              _vm._g(
                                                                {
                                                                  staticClass:
                                                                    "mx-0",
                                                                  attrs: {
                                                                    slot:
                                                                      "activator",
                                                                    icon: ""
                                                                  },
                                                                  on: {
                                                                    click: function(
                                                                      $event
                                                                    ) {
                                                                      return _vm.open(
                                                                        item.printed,
                                                                        item.id
                                                                      )
                                                                    }
                                                                  },
                                                                  slot:
                                                                    "activator"
                                                                },
                                                                on
                                                              ),
                                                              [
                                                                item.printed
                                                                  ? _c(
                                                                      "v-icon",
                                                                      {
                                                                        attrs: {
                                                                          small:
                                                                            "",
                                                                          color:
                                                                            "green"
                                                                        }
                                                                      },
                                                                      [
                                                                        _vm._v(
                                                                          "mdi-check-circle"
                                                                        )
                                                                      ]
                                                                    )
                                                                  : _c(
                                                                      "v-icon",
                                                                      {
                                                                        attrs: {
                                                                          small:
                                                                            "",
                                                                          color:
                                                                            "grey darken-2"
                                                                        }
                                                                      },
                                                                      [
                                                                        _vm._v(
                                                                          "mdi-check-circle"
                                                                        )
                                                                      ]
                                                                    )
                                                              ],
                                                              1
                                                            )
                                                          : _c(
                                                              "v-btn",
                                                              _vm._g(
                                                                {
                                                                  staticClass:
                                                                    "mx-0",
                                                                  attrs: {
                                                                    slot:
                                                                      "activator",
                                                                    icon: ""
                                                                  },
                                                                  slot:
                                                                    "activator"
                                                                },
                                                                on
                                                              ),
                                                              [
                                                                item.printed
                                                                  ? _c(
                                                                      "v-icon",
                                                                      {
                                                                        attrs: {
                                                                          small:
                                                                            "",
                                                                          color:
                                                                            "green"
                                                                        }
                                                                      },
                                                                      [
                                                                        _vm._v(
                                                                          "mdi-check-circle"
                                                                        )
                                                                      ]
                                                                    )
                                                                  : _c(
                                                                      "v-icon",
                                                                      {
                                                                        attrs: {
                                                                          small:
                                                                            "",
                                                                          color:
                                                                            "grey darken-2"
                                                                        }
                                                                      },
                                                                      [
                                                                        _vm._v(
                                                                          "mdi-check-circle"
                                                                        )
                                                                      ]
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
                                            [
                                              _vm._v(" "),
                                              item.printed
                                                ? _c("span", [
                                                    _vm._v(
                                                      "Mark order as not printed "
                                                    )
                                                  ])
                                                : _c("span", [
                                                    _vm._v(
                                                      "Mark order as printed "
                                                    )
                                                  ])
                                            ]
                                          )
                                        ]
                                      }
                                    },
                                    {
                                      key: "item.mpesa_code",
                                      fn: function(ref) {
                                        var item = ref.item
                                        return [
                                          _c("el-tag", [
                                            _vm._v(_vm._s(item.mpesa_code))
                                          ])
                                        ]
                                      }
                                    },
                                    _vm.user.can["Order view"]
                                      ? {
                                          key: "item.actions",
                                          fn: function(ref) {
                                            var item = ref.item
                                            return [
                                              _c(
                                                "div",
                                                {
                                                  staticStyle: {
                                                    "min-width": "240px"
                                                  }
                                                },
                                                [
                                                  _vm.user.can["Order edit"] &&
                                                  !_vm.deleted_orders
                                                    ? _c(
                                                        "v-tooltip",
                                                        {
                                                          attrs: { bottom: "" },
                                                          scopedSlots: _vm._u(
                                                            [
                                                              {
                                                                key:
                                                                  "activator",
                                                                fn: function(
                                                                  ref
                                                                ) {
                                                                  var on =
                                                                    ref.on
                                                                  return [
                                                                    _c(
                                                                      "v-btn",
                                                                      _vm._g(
                                                                        {
                                                                          staticClass:
                                                                            "mx-0",
                                                                          attrs: {
                                                                            slot:
                                                                              "activator",
                                                                            icon:
                                                                              ""
                                                                          },
                                                                          on: {
                                                                            click: function(
                                                                              $event
                                                                            ) {
                                                                              return _vm.openEdit(
                                                                                item
                                                                              )
                                                                            }
                                                                          },
                                                                          slot:
                                                                            "activator"
                                                                        },
                                                                        on
                                                                      ),
                                                                      [
                                                                        _c(
                                                                          "v-icon",
                                                                          {
                                                                            attrs: {
                                                                              small:
                                                                                "",
                                                                              color:
                                                                                "blue darken-2"
                                                                            }
                                                                          },
                                                                          [
                                                                            _vm._v(
                                                                              "mdi-pencil"
                                                                            )
                                                                          ]
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
                                                        [
                                                          _vm._v(" "),
                                                          _c("span", [
                                                            _vm._v(
                                                              "Edit order " +
                                                                _vm._s(
                                                                  item.order_no
                                                                )
                                                            )
                                                          ])
                                                        ]
                                                      )
                                                    : _vm._e(),
                                                  _vm._v(" "),
                                                  _vm.user.can[
                                                    "Order update status"
                                                  ] && !_vm.deleted_orders
                                                    ? _c(
                                                        "v-tooltip",
                                                        {
                                                          attrs: { bottom: "" },
                                                          scopedSlots: _vm._u(
                                                            [
                                                              {
                                                                key:
                                                                  "activator",
                                                                fn: function(
                                                                  ref
                                                                ) {
                                                                  var on =
                                                                    ref.on
                                                                  return [
                                                                    _c(
                                                                      "v-btn",
                                                                      _vm._g(
                                                                        {
                                                                          staticClass:
                                                                            "mx-0",
                                                                          attrs: {
                                                                            slot:
                                                                              "activator",
                                                                            icon:
                                                                              ""
                                                                          },
                                                                          on: {
                                                                            click: function(
                                                                              $event
                                                                            ) {
                                                                              return _vm.updateStatus(
                                                                                item
                                                                              )
                                                                            }
                                                                          },
                                                                          slot:
                                                                            "activator"
                                                                        },
                                                                        on
                                                                      ),
                                                                      [
                                                                        _c(
                                                                          "v-icon",
                                                                          {
                                                                            attrs: {
                                                                              small:
                                                                                "",
                                                                              color:
                                                                                "blue darken-2"
                                                                            }
                                                                          },
                                                                          [
                                                                            _vm._v(
                                                                              "mdi-upload-network-outline"
                                                                            )
                                                                          ]
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
                                                        [
                                                          _vm._v(" "),
                                                          _c("span", [
                                                            _vm._v(
                                                              "Update order " +
                                                                _vm._s(
                                                                  item.order_no
                                                                ) +
                                                                " Status"
                                                            )
                                                          ])
                                                        ]
                                                      )
                                                    : _vm._e(),
                                                  _vm._v(" "),
                                                  _c(
                                                    "v-tooltip",
                                                    {
                                                      attrs: { bottom: "" },
                                                      scopedSlots: _vm._u(
                                                        [
                                                          _vm.user.can[
                                                            "Order view details"
                                                          ]
                                                            ? {
                                                                key:
                                                                  "activator",
                                                                fn: function(
                                                                  ref
                                                                ) {
                                                                  var on =
                                                                    ref.on
                                                                  return [
                                                                    _c(
                                                                      "v-btn",
                                                                      _vm._g(
                                                                        {
                                                                          staticClass:
                                                                            "mx-0",
                                                                          attrs: {
                                                                            slot:
                                                                              "activator",
                                                                            icon:
                                                                              ""
                                                                          },
                                                                          on: {
                                                                            click: function(
                                                                              $event
                                                                            ) {
                                                                              return _vm.openOrder(
                                                                                item
                                                                              )
                                                                            }
                                                                          },
                                                                          slot:
                                                                            "activator"
                                                                        },
                                                                        on
                                                                      ),
                                                                      [
                                                                        _c(
                                                                          "v-icon",
                                                                          {
                                                                            attrs: {
                                                                              small:
                                                                                "",
                                                                              color:
                                                                                "blue darken-2"
                                                                            }
                                                                          },
                                                                          [
                                                                            _vm._v(
                                                                              "mdi-eye"
                                                                            )
                                                                          ]
                                                                        )
                                                                      ],
                                                                      1
                                                                    )
                                                                  ]
                                                                }
                                                              }
                                                            : null
                                                        ],
                                                        null,
                                                        true
                                                      )
                                                    },
                                                    [
                                                      _vm._v(" "),
                                                      _c("span", [
                                                        _vm._v(
                                                          "Order " +
                                                            _vm._s(
                                                              item.order_no
                                                            ) +
                                                            " details"
                                                        )
                                                      ])
                                                    ]
                                                  ),
                                                  _vm._v(" "),
                                                  _vm.user.can[
                                                    "Order print edit"
                                                  ] &&
                                                  !item.printed &&
                                                  !_vm.deleted_orders
                                                    ? _c(
                                                        "v-tooltip",
                                                        {
                                                          attrs: { bottom: "" },
                                                          scopedSlots: _vm._u(
                                                            [
                                                              {
                                                                key:
                                                                  "activator",
                                                                fn: function(
                                                                  ref
                                                                ) {
                                                                  var on =
                                                                    ref.on
                                                                  return [
                                                                    _c(
                                                                      "v-btn",
                                                                      _vm._g(
                                                                        {
                                                                          staticClass:
                                                                            "mx-0",
                                                                          attrs: {
                                                                            slot:
                                                                              "activator",
                                                                            icon:
                                                                              "",
                                                                            href:
                                                                              "/pack_download/" +
                                                                              item.id,
                                                                            target:
                                                                              "_blank"
                                                                          },
                                                                          slot:
                                                                            "activator"
                                                                        },
                                                                        on
                                                                      ),
                                                                      [
                                                                        _c(
                                                                          "v-icon",
                                                                          {
                                                                            attrs: {
                                                                              small:
                                                                                "",
                                                                              color:
                                                                                "blue darken-2"
                                                                            }
                                                                          },
                                                                          [
                                                                            _vm._v(
                                                                              "mdi-printer"
                                                                            )
                                                                          ]
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
                                                        [
                                                          _vm._v(" "),
                                                          _c("span", [
                                                            _vm._v(
                                                              "Print " +
                                                                _vm._s(
                                                                  item.order_no
                                                                )
                                                            )
                                                          ])
                                                        ]
                                                      )
                                                    : _vm._e(),
                                                  _vm._v(" "),
                                                  _vm.user.can[
                                                    "Order delete"
                                                  ] && !_vm.deleted_orders
                                                    ? _c(
                                                        "v-tooltip",
                                                        {
                                                          attrs: { bottom: "" },
                                                          scopedSlots: _vm._u(
                                                            [
                                                              {
                                                                key:
                                                                  "activator",
                                                                fn: function(
                                                                  ref
                                                                ) {
                                                                  var on =
                                                                    ref.on
                                                                  return [
                                                                    _c(
                                                                      "v-btn",
                                                                      _vm._g(
                                                                        {
                                                                          staticClass:
                                                                            "mx-0",
                                                                          attrs: {
                                                                            slot:
                                                                              "activator",
                                                                            icon:
                                                                              ""
                                                                          },
                                                                          on: {
                                                                            click: function(
                                                                              $event
                                                                            ) {
                                                                              return _vm.confirm_delete(
                                                                                item
                                                                              )
                                                                            }
                                                                          },
                                                                          slot:
                                                                            "activator"
                                                                        },
                                                                        on
                                                                      ),
                                                                      [
                                                                        _c(
                                                                          "v-icon",
                                                                          {
                                                                            attrs: {
                                                                              small:
                                                                                "",
                                                                              color:
                                                                                "pink darken-2"
                                                                            }
                                                                          },
                                                                          [
                                                                            _vm._v(
                                                                              "mdi-delete"
                                                                            )
                                                                          ]
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
                                                        [
                                                          _vm._v(" "),
                                                          _c("span", [
                                                            _vm._v(
                                                              "Delete order " +
                                                                _vm._s(
                                                                  item.order_no
                                                                )
                                                            )
                                                          ])
                                                        ]
                                                      )
                                                    : _vm._e(),
                                                  _vm._v(" "),
                                                  _vm.user.can[
                                                    "Order delete"
                                                  ] && _vm.deleted_orders
                                                    ? _c(
                                                        "v-tooltip",
                                                        {
                                                          attrs: { bottom: "" },
                                                          scopedSlots: _vm._u(
                                                            [
                                                              {
                                                                key:
                                                                  "activator",
                                                                fn: function(
                                                                  ref
                                                                ) {
                                                                  var on =
                                                                    ref.on
                                                                  return [
                                                                    _c(
                                                                      "v-btn",
                                                                      _vm._g(
                                                                        {
                                                                          staticClass:
                                                                            "mx-0",
                                                                          attrs: {
                                                                            slot:
                                                                              "activator",
                                                                            icon:
                                                                              ""
                                                                          },
                                                                          on: {
                                                                            click: function(
                                                                              $event
                                                                            ) {
                                                                              return _vm.restore_order(
                                                                                item
                                                                              )
                                                                            }
                                                                          },
                                                                          slot:
                                                                            "activator"
                                                                        },
                                                                        on
                                                                      ),
                                                                      [
                                                                        _c(
                                                                          "v-icon",
                                                                          {
                                                                            attrs: {
                                                                              small:
                                                                                "",
                                                                              color:
                                                                                "pink darken-2"
                                                                            }
                                                                          },
                                                                          [
                                                                            _vm._v(
                                                                              "mdi-history"
                                                                            )
                                                                          ]
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
                                                        [
                                                          _vm._v(" "),
                                                          _c("span", [
                                                            _vm._v(
                                                              "Delete order " +
                                                                _vm._s(
                                                                  item.order_no
                                                                )
                                                            )
                                                          ])
                                                        ]
                                                      )
                                                    : _vm._e()
                                                ],
                                                1
                                              )
                                            ]
                                          }
                                        }
                                      : null,
                                    _vm.user.can["Order edit"]
                                      ? {
                                          key: "item.client_phone",
                                          fn: function(props) {
                                            return [
                                              _c(
                                                "v-edit-dialog",
                                                {
                                                  attrs: {
                                                    "return-value":
                                                      props.item.client_phone,
                                                    large: "",
                                                    persistent: ""
                                                  },
                                                  on: {
                                                    "update:returnValue": function(
                                                      $event
                                                    ) {
                                                      return _vm.$set(
                                                        props.item,
                                                        "client_phone",
                                                        $event
                                                      )
                                                    },
                                                    "update:return-value": function(
                                                      $event
                                                    ) {
                                                      return _vm.$set(
                                                        props.item,
                                                        "client_phone",
                                                        $event
                                                      )
                                                    },
                                                    save: function($event) {
                                                      return _vm.update_data(
                                                        props.item
                                                      )
                                                    },
                                                    cancel: _vm.cancel,
                                                    open: _vm.open_dialog,
                                                    close: _vm.close
                                                  },
                                                  scopedSlots: _vm._u(
                                                    [
                                                      {
                                                        key: "input",
                                                        fn: function() {
                                                          return [
                                                            _c(
                                                              "div",
                                                              {
                                                                staticClass:
                                                                  "mt-4 title",
                                                                staticStyle: {
                                                                  color: "#333"
                                                                }
                                                              },
                                                              [
                                                                _vm._v(
                                                                  "\r\n                                        Update phone\r\n                                    "
                                                                )
                                                              ]
                                                            ),
                                                            _vm._v(" "),
                                                            _c("v-text-field", {
                                                              attrs: {
                                                                label: "Edit",
                                                                "single-line":
                                                                  "",
                                                                counter: "",
                                                                autofocus: ""
                                                              },
                                                              model: {
                                                                value:
                                                                  props.item
                                                                    .client_phone,
                                                                callback: function(
                                                                  $$v
                                                                ) {
                                                                  _vm.$set(
                                                                    props.item,
                                                                    "client_phone",
                                                                    $$v
                                                                  )
                                                                },
                                                                expression:
                                                                  "props.item.client_phone"
                                                              }
                                                            })
                                                          ]
                                                        },
                                                        proxy: true
                                                      }
                                                    ],
                                                    null,
                                                    true
                                                  )
                                                },
                                                [
                                                  _c("div", [
                                                    _vm._v(
                                                      _vm._s(
                                                        props.item.client_phone
                                                      )
                                                    )
                                                  ])
                                                ]
                                              )
                                            ]
                                          }
                                        }
                                      : null,
                                    _vm.user.can["Order edit"]
                                      ? {
                                          key: "item.client_name",
                                          fn: function(props) {
                                            return [
                                              _c(
                                                "v-edit-dialog",
                                                {
                                                  attrs: {
                                                    "return-value":
                                                      props.item.client_name,
                                                    large: "",
                                                    persistent: ""
                                                  },
                                                  on: {
                                                    "update:returnValue": function(
                                                      $event
                                                    ) {
                                                      return _vm.$set(
                                                        props.item,
                                                        "client_name",
                                                        $event
                                                      )
                                                    },
                                                    "update:return-value": function(
                                                      $event
                                                    ) {
                                                      return _vm.$set(
                                                        props.item,
                                                        "client_name",
                                                        $event
                                                      )
                                                    },
                                                    save: function($event) {
                                                      return _vm.update_data(
                                                        props.item
                                                      )
                                                    },
                                                    cancel: _vm.cancel,
                                                    open: _vm.open_dialog,
                                                    close: _vm.close
                                                  },
                                                  scopedSlots: _vm._u(
                                                    [
                                                      {
                                                        key: "input",
                                                        fn: function() {
                                                          return [
                                                            _c(
                                                              "div",
                                                              {
                                                                staticClass:
                                                                  "mt-4 title",
                                                                staticStyle: {
                                                                  color: "#333"
                                                                }
                                                              },
                                                              [
                                                                _vm._v(
                                                                  "\r\n                                        Update name\r\n                                    "
                                                                )
                                                              ]
                                                            ),
                                                            _vm._v(" "),
                                                            _c("v-text-field", {
                                                              attrs: {
                                                                label: "Edit",
                                                                "single-line":
                                                                  "",
                                                                counter: "",
                                                                autofocus: ""
                                                              },
                                                              model: {
                                                                value:
                                                                  props.item
                                                                    .client_name,
                                                                callback: function(
                                                                  $$v
                                                                ) {
                                                                  _vm.$set(
                                                                    props.item,
                                                                    "client_name",
                                                                    $$v
                                                                  )
                                                                },
                                                                expression:
                                                                  "props.item.client_name"
                                                              }
                                                            })
                                                          ]
                                                        },
                                                        proxy: true
                                                      }
                                                    ],
                                                    null,
                                                    true
                                                  )
                                                },
                                                [
                                                  _c("div", [
                                                    _vm._v(
                                                      _vm._s(
                                                        props.item.client_name
                                                      )
                                                    )
                                                  ])
                                                ]
                                              )
                                            ]
                                          }
                                        }
                                      : null,
                                    _vm.user.can["Order edit"]
                                      ? {
                                          key: "item.client_address",
                                          fn: function(props) {
                                            return [
                                              _c(
                                                "v-edit-dialog",
                                                {
                                                  attrs: {
                                                    "return-value":
                                                      props.item.client_address,
                                                    large: "",
                                                    persistent: ""
                                                  },
                                                  on: {
                                                    "update:returnValue": function(
                                                      $event
                                                    ) {
                                                      return _vm.$set(
                                                        props.item,
                                                        "client_address",
                                                        $event
                                                      )
                                                    },
                                                    "update:return-value": function(
                                                      $event
                                                    ) {
                                                      return _vm.$set(
                                                        props.item,
                                                        "client_address",
                                                        $event
                                                      )
                                                    },
                                                    save: function($event) {
                                                      return _vm.update_data(
                                                        props.item
                                                      )
                                                    },
                                                    cancel: _vm.cancel,
                                                    open: _vm.open_dialog,
                                                    close: _vm.close
                                                  },
                                                  scopedSlots: _vm._u(
                                                    [
                                                      {
                                                        key: "input",
                                                        fn: function() {
                                                          return [
                                                            _c(
                                                              "div",
                                                              {
                                                                staticClass:
                                                                  "mt-4 title",
                                                                staticStyle: {
                                                                  color: "#333"
                                                                }
                                                              },
                                                              [
                                                                _vm._v(
                                                                  "\r\n                                        Update address\r\n                                    "
                                                                )
                                                              ]
                                                            ),
                                                            _vm._v(" "),
                                                            _c("v-text-field", {
                                                              attrs: {
                                                                label: "Edit",
                                                                "single-line":
                                                                  "",
                                                                counter: "",
                                                                autofocus: ""
                                                              },
                                                              model: {
                                                                value:
                                                                  props.item
                                                                    .client_address,
                                                                callback: function(
                                                                  $$v
                                                                ) {
                                                                  _vm.$set(
                                                                    props.item,
                                                                    "client_address",
                                                                    $$v
                                                                  )
                                                                },
                                                                expression:
                                                                  "props.item.client_address"
                                                              }
                                                            })
                                                          ]
                                                        },
                                                        proxy: true
                                                      }
                                                    ],
                                                    null,
                                                    true
                                                  )
                                                },
                                                [
                                                  _c(
                                                    "span",
                                                    {
                                                      attrs: {
                                                        id: "address_tab"
                                                      }
                                                    },
                                                    [
                                                      _c(
                                                        "el-tooltip",
                                                        {
                                                          staticClass: "item",
                                                          attrs: {
                                                            effect: "dark",
                                                            content:
                                                              props.item
                                                                .client_address,
                                                            placement:
                                                              "top-start"
                                                          }
                                                        },
                                                        [
                                                          _c("span", [
                                                            _vm._v(
                                                              "\r\n                                            " +
                                                                _vm._s(
                                                                  props.item
                                                                    .client_address
                                                                ) +
                                                                "\r\n                                        "
                                                            )
                                                          ])
                                                        ]
                                                      )
                                                    ],
                                                    1
                                                  )
                                                ]
                                              )
                                            ]
                                          }
                                        }
                                      : null
                                  ],
                                  null,
                                  true
                                ),
                                model: {
                                  value: _vm.selected,
                                  callback: function($$v) {
                                    _vm.selected = $$v
                                  },
                                  expression: "selected"
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
            : _c(
                "v-sheet",
                {
                  staticClass: "px-3 pt-3 pb-3",
                  staticStyle: { width: "100vw" },
                  attrs: {
                    color:
                      "grey " + (_vm.theme.isDark ? "darken-2" : "lighten-4")
                  }
                },
                [
                  _c("v-skeleton-loader", {
                    staticClass: "mx-auto",
                    attrs: { "max-width": "900", type: "table" }
                  })
                ],
                1
              ),
          _vm._v(" "),
          _c("myShow"),
          _vm._v(" "),
          _c("myStatus")
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

/***/ "./resources/js/marketplace/shopifyapp/orders/index.vue":
/*!**************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/orders/index.vue ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_vue_vue_type_template_id_a464446c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.vue?vue&type=template&id=a464446c& */ "./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=template&id=a464446c&");
/* harmony import */ var _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.vue?vue&type=script&lang=js& */ "./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./index.vue?vue&type=style&index=0&lang=css& */ "./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _index_vue_vue_type_template_id_a464446c___WEBPACK_IMPORTED_MODULE_0__["render"],
  _index_vue_vue_type_template_id_a464446c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/marketplace/shopifyapp/orders/index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=script&lang=js&":
/*!***************************************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=style&index=0&lang=css&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=style&index=0&lang=css& ***!
  \***********************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=template&id=a464446c&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=template&id=a464446c& ***!
  \*********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_a464446c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=template&id=a464446c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/marketplace/shopifyapp/orders/index.vue?vue&type=template&id=a464446c&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_a464446c___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_a464446c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);