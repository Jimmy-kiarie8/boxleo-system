<template>
    <v-container>
        <v-card>
            <v-card-title>Product Transfer</v-card-title>
            <v-card-text>
                <v-data-table :headers="headers" :items="transfers.data" class="elevation-1">

                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" icon class="mx-0" @click="receive(item)" slot="activator" v-if="item.status != 'completed'">
                                    <v-icon small color="blue darken-2">mdi-tanker-truck</v-icon>
                                </v-btn>
                            </template>
                            <span>Receive</span>
                        </v-tooltip>
                       
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
        <myReceive />
    </v-container>
</template>

<script>
import { mapState } from 'vuex';
import myReceive from './receive.vue';

export default {
    components: {
        myReceive
    },
    data() {
        return {
            form: {},
            selectedProduct: null,
            transferItems: [],
            loading: false,
            headers: [
                { text: 'Created At', value: 'created_at' },
                { text: 'Product', value: 'product.product_name' },
                { text: 'Transfered Quantity', value: 'quantity' },
                { text: 'Received', value: 'received' },
                { text: 'Faulty', value: 'faulty' },
                { text: 'From', value: 'warehouse_from.name' },
                { text: 'To', value: 'warehouse.name' },
                { text: 'Status', value: 'status' },
                { text: 'Actions', value: 'actions', sortable: false },
            ]
        };
    },
    methods: {
        getTransfers() {
            // console.log(search);
            var payload = {
                model: 'transfers',
                update: 'updateTransfers',
            }
            this.$store.dispatch("getItems", payload);
        },
        receive(item) {
            eventBus.$emit("receiveEvent", item);

        }
        
    },
    mounted() {
        this.getTransfers();
    },

    computed: {
        ...mapState(['transfers'])
    }
};
</script>