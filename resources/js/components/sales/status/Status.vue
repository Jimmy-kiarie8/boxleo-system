<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="600px">
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
                                <el-select v-model="form.delivery_status" filterable clearable placeholder="Select"
                                    style="width: 100%;">
                                    <el-option v-for="item in statuses" :key="item.id" :label="item.status"
                                        :value="item.status">
                                    </el-option>
                                </el-select>
                                <small class="has-text-danger" v-if="errors.delivery_status">{{
                                    errors.delivery_status[0] }}</small>
                            </v-flex>
                            <v-flex sm12 v-if="form.delivery_status == 'Scheduled'">
                                <div class="block">
                                    <span class="demonstration" style="float: left">Schedule Date</span>
                                    <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd"
                                        :picker-options="pickerOptions" v-model="form.delivery_date" type="date"
                                        placeholder="Pick a day" style="width: 100%;">
                                    </el-date-picker>
                                </div>
                                <small class="has-text-danger" v-if="errors.delivery_date">{{ errors.delivery_date[0]
                                }}</small>
                            </v-flex>
                            <v-flex sm12 v-if="form.delivery_status != 'Scheduled'">
                                <div class="block">
                                    <span class="demonstration" style="float: left">Recall Date</span>
                                    <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd"
                                        :picker-options="pickerOptions" v-model="form.recall_date" type="date"
                                        placeholder="Pick a day" style="width: 100%;">
                                    </el-date-picker>
                                </div>
                                <small class="has-text-danger" v-if="errors.delivery_date">{{ errors.delivery_date[0]
                                }}</small>
                            </v-flex>
                            <v-flex sm12 v-if="form.delivery_status == 'Dispatched'">
                                <label class="col-md-5 col-lg-5">Zone from</label>
                                <el-select v-model="form.zone_from" filterable clearable placeholder="Select"
                                    style="width: 100%;">
                                    <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id">
                                    </el-option>
                                </el-select>
                                <small class="has-text-danger" v-if="errors.zone_id">{{ errors.zone_id[0] }}</small>
                            </v-flex>
                            <v-flex sm12 v-if="showZoneTo">
                                <label class="col-md-5 col-lg-5">Zone to</label>
                                <el-select v-model="selectedZone" filterable clearable placeholder="Select"
                                    style="width: 100%;" @change="zoneToChanged">
                                    <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id">
                                    </el-option>
                                </el-select>
                                <small class="has-text-danger" v-if="errors.zone_id">{{ errors.zone_id[0] }}</small>
                            </v-flex>

                            <v-flex sm6 v-if="api_connect.mpesa && form.delivery_status == 'Delivered'">
                                <el-radio v-model="form.payment_method" border label="Cash">Cash</el-radio>
                                <el-radio v-model="form.payment_method" border label="Mpesa">M-pesa</el-radio>
                                <!-- <small class="has-text-danger" v-if="errors.zone_id">{{ errors.zone_id[0] }}</small> -->
                            </v-flex>

                            <v-flex sm6 v-if="form.delivery_status == 'Delivered'">
                                <el-radio v-model="form.partial_delivery" label="delivered">Delivered</el-radio>
                                <el-radio v-model="form.partial_delivery" label="partial_delivery">Partial
                                    delivery</el-radio>
                            </v-flex>

                            <v-flex sm12
                                v-if="form.delivery_status == 'Delivered' && form.partial_delivery == 'partial_delivery'">
                                <el-table :data="form.products" style="width: 100%">
                                    <el-table-column prop="product_name" label="Date" width="180">
                                    </el-table-column>
                                    <el-table-column prop="pivot.quantity_tobe_delivered" label="Expected items"
                                        width="180">
                                    </el-table-column>

                                    <el-table-column>
                                        <template slot-scope="scope">
                                            <el-input placeholder="Please input"
                                                v-model="scope.row.pivot.quantity_delivered"></el-input>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </v-flex>
                            <v-flex sm12
                                v-if="form.delivery_status == 'Delivered' && form.partial_delivery == 'partial_delivery'">
                                <label class="col-md-5 col-lg-5">New Cod</label>
                                <el-input placeholder="Please input" v-model="form.amount_paid"></el-input>
                            </v-flex>

                            <v-flex sm12
                                v-if="api_connect.mpesa && form.delivery_status == 'Delivered' && form.payment_method == 'Mpesa'">
                                <label class="col-md-5 col-lg-5">Mpesa Code</label>
                                <el-input placeholder="PH9..." v-model="form.mpesa_code"></el-input>
                                <small class="has-text-danger" v-if="errors.mpesa_code">{{ errors.mpesa_code[0]
                                }}</small>
                            </v-flex>
                            <v-flex sm12
                                v-if="form.delivery_status == 'Pending' || form.delivery_status == 'Cancelled'">
                                <label for="" style="color: #52627d;">Comments</label>

                                <el-select allow-create v-model="form.customer_notes" filterable clearable
                                    placeholder="Select" style="width: 100%;" v-if="form.delivery_status == 'Pending'">
                                    <el-option v-for="item in pendingReason" :key="item" :label="item" :value="item">
                                    </el-option>
                                </el-select>

                                <el-select allow-create v-model="form.customer_notes" filterable clearable
                                    placeholder="Select" style="width: 100%;"
                                    v-else-if="form.delivery_status == 'Cancelled'">
                                    <el-option v-for="item in cancelReason" :key="item" :label="item" :value="item">
                                    </el-option>
                                </el-select>
                                <br>

                            </v-flex>

                            <v-flex sm12>
                                <label for="" style="color: #52627d;">Comments</label>

                                <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4 }" placeholder="comments"
                                    v-model="form.customer_notes" maxlength="500" show-word-limit>
                                </el-input>
                                <small class="has-text-danger" v-if="errors.customer_notes">{{ errors.customer_notes[0]
                                }}</small>
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
        pendingReason: ["UNRESPONSIVE", "UNREACHABLE", "LINE BUSY", "CLIENT IS IN A MEETING, WILL CALL US BACK LATER", "CLIENT IS IN A MEETING, WANTS TO BE CALLED LATER", "HANGED UP ON CALLS", "CLIENT IS IN A NOISY PLACE", "CLIENT IS SILENT ON CALLS", "TO BE CALLED TOMORROW", "TO BE CALLED NEXT WEEK (dates to be indicated)", "WHATSAPP PHOTO SENT, AWAITING RESPONSE", "MERCHANT INFORMED, AWAITING RESPONSE", "OWNER OF THE PHONE IS NOT AROUND, WILL CALL US BACK"],
        cancelReason: ["CLIENT CHANGED THEIR MIND", "PRODUCT IS TOO EXPENSIVE", "DID NOT PLACE THE ORDER", "THE ORDER TOOK LONG", "WILL CALL AND REORDER WHEN READY FINANCIALLY", "WILL CALL AND REORDER WHEN BACK IN TOWN", "GOT A CHEAPER ALTERNATIVE", "WRONG NUMBER", "INCOMPLETE NUMBER", "NOT A KENYAN ORDER", "WILL CALL AND REORDER WHEN READY TO PICK THE PARCEL", "DOES NOT MEET THEIR EXPECTATIONS", "WAS ONLY INQUIRING", "WANTED A FURTHER DISCOUT", "NEVER PICKS PARCELS"],
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
            partial_delivery: 'delivered',
            zone_from: null,
            zone_to: null
        },
        selectedZone: null, // Track the selected zone separately for UI
        zone_data: {
            zone_from: null,
            zone_to: null
        },
        zoneSelectKey: 0, // Add a key to force component re-render
    }),
    created() {
        eventBus.$on('orderStatusEvent', data => {
            console.log('data');
            console.log(data);
            console.log('data');
            this.dialog = true

            data.payment_method = (data.payment_method == null) ? 'Cash' : data.payment_method;

            this.form = data
            // Initialize selectedZone from form data
            this.selectedZone = this.form.zone_to;

            // this.zone_data.zone_from = (data.zones.length > 0) ? data.zones[0].pivot.zone_id : null
            // this.zone_data.zone_to = (data.zones.length > 0) ? data.zones[0].id : null
            if (this.zones.length < 1) {
                this.getZones()
            }

            if (this.statuses.length < 1) {
                this.getStatus();
            }


            if (this.api_connect.length < 1) {
                this.getApi();
            }



            // this.getOrderZones(data.id)


        })
    },
    watch: {
        'form.delivery_status': function (newVal, oldVal) {
            // When status changes, update the selectedZone to match form.zone_to
            this.selectedZone = this.form.zone_to;
        },
        'form.zone_to': function (newVal) {
            // Keep selectedZone in sync with form.zone_to
            this.selectedZone = newVal;
        }
    },
    methods: {
        zoneToChanged(value) {
            // Update form.zone_to when selectedZone changes
            this.form.zone_to = value;
            console.log('Zone To Changed:', value);
            console.log('Form Zone To:', this.form.zone_to);
        },
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
        getOrderZones(id) {
            var payload = {
                model: 'sale_zones/' + id,
                update: 'UpdateEmpty',
            }
            this.$store.dispatch('getItems', payload).then(res => {
                console.log(res.data.zone_id)
                this.form.zone_to = res.data.zone_to;
                this.form.zone_from = res.data.zone_id;
                console.log('this');
                console.log(this.form);
            });
        },
        updateStatus() {
            // this.form.zone_from = this.zone_data.zone_from
            // this.form.zone_to = this.zone_data.zone_to

            // Ensure form.zone_to is set correctly before submitting
            if (this.selectedZone && (this.form.delivery_status === 'Scheduled' || this.form.delivery_status === 'Dispatched')) {
                this.form.zone_to = this.selectedZone;
            }

            console.log('Submitting form with zone_to:', this.form.zone_to);

            var payload = {
                model: 'status_update',
                id: this.form.id,
                data: this.form
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    this.close()
                    // eventBus.$emit("saleEvent")
                });
        },
        getApi() {
            var payload = {
                model: 'api_check',
                // id: 'shopify_key',
                update: 'updatApiConnection'
            }
            this.$store.dispatch('getItems', payload)
                .then(response => {

                });
        },
        close() {
            this.dialog = false
        }
    },
    mounted() {
    },
    computed: {
        ...mapState(['statuses', 'zones', 'errors', 'loading', 'api_connect']),
        showZoneTo() {
            return this.form.delivery_status === 'Dispatched' || this.form.delivery_status === 'Scheduled';
        }
    },
}
</script>
