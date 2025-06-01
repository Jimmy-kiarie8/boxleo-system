<template>
<v-stepper v-model="e6" vertical style="width: 70%;margin: auto">
    <v-stepper-step :complete="e6 > 1" step="1" :editable="e6 > 1">
        Vendor
        <small>details</small>
    </v-stepper-step>

    <v-stepper-content step="1">
        <v-card class="mb-12" elevation="3" style="padding: 10px">
            <v-layout row wrap>
                <v-flex sm12>
                    <label for="">Estimated arrival date</label>
                    <el-date-picker v-model="form.arrival_date" type="date" placeholder="Pick a Date" format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="width:100%">
                    </el-date-picker>
                </v-flex>

                <v-flex sm12>
                    <label for="">Vendor</label>
                    <el-select v-model="form.vendor_id" placeholder="Select" @change="getProducts" style="width:100%" clearable filterable>
                        <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id"></el-option>
                    </el-select>
                </v-flex>
                <v-flex sm12 v-if="form.vendor_id">
                    <label for="">Product</label>
                    <el-select v-model="form.products" placeholder="Select" multiple clearable filterable style="width:100%" value-key="id">
                        <el-option v-for="item in products" :key="item.id" :label="item.product_name" :value="item">
                        </el-option>
                    </el-select>
                </v-flex>

                <v-flex sm12 v-if="form.vendor_id">
                    <label for="">Invoice number</label>
                    <el-input placeholder="INV_0001" v-model="form.invoice_no"></el-input>
                </v-flex>
                <v-flex sm12 v-if="form.vendor_id">
                    <label for="">Po number</label>
                    <el-input placeholder="PO_0001" v-model="form.po_number"></el-input>
                </v-flex>
                <v-flex sm12 v-if="form.vendor_id">
                    <label for="">Vehicle No.(optional)</label>
                    <el-input placeholder="" v-model="form.vehicle_no"></el-input>
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
            <!-- <v-card-title primary-title>
                <v-btn color="primary" text @click="bin_check">Check for bins</v-btn>
            </v-card-title> -->
            <v-card-text>
                <template>
                    <v-data-table :headers="headers" :items="form.products" :items-per-page="5" class="elevation-1" :search="search">
                        <template v-slot:item.quantity="{ item }">
                            <el-input placeholder="Please input" v-model="item.quantity"></el-input>
                        </template>
                        <!-- <template v-slot:item.clearing_company="{ item }">
                            <el-input placeholder="Please input" v-model="item.clearing_company"></el-input>
                        </template>
                        <template v-slot:item.price="{ item }">
                            <el-input placeholder="Price/KG" v-model="item.price"></el-input>
                        </template> -->
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
                    <v-data-table :headers="columns" :items="form.products" :items-per-page="5" class="elevation-1" :search="search"></v-data-table>
                </template>
            </v-card-text>
        </v-card>
        <v-btn color="primary" @click="submit">
            Submit
        </v-btn>
        <v-btn text @click="e6 = 2">
            Back
        </v-btn>
    </v-stepper-content>
    <!-- 
    <v-stepper-step step="4">
        View setup instructions
    </v-stepper-step> -->
    <!-- <v-stepper-content step="4">
        <v-card color="grey lighten-1" class="mb-12" height="200px"></v-card>
        <v-btn color="primary" @click="e6 = 1">
            Continue
        </v-btn>
        <v-btn text>
            Cancel
        </v-btn>
    </v-stepper-content> -->
</v-stepper>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    data() {
        return {
            e6: 1,
            search: '',
            form: {},
            form_id: null,
            headers: [{
                    text: 'Product name',
                    value: 'product_name',
                },
                {
                    text: 'Quantity',
                    value: 'quantity',
                },
                {
                    text: 'Weight(KGS)',
                    value: 'weight',
                },
                {
                    text: 'Actions',
                    value: 'actions',
                },
            ],
            columns: [{
                    text: 'Product name',
                    value: 'product_name',
                },
                {
                    text: 'Quantity',
                    value: 'quantity',
                },
            ]
        }
    },

    methods: {
        submit() {
            var payload = {
                model: 'asn',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    
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
        goTo2() {
            this.e6 = 2
        },
        goTo3() {
            this.e6 = 3
            // var payload = {
            //     model: 'warehouse_pickup',
            //     data: this.form
            // }
            // this.$store.dispatch('postItems', payload)
            //     .then(response => {
            //         this.form_id = response.data.id
            //         this.e6 = 3
            //     });
        },
    },

    computed: {
        ...mapState(['sellers', 'products'])
    },

    mounted() {
        this.getVendors();
    },
}
</script>
