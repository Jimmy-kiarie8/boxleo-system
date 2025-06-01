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
                                <v-subheader>{{ form.order_no }}</v-subheader>
                                <v-list-item-group v-model="selectedItem" color="primary">
                                    <v-list-item>
                                        <v-list-item-icon>
                                            <v-icon>mdi-cash-100</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title v-text="form.total_price"></v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-icon>
                                            <v-icon>mdi-update</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title v-text="form.delivery_status"></v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-flex>
                        <v-flex sm12>
                            <el-table :data="form.products" style="width: 100%">
                                <el-table-column prop="product_name" label="Product Name" width="180"></el-table-column>
                                <el-table-column prop="pivot.quantity" label="Quantity" width="180"></el-table-column>
                                <el-table-column prop="pivot.price" label="Price"></el-table-column>
                            </el-table>
                        </v-flex>
                            <v-divider></v-divider>

                        <v-flex sm12>
                            <h5>Comments</h5>
                            <el-collapse accordion>
                                <el-collapse-item name="1" v-for="comment in comments" :key="comment.id">
                                    <template slot="title">
                                        {{ comment.created_at }}<i class="header-icon el-icon-info"></i>
                                    </template>
                                    <div>
                                        <strong>{{ comment.comment }}</strong>
                                        </div>
                                    <div>By <b style="color: red;">{{ comment.user_name }} </b> </div>
                                </el-collapse-item>
                            </el-collapse>
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
        form: {},
        comments: [],
        selectedItem: 0,
        payload: {
            model: 'leaves',
        },
    }),
    created() {
        eventBus.$on("openShowSale", data => {
            this.dialog = true;
            this.form = data;
            this.getComments(data.id)
        });
    },

    methods: {
        close() {
            this.dialog = false;
        },
        getComments(id) {
            axios.get('comments/' + id).then((response) => {
                this.comments = response.data;
            }).catch((error) => {
                console.error(error)
            })
        }
    },
    computed: {
        total() {
            var price = 0
            this.form.products.forEach(element => {
                price += parseFloat(element.pivot.price) * parseFloat(element.pivot.quantity)
            });
            return price
        }
    },
};
</script>
