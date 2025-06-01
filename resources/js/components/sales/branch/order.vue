<template>
<v-card :style="{width: table_width}">
    <v-card-title>
        Returns
        <v-btn text icon color="primary" small @click="getBranchSale">
            <v-icon small>mdi-refresh</v-icon>
        </v-btn>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
    </v-card-title>
    <v-data-table :headers="headers" :items="branch_sale.data" :search="search" @click:row="handleClick">
        <template v-slot:item.order="{ item }">
            <v-list-item style="cursor: pointer">
                <v-list-item-content>
                    <v-list-item-title>{{ item.client.name }} </v-list-item-title>
                    <v-list-item-subtitle class="text--primary"> <span style="color: #f00"> {{ item.order_no }} </span>| {{ item.created_at }}</v-list-item-subtitle>
                    <v-list-item-subtitle>{{ item.delivery_status }}</v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action>
                    <v-list-item-action-text>
                        <el-tag type="success">{{ item.status }}</el-tag>
                    </v-list-item-action-text>
                </v-list-item-action>
            </v-list-item>
        </template>
    </v-data-table>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    data() {
        return {
            search: '',
            headers: [{
                    text: 'Created On',
                    align: 'start',
                    value: 'created_at',
                },
                {
                    text: 'Order No#',
                    value: 'order_no'
                },
                {
                    text: 'Client Name',
                    value: 'client.name'
                },
                {
                    text: 'Client Phone',
                    value: 'client.phone'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Receive Status',
                    value: 'receive_status'
                },
                {
                    text: 'Amount Refund',
                    value: 'amount_refund'
                },
            ],
            table_width: '99%',
        }
    },
    methods: {
        handleClick(value) {
            console.log(value);

            eventBus.$emit('resizeEvent', value)

            this.headers = [{
                text: 'Order',
                value: 'order'
            }]

        },
        getBranchSale() {
            var payload = {
                model: '/branch_sale',
                update: 'updateBranchSaleList'
            }
            this.$store.dispatch("getItems", payload);
        },
    },
    mounted() {
        this.getBranchSale();
        // this.getOrder();
    },
    computed: {
        ...mapState(['branch_sale', 'saleorder'])
    },

    created () {

        eventBus.$on('closeReturnEvent', data => {
            this.table_size = '100'
            this.edit_size = '0'

            this.headers = [{
                    text: 'Created On',
                    align: 'start',
                    value: 'created_at',
                },
                {
                    text: 'Order No#',
                    value: 'order_no'
                },
                {
                    text: 'Client Name',
                    value: 'client.name'
                },
                {
                    text: 'Client Phone',
                    value: 'client.phone'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Receive Status',
                    value: 'receive_status'
                },
                {
                    text: 'Amount Refund',
                    value: 'amount_refund'
                },
            ]
            console.log(this.headers);

        });
    },
}
</script>

<style>
.splitpanes__pane {
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: Helvetica, Arial, sans-serif;
    color: red;
    /* font-size: 5em; */
}

.splitpanes--vertical>.splitpanes__splitter {
    background: #333 !important;
    /* width: 4px; */
    width: 7px;
    border-left: 1px solid #eee;
    margin-left: -1px;
}

.splitpanes__splitter:before {
    margin-left: -2px;
}

.splitpanes__splitter:after {
    margin-left: 1px;
}
</style>
