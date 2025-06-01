<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="400px">
        <v-card>
            <v-card-title>
                <span class="headline" style="margin: auto;">Order Details</span>
            </v-card-title>
            <VDivider />
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex sm12>
                            <label class="col-md-5 col-lg-5">Status</label>
                            <el-select v-model="form.delivery_status" filterable clearable placeholder="Select" style="width: 100%;">
                                <el-option v-for="item in statuses" :key="item.id" :label="item.status" :value="item.status">
                                </el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.delivery_status">{{ errors.delivery_status[0] }}</small>
                        </v-flex>
                        <v-flex sm12 v-if="form.delivery_status == 'Scheduled'">
                            <div class="block">
                                <span class="demonstration" style="float: left">Schedule Date</span>
                                <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" :picker-options="pickerOptions" v-model="form.delivery_date" type="date" placeholder="Pick a day" style="width: 100%;">
                                </el-date-picker>
                            </div>
                            <small class="has-text-danger" v-if="errors.delivery_date">{{ errors.delivery_date[0] }}</small>
                        </v-flex>
                        <v-flex sm12 v-if="form.delivery_status == 'Dispatched'">
                            <label class="col-md-5 col-lg-5">Zone from</label>
                            <el-select v-model="form.zone_from" filterable clearable placeholder="Select" style="width: 100%;">
                                <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.zone_from">{{ errors.zone_from[0] }}</small>
                        </v-flex>
                        <v-flex sm12 v-if="form.delivery_status == 'Dispatched'">
                            <label class="col-md-5 col-lg-5">Zone to</label>
                            <el-select v-model="form.zone_to" filterable clearable placeholder="Select" style="width: 100%;">
                                <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.zone_to">{{ errors.zone_to[0] }}</small>
                        </v-flex>
                        <v-flex sm12>
                            <label for="" style="color: #52627d;">Comments</label>
                            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" placeholder="comments" v-model="form.customer_notes" maxlength="500" show-word-limit>
                            </el-input>
                            <small class="has-text-danger" v-if="errors.customer_notes">{{ errors.customer_notes[0] }}</small>
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
    name: 'orderStatus',
    data: () => ({
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
            customer_notes: "",
            delivery_date: "",
            delivery_status: "",
            zone_from: null,
            zone_to: null
        },
        zone_data: {
            zone_from: null,
            zone_to: null
        },
    }),
    created() {
        eventBus.$on('multStatusEvent', data => {
            console.log(data);
            this.dialog = true
            this.selected = data

            if (this.zones.length < 1) {
                this.getZones()
            }
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
        getZones() {
            var payload = {
                model: 'zones',
                update: 'updateZone',
            }
            this.$store.dispatch('getItems', payload);
        },
        updateStatus() {
            var payload = {
                model: '/sales_update',
                data: {
                    form: this.form,
                    orders: this.selected
                }
            }
            this.$store.dispatch("postItems", payload).then(() => {
                this.close()
                eventBus.$emit("saleEvent", 'mult')
                this.form = {
                    customer_notes: "",
                    delivery_date: "",
                    delivery_status: "",
                    zone_from: null,
                    zone_to: null
                }
            });
        },
        close() {
            this.dialog = false
        }
    },
    mounted() {
        this.getStatus();
    },
    computed: {
        ...mapState(['statuses', 'zones', 'errors', 'loading'])
    },
}
</script>
