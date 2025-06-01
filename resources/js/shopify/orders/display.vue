<template>
<v-row justify="center">
    <v-dialog v-model="dialog" persistent max-width="1500px">
        <v-card>
            <v-card-title>
                <span class="text-h5">Orders</span>

                </v-card-title>
                <v-card-text>
                    <!-- <el-collapse accordion>

                        <el-collapse-item name="1" v-if="orders.exist_orders.length > 0">
                            <template slot="title" style="color: red">
                                <span><b style="color: red">{{ orders.exist_orders.length }} Orders Exists</b><i class="header-icon el-icon-info"></i></span>
                            </template>
                            <div>
                                <v-list dense two-line>
                                    <v-list-item-group color="primary">
                                        <v-list-item v-for="(item, i) in orders.exist_orders" :key="i">
                                            <v-list-item-content>
                                                <v-list-item-title>{{ item.order_no }} </v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                            </div>
                        </el-collapse-item>
                    </el-collapse> -->

                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
                    <!-- <v-btn color="success" @click="checkvalidate">checkvalidate</v-btn> -->
                    <v-data-table :headers="headers" :items="orders" :search="search" :loading="loading">
                        <!-- <v-data-table :headers="headers" :items="orders" :search="search" :item-class="itemRowBackground"> -->
                        <!-- <div :style="[ (!order.product.id || !order.entry.orderid || !order.entry.quantity || !order.entry.address || !order.entry.clientname || !order.entry.phone) ? {'background': '#ff00008a'} : '']"> -->
                        <template v-slot:item.product_name="{ item }">
                            <v-layout row v-for="(product, index) in item.products" :key="index" style="padding: 10px 0;">
                                <v-flex sm8>
                                    <el-select v-model="product.id" placeholder="Select" filterable clearable>
                                        <el-option v-for="item in products.data" :key="item.id" :label="item.product_name" :value="item.id">
                                        </el-option>
                                    </el-select>
                                </v-flex>
                                <v-flex sm4>
                                    <el-input placeholder="Please input" v-model="product.quantity"></el-input>
                                </v-flex>
                            </v-layout>
                        </template>

                        <template v-slot:item.actions="{ item }">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="remove(item)" slot="activator">
                                        <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Remove</span>
                            </v-tooltip>
                        </template>

                        <template v-slot:item.address="{ item }">
                            <el-input type="textarea" placeholder="Please input" rows="3" v-model="item.address"></el-input>
                        </template>
                        <template v-slot:item.client_name="{ item }">
                            <el-input placeholder="Please input" v-model="item.client_name"></el-input>
                        </template>
                        <template v-slot:item.phone="{ item }">
                            <el-input placeholder="Please input" v-model="item.phone"></el-input>
                        </template>

                        <!-- <template v-slot:item.status="{ item }">
                            <el-select v-model="item.status" placeholder="Select" filterable clearable>
                                <el-option v-for="item in statuses" :key="item.id" :label="item.status" :value="item.status"></el-option>
                            </el-select>
                        </template>
                        <template v-slot:item.delivery_date="{ item }">
                            <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="item.delivery_date" type="date" placeholder="Pick a day" style="width: width: 140px;" :picker-options="pickerOptions"></el-date-picker>
                        </template> -->
                        <template v-slot:item.notes="{ item }">
                            <el-input type="textarea" placeholder="Please input" v-model="item.notes" rows="3"></el-input>
                        </template>
                        <!-- </div> -->
                    </v-data-table>

                    <!-- </div> -->

                </v-card-text>

                <v-card-actions>
                        <v-btn small elevation="3" color="primary" text>Close</v-btn>
                        <v-btn small elevation="3" color="primary" text @click="save">Save</v-btn>

                </v-card-actions>
        </v-card>
    </v-dialog>
</v-row>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    // props: ['orders', 'e1'],
    data() {
        return {
            search: '',
            valid: false,
            invalid_row: [],
            dialog: false,
            headers: [{
                    text: 'Order id',
                    value: 'order_no'
                },
                {
                    text: 'Product name',
                    value: 'product_name'
                },
                {
                    text: 'Client name',
                    value: 'client_name'
                },
                {
                    text: 'Address',
                    value: 'address'
                },
                {
                    text: 'Phone',
                    value: 'phone'
                },
               /*  {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Delivery date',
                    value: 'delivery_date'
                }, */
                {
                    text: 'Special instructions',
                    value: 'notes'
                },
                {
                    text: 'Total price',
                    value: 'cod_amount'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ],
            pickerOptions: {
                disabledDate(time) {
                    return time.getTime() + 3600 * 1000 * 24 < Date.now();
                },
                shortcuts: [{
                    text: 'Today',
                    onClick(picker) {
                        picker.$emit('pick', new Date());
                    }
                }, {
                    text: 'Tomorrow',
                    onClick(picker) {
                        const date = new Date();
                        date.setTime(date.getTime() + 3600 * 1000 * 24);
                        picker.$emit('pick', date);
                    }
                }]
            },
        }
    },
    methods: {
        save(){
            eventBus.$emit('saveEvent')
        },
        getProducts() {
            var payload = {
                model: 'products',
                update: 'updateProductsList'
            }
            this.$store.dispatch("getItems", payload);

        },
        remove(item) {
            const index = this.orders.sales.indexOf(item)
            this.orders.sales.splice(index, 1)
        },
        getStatus() {
            var payload = {
                model: 'statuses',
                update: 'updateStatusList',
            }
            this.$store.dispatch("getItems", payload);
        },

        checkvalidate() {
            var valid = true
            for (let index = 0; index < this.orders.sales.length; index++) {
                const element = this.orders.sales[index];
                console.log(element);

                console.log(element.products.length > 0, !element.order_no, !element.address, !element.client_name, !element.phone);

                if (element.products.length > 0 || !element.order_no || !element.address || !element.client_name || !element.phone) {
                    valid = false
                    break;
                } else {
                    valid = true
                    // return true
                }
            }
            console.log(valid);
        }
    },
    computed: {
        ...mapState(['orders','products', 'loading', 'statuses']),
    },
    created () {
        eventBus.$on('openDisplayEvent', data => {
            this.dialog = true
        });
    },
    mounted() {
        this.getStatus();
        this.getProducts();
    },
}
</script>

<style scoped>
.el-collapse-item__header {
    color: #f00 !important;
}
</style>
