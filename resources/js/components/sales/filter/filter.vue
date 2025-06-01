<template>
    <v-navigation-drawer absolute right v-model="sheet" clipped>
        <template v-slot:prepend>

            <!-- <v-list disabled>
            <v-subheader>Filter</v-subheader>
            <v-list-item-group color="primary">
                <v-list-item>
                        
                    <v-list-item-content>
                        <v-list-item-title>
                            Filter
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-list> -->

            <v-layout row wrap>
                <v-flex sm9 style="margin-left: 10px">
                    <v-list disabled>
                        <v-subheader>Filter</v-subheader>

                    </v-list>
                </v-flex>
                <v-flex sm1>
                    <v-btn class="mx-2" dark rounded small @click="close">
                        <v-icon dark>
                            mdi-close
                        </v-icon>
                    </v-btn>
                </v-flex>
            </v-layout>

            <v-divider></v-divider>
            <v-list dense>
                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>
                            <label for="">Operating unit</label>
                        </v-list-item-title>
                        <v-list-item-subtitle>
                            <el-select v-model="form.ou_id" placeholder="Select" filterable clearable
                                style="width: 100%;">
                                <el-option v-for="item in ous" :key="item.id" :label="item.ou"
                                    :value="item.id"></el-option>
                            </el-select>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item link v-if="!user.is_vendor">
                    <v-list-item-content>
                        <v-list-item-title>
                            <label for="">Vendor</label>
                        </v-list-item-title>
                        <v-list-item-subtitle>

                            <el-select v-model="form.client" multiple placeholder="Vendor" clearable filterable
                                style="width: 100%;">
                                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name"
                                    :value="item.id">
                                </el-option>
                            </el-select>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>



                <v-list-item link v-if="!user.is_vendor">
                    <v-list-item-content>
                        <v-list-item-title>
                            <label for="">Agent</label>
                        </v-list-item-title>
                        <v-list-item-subtitle>
                            <el-select v-model="form.agent_id" filterable clearable placeholder="Select"
                                style="width: 100%;">
                                <el-option v-for="item in users" :key="item.id" :label="item.name"
                                    :value="item.id"></el-option>
                            </el-select>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>



                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>

                            <label>Status</label>
                        </v-list-item-title>
                        <v-list-item-subtitle>

                            <el-select v-model="form.delivery_status" filterable clearable placeholder="Select"
                                style="width: 100%;" multiple>
                                <el-option v-for="item in statuses" :key="item.id" :label="item.status"
                                    :value="item.status">
                                </el-option>
                            </el-select>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>
                            <label style="float: left; width: 100%">Created On</label>
                        </v-list-item-title>
                        <v-list-item-subtitle>
                            <el-date-picker v-model="form.created_at" type="daterange" align="right"
                                start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                            </el-date-picker>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>
                            <label style="float: left; width: 100%">Delivery date</label>
                        </v-list-item-title>
                        <v-list-item-subtitle>
                            <el-date-picker v-model="form.delivery_date" type="daterange" align="right"
                                start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                            </el-date-picker>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>
                            <label style="float: left; width: 100%">Delivered on</label>
                        </v-list-item-title>
                        <v-list-item-subtitle>
                            <el-date-picker v-model="form.delivered_on" type="daterange" align="right"
                                start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                            </el-date-picker>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>
                            <label style="float: left; width: 100%">Returned on</label>
                        </v-list-item-title>
                        <v-list-item-subtitle>
                            <el-date-picker v-model="form.returned_on" type="daterange" align="right"
                                start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                            </el-date-picker>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>
                            <label style="float: left; width: 100%">Dispatched on</label>
                        </v-list-item-title>
                        <v-list-item-subtitle>
                            <el-date-picker v-model="form.dispatched_on" type="daterange" align="right"
                                start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                            </el-date-picker>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>
                            <label style="float: left; width: 100%">Recall on</label>
                        </v-list-item-title>
                        <v-list-item-subtitle>
                            <el-date-picker v-model="form.recall_date" type="daterange" align="right"
                                start-placeholder="Start Date" end-placeholder="End Date" style="width: 100%;"
                                format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                            </el-date-picker>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>
                            <v-btn-toggle dense background-color="primary" dark style="margin-top: 22px;">
                                <v-btn @click="filter_orders">
                                    <v-icon>mdi-magnify</v-icon>
                                </v-btn>
                            </v-btn-toggle>
                        </v-list-item-title>

                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </template>
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
            lastRequestTimestamp: 0,
            lastRequestCount: 0,
        }
    },
    computed: {
        ...mapState(['ous', 'zones', 'statuses', 'sellers', 'users'])
    },
    methods: {
        close() {
            this.sheet = false;
            eventBus.$emit('closeFilterEvent')

        },
        filter_orders() {
            const now = Date.now();

            if (now - this.lastRequestTimestamp <= 2000) { // 2000 milliseconds = 2 seconds
                return;
            }
            var payload = {
                model: 'sale_filter',
                update: 'updateSaleList',
                data: this.form
            }
            this.$store.dispatch('filterItems', payload).then((res) => {
                this.lastRequestTimestamp = now;
                this.close();
            }).catch((error) => {
                this.lastRequestTimestamp = now;
            });
        },
        download_orders() {
            const now = Date.now();

            if (now - this.lastRequestTimestamp <= 2000) { // 2000 milliseconds = 2 seconds
                return;
            }
            var payload = {
                model: 'sale-report',
                data: this.form
            }
            this.$store.dispatch('postItems', payload).then((res) => {
                this.$message({
                    type: 'success',
                    message: 'Please check your email for a link!'
                });
                console.log("🚀 ~ this.$store.dispatch ~ res:", res)
                this.lastRequestTimestamp = now;
                this.close();
            }).catch((error) => {
                this.$message({
                    type: 'error',
                    message: 'Something went wrong'
                });
                this.lastRequestTimestamp = now;
            });
        },
        getOus() {
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
            const now = Date.now();
            this.lastRequestCount += 1;
            console.log("🚀 ~ file: filter.vue:243 ~ next_page ~ now - this.lastRequestCount:", (this.lastRequestCount))
            console.log("🚀 ~ file: filter.vue:243 ~ next_page ~ now - this.lastRequestTimestamp:", (this.lastRequestTimestamp))

            if (now - this.lastRequestTimestamp <= 2000) { // 2000 milliseconds = 2 seconds
                return;
            }
            var payload = {
                data: this.form,
                path: path,
                page: page,
                update: 'updateSaleList'
            }
            this.$store.dispatch("nextPagePost", payload).then((res) => {
                this.lastRequestTimestamp = now;
            }).catch((error) => {
                this.lastRequestTimestamp = now;
            });
        },

        getUsers() {
            var payload = {
                model: 'agents',
                update: 'updateUsersList',
            }
            this.$store.dispatch("getItems", payload);
        },
    },
    created() {
        eventBus.$on('refreshEvent', data => {
            console.log("🚀 ~ file: filter.vue:251 ~ created ~ data:", data)
            this.form.status = data.tab;
            this.next_page(data.path, data.page)
        });
        eventBus.$on('refreshNextEvent', data => {
            this.form.status = data.tab;
            this.next_page(data.path, data.page)
        });
        eventBus.$on('filterItemsEvent', data => {
            this.form.status = data.tab;
            this.filter_orders()
        });


        eventBus.$on('downloadItemsEvent', data => {
            this.form.status = data.tab;
            this.download_orders()
        });

        eventBus.$on('openFilterEvent', data => {
            this.sheet = true
            this.overlay = true

        // ...mapState(['ous', 'zones', 'statuses', 'sellers', 'users'])


            if (this.ous.length < 1) {
                this.getOus();
            }
            if (this.sellers.length < 1) {
                this.getSellers();
            }
            if (this.users.length < 1) {
                this.getUsers();
            }
        });
    },
    mounted() {
    },

}
</script>

<style>
#filter aside {
    /* margin-top: -7%; */
    width: 500px !important;
    z-index: 5;
    margin-right: -10px;
    /* height: 100vh !important; */
    position: fixed !important;
    overflow-y: scroll;
}

#filter .v-navigation-drawer__prepend {
    margin-top: 30px !important;

}
</style>
