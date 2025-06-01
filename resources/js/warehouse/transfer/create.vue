<template>
    <v-row justify="center">
        <v-dialog persistent v-model="dialog" width="800">
            <v-card>
                <v-card-title class="text-h5">
                    Create A {{ title }}
                    <v-btn icon color="info" @click="close()" style="float: right;" variant="tonal">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <myForm :form_data="form_data" />
                </v-card-text>
                <v-card-actions>
                    <v-btn variant="outlined" color="error" @click="close">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn variant="outlined" color="info" @click="submit" :loading="loading">
                        <v-icon>mdi-plus-circle</v-icon>
                        Submit
                    </v-btn>
                </v-card-actions>
            </v-card>
            <!-- <v-snackbar v-model="snackbar">
                {{ text }}
                <template v-slot:actions>
                    <v-btn color="pink" variant="text" @click="snackbar = false">Close</v-btn>
                </template>
            </v-snackbar> -->
        </v-dialog>
    </v-row>
</template>

<script>
import myForm from '../../components/components/FormComponent.vue';
import axios from 'axios';

export default {
    props: {
        form_data: Array
    },
    components: {
        myForm
    },
    data() {
        return {
            modelRoute: '',
            receiver_data: {},
            sender_data: {},
            title: '',
            loc_show: true,
            dialog: false,
            loading: false,
            payment_complete: false,
            form: {},
            totalCharge: 0,
            sale_id: null,
            baseShippingCharge: 20,
            extraWeightCharge: 10,
            doorDeliveryCharge: 150,
            shipping_charges: 0,
            payment_method: 'Mpesa',
            searchQuery: '',
            searchResults: [],
            receiverQuery: '',
            receiverResults: [],
            e1: 1,
            product_arr: []
        }
    },
    mounted() {
        console.log(this.form_data);
    },
    methods: {
        submit() {
            this.loading = true
            console.log(this.form_data);
            let payload = {
                form_data: this.form_data,
                receiver_data: this.receiver_data,
                sender_data: this.sender_data,
            }

            axios.post(`/${this.modelRoute}`, payload).then((res) => {
                this.loading = false
                console.log("🚀 ~ axios.post ~ res:", res)
                this.sale_id = res.data.id

            }).catch((error) => {
                console.log("🚀 ~ axios.post ~ error:", error)
                this.loading = false

            })
        },
        close() {
            this.dialog = false
        },
        updateFormData(updatedFormData) {
            this.form_data = updatedFormData;
            const transferTypeField = this.form_data.find(field => field.model === 'transfer_type');
            if (transferTypeField) {
                this.handleTransferTypeChange(transferTypeField.value);
            }
        },
        handleTransferTypeChange(transferType) {
            if (transferType === 'merchant') {
                const merchantField = this.form_data.find(field => field.model === 'seller_id');
                if (merchantField) {
                    merchantField.display = true;
                }
                const productField = this.form_data.find(field => field.model === 'product_id');
                if (productField) {
                    this.fetchMerchantProducts();
                    productField.display = true;
                }
            } else {
                const merchantField = this.form_data.find(field => field.model === 'seller_id');
                if (merchantField) {
                    merchantField.display = false;
                }
                const productField = this.form_data.find(field => field.model === 'product_id');
                if (productField) {
                    productField.display = true;
                }
            }
        },
        fetchMerchantProducts() {
            const merchantField = this.form_data.find(field => field.model === 'seller_id');
            if (merchantField && merchantField.value) {
                axios.get(`/merchant-products/${merchantField.value}`).then((res) => {
                    const productField = this.form_data.find(field => field.model === 'product_id');
                    if (productField) {
                        productField.items = res.data;
                    }
                }).catch((error) => {
                    console.log("🚀 ~ axios.get ~ error:", error);
                });
            }
        }
    },
    created() {
        eventBus.$on('transferEvent', () => {
            this.dialog = true
        })

        eventBus.$on('update', (data) => {
            console.log("🚀 ~ eventBus.$on ~ data:", data)
            this.updateFormData(data.model)
        })
    }
}
</script>

<style>
.v-window__container {
    padding: 20px 0;
}

ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

li {
    padding: 10px;
    cursor: pointer;
}

li:hover {
    background-color: #eee;
}

.pac-container {
    z-index: 2400 !important;
    position: absolute !important;
}

.map-field__input {
    background-color: #333;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 5px;
    width: 100%;
}

.map-field__input::placeholder {
    color: #bbb;
}

.map-field__input:focus {
    outline: none;
    border: 1px solid #555;
}
</style>
