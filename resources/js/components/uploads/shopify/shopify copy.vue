<template>
<v-card>
    <v-card-title>
        Upload orders from Shopify
    </v-card-title>
    <v-container grid-list-md>
        <v-card-text>
            <div v-if="!user.is_vendor">
                <label for="">Vendor</label>
                <el-select name="client" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" v-model="form.vendor_id">
                    <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                    </el-option>
                </el-select>
            </div>
            <div style="height: 10px;"></div>
            <label for="">Warehouse</label>
            <el-select name="warehouse" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" v-model="form.warehouse_id">
                <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                </el-option>
            </el-select>
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
            orders: {},
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

        get_orders() {
            var payload = {
                model: 'google_sheets',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // console.log(response.data);
                    this.orders = response.data
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
            this.get_orders()
        })
    }
};
</script>
