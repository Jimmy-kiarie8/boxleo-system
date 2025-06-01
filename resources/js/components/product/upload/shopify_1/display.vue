<template>
<v-card>
    <v-card-title primary-title>

    </v-card-title>
    <v-card-text>
        <!-- {{ product_upload.length }}
{{ product_upload.length }} -->
        <!-- <el-collapse accordion>

            <el-collapse-item name="1" v-if="product_upload.exist_product_upload.length > 0">
                <template slot="title" style="color: red">
                    <span><b style="color: red">{{ product_upload.exist_product_upload.length }} product_upload Exists</b><i class="header-icon el-icon-info"></i></span>
                </template>
                <div>
                    <v-list dense two-line>
                        <v-list-item-group color="primary">
                            <v-list-item v-for="(item, i) in product_upload.exist_product_upload" :key="i">
                                <v-list-item-content>
                                    <v-list-item-title>{{ item.order_no }} </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </div>
            </el-collapse-item>
        </el-collapse> -->
        <div v-if="product_upload.length > 0">
            <h2 style="color: #1876d2;"> {{ product_upload.length }} products will be imported </h2>

            <v-card>
                <v-card-title>
                    product_upload
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="product_upload" :search="search" :item-class="itemRowBackground">
                    <template v-slot:item.image.src="{ item }">
                        <img :src="item.image.src" style="width: 60px;border-radius: 50%" />
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" icon class="mx-0" @click="remove(item)" slot="activator">
                                    <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                </v-btn>
                            </template>
                            <span>Remove</span>
                        </v-tooltip>
                    </template>
                </v-data-table>
            </v-card>

        </div>

    </v-card-text>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['product_upload'],
    data() {
        return {
            valid: false,
            invalid_row: [],
            search: '',
            headers: [{
                    text: 'Product name',
                    value: 'title'
                },
                {
                    text: 'Product',
                    value: 'productname'
                },
                {
                    text: 'Image',
                    value: 'image.src'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ]
        }
    },
    methods: {
        getStatus() {
            var payload = {
                model: 'statuses',
                update: 'updateStatusList',
            }
            this.$store.dispatch("getItems", payload);
        },
        remove(item) {
            const index = this.product_upload.indexOf(item)

            this.product_upload.splice(index, 1)
        },
        itemRowBackground: function (order) {
            console.log(order);

            // console.log(!order.product.id || !order.entry.orderid || !order.entry.quantity || !order.entry.address || !order.entry.clientname || !order.entry.phone);

            // return (!order.product.id || !order.entry.orderid || !order.entry.quantity || !order.entry.address || !order.entry.clientname || !order.entry.phone) ? 'style-1' : 'style-2'
        }
    },
    computed: {
        ...mapState(['products_upload', 'statuses']),
        // validate() {
        //     var valid = true
        //     for (let index = 0; index < this.product_upload.length; index++) {
        //         const element = this.product_upload[index];

        //         if (!element.product.id || !element.entry.orderid || !element.entry.quantity || !element.entry.address || !element.entry.clientname || !element.entry.phone) {
        //             valid = false
        //             break;
        //         } else {
        //             valid = true
        //             // return true
        //         }
        //     }
        //     return valid;
        // }
    },
    mounted() {
        this.getStatus();
    },
}
</script>

<style>
.el-collapse-item__header {
    color: #f00 !important;
}

.style-1 {
    background-color: #f00;
}
</style>
