<template>
  <div>
    <v-container fluid fill-height>
      <v-layout justify-center align-center wrap>
        <!-- Breadcrumb Section -->
        <v-flex sm12>
          <v-card style="padding: 20px 0">
            <el-breadcrumb separator-class="el-icon-arrow-right">
              <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
              <el-breadcrumb-item>Dispatch</el-breadcrumb-item>
            </el-breadcrumb>
          </v-card>
        </v-flex>

        <!-- Main Content Card with Tabs -->
        <v-flex sm12>
          <v-card>
            <v-tabs v-model="activeTab" background-color="primary" dark slider-color="yellow">
              <v-tab key="dispatch">Dispatch Management</v-tab>
              <v-tab key="undispatch">Undispatch</v-tab>
              <v-tab key="batch">Batch Undispatch</v-tab>
            </v-tabs>

            <v-tabs-items v-model="activeTab">
              <!-- TAB 1: Dispatch Management -->
              <v-tab-item key="dispatch">
                <v-btn color="primary" @click="getRemainingOrders">
                  Remaining Orders
                </v-btn>

                <v-card flat>
                  <v-card-text>
                    <!-- Filter Controls -->
                    <v-layout row wrap style="margin-left: 5px">
                      <v-flex sm2>
                        <label for="">Zone from</label>
                        <el-select v-model="form.zone_from" placeholder="Select" filterable clearable>
                          <el-option v-for="item in zones" :key="item.id" :label="item.name"
                            :value="item.id"></el-option>
                        </el-select>
                        <small class="has-text-danger" v-if="errors.zone_from">{{ errors.zone_from[0] }}</small>
                      </v-flex>

                      <v-flex sm2>
                        <label for="">Zone to</label>
                        <el-select v-model="form.zone_to" placeholder="Select" filterable clearable multiple>
                          <el-option v-for="item in zones" :key="item.id" :label="item.name"
                            :value="item.id"></el-option>
                        </el-select>
                        <small class="has-text-danger" v-if="errors.zone_to">{{
                          errors.zone_to[0]
                          }}</small>
                      </v-flex>

                      <v-flex sm2>
                        <label for="">Rider</label>
                        <el-select v-model="form.rider_id" placeholder="Select" filterable clearable multiple>
                          <el-option v-for="item in riders" :key="item.id" :label="item.name"
                            :value="item.id"></el-option>
                        </el-select>
                        <small class="has-text-danger" v-if="errors.rider_id">{{
                          errors.rider_id[0]
                          }}</small>
                      </v-flex>

                      <v-flex sm2>
                        <label for="">Courier</label>
                        <el-select v-model="form.courier_id" placeholder="Courier" clearable filterable
                          style="width: 100%">
                          <el-option v-for="item in couriers" :key="item.id" :label="item.name"
                            :value="item.id"></el-option>
                        </el-select>
                        <small class="has-text-danger" v-if="errors.courier_id">{{ errors.courier_id[0] }}</small>
                      </v-flex>

                      <v-flex sm2>
                        <label for="">Vendor</label>
                        <el-select v-model="form.vendor" placeholder="Vendor" clearable filterable style="width: 100%">
                          <el-option v-for="item in sellers.data" :key="item.id" :label="item.name"
                            :value="item.id"></el-option>
                        </el-select>
                        <small class="has-text-danger" v-if="errors.vendor">{{
                          errors.vendor[0]
                          }}</small>
                      </v-flex>

                      <v-flex sm2>
                        <label>Status</label>
                        <el-select v-model="form.status" filterable clearable placeholder="Select" style="width: 100%"
                          multiple>
                          <el-option v-for="(item, index) in statuses" :key="index" :label="item.value"
                            :value="item.value"></el-option>
                        </el-select>
                        <small class="has-text-danger" v-if="errors.status">{{
                          errors.status[0]
                          }}</small>
                      </v-flex>

                      <v-flex sm3>
                        <label style="float: left; width: 100%">Dispatched on</label>
                        <el-date-picker v-model="form.dispatched_on" type="daterange" align="right"
                          start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%"
                          format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                        </el-date-picker>
                        <small class="has-text-danger" v-if="errors.dispatched_on">{{ errors.dispatched_on[0] }}</small>
                      </v-flex>

                      <!-- Action Buttons -->
                      <v-flex sm2 style="margin-left: 5px">
                        <v-btn-toggle mandatory style="margin-top: 20px">
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn icon v-bind="attrs" v-on="on" @click="filter" color="primary">
                                <v-icon small color="grey">mdi-magnify</v-icon>
                              </v-btn>
                            </template>
                            <span>Filter</span>
                          </v-tooltip>

                          <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn icon v-bind="attrs" v-on="on" @click="dispatch_list" color="primary">
                                <v-icon small color="grey">mdi-download</v-icon>
                              </v-btn>
                            </template>
                            <span>Download Dispatch list</span>
                          </v-tooltip>

                          <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn icon v-bind="attrs" v-on="on" @click="dispatchList" color="primary">
                                <v-icon small color="grey">mdi-file-pdf-box</v-icon>
                              </v-btn>
                            </template>
                            <span>Download dispatch list</span>
                          </v-tooltip>

                          <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn icon v-bind="attrs" v-on="on" @click="pickingList" color="primary">
                                <v-icon small color="grey">mdi-printer</v-icon>
                              </v-btn>
                            </template>
                            <span>Download Picking list</span>
                          </v-tooltip>

                          <v-tooltip bottom v-if="
                            dispatch_sales.length > 0 &&
                            user.can['Order dispatch']
                          ">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn icon v-bind="attrs" v-on="on" @click="dispatchItems('Dispatched')" color="primary">
                                <v-icon small color="grey">mdi-send</v-icon>
                              </v-btn>
                            </template>
                            <span>Dispatch Orders</span>
                          </v-tooltip>

                          <v-btn>
                            <download-excel :data="dispatch_sales" :fields="json_fields" name="dispatch.xls">
                              <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                  <v-btn icon v-on="on" slot="activator" class="mx-0">
                                    <v-icon>mdi-file-excel</v-icon>
                                  </v-btn>
                                </template>
                                <span>Download report</span>
                              </v-tooltip>
                            </download-excel>
                          </v-btn>

                          <v-tooltip bottom v-if="
                            dispatch_sales.length > 0 &&
                            user.can['Order dispatch']
                          ">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn icon v-bind="attrs" v-on="on" @click="dispatchItems('In Transit')" color="primary">
                                <v-icon small color="success">mdi-checkbox-marked</v-icon>
                              </v-btn>
                            </template>
                            <span>Send to Intransit</span>
                          </v-tooltip>
                        </v-btn-toggle>
                      </v-flex>
                    </v-layout>

                    <v-divider class="my-3"></v-divider>

                    <!-- Search and Data Table -->
                    <v-flex sm12>
                      <v-text-field v-model="search" append-icon="mdi-magnify" label="Quick Search" single-line
                        hide-details class="mb-4">
                      </v-text-field>

                      <v-data-table :headers="headers" :items="dispatch_sales" :search="search" :loading="loading"
                        :single-select="singleSelect" item-key="id" show-select class="elevation-1" v-model="selected">
                        <template v-slot:top>

                          <v-tooltip bottom v-if="selected.length > 0">
                            <template v-slot:activator="{ on }">
                              <v-btn v-on="on" icon class="mx-0" @click="re_dispatch()" slot="activator">
                                <v-icon small color="blue darken-2">mdi-clipboard-text</v-icon>
                              </v-btn>
                            </template>
                            <span>Re-dispatch orders</span>
                          </v-tooltip>
                        </template>

                        <template v-slot:item.actions="{ item }" v-if="user.can['Order view']">
                          <v-tooltip bottom v-if="user.can['Order update status']">
                            <template v-slot:activator="{ on }">
                              <v-btn v-on="on" icon class="mx-0" @click="undispatch(item)" slot="activator">
                                <v-icon small color="blue darken-2">mdi-clipboard-text</v-icon>
                              </v-btn>
                            </template>
                            <span>Undispatch Orders</span>
                          </v-tooltip>

                        </template>
                      </v-data-table>
                    </v-flex>
                  </v-card-text>
                </v-card>
              </v-tab-item>

              <!-- TAB 2: Undispatch -->
              <v-tab-item key="undispatch">
                <v-card flat :loading="loading">
                  <v-card-text>
                    <v-layout row>
                      <v-flex sm12>
                        <v-card-title>Undispatch Individual Orders</v-card-title>
                        <v-text-field v-model="form.search" append-icon="mdi-magnify" label="Scan"
                          @keyup.enter="undispatch_order()" ref="focusMe">
                        </v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-card-text>
                </v-card>
              </v-tab-item>

              <!-- TAB 3: Batch Undispatch -->
              <v-tab-item key="batch">
                <v-card flat>
                  <v-card-text>
                    <v-card-title>Undispatch Multiple Orders</v-card-title>
                    <v-flex sm12>
                      <v-text-field v-model="undispatched_search" append-icon="mdi-magnify" label="Scan"
                        @keyup.enter="undispatchOrder()" ref="focusMe">
                      </v-text-field>

                      <v-btn color="info" @click="unDispatchItems" :disabled="undispatch_sales.length === 0"
                        class="mb-4">
                        Undispatch Selected Orders
                      </v-btn>

                      <v-data-table :headers="headers2" :items="undispatch_sales" :loading="loading" item-key="id"
                        class="elevation-1">
                        <template v-slot:item.customer_notes="props">
                          <v-edit-dialog :return-value.sync="props.item.customer_notes" large persistent
                            @cancel="cancel" @open="open_dialog" @close="close" @save="update_data(props.item)">
                            {{ props.item.customer_notes }}
                            <template v-slot:input>
                              <div class="mt-4 title" style="color: #333">
                                Notes
                              </div>
                              <el-select allow-create v-model="props.item.customer_notes" filterable clearable
                                placeholder="Select" style="width: 100%">
                                <el-option v-for="(item, index) in notes" :key="index" :label="item" :value="item">
                                </el-option>
                              </el-select>
                            </template>
                          </v-edit-dialog>
                        </template>
                      </v-data-table>
                    </v-flex>
                  </v-card-text>
                </v-card>
              </v-tab-item>
            </v-tabs-items>
          </v-card>
        </v-flex>
      </v-layout>

      <!-- Hidden Forms for Downloads -->
      <form action="/dispatch_list" method="post" ref="dispatch_list" target="_blank">
        <input type="hidden" name="_token" :value="csrf" />
        <input type="hidden" name="packages" :value="serialize_data" />
      </form>
      <form action="/dispatchList" method="post" ref="dispatchList" target="_blank">
        <input type="hidden" name="_token" :value="csrf" />
        <input type="hidden" name="packages" :value="serialize_data" />
      </form>
      <form action="/pickingList" method="post" ref="pickingList" target="_blank">
        <input type="hidden" name="_token" :value="csrf" />
        <input type="hidden" name="packages" :value="serialize_data" />
      </form>
    </v-container>

    <!-- Component Imports -->
    <myStatus />
    <myMStatus />
    <unDispatch />
  </div>
</template>

<script>
import { mapState } from "vuex";
import myStatus from "../../../sales/status/Status";
import myMStatus from "../../../sales/status/status_mult.vue";
import unDispatch from "./undipatch.vue";

export default {
  props: ["user"],
  components: {
    myStatus,
    myMStatus,
    unDispatch,
  },
  data() {
    return {
      activeTab: "dispatch", // Add this line for tabs management
      notes: [
        "Cancelled",
        "Not picking",
        "Busy",
        "Will call back",
        "Not Around",
        "Not financially ready",
        "Dublicate order",
        "Dublicate of already delivered",
        "Wrong order",
        "Office pickup",
        "Not Dispatched",
      ],
      search: "",
      undispatched_search: "",
      singleSelect: false,
      selected: [],
      undispatch_sales: [],
      undispatch_sales_select: [],
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      form: {
        zone_from: null,
        zone_to: null,
        rider_id: null,
        status: null,
        start_date: null,
        end_date: null,
      },
      statuses: [
        { value: "Dispatched" },
        { value: "Undispatched" },
        { value: "In Transit" },
        { value: "Awaiting Return" },
        { value: "Awaiting Dispatch" },
        { value: "Return received" },
        { value: "Returned" },
        { value: "Delivered" },
      ],
      headers: [
        { text: "Created On", value: "created_at" },
        { text: "Order no", value: "order_no" },
        { text: "Client Name", value: "client_name" },
        { text: "Client Address", value: "client.address" },
        { text: "Client Phone", value: "client.phone" },
        { text: "Delivery Status", value: "delivery_status" },
        { text: "Total", value: "total_price" },
        { text: "Actions", value: "actions", sortable: false },
      ],
      headers2: [
        { text: "Created On", value: "created_at" },
        { text: "Order no", value: "order_no" },
        { text: "Client Name", value: "client_name" },
        { text: "Client Address", value: "client.address" },
        { text: "Client Phone", value: "client.phone" },
        { text: "Delivery Status", value: "delivery_status" },
        { text: "Notes", value: "customer_notes" },
        { text: "Actions", value: "actions", sortable: false },
      ],
      json_fields: {
        "Order Number": "order_no",
        "Client Name": "client.name",
        Vendor: "seller.name",
        "Client Address": "client.address",
        "Client Phone": "client.phone",
        "Delivery status": "delivery_status",
        "Product Name": "product_name",
        "Cod amount": "total_price",
        Remarks: "customer_notes",
        "Return Notes": "return_notes",
      },
    };
  },

  methods: {
    undispatch_order() {
      var payload = {
        model: "undispatch",
        update: "updateSaleList",
        search: this.form.search,
      };
      this.$store
        .dispatch("searchItems", payload)
        .then((response) => {
          const data = {
            data: response.data,
            id: response.data.id,
            multiple: false
          }
          eventBus.$emit("unDispatchEvent", data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    open_dialog() { },
    cancel() { },
    close() { },

    getRemainingOrders() {
      var payload = {
        model: "remaining_orders",
        update: "updateDispatchList",
        data: this.form,
      };
      this.$store.dispatch("filterItems", payload);
    },

    undispatchOrder() {
      var payload = {
        model: "undispatch",
        update: "updateSaleList",
        search: this.undispatched_search,
      };
      this.$store
        .dispatch("searchItems", payload)
        .then((response) => {
          console.log(response);
          if (
            !this.undispatch_sales.some((item) => item.id === response.data.id)
          ) {
            this.undispatch_sales.push(response.data);
            this.undispatched_search = "";
          } else {
            this.$message({
              type: "error",
              message: "Order already scanned",
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    dispatchItems(status) {
      const form = {
        orders: this.dispatch_sales,
        status: status,
        zone_from: this.form.zone_from,
        zone_to: this.form.zone_to,
        rider_id: this.form.rider_id,
        courier_id: this.form.courier_id,
      };
      var payload = {
        model: "in_transit",
        data: form,
      };
      this.$store.dispatch("postItems", payload).then((res) => {
        // Success handling
      });
    },
    unDispatchItems() {
      var payload = {
        model: "undispatch_sales",
        data: this.undispatch_sales.map((item) => ({
          id: item.id,
          customer_notes: item.customer_notes,
        })),
      };
      this.$store.dispatch("postItems", payload).then((res) => {
        this.undispatch_sales = [];
        this.search = "";
      });
    },
    orderStatus() {
      eventBus.$emit("multStatusEvent", this.selected);
    },
    updateStatus(data) {
      eventBus.$emit("orderStatusEvent", data);
    },
    undispatch(item) {
      const data = {
        data: item,
        multiple: false
      }
      eventBus.$emit("unDispatchEvent", data);
    },
    re_dispatch() {
      const selectedIds = this.selected.map(item => item.id);
      const data = {
        data: selectedIds,
        multiple: true
      }
      eventBus.$emit("unDispatchEvent", data);
    },
    dispatch_list() {
      this.$refs.dispatch_list.submit();
    },
    dispatchList() {
      this.$refs.dispatchList.submit();
    },
    pickingList() {
      this.$refs.pickingList.submit();
    },
    getZones() {
      var payload = {
        model: "zones",
        update: "updateZone",
      };
      this.$store.dispatch("getItems", payload);
    },
    getRiders() {
      var payload = {
        model: "rider/riders",
        update: "updateRidersList",
      };
      this.$store.dispatch("getItems", payload);
    },
    filter() {
      var payload = {
        model: "dispatch_filter",
        update: "updateDispatchList",
        data: this.form,
      };
      this.$store.dispatch("filterItems", payload);
    },
    getCouriers() {
      var payload = {
        model: "couriers",
        update: "updateCouriersList",
      };
      this.$store.dispatch("getItems", payload);
    },
    getSellers() {
      var payload = {
        model: "/seller/sellers",
        update: "updateSellerList",
      };
      this.$store.dispatch("getItems", payload);
    },
    resetSales() {
      var payload = {
        item: "sales",
        update: "updateDispatchList",
      };
      this.$store.dispatch("resetItems", payload);
    },
  },
  computed: {
    ...mapState([
      "zones",
      "loading",
      "riders",
      "dispatch_sales",
      "errors",
      "couriers",
      "sellers",
    ]),
    serialize_data() {
      return JSON.stringify(this.form);
    },
  },
  mounted() {
    this.getCouriers();
    this.getZones();
    this.getRiders();
    this.getSellers();
  },
  created() {
    eventBus.$on("saleEvent", (data) => {
      this.filter();
    });
  },
};
</script>

<style scoped>
.has-text-danger {
  font-size: 10px !important;
}

.v-card {
  margin-bottom: 16px;
}

.v-tab {
  text-transform: none;
  font-weight: 500;
}

.v-card-title {
  font-size: 1.25rem;
  font-weight: 500;
  padding-bottom: 12px;
}
</style>
