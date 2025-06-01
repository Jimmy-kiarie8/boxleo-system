<template>
<div>
    <v-card class="mx-auto" max-width="100%" outlined style="padding-bottom: 30px">
        <v-list-item three-line>
            <v-list-item-content>
                <div class="text-overline mb-4">
                    Logixsaas
                </div>
                <v-list-item-title class="text-h5 mb-1">
                    Shopify
                </v-list-item-title>
                <v-list-item-subtitle style="overflow: visible;">Integrating your Shopify store with Logixsaas bridges the gap between your sales channel and inventory, giving you the ability to tackle any number of online orders with ease while keeping the stock in continuous sync in both platforms.</v-list-item-subtitle>
            </v-list-item-content>

            <v-list-item-avatar tile size="200" style="width: 200px !important; height: 200px !important;margin: 0 !important;">
                <!-- <img src="https://d12h6dzwzn4m10.cloudfront.net/zbooks/assets/images/svgs/zom/shopify-logo.svg" alt=""> -->
                <v-icon :color="s_color" style="font-size:190px;">fab fa-shopify</v-icon>
            </v-list-item-avatar>
        </v-list-item>

        <v-card-actions>
            <v-btn outlined rounded text @click="openApi">
                Add Store
            </v-btn>
        </v-card-actions>
    </v-card>
    <v-divider></v-divider>

    <h3>Connected Stores</h3>
    <v-btn color="primary" text icon @click="getStores">
        <v-icon small>mdi-refresh</v-icon>
    </v-btn>

    <el-table :data="shopify_stores" style="width: 100%" v-loading="loading">
        <el-table-column prop="shopify_name" label="Store Name" width="180"></el-table-column>
        <el-table-column prop="active" label="Status" width="180">
            <template slot-scope="scope">
                <v-btn icon class="mx-0" slot="activator">
                    <v-icon small :color="(scope.row.active) ? 'success' : 'red'" v-text="(scope.row.active) ? 'mdi-check-circle' : 'mdi-cancel'"></v-icon>
                </v-btn>
            </template>
        </el-table-column>
        <el-table-column prop="created_at" label="Created on" width="180"></el-table-column>
        <el-table-column label="Actions">
            <template slot-scope="scope">
                <v-tooltip bottom v-if="scope.row.active">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="openStore(scope.row)" slot="activator">
                            <v-icon small color="blue darken-2">mdi-eye</v-icon>
                        </v-btn>
                    </template>
                    <span>Open store</span>
                </v-tooltip>

                <v-tooltip bottom v-if="scope.row.active">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="openSettings(scope.row)" slot="activator">
                            <v-icon small color="blue darken-2">mdi-cogs</v-icon>
                        </v-btn>
                    </template>
                    <span>Store settings</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="changeStatus(scope.row)" slot="activator">
                            <v-icon small :color="(scope.row.active) ? 'success' : 'red'" v-text="(scope.row.active) ? 'mdi-check-circle' : 'mdi-cancel'"></v-icon>
                        </v-btn>
                    </template>
                    <span v-if="scope.row.active">Deactivate</span>
                    <span v-else>Activate</span>
                </v-tooltip>

                <v-tooltip bottom  v-if="scope.row.active">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="webhook(scope.row)" slot="activator">
                            <v-icon small color="success">mdi-webhook</v-icon>
                        </v-btn>
                    </template>
                    <span>Activate webhook</span>
                </v-tooltip>
            </template>
        </el-table-column>
    </el-table>

    <createStore />
    <settings />
</div>
</template>

<script>
import {
    mapState
} from 'vuex'
import createStore from '../../../components/settings/company/inc/api.vue'
import settings from './settings.vue'
export default {
    components: {
        createStore,
        settings
    },
    data() {
        return {
            s_color: '#95bf47',
        }
    },
    methods: {
        openApi() {
            var data = {
                site: 'Shopify',
                value: 'shopify',
                icon: 'fab fa-shopify',
            }

            eventBus.$emit('ApiConnectEvent', data)
        },

        getStores() {
            var payload = {
                model: '/shopify_stores',
                update: 'updateShopifyStore'
            }
            this.$store.dispatch("getItems", payload);
        },
        openStore(data) {
            this.$router.push({
                name: "sync",
                params: {
                    id: data.id
                }
            });

        },
        changeStatus(data) {
            var payload = {
                model: 'shopify_status',
                id: data.id,
                data: data,
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    this.getStores()
                });
        },
        openSettings(data) {
            eventBus.$emit('settingsEvent', data)
        },
        webhook(data) {
            var payload = {
                model: 'webhook_create',
                id: data.id,
                data: data,
            }
            this.$store.dispatch('patchItems', payload)
                .then(() => {
                    this.getStores()
                });
        },

    },
    computed: {
        ...mapState(['shopify_stores', 'loading'])
    },
    mounted() {
        this.getStores();
    },
}
</script>
