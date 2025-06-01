<template>
<v-dialog v-model="dialog" persistent width="1500px">
    <v-stepper v-model="e1">
        <v-stepper-header>
            <v-stepper-step :complete="e1 > 1" step="1">Upload details</v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step :complete="e1 > 2" step="2">Confirmation</v-stepper-step>

        </v-stepper-header>

        <v-stepper-items>
            <v-stepper-content step="1">
                <myGoogle :form="form" :user="user" v-if="form.platform == 'Google'" />
                <myShopify :form="form" :user="user" v-if="form.platform == 'Shopify'" />
                <myWoocommerce :form="form" :user="user" v-if="form.platform == 'Woocommerce'" />

                <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="goToStep2">
                    <v-icon>mdi-chevron-right</v-icon>
                    <span>Next</span>
                </v-btn>

                <v-btn text @click="close">Close</v-btn>
            </v-stepper-content>

            <v-stepper-content step="2">
                <div v-if="e1 == 2">
                    <myDisplayOrders :orders="orders" />
                    <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="importOrders" >
                        <v-icon>mdi-import</v-icon>
                        <span>Import</span>
                    </v-btn>
                    <v-btn text @click="e1 = 1">Back</v-btn>
                    <v-btn text @click="close">Close</v-btn>
                </div>
            </v-stepper-content>

        </v-stepper-items>
    </v-stepper>
</v-dialog>
</template>

<script>
import myGoogle from './google'
import myShopify from './shopify'
import myWoocommerce from './woocommerce'
import myDisplayOrders from './DisplayOrders'
export default {
    name: 'Order_import',
    props: ['user'],
    components: {
        myGoogle,
        myShopify,
        myWoocommerce,
        myDisplayOrders
    },
    data() {
        return {
            dialog: false,
            loading: false,
            e1: 1,
            form: {
                vendor_id: null,
                warehouse_id: null,
                platform: '',
                upload_type: '1'

            },
            orders: [],
        }
    },
    methods: {

        // goToStep2() {
        //     if (this.form.platform == 'Excel' || this.form.platform == 'Excel2') {
        //         if (this.upload_type == '1') {
        //             this.form.platform = 'Excel'
        //         } else {
        //             this.form.platform = 'Excel2'
        //         }
        //     }
        //     this.loading = true;
        //     eventBus.$emit('fileUploadEvent')
        // },

        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
        },
        goToStep2() {
            this.loading = true;
            if (this.form.platform == 'Excel' || this.form.platform == 'Excel') {
                eventBus.$emit('fileUploadEvent')
            } else {
                this.get_orders()
            }
                // eventBus.$emit('OrderResponseEvent')
        },
        importOrders() {

            this.form.orders = this.orders.sales
            // this.form.platform = 'Google'

            var payload = {
                data: this.form,
                // model: '/googleUpload'
                model: '/salesUpload'
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("saleEvent")
                });
        },

        getProducts() {
            var vendor_id = (this.user.is_vendor) ? this.user.id : this.form.vendor_id
            var payload = {
                model: 'client_products',
                update: 'updateProductsList',
                id: vendor_id
            }
            this.$store.dispatch("showItem", payload);
        },
        get_orders() {

            var payload = {
                model: 'get_orders',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // console.log(response.data);
                    this.getProducts()
                    this.orders = response.data
                    if (response.data.sales.length > 0) {
                        eventBus.$emit('OrderResponseEvent')
                    } else {
                        this.$message({
                            type: 'error',
                            message: 'No Data found'
                        });
                    }

                    // eventBus.$emit("MenuEvent")
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
        //         for (let index = 0; index < this.orders.sales.length; index++) {
        //             const element = this.orders.sales[index];

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
        eventBus.$on('openOrderUploadEvent', data => {
            console.log(data);
            this.dialog = true
            this.form.platform = data

            this.getWarehouses()
        })
        eventBus.$on('OrderResponseEvent', data => {
            console.log(data)
            // this.orders = data

            this.loading = false;
            this.e1 = 2
        })

    },
}
</script>
