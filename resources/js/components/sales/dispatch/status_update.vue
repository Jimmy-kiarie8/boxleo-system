<template>
    <div>
        <v-container fluid fill-height>
            <v-layout justify-center align-center wrap>
                <v-flex sm12>
                    <v-card style="padding: 20px 0;">
                        <el-breadcrumb separator-class="el-icon-arrow-right">
                            <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                            <el-breadcrumb-item>Status Update</el-breadcrumb-item>
                        </el-breadcrumb>
                    </v-card>
                </v-flex>
                <v-flex sm12>
                    <v-card style="padding: 10px 10px;">
                        <v-layout row wrap style="margin-left: 5px">
                            <v-flex sm2 v-if="user.roles[0].name == 'Super admin'">
                                <label for="">Order status</label>
                                <el-select v-model="form.delivery_status" filterable clearable placeholder="Select"
                                    style="width: 100%;">
                                    <el-option v-for="(item, index) in admin_statuses" :key="index" :label="item.status"
                                        :value="item.status">
                                    </el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm2 v-else-if="user.roles[0].name == 'Call center'">
                                <label for="">Order status</label>
                                <el-select v-model="form.delivery_status" filterable clearable placeholder="Select"
                                    style="width: 100%;">
                                    <el-option v-for="(item, index) in call_statuses" :key="index" :label="item.status"
                                        :value="item.status">
                                    </el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm2 v-else-if="user.roles[0].name == 'Agent' || user.can['Update Awaiting Return']">
                                <label for="">Order status</label>
                                <el-select v-model="form.delivery_status" filterable clearable placeholder="Select"
                                    style="width: 100%;">
                                    <el-option v-for="(item, index) in agentStatuses" :key="index" :label="item.status"
                                        :value="item.status">
                                    </el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm2 v-else-if="user.can['Order update delivered or returned']">
                                <label for="">Order status</label>
                                <el-select v-model="form.delivery_status" filterable clearable placeholder="Select"
                                    style="width: 100%;">
                                    <el-option v-for="(item, index) in statuses" :key="index" :label="item.status"
                                        :value="item.status">
                                    </el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm2 v-if="user.can['Order update delivered or returned']">
                                <label for="">Update Date</label>
                                <el-date-picker v-model="form.update_date" type="date" placeholder="Date" style="width: 100%;" format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                                </el-date-picker>
                            </v-flex>
                            <v-flex sm1 style="margin-left: 5px; margin-top: 30px"
                                v-if="user.can['Order update delivered or returned'] || user.roles[0].name == 'Agent' || user.can['Update Awaiting Return']">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn icon v-bind="attrs" v-on="on" @click="dispatch" color="primary">
                                            <v-icon>mdi-update</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Update</span>
                                </v-tooltip>
                            </v-flex>
                            <v-spacer></v-spacer>
                            <!-- Return -->
                        </v-layout>
                        <v-divider></v-divider>

                        <v-flex sm6 style="margin-right: 20px; margin-top: 30px">
                            <el-radio v-model="radio" label="1">Scan one by one</el-radio>
                            <el-radio v-model="radio" label="2">Input waybills</el-radio>
                            <v-text-field v-model="form.search" append-icon="mdi-magnify" label="Scan"
                                @keyup.enter="add_order" v-if="radio == 1"></v-text-field>
                            <div v-else>
                                <label for="">Input waybills separated by commas or a new line</label>
                                <el-input type="textarea" :rows="3" placeholder="Please input" v-model="data">
                                </el-input>
                                <v-spacer></v-spacer>

                                <v-btn small elevation="3" color="primary" text @click="get_waybills">Search</v-btn>
                            </div>
                        </v-flex>
                        <v-divider></v-divider>

                        <v-layout row>
                            <v-flex sm1 style="margin-left: 10px;">
                                <v-tooltip right>
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="reset">
                                            <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Reset</span>
                                </v-tooltip>
                            </v-flex>
                            <v-flex sm2>
                                <h3 style="margin-left: 30px !important;margin-top: 10px;">Status</h3>
                            </v-flex>
                        </v-layout>
                        <v-flex sm12>
                            <v-data-table :headers="headers" :items="orders" :search="search" :loading="loading">
                                <template v-slot:item.actions="{ item, index }">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="remove(index)" slot="activator">
                                                <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Remove</span>
                                    </v-tooltip>
                                </template>
                                <template v-slot:item.return_notes="props">
                                    <v-edit-dialog :return-value.sync="props.item.return_notes" large persistent
                                        @cancel="cancel" @open="open_dialog" @close="close"
                                        @save="update_data(props.item)">
                                        {{ props.item.return_notes }}
                                        <template v-slot:input>
                                            <div class="mt-4 title" style="color: #333">
                                                Update Reason
                                            </div>
                                            <el-select allow-create v-model="props.item.return_notes" filterable
                                                clearable placeholder="Select" style="width: 100%;">
                                                <el-option v-for="(item, index) in reasons" :key="index" :label="item"
                                                    :value="item"></el-option>
                                            </el-select>
                                        </template>
                                    </v-edit-dialog>

                                </template>
                            </v-data-table>

                        </v-flex>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
import {
    mapState
} from 'vuex';

export default {
    props: ['user'],
    data() {
        return {
            indexUpdate: null,
            radio: '1',
            data: '',
            search: '',
            form: {
                search: '',
                zone_from: null,
                zone_to: null,
                rider_id: null,
            },
            orders: [],
            headers: [{
                text: "Created On",
                value: "created_at"
            },
            {
                text: "Order No",
                value: "order_no"
            },
            {
                text: "Client Name",
                value: "client_name"
            },
            {
                text: "Client Address",
                value: "client_address"
            },
            {
                text: "Client Phone",
                value: "client_phone"
            },
            {
                text: "Delivery Status",
                value: "delivery_status"
            },
            {
                text: "Reason",
                value: "return_notes"
            },
            {
                text: "Total",
                value: "total_price"
            },
            {
                text: "Actions",
                value: "actions",
                sortable: false
            }
            ],
            statuses: [{
                status: 'Delivered'
            }, {
                status: 'Returned'
            }, {
                status: 'Received'
            },{
                status: 'Pending'
            },{
                status: 'Cancelled'
            }],
            admin_statuses: [{
                status: 'Delivered'
            }, {
                status: 'Returned'
            }, {
                status: 'Received'
            },{
                status: 'Cancelled'
            },{
                status: 'Pending'
            },{
                status: 'In Transit'
            },{
                status: 'Scheduled'
            }],
            call_statuses: [{
                status: 'Cancelled'
            }],
            agentStatuses: [{
                status: 'Awaiting Return'
            }],

            reasons: ['Not picking calls', 'Not Financially Ready', 'Assign another rider', 'Office pickup', 'Offline', 'Schedule another day', 'Outbound order', 'Cancelled', 'User Busy', 'Unresponsive', 'Dublicate order', ' Dublicate of already delivered', 'Not interested', 'Wrong order', 'Will call later', 'Client not around', 'Custom']
        }
    },

    methods: {
        close() { },
        cancel() { },
        open_dialog() { },
        update_data(data) {
            console.log("🚀 ~ file: status_update.vue:176 ~ update_data ~ data:", data)
        },
        reset() {
            this.orders = []
        },
        dispatch() {
            var data = {
                orders: this.orders,
                status: this.form.delivery_status,
                update_date: this.form.update_date,
                // 'zone_to': this.form.zone_to,
                // 'rider_id': this.form.rider_id,
            }
            var payload = {
                model: 'update_order_status',
                data: data
            }
            this.$store.dispatch('postItems', payload).then((response) => {
                // console.log(response);
                this.reset()
                // this.orders.push(response.data)
            }).catch((error) => {
                console.log(error);
            })
        },
        get_waybills() {
            var payload = {
                model: 'get_dispatch?bulkupdate=true',
                update: 'UpdateEmpty',
                data: {
                    data: this.data
                }
            }
            this.$store.dispatch('filterItems', payload).then((response) => {
                // console.log(response);
                response.data.forEach(element => {

                    this.orders.push(element)
                    /*var exists = false
                    if (this.orders.length > 0) {
                        for (let index = 0; index < this.orders.length; index++) {
                            console.log('2222222222222222222222222222222222');
                            const item = this.orders[index];
                            if (item.order_no == element.order_no) {
                                console.log('boom');
                                exists = true
                            } else {
                                console.log('121');
                                this.orders.push(element)
                            }
                        }
                    } else {
                        this.orders.push(element)
                    }*/

                    // this.orders.forEach(order_el => {
                    //     if (order_el.order_no == element.order_no) {
                    //         console.log('boom');
                    //         exists = true
                    //     } else {
                    //         console.log('121');
                    //         this.orders.push(element)
                    //     }
                    // });

                });
                // if (exists) {
                //     this.$message({
                //         type: 'error',
                //         message: 'Some orders are already scanned'
                //     });
                // }
            }).catch((error) => {
                console.log(error);
            })
        },
        getStatus() {
            var payload = {
                model: 'statuses',
                update: 'updateStatusList',
            }
            this.$store.dispatch("getItems", payload);
        },
        add_order() {
            var exists = false
            this.orders.forEach(element => {
                if (element.order_no == this.form.search) {
                    exists = true
                    this.$message({
                        type: 'error',
                        message: 'Order already scanned'
                    });
                }
            });

            if (!exists) {
                var payload = {
                    model: 'scan_status',
                    update: 'updateSaleList',
                    search: this.form.search
                }
                this.$store.dispatch('searchItems', payload).then((response) => {
                    // console.log(response);
                    this.form.search = ''
                    this.orders.push(response.data)
                }).catch((error) => {
                    console.log(error);
                })
            }

        },
        remove(index) {
            this.orders.splice(index, 1)
        },

        getZones() {
            var payload = {
                model: 'zones',
                update: 'updateZone',
            }
            this.$store.dispatch('getItems', payload);
        },
        getRiders() {
            var payload = {
                model: 'rider/riders',
                update: 'updateRidersList'
            }
            this.$store.dispatch("getItems", payload);
        },
    },
    computed: {
        ...mapState(['loading'])
    },
    mounted() {
        // this.getZones();
        // this.getStatus();
    },
};
</script>

<style scoped></style>
