<template>
<v-card>
    <v-card-title primary-title>

    </v-card-title>
    <v-card-text>
        <!-- {{ orders.sales.length }}
{{ orders.length }} -->
        <el-collapse accordion>

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
        </el-collapse>
        <div v-if="orders.sales.length > 0">
            <h2 style="color: #1876d2;"> {{ orders.sales.length }} Orders will be imported </h2>

            <v-card>
                <v-card-title>
                    Orders
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="orders.sales" :search="search" :item-class="itemRowBackground">
                    <!-- <div :style="[ (!order.product.id || !order.entry.orderid || !order.entry.quantity || !order.entry.address || !order.entry.clientname || !order.entry.phone) ? {'background': '#ff00008a'} : '']"> -->
                        <template v-slot:item.productname="{ item }">
                            <el-select v-model="item.product.id" placeholder="Select" filterable clearable>
                                <el-option v-for="item in products.data" :key="item.id" :label="item.product_name" :value="item.id">
                                </el-option>
                            </el-select>
                        </template>
                        <template v-slot:item.entry.quantity="{ item }">
                            <el-input-number v-model="item.entry.quantity" :min="1"></el-input-number>
                        </template>
                        <template v-slot:item.entry.address="{ item }">
                            <el-input type="textarea" autosize placeholder="Please input" v-model="item.entry.address"></el-input>
                        </template>
                        <template v-slot:item.entry.clientname="{ item }">
                            <el-input placeholder="Please input" v-model="item.entry.clientname"></el-input>
                        </template>
                        <template v-slot:item.entry.phone="{ item }">
                            <el-input placeholder="Please input" v-model="item.entry.phone"></el-input>
                        </template>
                        <template v-slot:item.entry.status="{ item }">
                            <el-select v-model="item.entry.status" placeholder="Select" filterable clearable>
                                <el-option v-for="item in statuses" :key="item.id" :label="item.status" :value="item.status">
                                </el-option>
                            </el-select>
                        </template>
                        <template v-slot:item.entry.deliverydate="{ item }">
                            <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="item.entry.deliverydate" type="date" placeholder="Pick a day"></el-date-picker>
                        </template>
                        <template v-slot:item.entry.specialinstructions="{ item }">
                            <el-input type="textarea" autosize placeholder="Please input" v-model="item.entry.specialinstructions"></el-input>
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
                    <!-- </div> -->
                </v-data-table>
            </v-card>

        </div>

    </v-card-text>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['orders'],
    data() {
        return {
            valid: false,
            invalid_row: [],
            search: '',
            headers: [{
                    text: 'Order id',
                    value: 'entry.orderid'
                },
                {
                    text: 'Product',
                    value: 'productname'
                },
                {
                    text: 'Quantity',
                    value: 'entry.quantity'
                },
                {
                    text: 'Client name',
                    value: 'entry.clientname'
                },
                {
                    text: 'Address',
                    value: 'entry.address'
                },
                {
                    text: 'Phone',
                    value: 'entry.phone'
                },
                {
                    text: 'Status',
                    value: 'entry.status'
                },
                {
                    text: 'Special instructions',
                    value: 'entry.specialinstructions'
                },
                {
                    text: 'Total price',
                    value: 'entry.totalamount'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ]
        }
    },
    methods: {
        getStatus() {
            var payload = {
                model: 'statuses',
                update: 'updateStatusList',
            }
            this.$store.dispatch("getItems", payload);
        },
        remove(item) {
            const index = this.orders.sales.indexOf(item)

            this.orders.sales.splice(index, 1)
        },
        itemRowBackground: function (order) {
            console.log(order);

            console.log(!order.product.id || !order.entry.orderid || !order.entry.quantity || !order.entry.address || !order.entry.clientname || !order.entry.phone);

            return (!order.product.id || !order.entry.orderid || !order.entry.quantity || !order.entry.address || !order.entry.clientname || !order.entry.phone) ? 'style-1' : 'style-2'
        }
    },
    computed: {
        ...mapState(['products', 'statuses']),
        validate() {
            var valid = true
            for (let index = 0; index < this.orders.sales.length; index++) {
                const element = this.orders.sales[index];

                if (!element.product.id || !element.entry.orderid || !element.entry.quantity || !element.entry.address || !element.entry.clientname || !element.entry.phone) {
                    valid = false
                    break;
                } else {
                    valid = true
                    // return true
                }
            }
            return valid;
        }
    },
    mounted() {
        this.getStatus();
    },
}
</script>

<style>
.el-collapse-item__header {
    color: #f00 !important;
}
.style-1 {
  background-color: #f00;
}

</style>
