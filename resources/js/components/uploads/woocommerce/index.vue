<template>
<v-dialog v-model="dialog" persistent width="1500px">
    <v-stepper v-model="e1">
        <v-stepper-header>
            <v-stepper-step :complete="e1 > 1" step="1">Name of 1</v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step :complete="e1 > 2" step="2">Name of step 2</v-stepper-step>

        </v-stepper-header>

        <v-stepper-items>
            <v-stepper-content step="1">
                <myWoocommerce :form="form" :user="user" />
                <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="goToStep2" :loading="loading">
                    <v-icon>mdi-chevron-right</v-icon>
                    <span>Get Orders</span>
                </v-btn>
                <v-btn text @click="close">Close</v-btn>
            </v-stepper-content>

            <v-stepper-content step="2">
                <myDisplayOrders :woocommerce_orders="woocommerce_orders" />
                <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="woocommerce_import">
                    <v-icon>mdi-import</v-icon>
                    <span>Import</span>
                </v-btn>

                <v-btn text @click="e1 = 1">Back</v-btn>
                <v-btn text @click="close">Close</v-btn>
            </v-stepper-content>

        </v-stepper-items>
    </v-stepper>
</v-dialog>
</template>

<script>
import myWoocommerce from './woocommerce'
import myDisplayOrders from './DisplayOrders'
export default {
    name: 'woocommerce',
    props: ['user'],
    components: {
        myWoocommerce,
        myDisplayOrders
    },
    data() {
        return {
            dialog: false,
            loading: false,
            e1: 1,
            form: {
                vendor_id: '',
                warehouse_id: '',
                platform: 'Woocommerce'
            },
            woocommerce_orders: [],
        }
    },
    methods: {
        goToStep2() {
            this.loading = true;
            this.importOrders()
        },
        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
        },
        getProducts() {
            var payload = {
                model: 'client_products',
                update: 'updateProductsList',
                id: this.form.vendor_id
            }
            this.$store.dispatch("showItem", payload);
        },
        importOrders() {

            // this.form.woocommerce_orders = this.woocommerce_orders.sales
            var payload = {
                data: this.form,
                model: '/get_orders'
            }
            this.loading = true
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    this.loading = false
                    eventBus.$emit('woocommerceUploadEvent')
                    this.e1 = 2
                    this.getProducts()
                    // console.log(response.data);
                    this.woocommerce_orders = response.data
                }).catch((error) => {
                    this.loading = false
                    console.log(error);

                });
        },
        woocommerce_import() {

            this.form.orders = this.woocommerce_orders.sales
            var payload = {
                data: this.form,
                model: '/salesUpload'
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    console.log(response.data);
                    // this.woocommerce_orders = response.data
                });
        },
        close() {
            this.dialog = false
        }
    },
    computed: {
        // validate() {
        //     var valid = true
        //     if (this.e1 == 2) {
        //         for (let index = 0; index < this.woocommerce_orders.sales.length; index++) {
        //             const element = this.woocommerce_orders.sales[index];

        //             if (!element.product.id || !element.entry.orderid || !element.entry.quantity || !element.entry.address || !element.entry.clientname || !element.entry.phone) {
        //                 valid = false
        //                 break;
        //             } else {
        //                 valid = true
        //                 // return true
        //             }
        //         }
        //     }
        //     return valid;
        // }
    },
    created() {
        eventBus.$on('WoocommerceUploadEvent', data => {
            this.dialog = true
            this.getWarehouses()
        })
        eventBus.$on('woocommerceResponseEvent', data => {
            this.woocommerce_orders = data
            this.loading = false;
            this.e1 = 2
        })

    },
}
</script>
