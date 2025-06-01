<template>
<v-card>
    <v-flex sm12 style="margin: 10px 0 0 15px;">
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/sales' }">Orders</el-breadcrumb-item>
            <el-breadcrumb-item>Order details</el-breadcrumb-item>
        </el-breadcrumb>
    </v-flex>
    <v-subheader>All sales orders</v-subheader>
    <v-card-title>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
    </v-card-title>
    <v-data-table :headers="headers" :items="sales.sales.data" :search="search">
        <template v-slot:item.order_no="{ item }">
            <v-list-item style="cursor: pointer">
                <v-list-item-content @click="openOrder(item.id)">
                    <v-list-item-title>{{ item.client_name }} </v-list-item-title>
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
                text: 'Order',
                align: 'start',
                value: 'order_no',
            }, ],
        }
    },
    methods: {
        openOrder(id) {
            this.$router.push({
                name: "saleorder",
                params: {
                    id: id
                }
            });
            eventBus.$emit('routerChangeEvent')
        },

    },
    computed: {
        ...mapState(['sales']),

        filter_orders() {

            if (this.sales.data) {
                return this.sales.data.filter(sale => {
                    if (sale.order_no != null) {
                        return sale.order_no.toLowerCase().includes(this.search.toLowerCase())
                    }
                })
            } else {
                return this.sales.data
            }

        }
    }

}
</script>


<style scoped>
.v-sheet.v-card {
    box-shadow: none !important;
}

</style>

