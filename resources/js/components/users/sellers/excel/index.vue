<template>
<v-dialog v-model="dialog" persistent width="800px">
    <v-card>

        <v-stepper v-model="e1">
            <v-stepper-header>
                <v-stepper-step :complete="e1 > 1" step="1">Upload Details</v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step :complete="e1 > 2" step="2">Upload sellers</v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content step="1">
                    <!-- <el-radio v-model="upload_type" label="1">Sellers with single sellers</el-radio>
                    <el-radio v-model="upload_type" label="2">Sellers with multiple sellers</el-radio> -->
                    <myFileUpload :form="form" :user="user" />

                    <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="goToStep2">
                        <v-icon>mdi-chevron-right</v-icon>
                        <span>Next</span>
                    </v-btn>

                    <v-btn text @click="close">Cancel</v-btn>
                </v-stepper-content>

                <v-stepper-content step="2">
                    <myDisplaySellers :sellers="sellers" :e1="e1" />
                    <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="importSellers">
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
import myDisplaySellers from './DisplaySeller'
import myMapping from './mapping'
export default {
    name: 'excel',
    props: ['user'],
    components: {
        myFileUpload,
        myDisplaySellers,
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
            sellers: [],
            upload_type: '1',
            options: 'Sellers with single sellers'
        }
    },
    methods: {
        goToStep2() {
            this.form.platform = 'Excel'
            this.loading = true;
            eventBus.$emit('fileUploadEvent')
        },
        importSellers() {
            var model = 'sellersUpload'
            this.form.sellers = this.sellers.sellers

            var payload = {
                data: this.form,
                model: model
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("MenuEvent")
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
        eventBus.$on('sellerResponseEvent', data => {
            this.sellers = data
            this.loading = false;
            this.e1 = 2
        })
    },
    computed: {
        // validate() {
        //     var valid = true
        //     if (this.e1 == 2) {
        //         for (let index = 0; index < this.sellers.sellers.length; index++) {
        //             const element = this.sellers.sellers[index];

        //             if (!element.seller.id || !element.entry.seller_id || !element.entry.quantity || !element.entry.address || !element.entry.seller_name || !element.entry.phone) {
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
