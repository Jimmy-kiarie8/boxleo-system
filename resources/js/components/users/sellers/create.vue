<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="800px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Vendor</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm6>
                            <label for="">Full Name</label>
                            <el-input placeholder="John Doe" v-model="form.name"></el-input>
                            <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Email Address</label>
                            <el-input placeholder="john@gmail.com" v-model="form.email"></el-input>
                            <small class="has-text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Phone Number</label>
                            <el-input placeholder="+1..." v-model="form.phone"></el-input>
                            <small class="has-text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Address</label>
                            <el-input placeholder="123 main st" v-model="form.address"></el-input>
                            <small class="has-text-danger" v-if="errors.address">{{ errors.address[0] }}</small>
                        </v-flex>
                    </v-layout>
                    <v-divider></v-divider>
                    <h3 class="text-center">Company details</h3>
                    <v-divider></v-divider>
                    <v-layout row wrap>
                        <v-flex sm6>
                            <label for="">Company name</label>
                            <el-input placeholder="jane's store" v-model="form.company_name"></el-input>
                            <small class="has-text-danger" v-if="errors.company_name">{{ errors.company_name[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Address</label>
                            <el-input placeholder="123 mainst" v-model="form.company_address"></el-input>
                            <small class="has-text-danger" v-if="errors.company_address">{{ errors.company_address[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Address 2</label>
                            <el-input placeholder="123 mainst" v-model="form.address_2"></el-input>
                            <small class="has-text-danger" v-if="errors.address_2">{{ errors.address_2[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Company phone no.</label>
                            <el-input placeholder="+1..." v-model="form.company_phone"></el-input>
                            <small class="has-text-danger" v-if="errors.company_phone">{{ errors.company_phone[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Company email</label>
                            <el-input placeholder="jane@store.com" v-model="form.company_email"></el-input>
                            <small class="has-text-danger" v-if="errors.company_email">{{ errors.company_email[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Company website</label>
                            <el-input placeholder="https://123.com" v-model="form.company_website"></el-input>
                            <small class="has-text-danger" v-if="errors.company_website">{{ errors.company_website[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Postal Code</label>
                            <el-input placeholder="000192" v-model="form.postal_code"></el-input>
                            <small class="has-text-danger" v-if="errors.postal_code">{{ errors.postal_code[0] }}</small>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm4>
                            <!-- <label for="">Auto generate order no.</label> -->
                            <el-radio v-model="form.generate" label="input">Input client order no</el-radio>
                            <el-radio v-model="form.generate" label="generate">Auto generate order no.</el-radio>
                            <small class="has-text-danger" v-if="errors.order">{{ errors.order[0] }}</small>
                        </v-flex>
                        <v-flex sm4 v-if="form.generate == 'generate'">
                            <label for="">Order number Start</label>
                            <el-input placeholder="000192" v-model="form.order_no_start"></el-input>
                            <small class="has-text-danger" v-if="errors.order_no_start">{{ errors.order_no_start[0] }}</small>
                        </v-flex>
                        <v-flex sm4 v-if="form.generate == 'generate'">
                            <label for="">Order number End</label>
                            <el-input placeholder="000192" v-model="form.order_no_end"></el-input>
                            <small class="has-text-danger" v-if="errors.order_no_end">{{ errors.order_no_end[0] }}</small>
                        </v-flex>
                    </v-layout>

                    
                    <!-- <v-layout row wrap>
                        <v-flex sm4>
                            <label for="">Shopify Email</label>
                            <el-input placeholder="123@myshopify.com" v-model="form.shopify_email"></el-input>
                            <small class="has-text-danger" v-if="errors.shopify_email">{{ errors.shopify_email[0] }}</small>
                        </v-flex>
                    </v-layout> -->

                    <v-divider></v-divider>
                    <h3 class="text-center">Operating Units</h3>
                    <v-divider></v-divider>
                    <v-layout row wrap>
                        <v-flex v-for="(ou, index) in ous" :key="index" xs6 sm4>
                            <v-checkbox v-model="ou.checked" :label="ou.ou" :value="ou.checked"></v-checkbox>
                        </v-flex>
                    </v-layout>
                    
                    <v-divider></v-divider>
                    <h3 class="text-center">Services</h3>
                    <v-divider></v-divider>
                    <v-layout row wrap>
                        <v-flex v-for="(service, index) in services" :key="index" xs6 sm4>
                            <v-checkbox v-model="form.selected" :label="service.name" :value="service.name"></v-checkbox>
                            <el-input placeholder="Please input" v-model="service.charges"></el-input>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="save" :loading="loading" :disabled="loading">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import { mapState } from 'vuex';
export default {
    // props: ['user'],
    data: () => ({
        dialog: false,
        form: {
            name: '',
            email: '',
            phone: '',
            address: '',
            company_name: '',
            company_email: '',
            company_phone: '',
            company_address: '',
            business_no: '',
            company_website: '',
            account_no: '',
            account_name: '',
            mpesa_name: '',
            mpesa_phone: '',
            payment_mode: 'bank',
            generate: 'input',
            selected: []
        },
    }),
    created() {
        eventBus.$on("openCreateSeller", data => {
            this.dialog = true;
            this.getServices()
            this.getOu()
        });
    },

    methods: {
        getServices() {
            var payload = {
                model: 'services',
                update: 'updateService',
            }
            this.$store.dispatch('getItems', payload);
        },
        save() {
            this.form.services = this.services
            this.form.ous = this.ous
            var payload = {
                data: this.form,
                model: '/seller/sellers',
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("sellerEvent")
                });
        },
        close() {
            this.dialog = false;
        },
        getOu() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload);
        }
    },
    computed: {
        ...mapState(['errors', 'loading', 'services', 'ous'])
    },
};
</script>
