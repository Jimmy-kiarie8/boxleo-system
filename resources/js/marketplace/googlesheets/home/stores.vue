<template>
<div>
    <v-card class="mx-auto" max-width="100%" outlined style="padding-bottom: 30px">
        <v-list-item three-line>
            <v-list-item-content>
                <div class="text-overline mb-4">
                    Logixsaas
                </div>
                <v-list-item-title class="text-h5 mb-1">
                    Google Sheets
                </v-list-item-title>
                <v-list-item-subtitle style="overflow: visible;">Integrating your googlesheets with Logixsaas bridges the gap between your sales channel and inventory, giving you the ability to tackle any number of online orders with ease while keeping the stock in continuous sync in both platforms.</v-list-item-subtitle>
            </v-list-item-content>

            <v-list-item-avatar tile size="200" style="width: 200px !important; height: 200px !important;margin: 0 !important;">
                <v-icon :color="s_color" style="font-size:190px;">mdi-google-spreadsheet</v-icon>
            </v-list-item-avatar>
        </v-list-item>

        <v-list-item three-line>
            <v-list-item-content>
                <div class="text-overline mb-4">
                    Share
                </div>
                <v-list-item-title class="text-h5 mb-1">
                    Google Sheets
                </v-list-item-title>
                <v-list-item-subtitle style="overflow: visible;">To connect, share your googlesheet with <b>sheets@courier-55c5e.iam.gserviceaccount.com</b>, then add Sheet. </v-list-item-subtitle>
            </v-list-item-content>

        </v-list-item>

        <v-card-actions>
            <v-btn outlined rounded text @click="openApi">
                Add Sheet
            </v-btn>
        </v-card-actions>
    </v-card>
    <v-divider></v-divider>

    <h3>Connected Stores</h3>
    <v-btn color="primary" text icon @click="getStores('Current')">
        <v-icon small>mdi-refresh</v-icon>
    </v-btn>

    <v-card-title>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
    </v-card-title>


    <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="Current" name="first"></el-tab-pane>
        <el-tab-pane label="All" name="second"></el-tab-pane>
    </el-tabs>

    <v-data-table :headers="headers" :items="sheets" :search="search" :loading="loading" class="elevation-1">
        <template v-slot:item.active="{ item }">
            <el-tooltip :content="(item.active) ? 'active' : 'Not active'" placement="top">
                <v-avatar style="cursor: pointer" :color="(item.active) ? 'green' : 'red'" small></v-avatar>
            </el-tooltip>
        </template>
        <template v-slot:item.is_current="{ item }">
            <el-tooltip :content="(item.is_current) ? 'Default' : 'Not Default'" placement="top">
                <v-avatar style="cursor: pointer" :color="(item.is_current) ? 'green' : 'red'" small></v-avatar>
            </el-tooltip>
        </template>
        <template v-slot:item.virtual="{ item }">
            <el-tooltip :content="(item.virtual) ? 'Inventory is not being tracked' : 'Inventory is being tracked' " placement="top">
                <v-avatar style="cursor: pointer" :color="(item.virtual) ? 'red' : 'green'" small></v-avatar>
            </el-tooltip>
        </template>
        <template v-slot:item.actions="{ item }">
            <div style="min-width: 160px">
                <v-tooltip bottom v-if="item.active">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="openStore(item)" slot="activator">
                            <v-icon small color="blue darken-2">mdi-eye</v-icon>
                        </v-btn>
                    </template>
                    <span>Open store</span>
                </v-tooltip>

                <v-tooltip bottom v-if="item.active">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="openSettings(item)" slot="activator">
                            <v-icon small color="blue darken-2">mdi-cogs</v-icon>
                        </v-btn>
                    </template>
                    <span>Store settings</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="changeStatus(item)" slot="activator">
                            <v-icon small :color="(item.active) ? 'success' : 'red'" v-text="(item.active) ? 'mdi-check-circle' : 'mdi-cancel'"></v-icon>
                        </v-btn>
                    </template>
                    <span v-if="item.active">Deactivate</span>
                    <span v-else>Activate</span>
                </v-tooltip>
                <v-tooltip bottom v-if="item.active">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="syncOrders(item.id)" slot="activator">
                            <v-icon small color="primary">mdi-sync-circle</v-icon>
                        </v-btn>
                    </template>
                    <span>Sync</span>
                </v-tooltip>

                <v-tooltip bottom v-if="item.active">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="syncSheet(item.id)" slot="activator">
                            <v-icon small color="success">mdi-file-sync</v-icon>
                        </v-btn>
                    </template>
                    <span>Push Updates</span>
                </v-tooltip>


                <v-tooltip bottom v-if="item.active">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="makeCurrent(item)" slot="activator" >
                            <v-icon small color="primary" v-if="item.is_current == false">mdi-thumb-up</v-icon>
                            <v-icon small color="red" v-else>mdi-thumb-down</v-icon>
                        </v-btn>
                    </template>
                    <span v-text="(item.is_current == false) ? 'Make default' : 'Make as not default'"></span>
                </v-tooltip>
            </div>
        </template>
    </v-data-table>

    <createStore />
    <settings />
</div>
</template>

<script>
import {
    mapState
} from 'vuex'
import createStore from '../../../components/users/sellers/sheets.vue'
import settings from './settings.vue'
export default {
    components: {
        createStore,
        settings
    },
    data() {
        return {
            activeName: 'first',
            search: '',
            s_color: '#47bf6c',

            headers: [{
                    text: 'Merchant',
                    value: 'vendor.name',
                }, {
                    text: 'Sheet Name',
                    value: 'sheet_name',
                },
                {
                    text: 'Status',
                    value: 'active',
                },
                {
                    text: 'Default',
                    value: 'is_current',
                },
                {
                    text: 'Last update',
                    value: 'last_order_synced',
                },
                {
                    text: 'Created On',
                    value: 'created_at',
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ]
        }
    },
    methods: {
        handleClick(tab, event) {
            // console.log(tab.label);

            this.getStores(tab.label)
        },
        openApi() {
            eventBus.$emit('openSheet', 0)
        },

        getStores(status) {
            var payload = {
                model: '/sheets?status=' + status,
                update: 'updateSheet'
            }
            this.$store.dispatch("getItems", payload);
        },
        openStore(data) {
            this.$router.push({
                name: "google_sync",
                params: {
                    id: data.id,
                    last_sync: data.last_order_synced,
                }
            });

        },
        syncSheet(id) {
            var payload = {
                model: 'sheet_sync/' + id,
                data: {
                    id: id
                },
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {});
        },
        syncOrders(id) {
            var payload = {
                model: 'google_sync/' + id,
                data: {
                    id: id
                },
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {});
        },
        makeCurrent(data) {
            var payload = {
                model: 'google_current',
                id: data.id,
                data: data
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    this.getStores('Current')
                });
        },
        changeStatus(data) {
            var payload = {
                model: 'google_status',
                id: data.id,
                data: data
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    this.getStores('Current')
                });
        },
        openSettings(data) {
            eventBus.$emit('GoogleSettingsEvent', data)
        },

    },
    computed: {
        ...mapState(['sheets', 'loading'])
    },
    mounted() {
        this.getStores('Current');
    },
}
</script>
