<template>
<v-container fluid fill-height id="orders">
    <v-card style="padding: 20px 20px;" width="100%">
        <v-layout justify-center align-center wrap style="float: left;">
            <el-tabs :tab-position="tabPosition">
                <el-tab-pane label="Google sheets">
                    <mySheets />
                </el-tab-pane>
                <el-tab-pane label="Shopify">
                    <myShopify />
                </el-tab-pane>
                <el-tab-pane label="Woocommerce">
                    <myWoocommerce />
                </el-tab-pane>
                <el-tab-pane label="Api">
                        <!-- https://{{ tenant.id }}{{ central_domain }}  -->
                        <v-card class="mx-auto" max-width="100%" outlined style="padding-bottom: 30px">
                            <v-list-item three-line>
                                <v-list-item-content>
                                    <div class="text-overline mb-4">
                                        Logixsaas
                                    </div>
                                    <v-list-item-title class="text-h5 mb-1">
                                        Api Documentation
                                    </v-list-item-title>
                                    <v-list-item-subtitle style="overflow: visible;">Integrating your custome store with Logixsaas. Bridges the gap between your sales channel and inventory, giving you the ability to tackle any number of online orders with ease while keeping the stock in continuous sync in both platforms.</v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-avatar tile size="200" style="width: 200px !important; height: 200px !important;margin: 0 !important;">
                                    <v-icon :color="s_color" style="font-size:190px;">mdi-api</v-icon>
                                </v-list-item-avatar>
                            </v-list-item>

                            <v-card-actions>
                                <v-btn outlined rounded text href="https://documenter.getpostman.com/view/4673881/2s935hS88x" target="_blank">
                                    Documentation
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                </el-tab-pane>
            </el-tabs>
        </v-layout>
    </v-card>
</v-container>
</template>

<script>
import myShopify from './shopifyapp/home/index.vue'
import myWoocommerce from './woocommerce/home/index.vue'
import mySheets from './googlesheets/home/index.vue'
export default {
    props: ['tenant'],
    components: {
        myShopify,
        myWoocommerce, mySheets
    },
    data() {
        return {
            central_domain: process.env.MIX_CENTRAL_DOMAIN,
            tabPosition: 'left'
        };
    }
};
</script>

<style scoped>
.container.fill-height {
    align-items: normal !important;
}

</style>
