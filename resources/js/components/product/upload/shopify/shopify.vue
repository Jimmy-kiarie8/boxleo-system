<template>
<v-card>
    <v-card-title>
        Upload products from Shopify
    </v-card-title>
    <v-container grid-list-md>
        <v-card-text>
            <v-layout row wrap>
                <v-flex sm5 v-if="!user.is_vendor">
                    <label for="">Vendor</label>
                    <el-select name="client" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" v-model="form.vendor_id">
                        <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </v-flex>
                <!-- <div style="height: 10px;"></div> -->
                <v-flex sm5>
                    <label for="">Warehouse</label>
                    <el-select name="warehouse" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" v-model="form.warehouse_id">
                        <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </v-flex>
                <v-flex sm5>
                    <label for="">Start date</label>
                    <el-date-picker v-model="form.start_date" type="date" placeholder="Pick a Date" format="yyyy/MM/dd" style="width: 100%">
                    </el-date-picker>
                </v-flex>
                <v-flex sm5>
                    <label for="">End date</label>
                    <el-date-picker v-model="form.end_date" type="date" placeholder="Pick a Date" format="yyyy/MM/dd"  style="width: 100%">
                    </el-date-picker>
                </v-flex>
            </v-layout>
        </v-card-text>
    </v-container>
</v-card>
</template>

<script>
import {
    mapState
} from "vuex";
export default {
    props: ['form', 'user'],
    data() {
        return {
            dialog: false,
            loading: false,
            products: {},
        };
    },
    methods: {
        disable_btn() {
            this.loading = true
            this.$refs.google_form.submit()
        },
        close() {
            this.dialog = false;
        },

        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload);
        },

        get_products() {
            var payload = {
                model: 'google_sheets',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // console.log(response.data);
                    this.products = response.data
                    eventBus.$emit('googleResponseEvent', response.data)

                    // eventBus.$emit("MenuEvent")
                });
        },
    },
    computed: {
        ...mapState(['sellers', 'warehouses'])
    },
    created() {
        // this.getSellers()
        // this.getWarehouses()
        eventBus.$on('googleUploadEvent', data => {
            this.get_products()
        })
    }
};
</script>
