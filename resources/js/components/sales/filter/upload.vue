<template>
<!-- <el-dropdown trigger="click">
    <span class="el-dropdown-link">

        <v-btn text rounded color="primary">
            <v-icon color="blue darken-2">mdi-dots-vertical</v-icon>
        </v-btn>
    </span>
    <el-dropdown-menu slot="dropdown">
        <el-dropdown-item >
            <el-button type="text" @click="createOrder">
                <v-icon color="primary">mdi-plus-circle</v-icon>New Order
            </el-button>
        </el-dropdown-item>
        <el-dropdown-item>
            <el-button type="text" @click="uploadOrder('Excel')" >
                <v-icon color="primary">mdi-file-excel</v-icon> Import orders from Excel
            </el-button>
        </el-dropdown-item>
        <el-dropdown-item >
            <el-button type="text" @click="uploadOrder('Google')" v-if="api_connect.google_sheets">
                <v-icon color="primary">mdi-google-spreadsheet</v-icon> Import orders from Google Sheets
            </el-button>
        </el-dropdown-item>
        <el-dropdown-item >
            <el-button type="text" @click="uploadOrder('Shopify')" v-if="api_connect.shopify">
                <v-icon color="primary">mdi-shopping</v-icon> Import orders from Shopify
            </el-button>
        </el-dropdown-item>
        <el-dropdown-item >
            <el-button type="text" @click="uploadOrder('Woocommerce')">
                <v-icon color="primary">mdi-wordpress</v-icon> Import orders from woocommerce
            </el-button>
        </el-dropdown-item>
    </el-dropdown-menu>
</el-dropdown> -->
<v-menu bottom left>
    <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on" color="primary">
            <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
    </template>
    <v-list dense>
        <v-list-item-group v-model="selectedItem" color="primary">
            <v-list-item @click="createOrder()">
                <v-list-item-icon>
                    <v-icon>mdi-plus-circle</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>New Order</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-list-item @click="uploadOrder('Excel')">
                <v-list-item-icon>
                    <v-icon color="primary">mdi-file-excel</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Import orders from Excel</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

                <v-list-item @click="uploadOrder('Google')" v-if="api_connect.google_sheets">
                    <v-list-item-icon>
                        <v-icon color="primary">mdi-shopping</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Import orders from Shopify</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item @click="uploadOrder('Shopify')">
                    <v-list-item-icon>
                        <v-icon color="primary">mdi-shopping</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Import orders from Shopify</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item @click="uploadOrder('Woocommerce')">
                    <v-list-item-icon>
                        <v-icon color="primary">mdi-wordpress</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Import orders from woocommerce</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>

    </v-list>
</v-menu>
</template>

<script>
import {
    mapState
} from 'vuex';
import myShopifyConnect from '../../settings/company/inc/api'
export default {
    props: ['user'],
    data() {
        return {
            selectedItem: 0
            // api_connect: false,

        }
    },
    methods: {
        createOrder(data) {
            // eventBus.$emit("openEditProduct", data.row);

            this.$router.push({
                name: "create_order"
            });
        },
        uploadOrder(data) {
            if (data == 'Excel') {
                eventBus.$emit("openExcelUploadEvent", data);
            } else {
                eventBus.$emit("openOrderUploadEvent", data);
            }
        },
        googleUpload() {
            eventBus.$emit("GoogleUploadEvent");
        },
        shopifyUpload(data) {
            eventBus.$emit("ShopifyUploadEvent", data.row);
        },
        woocommerceUpload(data) {
            eventBus.$emit("WoocommerceUploadEvent", data.row);
        }
    },
    mounted() {
        // this.getApi();
    },
    computed: {
        ...mapState(['api_connect'])
    },
}
</script>

<style scoped>
.el-dropdown-link {
    cursor: pointer;
    color: #409EFF;
}

.el-icon-arrow-down {
    font-size: 12px;
}
</style>
