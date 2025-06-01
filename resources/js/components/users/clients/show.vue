<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="800px">
        <v-card>
            <v-card-title color="primary">
                <span class="headline text-center" style="margin: auto;">{{client.name}}</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-card class="pa-2" outlined tile>
                                <v-list dense>
                                    <v-subheader>Details</v-subheader>
                                    <v-divider></v-divider>
                                    <v-list-item-group v-model="selectedItem" color="primary">
                                        <v-list-item>
                                            <v-list-item-icon>
                                                <v-icon>mdi-account-circle</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title>Name</v-list-item-title>
                                                <v-list-item-subtitle>{{ client.name }}</v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                        <v-list-item>
                                            <v-list-item-icon>
                                                <v-icon>mdi-mail</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title>Email</v-list-item-title>
                                                <v-list-item-subtitle>{{ client.email }}</v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                        <v-list-item>
                                            <v-list-item-icon>
                                                <v-icon>mdi-phone</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title>Phone</v-list-item-title>
                                                <v-list-item-subtitle>{{ client.phone }}</v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                        <v-list-item>
                                            <v-list-item-icon>
                                                <v-icon>mdi-map-marker</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title>Address</v-list-item-title>
                                                <v-list-item-subtitle>{{ client.address }}</v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                        <v-list-item>
                                            <v-list-item-icon>
                                                <v-icon>mdi-map</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title>City</v-list-item-title>
                                                <v-list-item-subtitle>{{ client.city }}</v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>

                                    </v-list-item-group>
                                  
                                </v-list>
                            </v-card>

                            <template>
                        <v-data-table
                            :headers="headers"
                            :items="client.sales"
                            :items-per-page="5"
                            class="elevation-1"
                        >

                        <template v-slot:item.order_no="{ item }" style="padding-left: 0 !important;" id="order-no">
                            <v-btn color="primary" text @click="openOrder(item)">
                                <v-icon :color="s_color" style="font-size: 16px;margin-right: 10px;" v-if="item.platform == 'Shopify'" small>fab fa-shopify</v-icon>
                                <v-icon :color="w_color" style="font-size: 16px;margin-right: 10px;" v-else-if="item.platform == 'Woocommerce'" small>fab fa-wordpress-simple</v-icon>

                                <v-icon :color="g_color" style="font-size: 16px;margin-right: 10px;" v-else-if="item.platform == 'Google Sheets'" small>mdi-google-spreadsheet</v-icon>
                                <v-icon color="primary" style="font-size: 16px;margin-right: 10px;" small v-else>fas fa-file-excel</v-icon>
                                {{ item.order_no }}
                            </v-btn>

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click="copyText(item.order_no)" color="primary" style="float: right;">
                                        <v-icon small>mdi-content-copy</v-icon>
                                    </v-btn>
                                </template>
                                <span>Copy order No.</span>
                            </v-tooltip>
                        </template>

                        </v-data-table>
                        </template>

                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import { mapState } from 'vuex';
export default {
    data: () => ({
        dialog: false,
        form: {},
        selectedItem: 0,
        headers: [
          {
            text: 'Order date',
            align: 'start',
            sortable: false,
            value: 'created_at',
          },
          { text: 'Order No', value: 'order_no' },
          { text: 'Status', value: 'delivery_status' },
          { text: 'Delivery date', value: 'delivery_date' },
          { text: 'Amount', value: 'total_price' },
        ],
    }),
    created() {
        eventBus.$on("openShowClient", data => {
            this.dialog = true;
            this.id = data;
            this.getOrder(data)
        });
    },

    methods: {
        getOrder(id) {
            const payload = {
                model: 'client_orders',
                id: id,
                update: 'updateClient'
            }
            this.$store.dispatch('showItem', payload)
                .then(response => {
                });
        },
        close() {
            this.dialog = false;
        },

        openOrder(data) {
            this.close()
            eventBus.$emit("drawerEvent", data.id);
        },
    },
    computed: {
        ...mapState(['client', 'loading'])
    },
};
</script>
