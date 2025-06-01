<template>
<div>
    <v-container fluid fill-height id="orders">
        <v-card style="padding: 20px 20px;" v-if="ready" width="100%">
            <div v-if="setup_loaded && (!setup.products || !setup.vendors)">
                <mySetup />
            </div>
            <v-layout justify-center align-center wrap v-else>
                <v-flex sm12>
                    <el-breadcrumb separator-class="el-icon-arrow-right">
                        <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                        <el-breadcrumb-item>Sales</el-breadcrumb-item>
                    </el-breadcrumb>
                </v-flex>
                <v-divider></v-divider>
                <v-flex sm9>
                    <v-text-field v-model="order_item.search" label="Track waybill" required prepend-icon="mdi-magnify" @keyup.enter="search_order"></v-text-field>
                </v-flex>
                <v-flex sm2 offset-sm1>
                </v-flex>
                <myFilter :form="filter_form" :user="user" />
                <v-flex sm12 style="margin-top: 30px">
                    <v-pagination v-model="sales.sales.current_page" :length="sales.sales.last_page" total-visible="5" @input="next_page(sales.sales.path, sales.sales.current_page)" circle v-if="sales.sales.last_page > 1"></v-pagination>
                </v-flex>
                <v-flex sm12>
                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Quick Search" single-line hide-details></v-text-field>
                    <v-data-table :headers="sales.columns" :items="sales.sales.data" :search="search" :single-select="singleSelect" item-key="id" show-select class="elevation-1" v-model="selected" :loading="loading" style="font-size: 10px !important">
                        <template v-slot:top>
                            <v-tooltip right>
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="refreshSales">
                                        <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                    </v-btn>
                                </template>
                                <span>Refresh</span>
                            </v-tooltip>

                            <v-tooltip right>
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="deletedOrders">
                                        <v-icon color="blue darken-2" small>mdi-delete-sweep-outline</v-icon>
                                    </v-btn>
                                </template>
                                <span>Deleted Orders</span>
                            </v-tooltip>

                            <v-tooltip right v-if="selected.length > 0 && user.can['Order delete'] && !deleted_orders">
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="deleteItems">
                                        <v-icon color="red darken-2" small>mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Delete selected orders</span>
                            </v-tooltip>
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
                            <el-tag v-if="item.delivery_status == 'Delivered'" type="success">{{ item.delivery_status }}</el-tag>
                            <el-tag v-else-if="item.delivery_status == 'Dispatched'" type="info">{{ item.delivery_status }}</el-tag>
                            <el-tag v-else-if="item.delivery_status == 'Scheduled'" type="info">{{ item.delivery_status }}</el-tag>
                            <el-tag v-else-if="item.delivery_status == 'Returned' || item.delivery_status == 'Cancelled'" type="danger">{{ item.delivery_status }}</el-tag>
                            <el-tag v-else type="warning">{{ item.delivery_status }}</el-tag>
                        </template>
                        <template v-slot:item.paid="{ item }">
                            <el-tooltip :content="(item.paid) ? 'Paid' : 'Not paid'" placement="top">
                                <v-avatar style="cursor: pointer" :color="(item.paid) ? 'green' : 'red'" small></v-avatar>
                            </el-tooltip>
                        </template>
                        <template v-slot:item.printed="{ item }">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" slot="activator" @click="open(item.printed, item.id)" v-if="user.can['Order print edit']">
                                        <v-icon small color="green" v-if="item.printed">mdi-check-circle</v-icon>
                                        <v-icon small color="grey darken-2" v-else>mdi-check-circle</v-icon>
                                    </v-btn>
                                    <v-btn v-on="on" icon class="mx-0" slot="activator" v-else>
                                        <v-icon small color="green" v-if="item.printed">mdi-check-circle</v-icon>
                                        <v-icon small color="grey darken-2" v-else>mdi-check-circle</v-icon>
                                    </v-btn>
                                </template>
                                <span v-if="item.printed">Mark order as not printed </span>
                                <span v-else>Mark order as printed </span>
                            </v-tooltip>
                        </template>

                        <template v-slot:item.mpesa_code="{ item }">
                            <el-tag>{{ item.mpesa_code }}</el-tag>
                        </template>

                        <template v-slot:item.actions="{ item }" v-if="user.can['Order view']">
                            <!-- <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="openShow(item)" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-eye</v-icon>
                                    </v-btn>
                                </template>
                                <span>View order {{ item.order_no }}</span>
                            </v-tooltip> -->
                            <div style="min-width: 240px">
                                <v-tooltip bottom v-if="user.can['Order edit'] && !deleted_orders">
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="openEdit(item)" slot="activator">
                                            <v-icon small color="blue darken-2">mdi-pencil</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Edit order {{ item.order_no }}</span>
                                </v-tooltip>
                                <v-tooltip bottom v-if="user.can['Order update status'] && !deleted_orders">
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="updateStatus(item)" slot="activator">
                                            <v-icon small color="blue darken-2">mdi-upload-network-outline</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Update order {{ item.order_no }} Status</span>
                                </v-tooltip>   <v-tooltip bottom>
                                    <template v-slot:activator="{ on }" v-if="user.can['Order view details']">
                                        <v-btn v-on="on" icon class="mx-0" @click="openOrder(item)" slot="activator">
                                            <v-icon small color="blue darken-2">mdi-eye</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Order {{ item.order_no }} details</span>
                                </v-tooltip>
                                <v-tooltip bottom v-if="user.can['Order print edit'] && !item.printed && !deleted_orders">
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" slot="activator" :href="'/pack_download/' + item.id" target="_blank">
                                            <v-icon small color="blue darken-2">mdi-printer</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Print {{ item.order_no }}</span>
                                </v-tooltip>
                                <v-tooltip bottom v-if="user.can['Order delete'] && !deleted_orders">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" class="mx-0" @click="confirm_delete(item)" slot="activator">
                                            <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Delete order {{ item.order_no }}</span>
                                </v-tooltip>

                                <v-tooltip bottom v-if="user.can['Order delete'] && deleted_orders">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" class="mx-0" @click="restore_order(item)" slot="activator">
                                            <v-icon small color="pink darken-2">mdi-history</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Delete order {{ item.order_no }}</span>
                                </v-tooltip>
                            </div>
                        </template>

                        <template v-slot:item.client_phone="props" v-if="user.can['Order edit']">
                            <v-edit-dialog :return-value.sync="props.item.client_phone" large persistent @save="update_data(props.item)" @cancel="cancel" @open="open_dialog" @close="close">
                                <div>{{ props.item.client_phone }}</div>
                                <template v-slot:input>
                                    <div class="mt-4 title" style="color: #333">
                                        Update phone
                                    </div>
                                    <v-text-field v-model="props.item.client_phone" label="Edit" single-line counter autofocus></v-text-field>
                                </template>
                            </v-edit-dialog>
                        </template>
                        <template v-slot:item.client_name="props" v-if="user.can['Order edit']">
                            <v-edit-dialog :return-value.sync="props.item.client_name" large persistent @save="update_data(props.item)" @cancel="cancel" @open="open_dialog" @close="close">
                                <div>{{ props.item.client_name }}</div>
                                <template v-slot:input>
                                    <div class="mt-4 title" style="color: #333">
                                        Update name
                                    </div>
                                    <v-text-field v-model="props.item.client_name" label="Edit" single-line counter autofocus></v-text-field>
                                </template>
                            </v-edit-dialog>
                        </template>
                        <template v-slot:item.client_address="props" v-if="user.can['Order edit']">
                            <v-edit-dialog :return-value.sync="props.item.client_address" large persistent @save="update_data(props.item)" @cancel="cancel" @open="open_dialog" @close="close">
                                <span id="address_tab">
                                    <el-tooltip class="item" effect="dark" :content="props.item.client_address" placement="top-start">
                                        <span>
                                            {{ props.item.client_address }}
                                        </span>
                                    </el-tooltip>
                                </span>
                                <template v-slot:input>
                                    <div class="mt-4 title" style="color: #333">
                                        Update address
                                    </div>
                                    <v-text-field v-model="props.item.client_address" label="Edit" single-line counter autofocus></v-text-field>
                                </template>
                            </v-edit-dialog>
                        </template>

                    </v-data-table>
                </v-flex>
            </v-layout>
        </v-card>
        <v-sheet :color="`grey ${theme.isDark ? 'darken-2' : 'lighten-4'}`" class="px-3 pt-3 pb-3" v-else style="width: 100vw;">
            <v-skeleton-loader class="mx-auto" max-width="900" type="table"></v-skeleton-loader>
        </v-sheet>
        <myShow />
        <myStatus />
    </v-container>
</div>
</template>

<script>
import myShow from '../../../components/sales/Show'
import myStatus from '../../../components/sales/status/Status'
import myFilter from '../../../components/sales/filter/filter'
import mySetup from '../../../components/sales/setup'

import {
    mapState
} from 'vuex'
export default {
    props: ['user', 'tenant'],
    components: {
        myShow,
        myStatus,
        myFilter,
        mySetup
    },
    inject: ['theme'],
    data() {
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
                    field: "created_at",
                },
                {
                    label: "Client Name",
                    field: "client_name"
                },
                {
                    label: "Created By",
                    field: "user_name"
                },
                {
                    label: "Sub total",
                    field: "sub_total"
                },
                {
                    label: "Discount",
                    field: "discount"
                },
                {
                    label: "Total",
                    field: "total_price"
                },
                {
                    label: "Charges",
                    field: "shipping_charges"
                },
                {
                    label: "Printed",
                    field: "printed"
                },
                {
                    label: "Actions",
                    field: "actions",
                    sortable: false
                }
            ],
            snack: false,
            setup_loaded: false,
            default_filter: true

        };
    },
    filters: {
        columnHead(value) {
            return value.split('_').join(' ').toUpperCase()
        }
    },
    methods: {

        update_data(data) {
            // console.log(data);
            var payload = {
                data: data,
                model: 'order_update',
                id: data.id
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    // this.goToSales()
                });
        },
        cancel() {
            // this.snack = true
            // this.snackColor = 'error'
            // this.snackText = 'Canceled'
        },
        open_dialog() {
            // this.snack = true
            // this.snackColor = 'info'
            // this.snackText = 'Dialog opened'
        },
        close() {
            console.log('Dialog closed')
        },

        open(printed, id) {
            this.$prompt('Please enter reason for re-printing', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
            }).then(({
                value
            }) => {
                this.print_change(printed, id, value)
            }).catch(() => {
                // this.$message({
                //     type: 'error',
                //     message: 'error'
                // });
            });
        },
        print_change(printed, id, comment) {
            // console.log(printed, id);
            var payload = {
                model: '/print_change',
                id: id,
                data: {
                    printed: printed,
                    comment: comment
                }
            }

            this.$store.dispatch("patchItems", payload).then((response) => {
                this.refreshSales()
            })
        },
        search_order() {
            this.default_filter = false

            var payload = {
                model: 'order_search',
                update: 'updateSaleList',
                search: this.order_item.search
            }
            this.$store.dispatch('searchItems', payload)
        },
        printOrder() {

        },
        openOrder(data) {
            console.log(data);

            var id = data.id
            this.$router.push({
                name: "saleorder",
                params: {
                    id: id
                }
            });
            this.getWarehouses()

        },
        openShow(data) {
            eventBus.$emit("openShowSale", data);
        },
        googleUpload() {
            eventBus.$emit("GoogleUploadEvent");
        },
        // shopifyUpload() {
        //     eventBus.$emit("ShopifyUploadEvent");
        // },
        updateStatus(data) {
            eventBus.$emit("orderStatusEvent", data);
        },

        assign(data, event) {
            eventBus.$emit(event, data);
        },

        openEdit(data) {
            var id = data.id
            this.$router.push({
                name: "editOrder",
                params: {
                    id: id
                }
            });

            this.getWarehouses()

        },

        get_orders(data) {
            console.log(data);
            if (data) {
                var payload = {
                    model: '/app_custom',
                    id: data,
                    update: 'updateSaleList'
                }

                this.$store.dispatch("showItem", payload).then((response) => {
                    this.columns = response.data.columns
                })
            } else {
                this.getSales()
            }

        },
        confirm_delete(item) {
            this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                this.deleteItem(item)
            }).catch(() => {
                this.$message({
                    type: 'error',
                    message: 'Delete canceled'
                });
            });
        },
        restore_order(item) {
            this.$confirm('This will restore this order. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                this.restoreItem(item)
            }).catch(() => {
                this.$message({
                    type: 'error',
                    message: 'Restore canceled'
                });
            });
        },
        restoreItem(item) {
            var payload = {
                model: 'sales_restore',
                data: {},
                id: item.id
            }
            this.$store.dispatch("patchItems", payload).then(response => {
                // this.$message({
                //     type: 'success',
                //     message: 'Order restored'
                // });
                this.deletedOrders();
            });
        },

        customView() {
            eventBus.$emit("openCustomViewEvent");
        },
        uploadOrder(data) {
            eventBus.$emit("openOrderUploadEvent", data.row);
        },
        createOrder(data) {
            // eventBus.$emit("openEditProduct", data.row);

            this.$router.push({
                name: "create_order"
            });
        },
        deleteItem(item) {
            this.$store.dispatch("deleteItem", "sales/" + item.id).then(response => {
                this.$message({
                    type: 'success',
                    message: 'Delete completed'
                });
                this.get_orders();
            });
        },
        deleteItems() {

            this.$confirm('This will delete the Order. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                // this.deleteItem(item)
                this.get_orders();
                var payload = {
                    model: '/sales_delete',
                    data: this.selected
                }
                this.$store.dispatch("postItems", payload).then(response => {
                    // this.$message({
                    //     type: 'success',
                    //     message: 'Delete completed'
                    // });
                    this.get_orders();
                });
            }).catch(() => {
                this.$message({
                    type: 'error',
                    message: 'Delete canceled'
                });
            });

        },
        getSales() {
            var payload = {
                model: 'sale_filter',
                update: 'updateSaleList',
                data: this.form
            }
            this.$store.dispatch('filterItems', payload).then((res) => {
                this.deleted_orders = false
                this.ready = true
            })
            // var payload = {
            //     model: 'sales',
            //     update: 'updateSaleList'
            // }
            // this.$store.dispatch("getItems", payload).then((res) => {
            //     this.deleted_orders = false
            //     this.ready = true
            // })
        },
        refreshSales() {
            if (!this.default_filter) {
                this.default_filter = true
                eventBus.$emit('filterItemsEvent')
            } else {
                this.deleted_orders = false
                var data = {
                    path: this.sales.sales.path,
                    page: this.sales.sales.current_page,
                }
                console.log(data);
                eventBus.$emit('refreshEvent', data)
            }
        },
        deletedOrders() {
            var payload = {
                model: 'deleted_sales',
                update: 'updateSaleList'
            }
            this.$store.dispatch("getItems", payload).then((res) => {
                this.deleted_orders = true
                this.ready = true
            })
        },

        get_filter(data) {
            // console.log(data);
            var payload = {
                model: '/columns',
                id: this.user.id,
                update: 'updateFilterList'
            }

            this.$store.dispatch("showItem", payload).then((response) => {
                this.deleted_orders = false
                this.form.app_custom_id = response.data.id
                this.filter_form.app_custom_id = response.data.id
                // console.log(response.data);

                if (response.data > 0) {
                    this.get_orders(response.data.id)
                }
            })
        },

        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
        },
        getCustom() {
            var payload = {
                model: '/app_custom',
                update: 'updateAppCustomList'
            }
            this.$store.dispatch("getItems", payload)
        },

        next_page(path, page) {
            var data = {
                path: path,
                page: page,
            }
            eventBus.$emit('refreshNextEvent', data)
        },
        getSetup() {
            var payload = {
                model: '/setup',
                update: 'updateSetup'
            }
            this.$store.dispatch("getItems", payload).then((res) => {
                this.setup_loaded = true
            });
        },
    },
    computed: {
        ...mapState(['app_custom', 'sales', 'loading', 'custom_sale', 'setup']),
    },
    mounted() {
        // this.$store.dispatch('getSales');
        eventBus.$emit("LoadingEvent");
        this.getSales();
        this.getCustom();
        this.getSetup();
    },
    created() {
        // this.getSales();
        this.getCustom();
        this.get_filter();
        eventBus.$on("saleEvent", data => {
            this.refreshSales()
            // this.getSales();
        });

        eventBus.$on("statusChangeEvent", data => {

            for (let index = 0; index < this.sales.sales.data.length; index++) {
                const element = this.sales.sales.data[index];
                if (element.id == data.id) {
                    var order = element
                }

            }

            var index = this.sales.sales.data.indexOf(order);
            var payload = {
                data: data,
                index: index,
            }
            this.$store.dispatch("updateStatus", payload);

            // this.sales.sales.index.set(index, data)

        });
        eventBus.$on("responseChooseEvent", data => {
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

<style>
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

#orders td {
    padding: 0 0 0 15px !important;
}

#orders .v-data-table>.v-data-table__wrapper>table {
    width: 150% !important;
}

#address_tab span {
    font-style: inherit;
    font-weight: inherit;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 180px;
    overflow: hidden;
    display: block;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
</style>
