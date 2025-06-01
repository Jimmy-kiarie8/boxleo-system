<template>
<v-dialog v-model="dialog" persistent width="700px">
    <v-card>
        <v-card-title primary-title>
            Import product
        </v-card-title>
        <v-card-text>
            <myShopify :form="form" />

        </v-card-text>
        <v-card-actions>
            <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="goToStep2">
                <v-icon>mdi-import</v-icon>
                <span>Import</span>
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn color="primary" text @click="close">Close</v-btn>
        </v-card-actions>
    </v-card>
</v-dialog>
</template>

<script>
import myShopify from './shopify'
import myDisplay from './display'
export default {
    name: 'shopify_imp',
    components: {
        myShopify,
        myDisplay
    },
    data() {
        return {
            dialog: false,
            loading: false,
            e1: 1,
            form: {
                vendor_id: 1,
                warehouse_id: 1,
            },
            product_upload: [],
        }
    },
    methods: {
        goToStep2() {
            this.loading = true;

            eventBus.$emit('ShopifyProductEvent')
        },
        importProducts() {

            this.form.product_upload = this.product_upload

            var payload = {
                data: this.form,
                model: '/shopify_pro_import'
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
        validate() {
            // var valid = true
            // if (this.e1 == 2) {
            //     for (let index = 0; index < this.product_upload.sales.length; index++) {
            //         const element = this.product_upload.sales[index];

            //         if (!element.product.id || !element.entry.orderid || !element.entry.quantity || !element.entry.address || !element.entry.clientname || !element.entry.phone) {
            //             valid = false
            //             break;
            //         } else {
            //             valid = true
            //             // return true
            //         }
            //     }
            // }
            // return valid;
            return true
        }
    },
    created() {
        eventBus.$on('ShopifyProductEvent', data => {
            this.dialog = true
        })
        eventBus.$on('ShopifyResponseEvent', data => {
            this.product_upload = data

            this.loading = false;
            this.e1 = 2
        })

    },
}
</script>
