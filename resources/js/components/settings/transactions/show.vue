<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" max-width="600px">
        <v-card v-if="dialog">
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Order Details</span>
                <VSpacer />

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="close" slot="activator">
                            <v-icon small color="blue darken-2">close</v-icon>
                        </v-btn>
                    </template>
                    <span>close</span>
                </v-tooltip>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm12>
                            <v-list dense>
                                <v-subheader>{{ order.order_no }}</v-subheader>
                                <v-list-item-group v-model="selectedItem" color="primary">
                                    <v-list-item>
                                        <v-list-item-icon>
                                            <v-icon>mdi-cash-100</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title v-text="order.total_price"></v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-icon>
                                            <v-icon>mdi-update</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title v-text="order.delivery_status"></v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-flex>
                        <v-flex sm12>
                            <el-table :data="order.products" style="width: 100%">
                                <el-table-column prop="product_name" label="Product Name" width="180"></el-table-column>
                                <el-table-column prop="pivot.quantity" label="Quantity" width="180"></el-table-column>
                                <el-table-column prop="pivot.price" label="Price"></el-table-column>
                            </el-table>
                        </v-flex>

                        <br>
                        <br>
                        <h5>Client</h5>
                        <v-divider></v-divider>
                        <v-flex sm12>
                            <table class="table table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ order.client.name }}</td>
                                        <td>{{ order.client.phone }}</td>
                                        <td>{{ order.client.address }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    data: () => ({
        dialog: false,
        loading: false,
        order: [],
        selectedItem: 0
    }),
    created() {
        eventBus.$on("mpesaTransaction", data => {
            this.dialog = true;
            this.getOrder(data)
        });
    },

    methods: {
        close() {
            this.dialog = false;
        },
        getOrder(code) {
            axios.get('transaction_order/' + code).then((response) => {
                this.order = response.data;
            }).catch((error) => {
                console.error(error)
            })
        }
    },
    computed: {},
};
</script>
