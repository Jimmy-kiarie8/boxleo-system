<template>
<v-dialog v-model="dialog" persistent fullscreen hide-overlay transition="dialog-bottom-transition">
    <v-card>
        <v-toolbar dark color="primary">
            <v-btn icon dark @click="dialog = false">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>Order upload</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <!-- <v-btn
              dark
              text
              @click="dialog = false"
            >
              Save
            </v-btn> -->
            </v-toolbar-items>
        </v-toolbar>

        <v-stepper v-model="e1">
            <v-stepper-header>
                <v-stepper-step :complete="e1 > 1" step="1">Upload Details</v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step :complete="e1 > 2" step="2">Upload verification</v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content step="1">
                    <el-radio v-model="upload_type" label="1">Orders with single products</el-radio>
                    <el-radio v-model="upload_type" label="2">Orders with multiple products</el-radio>
                    <myFileUpload :form="form" :user="user" :upload_type="upload_type" :tenant="tenant" />

                    <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="goToStep2">
                        <v-icon>mdi-chevron-right</v-icon>
                        <span>Next</span>
                    </v-btn>

                    <v-btn text @click="close">Cancel</v-btn>
                </v-stepper-content>

                <v-stepper-content step="2">
                    <myDisplayOrders :orders="orders" :form="form" :e1="e1" :upload_type="upload_type" />
                    <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="importOrders">
                    <!--<v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="importOrders" :disabled="invalid_data">-->
                        <v-icon>mdi-import</v-icon>
                        <span>Import</span>
                    </v-btn>

                    <v-btn text @click="e1 = 1">Back</v-btn>
                    <v-btn text @click="close">Close</v-btn>
                </v-stepper-content>

            </v-stepper-items>
        </v-stepper>

    </v-card>
</v-dialog>
</template>

<script>
import myFileUpload from './fileUpload'
import myDisplayOrders from './DisplayOrders'
import myMapping from './mapping'
export default {
    name: 'excel',
    props: ['user', 'tenant'],
    components: {
        myFileUpload,
        myDisplayOrders,
        myMapping
    },
    data() {
        return {
            dialog: false,
            loading: false,
            e1: 1,
            form: {
                vendor_id: '',
                warehouse_id: '',
                platform: 'Excel'
            },
            orders: [],
            upload_type: '1',
            options: 'Orders with single products',
            validate: true,
            invalid_data: false
        }
    },
    methods: {
        goToStep2() {
            if (this.upload_type == '1') {
                this.form.platform = 'Excel'
            } else {
                this.form.platform = 'Excel2'
            }
            this.loading = true;
            eventBus.$emit('fileUploadEvent')
            this.getProducts()
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
        importOrders() {
            var model = 'salesUpload'
            this.form.orders = this.orders.sales

            var payload = {
                data: this.form,
                model: model
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    this.e1 = 1
                    this.orders = []
                    this.dialog = false
                    // eventBus.$emit("MenuEvent")
                });
        },
        close() {
            this.dialog = false
        }
    },
    created() {
        eventBus.$on('openExcelUploadEvent', data => {
            this.dialog = true
        })
        eventBus.$on('orderResponseEvent', data => {
            this.orders = data
            this.loading = false;
            this.e1 = 2
        })
        eventBus.$on('validEvent', data => {
            this.invalid_data = false
            // this.validate = data
        })
        eventBus.$on('invalidEvent', data => {
            this.invalid_data = true
        })
    },
    computed: {
        /* validate() {
            if (this.e1 == 2) {

                // error_arr = []
                var valid = true;

                for (let index = 0; index < this.orders.sales.length; index++) {
                    var element = this.orders.sales[index];
                    if (element.order_no) {
                        element.products.forEach((item) => {
                            if (!item.id) {
                                valid = false
                            }
                        });
                    }
                    if (!element.products[index].id || element.products[index].id == '') {
                        valid = false
                    }
                }
                return valid;
            }
        }, */
        // validate() {

        //     if (this.e1 == 2) {

        //         var valid = true
        //         for (let index = 0; index < this.orders.sales.length; index++) {
        //             const element = this.orders.sales[index];

        //             if (element.products.length < 1 || !element.order_no || !element.address || !element.client_name || !element.phone) {

        //                 valid = false
        //                 break;
        //             } else {
        //                 // product = true
        //                 // element.products.forEach(item => {
        //                 //     if (!element.id) {
        //                 //         valid = false
        //                 //     }
        //                 // });
        //                 valid =  true
        //                 // return true
        //             }
        //         }
        //         return valid;
        //     }
        // }
        // validate() {
        //     var valid = true
        //     if (this.e1 == 2) {
        //         for (let index = 0; index < this.orders.sales.length; index++) {
        //             const element = this.orders.sales[index];

        //             if (!element.product.id || !element.entry.order_id || !element.entry.quantity || !element.entry.address || !element.entry.client_name || !element.entry.phone) {
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
}
</script>
