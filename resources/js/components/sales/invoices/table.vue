<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <v-card style="width: 100%">
                <v-card-title>
                    <v-tooltip right>
                        <template v-slot:activator="{ on }">
                            <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getInvoices">
                                <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                            </v-btn>
                        </template>
                        <span>Refresh</span>
                    </v-tooltip>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="invoices.data" :search="search" @click:row="handleClick">
                    <template v-slot:item.invoice_details="{ item }">
                        <v-list-item style="cursor: pointer">
                            <v-list-item-content>
                                <v-list-item-title>{{ item.client_name }} </v-list-item-title>
                                <v-list-item-subtitle class="text--primary"> <span style="color: #f00"> {{ item.order_no }} </span>| {{ item.created_at }}</v-list-item-subtitle>
                                <v-list-item-subtitle>{{ item.status }}</v-list-item-subtitle>
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
        </v-layout>
    </v-container>
</div>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    data() {
        return {
            search: '',
            headers: []
        }
    },
    methods: {
        getInvoices() {
            var payload = {
                model: 'invoices',
                update: 'updateInvoiceList'
            }
            this.$store.dispatch("getItems", payload).then((res) => {})
        },
        handleClick(value) {
            eventBus.$emit('resizeEvent', value)
            this.headers = [{
                text: 'Invoice no',
                value: 'invoice_details'
            }]
        },

        set_header() {
            this.headers = [{
                    text: 'Created On.',
                    align: 'start',
                    value: 'created_at',
                }, {
                    text: 'Invoice No.',
                    align: 'start',
                    value: 'invoice_no',
                },
                {
                    text: 'Order No.',
                    value: 'order_no'
                },
                {
                    text: 'Customer Name',
                    value: 'client_name'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Amount',
                    value: 'total'
                },
                {
                    text: 'Due Date',
                    value: 'due_date'
                }
            ]
        }
    },
    computed: {
        ...mapState(['invoices'])
    },
    created() {
        this.set_header()
        eventBus.$on('closeReturnEvent', data => {
            this.set_header()
        });
    },
    mounted() {
        this.getInvoices();
    },
}
</script>
