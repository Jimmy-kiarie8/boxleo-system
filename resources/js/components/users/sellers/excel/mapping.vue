<template>
<div>
    <!-- <el-collapse accordion>
        <el-collapse-item name="1" v-if="sellers.exist_sellers.length > 0">
            <template slot="title" style="color: red">
                <span><b style="color: red">{{ sellers.exist_sellers.length }} Sellers Exists</b><i class="header-icon el-icon-info"></i></span>
            </template>
            <div>
                <v-list dense two-line>
                    <v-list-item-group color="primary">
                        <v-list-item v-for="(item, i) in sellers.exist_sellers" :key="i">
                            <v-list-item-content>
                                <v-list-item-title>{{ item.seller_no }} </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
            </div>
        </el-collapse-item>
    </el-collapse> -->
    <v-data-table :headers="headers" :items="sellers.Sellers" :single-expand="singleExpand" :expanded.sync="expanded" item-key="id" show-expand class="elevation-1">
        <template v-slot:top>
            <v-toolbar flat>
                <v-toolbar-title>Expandable Table</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-switch v-model="singleExpand" label="Single expand" class="mt-2"></v-switch>
            </v-toolbar>
        </template>
        <template v-slot:expanded-item="{ item }">
            <!-- <tr v-for="(seller, index) in sellers.Sellers" :key="index">
        </tr> -->

            <table class="table table-responsive table-hover" style="width: 400px;">
                <tbody>
                    <tr v-for="(seller, index) in sellers.Sellers" :key="index" v-if="seller.seller_id == item.seller_id">
                        <td colspan="1">
                            <el-select v-model="seller.seller_name" placeholder="Select" filterable clearable>
                                <el-option v-for="(item, prod) in sellers.data" :key="prod" :label="item.seller_name" :value="item.seller_name">
                                </el-option>
                            </el-select>
                        </td>
                        <td colspan="1">
                            <el-input-number v-model="seller.quantity" :min="1"></el-input-number>
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>
    </v-data-table>
</div>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['sellers'],
    data() {
        return {
            expanded: [],
            singleExpand: false,
            headers: [{
                    text: 'Seller id',
                    value: 'seller_id'
                },
                {
                    text: 'Quantity',
                    value: 'Sellers.quantity'
                },
                {
                    text: 'Seller name',
                    value: 'seller_name'
                },
                {
                    text: 'Address',
                    value: 'address'
                },
                {
                    text: 'Phone',
                    value: 'phone'

                },
                {
                    text: 'Special instructions',
                    value: 'special_instructions'
                },
                {
                    text: 'Total price',
                    value: 'total_amount'
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
            const index = this.sellers.sellers.indexOf(item)

            this.sellers.sellers.splice(index, 1)
        },
        // itemRowBackground: function (seller) {
        //     console.log(seller);

        //     console.log(!seller.seller.id || !seller.entry.seller_id || !seller.entry.quantity || !seller.entry.address || !seller.entry.seller_name || !seller.entry.phone);

        //     return (!seller.seller.id || !seller.entry.seller_id || !seller.entry.quantity || !seller.entry.address || !seller.entry.seller_name || !seller.entry.phone) ? 'style-1' : 'style-2'
        // }
    },
    computed: {
        ...mapState(['statuses']),
        // validate() {
        //     var valid = true
        //     for (let index = 0; index < this.sellers.sellers.length; index++) {
        //         const element = this.sellers.sellers[index];

        //         if (!element.seller.id || !element.entry.seller_id || !element.entry.quantity || !element.entry.address || !element.entry.seller_name || !element.entry.phone) {
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
