<template>
<v-layout justify-center align-center wrap>
    <v-card elevation="3" style="width:100%">
        <v-card-title>
            <v-btn color="primary" @click="showDetails" icon small>
                <v-icon dark>mdi-refresh</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="products.bins" :single-expand="singleExpand" :expanded.sync="expanded" item-key="name" show-expand class="elevation-1">

            <template v-slot:expanded-item="{ headers, item }" show-expand>
                <td :colspan="headers.length">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>Code</th>
                            <th>Warehouse</th>
                            <th>Area</th>
                            <th>Row</th>
                            <th>Bay</th>
                            <th>Level</th>
                            <th>Bin</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <vue-qrcode :value="item.code" :options="{ width: 80 }"></vue-qrcode>
                                </td>
                                <td>
                                    {{ item.warehouse.name }}
                                </td>
                                <td>
                                    {{ item.area.name }}
                                </td>
                                <td>
                                    {{ item.row.name }}
                                </td>
                                <td>
                                    {{ item.bay.name }}
                                </td>
                                <td>
                                    {{ item.level.name }}
                                </td>
                                <td>
                                    {{ item.name }}
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </td>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-btn color="primary" icon @click="openAddQuantity(item)">
                    <v-icon small>mdi-thermometer-plus</v-icon>
                </v-btn>
            </template>
        </v-data-table>
        <myStock :product_id="$route.params.id" />
    </v-card>
</v-layout>
</template>

<script>
import {
    mapState
} from "vuex";
import VueQrcode from "@chenfengyuan/vue-qrcode";

import myStock from "./quantity";
export default {
    components: {
        VueQrcode,
        myStock
    },
    data() {
        return {
            singleExpand: true,
            search: "",
            expanded: [],
            form: {},
            headers: [{
                    text: "Bin name",
                    align: "start",
                    value: "name",
                },
                {
                    text: "Bin code",
                    value: "code",
                }, ,
                {
                    text: "On hand",
                    value: "pivot.onhand",
                }, ,
                {
                    text: "Commited",
                    value: "pivot.commited",
                }, ,
                {
                    text: "Available for sale",
                    value: "pivot.available_for_sale",
                },
                {
                    text: "Created on",
                    value: "created_at",
                },
                {
                    text: "Actions",
                    value: "actions",
                },
                {
                    text: "Details",
                    value: "data-table-expand",
                },
            ],
            value: "https://example.com",
            size: 300,
        };
    },
    methods: {
        showDetails() {
            var payload = {
                model: "product_details",
                update: "updateProductsList",
                id: this.$route.params.id,
            };
            this.$store.dispatch("showItem", payload);
        },
        openAddQuantity(item) {
            eventBus.$emit('updateQtyEvent', item)
        }
    },
    mounted() {
        this.showDetails();
    },
    computed: {
        ...mapState(["products"]),
    },
};
</script>

<style>
</style>
