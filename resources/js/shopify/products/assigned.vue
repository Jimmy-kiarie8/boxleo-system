<template>
<v-card>
    <v-card-title>
        <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" @click="getProducts">
                    <v-icon color="primary" small>
                        mdi-refresh
                    </v-icon>
                </v-btn>
            </template>
            <span>Refresh</span>
        </v-tooltip>

        <!-- <el-select v-model="form.ou" clearable filterable placeholder="Select a ou">
            <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.id"></el-option>
        </el-select> -->
        <el-select v-model="form.warehouse" clearable filterable placeholder="Select a warehouse">
            <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id"></el-option>
        </el-select>

        <v-btn color="primary" @click="send_products" v-if="selected.length > 0 && form.warehouse" text>Send products</v-btn>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
    </v-card-title>
    <v-data-table :headers="headers" :items="shopify_products" :search="search" :loading="loading" v-model="selected" show-select item-key="product_name">
        <template v-slot:item.product="{ item }">
            <v-menu bottom origin="center center" transition="scale-transition">
                <template v-slot:activator="{ on: menu, attrs }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on: tooltip }">
                            <v-btn color="primary" dark v-bind="attrs" v-on="{ ...tooltip, ...menu }" text>
                                <v-icon>mdi-menu-down</v-icon> {{ item.products.length }} items
                            </v-btn>
                        </template>
                        <span>Click to view</span>
                    </v-tooltip>
                </template>
                <v-list>

                    <v-list-item three-line v-for="(product, i) in item.products" :key="i">
                        <v-list-item-content>
                            <v-list-item-title>{{ product.name }}</v-list-item-title>
                            <v-list-item-subtitle>
                                {{ product.name }}
                            </v-list-item-subtitle>
                            <v-list-item-subtitle>
                                {{ product.quantity }}@ {{ product.price }}
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <!-- <v-list-item v-for="(product, i) in item.line_items" :key="i">
                        <v-list-item-title>{{ product.title }}</v-list-item-title>
                    </v-list-item> -->
                </v-list>
            </v-menu>
        </template>
        <template v-slot:item.customer="{ item }">
            <v-menu bottom origin="center center" transition="scale-transition">
                <template v-slot:activator="{ on: menu, attrs }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on: tooltip }">
                            <v-btn color="primary" dark v-bind="attrs" v-on="{ ...tooltip, ...menu }" text>
                                <v-icon>mdi-menu-down</v-icon>{{ item.client_name }}
                            </v-btn>
                        </template>
                        <span>Click to view</span>
                    </v-tooltip>
                </template>
                <v-list>
                    <v-list-item three-line>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.phone }}</v-list-item-title>
                            <v-list-item-subtitle>
                                {{ item.address }}
                            </v-list-item-subtitle>
                            <v-list-item-subtitle>
                                {{ item.email }}
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-menu>
        </template>
        <template v-slot:item.action="{ item }">
            <v-tooltip bottom v-if="assigned == 'assigned'">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on" @click="show" color="primary">
                        <v-icon small>mdi-eye</v-icon>
                    </v-btn>
                </template>
                <span>View</span>
            </v-tooltip>
            <v-tooltip bottom v-else>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on" @click="send_products" color="primary">
                        <v-icon small>mdi-send</v-icon>
                    </v-btn>
                </template>
                <span>Send</span>
            </v-tooltip>

        </template>
    </v-data-table>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    props: ['assigned'],
    data() {
        return {
            search: '',
            selected: [],
            form: {},
            headers: [{
                    text: 'Product',
                    value: 'product_name'
                },
                {
                    text: 'Price',
                    value: 'price'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ]
        }
    },

    methods: {
        send_products() {
            var data = {
                products: this.selected,
                form: this.form
            }
            var payload = {
                model: '/shopify_api_products',
                data: data
            }
            this.$store.dispatch('postItems', payload)
                .then((response) => {
                    console.log(response);
                }).catch((error) => {
                    console.log(error);
                })
        },
        getProducts() {
            var payload = {
                model: '/shopify_api_products',
                update: 'updateShopifyProducts',
            }
            this.$store.dispatch('getItems', payload)
                .then((response) => {
                    console.log(response);
                }).catch((error) => {
                    console.log(error);
                })
        },
        show() {

        },
    },
    computed: {
        ...mapState(['shopify_products', 'loading', 'ous', 'warehouses'])
    },
}
</script>
