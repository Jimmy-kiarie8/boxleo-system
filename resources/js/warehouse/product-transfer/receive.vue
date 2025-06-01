<template>
    <v-dialog v-model="dialog" width="900">
        <v-card>
            <v-card-title>Product Transfer</v-card-title>
            <v-card-text>
                <v-stepper v-model="e6" vertical>
                    <el-dialog title="Warning" :visible.sync="centerDialogVisible" width="30%" center>
                        <el-input placeholder="Please input" v-model="item.value"></el-input>
                        <span slot="footer" class="dialog-footer">
                            <el-button @click="centerDialogVisible = false">Cancel</el-button>
                            <el-button type="primary" @click="copy_details">Confirm</el-button>
                        </span>
                    </el-dialog>

                    <v-stepper-step :complete="e6 > 1" step="1" :editable="e6 > 1">
                        Vendor
                        <small>details</small>
                    </v-stepper-step>

                    <v-stepper-content step="1">
                        <v-layout row wrap>
                            <v-flex sm12>
                                <label for="">Arrival date</label>
                                <el-date-picker v-model="form.arrival_date" type="date" placeholder="Pick a Date"
                                    format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="width:100%">
                                </el-date-picker>
                            </v-flex>

                            <!-- <v-flex sm12>
                                    <label for="">Vendor</label>
                                    <el-select v-model="form.vendor_id" placeholder="Select" @change="getProducts"
                                        style="width:100%" clearable filterable>
                                        <el-option v-for="item in sellers.data" :key="item.id" :label="item.name"
                                            :value="item.id"></el-option>
                                    </el-select>
                                </v-flex>
                                <v-flex sm12 v-if="form.vendor_id">
                                    <label for="">Product</label>
                                    <el-select v-model="form.products" placeholder="Select" multiple clearable
                                        filterable style="width:100%" value-key="id">
                                        <el-option v-for="item in products" :key="item.id" :label="item.product_name"
                                            :value="item">
                                        </el-option>
                                    </el-select>
                                </v-flex> -->
                        </v-layout>
                        <v-btn color="primary" @click="goTo2">
                            Continue
                        </v-btn>
                    </v-stepper-content>

                    <v-stepper-step :complete="e6 > 2" step="2" :editable="e6 > 2">
                        Product details
                    </v-stepper-step>

                    <v-stepper-content step="2">

                        <v-data-table :headers="headers" :items="products" :items-per-page="5" class="elevation-1"
                            :search="search">


                            <template v-slot:item.quantity_received="{ item }">
                                <el-input placeholder="Qty" v-model="item.quantity_received"></el-input>
                            </template>
                            <template v-slot:item.faulty="{ item }">
                                <el-input placeholder="Faulty Qty" v-model="item.faulty"></el-input>
                            </template>
                            <template v-slot:item.weight="{ item }">
                                <el-input placeholder="Weight in kgs" v-model="item.weight"></el-input>
                            </template>

                            <template v-slot:item.notes="{ item }">
                                <el-input type="textarea" placeholder="Enter notes" v-model="item.notes"></el-input>
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-btn color="pink" text icon>
                                    <v-icon small>mdi-delete</v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
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
                            <!-- <v-card-title primary-title>
                                <v-btn color="primary" text :href="'/pickup_note/' + form_id" target="_blank">Print
                                    Clearance
                                    form</v-btn>
                                <v-btn color="primary" text :href="'/product_sticker/' + form_id" target="_blank">Print
                                    product
                                    stickers</v-btn>
                            </v-card-title> -->
                            <v-card-text>
                                <template>
                                    <v-data-table :headers="columns" :items="products" :items-per-page="5"
                                        class="elevation-1" :search="search">
                                        <template v-slot:item.position="{ item }">
                                            <!-- <el-input placeholder="Scan bin position" v-model="item.position"></el-input> -->
                                            <el-select v-model="item.position" filterable clearable placeholder="Bin"
                                                style="width: 100%;">
                                                <el-option v-for="item in bins" :key="item.id" :label="item.code"
                                                    :value="item.id">
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
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    data() {
        return {
            dialog: false,
            e6: 2,
            search: '',
            item: {
                value: null,
            },
            products: [],
            form: {},
            form_id: null,
            copy_val: '',
            centerDialogVisible: false,
            transfer: {},
            headers: [{
                text: 'Product name',
                value: 'product.product_name',
            },
            {
                text: 'Quantity',
                value: 'quantity',
            },
            {
                text: 'Received',
                value: 'quantity_received',
            },
            {
                text: 'Faulty Quantity',
                value: 'faulty',
            },
            {
                text: 'Weight(KGS)',
                value: 'weight',
            },
            {
                text: 'Notes',
                value: 'notes',
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
            const data ={ 
                products: this.products,
                transfer: this.transfer,
            }
            var payload = {
                model: `receive_transfer/${this.transfer.reference}`,
                data: data
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    console.log(response);
                    // this.form = {}
                    // this.e6 = 1
                    // eventBus.$emit("warehouseEvent")
                }).catch((error) => {
                    console.log(error);
                });
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

        getTransfers(id) {
            axios.get(`transfers/${id}`).then((res) => {
                console.log(res.data);
                this.products = res.data
            }).catch((error) => {
                console.log(error);

            })
        },
        goTo2() {
            this.e6 = 2
        },
        goTo3() {
            this.e6 = 3
            return
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
        },
        copy_details() {
            this.form.products.forEach(element => {
                if (this.copy_val == 'price') {
                    element.price = this.item.value
                } else if (this.copy_val == 'price') {
                    element.clearing_company = this.item.value
                } else if (this.copy_val == 'weight') {
                    element.weight = this.item.value
                }
            });

            // this.value = null
        },
        open_copy(data) {
            this.copy_val = data
            this.centerDialogVisible = true
        },

        handleFaultyInput(item) {
            let faultyQty = Math.max(0, parseInt(item.faulty) || 0);
            let totalQty = parseInt(item.quantity) || 0;
            item.faulty = Math.min(faultyQty, totalQty);
        }
    },

    computed: {
        ...mapState(['bins', 'sellers'])
    },

    created() {
        eventBus.$on('receiveEvent', (data) => {
            console.log(data);
            this.transfer = data;
            this.getTransfers(data.reference)

            this.dialog = true
        })
    },

    mounted() {
        this.getVendors();
        this.getBins();
    },
}
</script>
