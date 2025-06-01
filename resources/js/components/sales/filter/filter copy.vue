<template>
    <v-navigation-drawer
      absolute
      temporary
      right
      clipped
    >
    <!-- <v-bottom-sheet v-model="sheet"> -->
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="primary" dark v-bind="attrs" v-on="on" small outlined rounded>
                 <v-icon>mdi-filter</v-icon>
                Filter
            </v-btn>
        </template>
        <v-sheet class="text-center" height="200px">
            <v-btn class="mt-6" text color="error" @click="sheet = !sheet">
                close
            </v-btn>
            <div class="my-3">

                <v-layout row wrap>
                    <v-flex sm2 v-if="user.can['Order filter by OU']">
                        <label for="">Operating unit</label>
                        <el-select v-model="form.ou_id" placeholder="Select" filterable clearable>
                            <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.id"></el-option>
                        </el-select>
                    </v-flex>
                    <!-- <v-flex sm2 v-if="user.can['Order filter by zone']">
                        <label for="">Zones</label>
                        <el-select v-model="form.zone_id" placeholder="Select" filterable clearable>
                            <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id"></el-option>
                        </el-select>
                    </v-flex> -->
                    <v-flex sm2 v-if="user.can['Order filter by vendor']">
                        <label for="">Vendor</label>
                        <el-select v-model="form.client" multiple placeholder="Vendor" clearable filterable>
                            <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                            </el-option>
                        </el-select>
                    </v-flex>

                    <v-flex sm2 v-if="user.can['Order filter by status']">
                        <label>Status</label>
                        <el-select v-model="form.delivery_status" filterable clearable placeholder="Select" style="width: 100%;" multiple>
                            <el-option v-for="item in statuses" :key="item.id" :label="item.status" :value="item.status">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm2>
                        <div class="block">
                            <label style="float: left">Start date</label>
                            <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.start_date" type="date" placeholder="Pick a day" style="border-radius: 0 !important;width:100%">
                            </el-date-picker>
                        </div>
                    </v-flex>
                    <v-flex sm2 style="margin-left: 20px">
                        <div class="block">
                            <label style="float: left; width: 100%">End date</label>
                            <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.end_date" type="date" placeholder="Pick a day" style="width:100%">
                            </el-date-picker>
                        </div>
                    </v-flex>
                    <v-flex sm1 style="margin-left: 15px;">
                        <v-btn-toggle dense background-color="primary" dark style="margin-top: 22px;">
                            <v-btn @click="filter_orders">
                                <v-icon>mdi-magnify</v-icon>
                            </v-btn>
                        </v-btn-toggle>
                        <!-- <v-tooltip v-model="show" bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" @click="filter_orders">
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>
            </template>
            <span>Search</span>
        </v-tooltip> -->
                    </v-flex>
                    <!-- Return -->
                </v-layout>
            </div>
        </v-sheet>
    <!-- </v-bottom-sheet> -->

    </v-navigation-drawer>

</template>

<script>
import {
    mapState
} from "vuex";
export default {
    props: ['form', 'user'],
    data() {
        return {
            // form: {},
            show: false,
            sheet: false,
        }
    },
    computed: {
        ...mapState(['ous', 'zones', 'statuses', 'sellers'])
    },
    methods: {
        filter_orders() {
            // console.log(path, page, this.form);
            var payload = {
                model: 'sale_filter',
                // model: 'sale_filter',
                update: 'updateSaleList',
                data: this.form
            }
            this.$store.dispatch('filterItems', payload)
        },
        getCountries() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload);
        },
        getSellers() {
            var payload = {
                model: '/seller/sellers',
                update: 'updateSellerList'
            }
            this.$store.dispatch("getItems", payload);
        },
        next_page(path, page) {
            console.log(this.form);
            var payload = {
                data: this.form,
                path: path,
                page: page,
                update: 'updateSaleList'
            }
            this.$store.dispatch("nextPagePost", payload);
        }
    },
    created() {
        eventBus.$on('refreshEvent', data => {
            this.next_page(data.path, data.page)
        });
        eventBus.$on('refreshNextEvent', data => {
            this.next_page(data.path, data.page)
        });
        eventBus.$on('filterItemsEvent', data => {
            this.filter_orders()
        });
    },
    mounted() {
        this.getCountries();
        this.getSellers();
    },
}
</script>
