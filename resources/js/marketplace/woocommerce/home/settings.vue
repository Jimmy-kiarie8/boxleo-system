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
            <label for="">Operating unit</label>
            <el-select v-model="form.ou_id" filterable reserve-keyword placeholder="Operating unit" style="width: 100%;" v-if="form.auto_sync">
                <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.value">
                </el-option>
            </el-select>
            <el-radio-group v-model="form.sync_option" @change="change_sync" style="width: 100%">
                <el-tooltip content="Automatically synchronize orders at a given time interval" placement="top">
                <el-radio label="Auto">Auto Sync</el-radio>
                </el-tooltip>
                <el-tooltip content="Automatically create an order&products after it is created on shopify" placement="top">
                <el-radio label="Webhook">Webhooks</el-radio>
                </el-tooltip>
            </el-radio-group>
            <!-- <v-checkbox label="Auto sync" v-model="form.auto_sync"></v-checkbox> -->
            <label for="">Time interval</label>
            <el-select v-model="form.sync_interval" filterable reserve-keyword placeholder="Time interval" style="width: 100%;" v-if="form.auto_sync">
                <el-option v-for="item in intervals" :key="item.value" :label="item.label" :value="item.value">
                </el-option>
            </el-select>
            <!-- <v-checkbox label="Enable product webhook" v-model="form.product_webhook"></v-checkbox>
            <v-checkbox label="Enable order webhook" v-model="form.order_webhook"></v-checkbox> -->
            <label for="">Order number prefix</label>
            <el-input placeholder="eg SHP_" v-model="form.order_prefix"></el-input>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="update" text>Update</v-btn>
        </v-card-actions>
    </v-card>
</v-dialog>
</template>

<script>
import { mapState } from 'vuex'
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
            } else if(this.form.sync_option == 'Webhook') {
                this.form.product_webhook = true
                this.form.order_webhook = true

                this.form.auto_sync = false
            }
        },
        getOu() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload)

        },
        update() {
            var payload = {
                model: 'woocommerce_settings',
                id: this.form.id,
                data: this.form,
            }
            this.$store.dispatch('patchItems', payload)
        },
    },
    created() {
        eventBus.$on('WoocommerceSettingsEvent', data => {
            this.form = data
            this.dialog = true
                this.getOu()
        });
    },

    computed: {
        ...mapState(['ous'])
    },

}
</script>

<style>

</style>
