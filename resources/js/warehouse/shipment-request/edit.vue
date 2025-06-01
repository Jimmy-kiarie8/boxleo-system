<template>
    <div class="text-center">
        <v-dialog v-model="dialog" width="900">
            <v-stepper v-model="e6" vertical>
    
                <v-stepper-step :complete="e6 > 1" step="1" :editable="e6 > 1">
                    Basic details
                    <small>details</small>
                </v-stepper-step>
            
                <v-stepper-content step="1">
                    <v-card class="mb-12" elevation="3" style="padding: 10px">
                        <v-layout row wrap><v-flex sm12>
                            <label for="">Arrival date</label>
                            <el-date-picker v-model="form.arrival_date" type="date" placeholder="Pick a Date" format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="width:100%" disabled>
                            </el-date-picker>
                        </v-flex>

                        <v-flex sm12>
                            <label for="">Warehouse</label>
                            <el-select v-model="form.warehouse_id" placeholder="Select" style="width: 100%" disabled>
                                <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-flex>

                        <v-flex sm12 v-if="!user.is_vendor">
                            <label for="">Vendor</label>
                            <el-select v-model="form.vendor_id" placeholder="Select" style="width:100%" disabled>
                                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id"></el-option>
                            </el-select>
                        </v-flex>
                            <v-flex sm12>
                                <v-data-table :headers="headers" :items="form.products" :search="search">
                                </v-data-table>
                            </v-flex>
                        </v-layout>
                        <v-card-text>
                        </v-card-text>
                    </v-card>
                    <v-btn color="primary" @click="goTo2">
                        Continue
                    </v-btn>
                </v-stepper-content>
            
                <v-stepper-step :complete="e6 > 2" step="2" :editable="e6 > 2">
                    Product details
                </v-stepper-step>
            
                <v-stepper-content step="2">
                    <v-card class="mb-12" elevation="3" style="padding: 10px">
                        <v-card-title primary-title>
                            <v-btn color="primary" text @click="bin_check">Check for bins</v-btn>
                        </v-card-title>
                        <v-card-text>
                            <template>
                                <v-data-table :headers="headers2" :items="form.products" :items-per-page="5" class="elevation-1" :search="search">
                        
            
                                    <template v-slot:item.quantity="{ item }">
                                        <el-input placeholder="Please input" v-model="item.quantity" disabled></el-input>
                                    </template>
                                    <template v-slot:item.received_quantity="{ item }">
                                        <el-input placeholder="Please input" v-model="item.received_quantity"></el-input>
                                    </template>
                                    <template v-slot:item.clearing_company="{ item }">
                                        <el-input placeholder="Please input" v-model="item.clearing_company"></el-input>
                                    </template>
                                    <template v-slot:item.price="{ item }">
                                        <el-input placeholder="Price/KG" v-model="item.price"></el-input>
                                    </template>
                                    <template v-slot:item.weight="{ item }">
                                        <el-input placeholder="Weight in kgs" v-model="item.weight"></el-input>
                                    </template>
                                    <template v-slot:item.actions="{ item }">
                                        <v-btn color="pink" text icon>
                                            <v-icon small>mdi-delete</v-icon>
                                        </v-btn>
                                    </template>
                                </v-data-table>
                            </template>
                        </v-card-text>
                    </v-card>
                <v-btn color="primary" @click="goTo3">
                    Continue
                </v-btn>
                <v-btn text @click="e6 = 1">
                    Back
                </v-btn>
            </v-stepper-content>

            <v-stepper-step :complete="e6 > 3" step="3">
                Putaway
            </v-stepper-step>

            <v-stepper-content step="3">

                <v-card class="mb-12" elevation="3" style="padding: 10px">
                    <v-card-title primary-title>
                        <!-- <v-btn color="primary" text @click="bin_check">Check for bins</v-btn> -->
                        <v-btn color="primary" text :href="'/pickup_note/' + form_id" target="_blank">Print Clearance form</v-btn>
                        <v-btn color="primary" text :href="'/product_sticker/' + form_id" target="_blank">Print product stickers</v-btn>
                    </v-card-title>
                    <v-card-text>
                        <template>
                            <v-data-table :headers="columns" :items="form.products" :items-per-page="5" class="elevation-1" :search="search">
                                <template v-slot:item.position="{ item }">
                                    <el-select v-model="item.position" placeholder="Select" style="width: 100%">
                                <el-option v-for="item in bins" :key="item.id" :label="item.code" :value="item.code">
                                </el-option>
                            </el-select>
                                </template>
                                <template v-slot:item.actions="{ item }">
                                    <v-btn color="primary" text icon>
                                        <v-icon small>mdi-printer</v-icon>
                                    </v-btn>
                                </template>
                            </v-data-table>
                        </template>
                    </v-card-text>
                </v-card>
                <v-btn color="primary" @click="submit">
                    Put away
                </v-btn>
                <v-btn text @click="e6 = 2">
                    Back
                </v-btn>
            </v-stepper-content>
            
            </v-stepper>
        </v-dialog>
    </div>
</template>
    
<script>
    import {
        mapState
    } from 'vuex';
    export default {
        props: ['user'],
        data() {
            return {
            singleExpand: true,
            search: "",
            expanded: [],
                e6: 1,
                search: '',
                item: {
                    value: null,
                },
                form: {},
                form_id: null,
                copy_val: '',
                dialog: false,

                headers: [{
                        text: 'Product Name ',
                        value: 'product.product_name',
                    },
                    {
                        text: 'Quantity',
                        value: 'quantity'
                    },
                    {
                        text: 'Received Quantity',
                        value: 'received_quantity'
                    },
                    {
                        text: 'Status',
                        value: 'status'
                    },
                    {
                        text: 'Actions',
                        value: 'actions'
                    },
                ],

                headers2: [{
                        text: 'Product name',
                        value: 'product.product_name',
                    },
                    {
                        text: 'Quantity',
                        value: 'quantity',
                    },
                    {
                        text: 'Quantity Received',
                        value: 'received_quantity',
                    },
                    {
                        text: 'Price/Kg',
                        value: 'price',
                    },
                    {
                        text: 'Weight(KGS)',
                        value: 'weight',
                    },
                    {
                        text: 'Clearing company',
                        value: 'clearing_company',
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                    },
                ],
                columns: [{
                        text: 'Product name',
                        value: 'product.product_name',
                    },
                    {
                        text: 'Quantity',
                        value: 'quantity',
                    },
                    {
                        text: 'Quantity Received',
                        value: 'received_quantity',
                    },
                    {
                        text: 'Position',
                        value: 'position',
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                    },
                ]
            }
        },
    
        methods: {
            submit() {
                var payload = {
                    model: 'receive',
                    data: this.form,
                    id: this.form.id
                }
                this.$store.dispatch('patchItems', payload)
                    .then(response => {
                        console.log("🚀 ~ file: edit.vue:242 ~ submit ~ response:", response)
                        this.form = {}
                        this.e6 = 1
                        // eventBus.$emit("warehouseEvent")
                    });
            },
            getWarehouses() {
                var payload = {
                    model: 'warehouses',
                    update: 'updateWarehouseList',
                }
                this.$store.dispatch('getItems', payload);
            },
            getVendors() {
                var payload = {
                    model: 'sellers',
                    update: 'updateSellerList',
                }
                this.$store.dispatch('getItems', payload);
            },
            getProducts(id) {
                var payload = {
                    model: 'vendor_product',
                    update: 'updateProductsList',
                    id: id
                }
                this.$store.dispatch('showItem', payload);
            },
    
            goTo2() {
                this.e6 = 2
            },

            goTo3() {
            var payload = {
                model: 'warehouse_pickup',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    this.form_id = response.data.id
                    this.e6 = 3
                });
        },

        getBins() {
            var payload = {
                model: 'bins',
                update: 'updateBins',
            }
            this.$store.dispatch('getItems', payload);
        },

    
            bin_check() {
                var payload = {
                    model: 'bins_check',
                    update: 'updateBin',
                    data: this.form
                }
                this.$store.dispatch('postItems', payload);
            }
        },
    
        computed: {
            ...mapState(['sellers', 'products', 'warehouses', 'bins'])
        },
    
        mounted() {
            this.getVendors();
            this.getWarehouses();
            this.getBins()
        },
        created () {
            eventBus.$on('editShipmentEvent', data => {
                this.form = data;
                this.form.vendor_id = data.seller_id
                this.dialog = true;
            setTimeout(() => {
                if(this.user.is_vendor) {
                    this.getProducts(this.user.id);
                }
            }, 500);
            });
        },
    }
    </script>