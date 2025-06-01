<template>
<v-layout row justify-center>
    <v-dialog v-model="productDialog" persistent max-width="900px">
            <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
            <v-card-text>

                <h4 style="text-align: center"> Product Details</h4>
                <v-layout wrap>
                    <v-flex xs12 sm11 style="margin-left: 75px">
                        <table class="table table-hover table-striped table-bordered">

                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Item Details</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="products_arr.length > 0">
                                <tr v-for="(product, index) in products_arr" :key="index">
                                    <th scope="row">{{ index+1 }}</th>
                                    <td>
                                        <el-select v-model="product.pivot.product_id" filterable remote reserve-keyword placeholder="type at least 3 characters" :remote-method="getProduct" :loading="loading" style="width: 100%;" value-key="id">
                                            <el-option v-for="item in products.data" :key="item.id" :label="item.product_name" :value="item.id">
                                            </el-option>
                                        </el-select>
                                    </td>
                                    <td>
                                        <el-input v-model="product.pivot.quantity"></el-input>
                                    </td>
                                    <td>

                                        <v-tooltip right>
                                            <template v-slot:activator="{ on }">
                                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="remove(index)">
                                                    <v-icon color="pink darken-2" small>mdi-delete</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Remove</span>
                                        </v-tooltip>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <v-btn small color="primary" style="text-transform: none;" rounded @click="openDialog">
                            <v-icon>mdi-plus</v-icon>
                            <span>Add another</span>
                        </v-btn>
                    </v-flex>
                </v-layout>


            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="productDialog = false">Close</v-btn>
                <VSpacer />
                <v-btn color="primary" @click="updateProduct" :loading="loading" :disabled="loading">Update</v-btn>
            </v-card-actions>

            </v-card>
    </v-dialog>

    </v-layout>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'updates',
    data: () => ({
        color: '#f0f0f0',
        loading: false,
        products_arr: [],
        dialog: false,
        productDialog: false,
        product_item: {},
        client_id: '',
        order_id: null
    }),
    methods: {
        getProducts(id) {
            var payload = {
                model: 'client_products',
                update: 'updateProductsList',
                search: id
            }
            this.$store.dispatch("searchItems", payload);
        },
        getProduct(search) {
            console.log(search);
            if (search.length > 2) {
                var payload = {
                    model: 'product_search',
                    update: 'updateProductsList',
                    search: search
                }
                this.$store.dispatch("searchItems", payload);
            }
        },
        openDialog() {
            // this.dialog = true
            this.products_arr.push({
                product_name: '',
                pivot: {
                quantity: 1
                }
            })
        },

        add_another(option) {
            // console.log(Object.keys(this.product_item).length);

            if (Object.keys(this.product_item).length < 1) {
                this.$message({
                    type: 'error',
                    message: 'Please select an item'
                });
                return
            }
            var exists = false
            this.products_arr.forEach(element => {
                if (this.product_item.id == element.id) {
                    exists = true
                }
            });
            if (!exists) {
                this.products_arr.push(this.product_item)
                this.product_item = {}
                if (option == 'close') {
                    this.close()
                }

            } else {
                this.$message({
                    type: 'error',
                    message: 'Item already exists in the list'
                });
            }
        },
        remove(index) {
            this.products_arr.splice(index, 1)

        },
        close() {
            this.productDialog = false
        },
        updateProduct() {
            var payload = {
                data: this.products_arr,
                model: 'product_update',
                id: this.order_id
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("saleEvent")
                    this.close()
                });
        },

    },
    created () {
        eventBus.$on('productEvent', data => {
            this.getProducts(data.seller_id);
            this.order_id = data.id;
            this.products_arr = data.products;
            this.productDialog = true;

        });
    },

    computed: {
        ...mapState(['products'])
    },

}
</script>