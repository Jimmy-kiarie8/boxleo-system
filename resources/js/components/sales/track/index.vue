<template>
<div>
    <v-container fluid fill-height>
        <v-card style="padding: 20px 0">
            <v-layout justify-center align-center wrap>
                <v-flex sm12>
                    <el-breadcrumb separator-class="el-icon-arrow-right">
                        <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                        <el-breadcrumb-item>Sales</el-breadcrumb-item>
                    </el-breadcrumb>
                </v-flex>
                <v-flex sm12>
                    <!-- <myFilter :form="form" :user="user" style></myFilter> -->
                </v-flex>
                <v-flex sm12>
                    <v-layout row>
                        <v-flex sm12>
                            <h3 style="margin-left: 30px !important; margin-top: 10px">
                            {{this.$route.query.data}}    Orders
                            </h3>
                        </v-flex>
                    </v-layout>

                    <v-tooltip right>
                        <template v-slot:activator="{ on }">
                            <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getSales">
                                <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                            </v-btn>
                        </template>
                        <span>Refresh</span>
                    </v-tooltip>
                </v-flex>
                <v-flex sm12>
                    <v-pagination v-model="sales.current_page" :length="sales.last_page" total-visible="5" @input="next_page(sales.path, sales.current_page)" circle v-if="sales.last_page > 1"></v-pagination>
                </v-flex>
                <v-flex sm12 v-if="!$route.query.data">
                    <v-tabs v-model="tab" background-color="indigo" dark @change="getSales">
                        <v-tab> Scheduled </v-tab>
                        <v-tab> Dispatched </v-tab>
                        <v-tab> Pedding </v-tab>
                    </v-tabs>
                </v-flex>

                <v-flex sm12>
                    <v-data-table :headers="headers" :items="sales.data" :search="search">
                        <template v-slot:item.created_at="{ item }">
                            <el-tag type="warning">{{ item.created_at }}</el-tag>
                        </template>
                        <template v-slot:item.created_at="{ item }">
                            <el-tag type="warning">{{ item.created_at }}</el-tag>
                        </template>
                        <template v-slot:item.delivery_date="{ item }">
                            <el-tag type="info">{{ item.delivery_date }}</el-tag>
                        </template>
                        <template v-slot:item.sub_total="{ item }">
                            <el-tag type="info">{{ item.sub_total }}</el-tag>
                        </template>
                        <template v-slot:item.total="{ item }">
                            <el-tag type="error">{{ item.total }}</el-tag>
                        </template>
                        <template v-slot:item.delivery_status="{ item }">
                            <el-tag>{{ item.delivery_status }}</el-tag>
                        </template>
                        <template v-slot:item.actions="{ item }">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="updateStatus(item)" slot="activator"  v-if="user.can['Order update status']">
                                        <v-icon small color="blue darken-2">mdi-upload-network-outline</v-icon>
                                    </v-btn>
                                </template>
                                <span>Update order {{ item.order_no }} Status</span>
                            </v-tooltip>
                        </template>
                    </v-data-table>
                </v-flex>
            </v-layout>
        </v-card>
    </v-container>
    <myStatus />
</div>
</template>

<script>
import myStatus from "../status/Status";

import {
    mapState
} from "vuex";
export default {
    components: {
        // myShow,
        // myUpload,
        myStatus,
        // myGoogle,
        // myCustomView,
        // myBranch,
        // myRider
    },
    props: ["user"],
    data() {
        return {
            form: {},
            loader: false,
            search: "",
            checkedRows: [],
            tab: 0,
            headers: [{
                    text: "Created On",
                    value: "created_at",
                },
                {
                    text: "Order Number",
                    align: "start",
                    value: "order_no",
                },
                {
                    text: "Product Name",
                    value: "product_name",
                },
                {
                    text: "Special instructions",
                    value: "notes",
                },
                {
                    text: "Client Name",
                    value: "client.name",
                },
                {
                    text: "Client Phone",
                    value: "client.phone",
                },
                {
                    text: "Client Address",
                    value: "client.address",
                },
                {
                    text: "Vendor Name",
                    value: "seller.name",
                },
                {
                    text: "Delivery status",
                    value: "delivery_status",
                },
                {
                    text: "status",
                    value: "status",
                },
                {
                    text: "Delivery Date",
                    value: "delivery_date",
                },
                {
                    text: "Actions",
                    value: "actions",
                },
            ],
        };
    },
    filters: {
        columnHead(value) {
            return value.split("_").join(" ").toUpperCase();
        },
    },
    methods: {
        createOrder(data) {
            // eventBus.$emit("openEditProduct", data.row);

            this.$router.push({
                name: "create_order",
            });
        },
        updateStatus(data) {
            eventBus.$emit("orderStatusEvent", data);
        },
        deleteItem(item) {
            this.$store
                .dispatch("deleteItem", "sales/" + item.id)
                .then((response) => {
                    this.$message({
                        type: "success",
                        message: "Delete completed",
                    });
                    this.getSales();
                });
        },
        getSales() {
            if (this.tab == 0) {
                var model = "tracking/Scheduled";
            } else if (this.tab == 1) {
                var model = "tracking/Dispatched";
            } else {
                var model = "tracking/Pedding";
            }
            var payload = {
                model: model,
                update: "updateSaleList",
            };
            this.$store.dispatch("getItems", payload);
        },
        dashboardSales(data) {
            var model = "tracking/" + data;
            var payload = {
                model: model,
                update: "updateSaleList",
            };
            this.$store.dispatch("getItems", payload);
        },

        getWarehouses() {
            var payload = {
                model: "/warehouses",
                update: "updateWarehouseList",
            };
            this.$store.dispatch("getItems", payload);
        },
        getCustom() {
            var payload = {
                model: "/app_custom",
                update: "updateAppCustomList",
            };
            this.$store.dispatch("getItems", payload);
        },

        next_page(path, page) {
            var payload = {
                path: path,
                page: page,
                update: "updateSaleList",
            };
            this.$store.dispatch("nextPage", payload);
        },
    },
    computed: {
        ...mapState(["app_custom", "sales", "loading", "custom_sale"]),
    },
    mounted() {
        console.log(this.$route.query.data);

        if (this.$route.query.data) {
            this.dashboardSales(this.$route.query.data);
        } else {
            // this.$store.dispatch('getSales');
            eventBus.$emit("LoadingEvent");
            this.getSales();
        }
        this.getCustom();
    },
    created() {
        eventBus.$on("saleEvent", (data) => {
            this.getSales();
        });

        eventBus.$on("responseChooseEvent", (data) => {
            console.log(data);
            if (data == "Excel") {
                eventBus.$emit("openEditSale");
            } else {
                eventBus.$emit("openCreateSale");
            }
        });
    },

    //   beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //       if (vm.user.can["view sales"]) {
    //         next();
    //       } else {
    //         next("/");
    //       }
    //     });
    //   }
};
</script>

<style scoped>
.el-input--prefix .el-input__inner {
    border-radius: 50px !important;
}

.v-toolbar__content,
.v-toolbar__extension {
    height: auto !important;
}

.v-avatar {
    height: 10px !important;
    width: 10px !important;
    margin-left: 40% !important;
}

.theme--light.v-data-table>.v-data-table__wrapper>table>tbody>tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {
    cursor: pointer !important;
}
</style>
