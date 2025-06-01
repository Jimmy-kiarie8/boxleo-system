<template>
<v-dialog v-model="dialog" persistent max-width="600px" transition="dialog-transition">
    <v-card elevation="3">
        <v-card-title grey-title>
            {{ form.shopify_name }} Settings
            <v-spacer></v-spacer>
            <v-btn text icon color="primary" @click="close">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-flex sm12>
                <label for="">Sheet name</label>
                <el-input placeholder="Sheet1" v-model="form.sheet_name"></el-input>
                <!-- <small class="has-text-danger" v-if="errors.sheet_name">{{ errors.sheet_name[0] }}</small> -->
            </v-flex>
            <v-flex sm12>
                <label for="">Spreadsheet id</label>
                <el-input placeholder="" v-model="form.post_spreadsheet_id"></el-input>
                <!-- <small class="has-text-danger" v-if="errors.post_spreadsheet_id">{{ errors.post_spreadsheet_id[0] }}</small> -->
            </v-flex>
            <v-flex sm12>
                <label for="">Last update Order No.</label>
                <el-input placeholder="BLX001" v-model="form.lastUpdatedOrderNumber"></el-input>
                <!-- <small class="has-text-danger" v-if="errors.post_spreadsheet_id">{{ errors.post_spreadsheet_id[0] }}</small> -->
            </v-flex>
            <v-flex sm12>
                <label for="">Last time.</label>
                <el-date-picker  v-model="form.last_order_synced" type="datetime" placeholder="Select date and time"></el-date-picker>
            </v-flex>
            <label for="">Operating unit</label>
            <el-select v-model="form.country_id" filterable reserve-keyword placeholder="Operating unit" style="width: 100%;">
                <el-option v-for="item in countries" :key="item.id" :label="item.country" :value="item.id">
                </el-option>
            </el-select>
            <!-- <el-radio-group v-model="form.sync_option" @change="change_sync" style="width: 100%">
                <el-tooltip content="Automatically synchronize orders at a given time interval" placement="top">
                <el-radio label="Auto">Auto Sync</el-radio>
                </el-tooltip>
            </el-radio-group> -->
            <el-tooltip content="Automatically synchronize orders at a given time interval" placement="top">
                <v-checkbox label="Auto sync" v-model="form.auto_sync"></v-checkbox>
            </el-tooltip>
            <label for="">Time interval</label>
            <el-select v-model="form.sync_interval" filterable reserve-keyword placeholder="Time interval" style="width: 100%;" v-if="form.auto_sync">
                <el-option v-for="item in intervals" :key="item.value" :label="item.label" :value="item.value">
                </el-option>
            </el-select>
            <!-- <v-checkbox label="Enable product webhook" v-model="form.product_webhook"></v-checkbox>
            <v-checkbox label="Enable order webhook" v-model="form.order_webhook"></v-checkbox> -->
            <label for="">Order number prefix</label>
            <el-input placeholder="eg SHP_" v-model="form.order_prefix"></el-input>

            <el-radio-group v-model="form.sync_all">
                <el-radio :label="1">Sync all</el-radio>
                <el-radio :label="0">Sync scheduled only</el-radio>
            </el-radio-group>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="update" text>Update</v-btn>
        </v-card-actions>
    </v-card>
</v-dialog>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    data() {
        return {
            dialog: false,
            form: {},
            intervals: [{
                    value: 0.5,
                    label: '30 Minutes'
                },
                {
                    value: 1,
                    label: '1 Hour'
                },
                {
                    value: 2,
                    label: '2 Hours'
                },
                {
                    value: 4,
                    label: '4 Hours'
                },
                {
                    value: 24,
                    label: '1 Day'
                }
            ]
        }
    },
    methods: {
        close() {
            this.dialog = false
        },
        change_sync() {
            if (this.form.sync_option == 'Auto') {
                this.form.auto_sync = true

                this.form.product_webhook = false
                this.form.order_webhook = false
            } else if (this.form.sync_option == 'Webhook') {
                this.form.product_webhook = true
                this.form.order_webhook = true

                this.form.auto_sync = false
            }
        },
        getOu() {
            var payload = {
                model: 'countries',
                update: 'updateCountry',
            }
            this.$store.dispatch('getItems', payload)

        },
        update() {
            var payload = {
                model: 'sheet_update',
                id: this.form.id,
                data: this.form,
            }
            this.$store.dispatch('patchItems', payload)
        },
    },
    created() {
        eventBus.$on('GoogleSettingsEvent', data => {
            this.form = data
            this.dialog = true

            // if (this.countries.length < 1) {
            this.getOu()
            // }
        });
    },

    computed: {
        ...mapState(['countries'])
    },

}
</script>

<style>

</style>
