<template>
<v-card>
    <v-card-title>
        Upload orders from Google sheets
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

            <v-row>
                <v-col sm="5">
                    <label for="">Order date from</label>
                    <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.start_date" type="date" placeholder="Pick a day" style="width: 100%;"></el-date-picker>
                </v-col>
                <v-col sm="5" offset-sm="1">
                    <label for="">Order date to</label>
                    <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.end_date" type="date" placeholder="Pick a day" style="width: 100%;"></el-date-picker>
                </v-col>
            </v-row>
            <label for="">Sheet Name</label>
            <el-input placeholder="" v-model="form.sheet_name"></el-input>

            <label for="">Worksheet</label>
            <el-input placeholder="" v-model="form.work_sheet"></el-input>

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

        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
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
                model: 'get_orders',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // console.log(response.data);
                    this.getProducts()
                    this.orders = response.data
                    if (response.data.sales.length > 0) {
                        eventBus.$emit('googleResponseEvent', response.data)
                    } else {
                        this.$message({
                            type: 'error',
                            message: 'No Data found'
                        });
                    }

                    // eventBus.$emit("MenuEvent")
                });
        },
        getProducts() {
            var payload = {
                model: 'client_products',
                update: 'updateProductsList',
                id: this.form.vendor_id
            }
            this.$store.dispatch("showItem", payload);
        },
    },
    computed: {
        ...mapState(['sellers', 'warehouses'])
    },
    created() {
        this.getSellers()
        this.getWarehouses()
        eventBus.$on('googleUploadEvent', data => {
            this.get_orders()
        })
    }
};
</script>
