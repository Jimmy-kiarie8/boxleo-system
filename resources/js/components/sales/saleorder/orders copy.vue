<template>
<v-card class="mx-auto" tile style="width:100%;">
    <v-list dense two-line>
        <v-subheader>All sales orders</v-subheader>
        <el-input placeholder="Please enter order no." v-model="search" @change="filter_orders" style="width: 90%; margin-left: 10px"></el-input>
        <v-list-item-group color="primary">
            <v-list-item v-for="(item, i) in filter_orders" :key="i">
                <v-list-item-content @click="openOrder(item.id)">
                    <v-list-item-title>{{ item.client_name }} </v-list-item-title>
                    <v-list-item-subtitle class="text--primary"> <span style="color: #f00"> {{ item.order_no }} </span>| {{ item.created_at }}</v-list-item-subtitle>
                    <v-list-item-subtitle>{{ item.status }}</v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action>
                    <v-list-item-action-text>
                        <el-tag type="success">Open</el-tag>
                    </v-list-item-action-text>
                </v-list-item-action>
            </v-list-item>
        </v-list-item-group>
    </v-list>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    data() {
        return {
            search: ''
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
