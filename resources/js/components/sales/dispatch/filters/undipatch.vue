<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline" style="margin: auto;">Order Update</span>
                </v-card-title>
                <VDivider />
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex sm12>
                                <label class="col-md-5 col-lg-5">Status</label>
                                <el-select allow-create v-model="form.status" filterable clearable placeholder="Select" style="width: 100%;">
                                    <el-option v-for="item in (multiple ? multipleStatus : statuses)" :key="item" :label="item" :value="item">
                                    </el-option>
                                </el-select>
                                <small class="has-text-danger" v-if="errors.status">{{ errors.status[0] }}</small>
                            </v-flex>
                            <v-flex sm12 v-if="form.status == 'Reschedule'">
                                <div class="block">
                                    <span class="demonstration" style="float: left">Schedule Date</span>
                                    <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" :picker-options="pickerOptions" v-model="form.delivery_date" type="date" placeholder="Pick a day" style="width: 100%;">
                                    </el-date-picker>
                                </div>
                                <small class="has-text-danger" v-if="errors.delivery_date">{{ errors.delivery_date[0] }}</small>
                            </v-flex>

                            <v-flex sm12 v-if="form.status == 'Redispatch'">
                                <label for="">Rider</label>
                                <el-select v-model="form.rider_id" placeholder="Select" filterable clearable style="width: 100%;">
                                    <el-option v-for="item in riders" :key="item.id" :label="item.name" :value="item.id"></el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm12 v-if="form.status == 'Redispatch'">
                                <label for="">Zone To</label>
                                <el-select v-model="form.zone_to" placeholder="Select" filterable clearable style="width: 100%;">
                                    <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id"></el-option>
                                </el-select>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                    <VSpacer />
                    <v-btn color="primary" @click="updateStatus" :loading="loading" :disabled="loading">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    name: 'saleStatus',
    data: () => ({
        statuses: ['Redispatch', 'Reschedule', 'Cancelled', 'Not picking', 'Busy', 'Will call back', 'Not Around', 'Not financially ready', 'Dublicate order', 'Dublicate of already delivered', 'Wrong order', 'Office pickup', 'Not Dispatched'],
        multiple: false,
        multipleStatus: ['Redispatch', 'Reschedule'],
        pickerOptions: {
                    disabledDate(time) {
                        return time.getTime() + 3600 * 1000 * 24 < Date.now();
                    },
                    shortcuts: [{
                        text: 'Today',
                        onClick(picker) {
                            picker.$emit('pick', new Date());
                        }
                    }, {
                        text: 'Tomorrow',
                        onClick(picker) {
                            const date = new Date();
                            date.setTime(date.getTime() + 3600 * 1000 * 24);
                            picker.$emit('pick', date);
                        }
                    }]
                },
                dialog: false,
                form: {
                    delivery_date: '',
                    status: '',
                    orders: []
                },
                zone_data: {
                    zone_from: null,
                    zone_to: null
                },
            }),
        created() {
            eventBus.$on('unDispatchEvent', data => {
                console.log(data);
                this.form.id = data.id
                this.form.orders = data.data;
                this.multiple = data.multiple
                this.dialog = true   
            })
        },
        methods: {
            getStatus() {
                var payload = {
                    model: 'statuses',
                    update: 'updateStatusList',
                }
                this.$store.dispatch("getItems", payload);
            },
            updateStatus() {

                if(this.multiple) {
                    var payload = {
                        model: 'undispatch_multiple_status',
                        data: this.form
                    }
                    this.$store.dispatch('postItems', payload)
                        .then(response => {
                            this.close()
                            // eventBus.$emit("saleEvent")
                        });
                } else {

                // this.form.zone_from = this.zone_data.zone_from
                // this.form.zone_to = this.zone_data.zone_to
                var payload = {
                    model: 'undispatch_status',
                    id: this.form.id,
                    data: this.form
                }
                this.$store.dispatch('patchItems', payload)
                    .then(response => {
                        this.close()
                        // eventBus.$emit("saleEvent")
                    });
                }
            },
            close() {
                this.dialog = false
            },

            getRiders() {
                var payload = {
                    model: 'rider/riders',
                    update: 'updateRidersList'
                }
                this.$store.dispatch("getItems", payload);
            },
            getZones() {
                var payload = {
                    model: 'zones',
                    update: 'updateZone',
                }
                this.$store.dispatch('getItems', payload);
            },
        },
        mounted() {
            this.getRiders();
            this.getZones();
        },
        computed: {
            ...mapState(['riders', 'errors', 'loading', 'zones'])
        },
    }
</script>
    