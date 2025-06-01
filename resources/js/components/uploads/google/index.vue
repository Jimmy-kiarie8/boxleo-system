<template>
<v-dialog v-model="dialog" persistent width="1500px">
    <v-stepper v-model="e1">
        <v-stepper-header>
            <v-stepper-step :complete="e1 > 1" step="1">Name of step 1</v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step :complete="e1 > 2" step="2">Name of step 2</v-stepper-step>

        </v-stepper-header>

        <v-stepper-items>
            <v-stepper-content step="1">
                <myGoogle :form="form" :user="user" />

                <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="goToStep2">
                    <v-icon>mdi-chevron-right</v-icon>
                    <span>Next</span>
                </v-btn>

                <v-btn text @click="close">Close</v-btn>
            </v-stepper-content>

            <v-stepper-content step="2">
                <div v-if="e1 == 2">
                    <myDisplayOrders :orders="orders"/>
                    <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="importOrders"  v-if="orders.sales.length > 0">
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
import myDisplayOrders from './DisplayOrders'
export default {
    name: 'google_import',
    props: ['user'],
    components: {
        myGoogle,
        myDisplayOrders
    },
    data() {
        return {
            dialog: false,
            loading: false,
            e1: 1,
            form: {
                vendor_id: 1,
                warehouse_id: 1,
                start_date: new Date(),
                end_date: '',
                sheet_name: 'Upload',
                work_sheet: 'Sheet2',
                platform: 'Google'

            },
            orders: [],
        }
    },
    methods: {
        goToStep2() {
            this.loading = true;
            eventBus.$emit('googleUploadEvent')
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
        eventBus.$on('GoogleUploadEvent', data => {
            this.dialog = true
        })
        eventBus.$on('googleResponseEvent', data => {
            this.orders = data

            this.loading = false;
            this.e1 = 2
        })

    },
}
</script>
