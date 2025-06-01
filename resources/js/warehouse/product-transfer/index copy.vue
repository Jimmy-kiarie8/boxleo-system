<template>
    <v-container>
        <v-card>
            <v-card-title>Product Transfer</v-card-title>
            <v-card-text>
                <br>
                <br>
                <v-row>
                    <v-flex sm3 style="margin-left: 10px; margin-bottom: 20px;">
                        <label for="">Product</label>
                        <el-select v-model="selectedProduct" filterable remote reserve-keyword
                            placeholder="type at least 3 characters" :remote-method="getProduct" :loading="loading"
                            style="width: 100%;" value-key="id" clearable @change="addProduct()">
                            <el-option v-for="item in products.data" :key="item.id" :label="item.product_name"
                                :value="item">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm3 style="margin-left: 10px; margin-bottom: 20px;">
                        <label for="">Type</label>
                        <el-select v-model="form.type" filterable placeholder="Select" style="width: 100%;"
                            value-key="value" clearable>
                            <el-option v-for="(item, index) in transfers" :key="index" :label="item.label"
                                :value="item.value">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm3 style="margin-left: 10px; margin-bottom: 20px;">
                        <label for="">Warehouse To</label>
                        <el-select v-model="form.warehouse" filterable placeholder="Select" style="width: 100%;"
                            value-key="id" clearable>
                            <el-option v-for="(item, index) in warehouses" :key="index" :label="item.name"
                                :value="item.id">
                            </el-option>
                        </el-select>
                    </v-flex>
                </v-row>


                <v-data-table :headers="headers" :items="transferItems" class="elevation-1">
                    <template v-slot:item.quantity="{ item }">
                        <v-text-field v-model.number="item.transferQuantity" type="number"
                            :rules="[quantityRules(item)]" @input="validateQuantity(item)"></v-text-field>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <!-- <v-btn small color="error" @click="removeItem(item)">Remove</v-btn> -->


                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" class="mx-0" @click="removeItem(item)" slot="activator">
                                    <v-icon small color="red">mdi-delete</v-icon>
                                </v-btn>
                            </template>
                            <span>Remove</span>
                        </v-tooltip>
                    </template>
                </v-data-table>

                <v-btn color="primary" :disabled="!isFormValid" @click="submitTransfer" class="mt-4">
                    Submit Transfer
                </v-btn>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
import { mapState } from 'vuex';

export default {
    data() {
        return {
            form: {},
            selectedProduct: null,
            transferItems: [],
            loading: false,
            headers: [
                { text: 'Product Name', value: 'product_name' },
                { text: 'Available Stock', value: 'available_for_sale' },
                { text: 'Transfer Quantity', value: 'quantity' },
                { text: 'Actions', value: 'actions', sortable: false },
            ],
            transfers: [
                {
                    label: 'Warehouse to warehouse',
                    value: 'warehouse'
                },
                {
                    label: 'Bin To Bin',
                    value: 'bin'
                },
                {
                    label: 'Merchant To Merchant',
                    value: 'merchant'
                }
            ]
        };
    },
    methods: {
        getProduct(search) {
            // console.log(search);
            if (search.length > 2) {
                var payload = {
                    model: 'product_filter_search',
                    update: 'updateProductsList',
                    data: {
                        search: search,
                        vendors: this.form.client
                    }
                }
                this.$store.dispatch("filterItems", payload);
            }
        },
        addProduct() {
            const product = this.selectedProduct;
            if (product && !this.transferItems.some(item => item.id === product.id)) {
                // this.transferItems.push({
                //     ...product,
                //     transferQuantity: 0,
                // });

                if (product.available_for_sale < 1) {

                    this.$message({
                        type: 'error',
                        message: 'Quantity is 0'
                    });
                    return;
                }
                this.transferItems.push(product);
            }
            this.selectedProduct = null;
        },
        removeItem(item) {
            const index = this.transferItems.indexOf(item);
            if (index !== -1) {
                this.transferItems.splice(index, 1);
            }
        },
        quantityRules(item) {
            return v => (v > 0 && v <= item.available_for_sale) || `Quantity must be between 1 and ${item.available_for_sale}`;
        },
        validateQuantity(item) {
            item.transferQuantity = Math.min(Math.max(0, item.transferQuantity), item.available_for_sale);
        },
        submitTransfer() {
            if (this.isFormValid) {
                // Here you would typically send the transfer data to your backend
                console.log('Submitting transfer:', this.transferItems);
                const form = {
                    form: this.form,
                    transferItems: this.transferItems
                }

                axios.post('stock_transfer', form).then((res) => {
                    console.log(res);
                    // this.transferItems = [];

                }).catch((error) => {
                    console.log(error);

                })
                // Reset the form after submission
            }
        },
        getWarehouses() {
            var payload = {
                model: 'warehouses',
                update: 'updateWarehouseList',
            }
            this.$store.dispatch('getItems', payload);
        },


    },
    mounted() {
        this.getWarehouses();
    },

    computed: {
        ...mapState(['products', 'warehouses']),
        isFormValid() {
            return this.transferItems.length > 0 && this.transferItems.every(item =>
                item.transferQuantity > 0 && item.transferQuantity <= item.available_for_sale
            );
        },
    }
};
</script>