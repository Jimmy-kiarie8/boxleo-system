<template>
<v-card>
    <v-card-title primary-title>

    </v-card-title>
    <v-card-text>
        <v-data-table :headers="headers" :items="woocommerce_products" :search="search" :loading="loading">
            <template v-slot:item.actions="{ item }">
                <v-btn text icon color="pink" @click="remove(item)">
                    <v-icon small>mdi-delete</v-icon>
                </v-btn>

            </template>

            <!-- </div> -->
        </v-data-table>

        <!-- </div> -->

    </v-card-text>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['woocommerce_products'],
    data() {
        return {
            search: '',
            valid: false,
            invalid_row: [],
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

        getProducts() {
            var payload = {
                model: 'products',
                update: 'updateProductsList'
            }
            this.$store.dispatch("getItems", payload);

        },
        getApiStatuses() {
            this.$store.dispatch('getApiStatuses')
        },
        remove(item) {
            const index = this.woocommerce_products.indexOf(item)
            this.woocommerce_products.splice(index, 1)
        }
    },
    computed: {
        ...mapState(['products', 'api_status', 'loading']),
        // validate() {
        //     var valid = true
        //     for (let index = 0; index < this.woocommerce_products.sales.length; index++) {
        //         const element = this.woocommerce_products.sales[index];

        //         if (!element.product.id || !element.entry.productid || !element.entry.quantity || !element.entry.address || !element.entry.clientname || !element.entry.phone) {
        //             valid = false
        //             break;
        //         } else {
        //             valid = true
        //             // return true
        //         }
        //     }
        //     return valid;
        // }
    },
    mounted() {
        // this.getApiStatuses();
        // this.getProducts();
    },
}
</script>

<style scoped>
.el-collapse-item__header {
    color: #f00 !important;
}
</style>
