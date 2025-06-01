<template>
<v-card class="mx-auto" style="padding-bottom: 30px">
    <v-card-text>
        <el-tabs v-model="activeName" @tab-click="handleClick">
            <el-tab-pane label="Overview" name="first">
                <myOverview />
            </el-tab-pane>
            <el-tab-pane label="Transactions" name="second">
                <Transactions :stats_data="stats_data" />
            </el-tab-pane>
            <el-tab-pane label="Adjustments" name="third">Adjustments</el-tab-pane>
            <el-tab-pane label="History" name="fourth">
                <History />
            </el-tab-pane>

            
        </el-tabs>

    </v-card-text>
</v-card>
</template>

<script>
import myOverview from './Overview'
import Transactions from './Transactions'
import History from './History'
import {
    mapState
} from 'vuex';
export default {
    name: 'transactions',
    components: {
        myOverview,
        Transactions,
        History
    },
    data() {
        return {
            activeName: 'first',
            stats_data: {
                total: 0,
                delivered: 0,
                returned: 0,
                dispatched: 0,
                scheduled: 0,
                pending: 0
            },
        };
    },
    methods: {
        stats() {
            this.product_transactions.sales.data.forEach((value) => {
                if (value.delivery_status == 'Delivered') {
                    this.stats_data.delivered += 1
                    this.stats_data.total += 1
                } else if (value.delivery_status == 'Returned') {
                    this.stats_data.returned += 1
                    this.stats_data.total += 1
                } else if (value.delivery_status == 'Dispatched') {
                    this.stats_data.dispatched += 1
                    this.stats_data.total += 1
                } else if (value.delivery_status == 'Scheduled') {
                    this.stats_data.scheduled += 1
                    this.stats_data.total += 1
                } else {
                    this.stats_data.pending += 1
                    this.stats_data.total += 1
                }
            });
        },


        handleClick(tab, event) {
            if (tab.label == 'Transactions') {
                this.getTransactions()
            } else if (tab.label == "History") {
                this.getHistory()
            }
        },
        getHistory() {
            var payload = {
                model: '/product_history',
                id: this.$route.params.id,
                update: 'updateProductHistory'
            }
            this.$store.dispatch("showItem", payload);
        },

        getTransactions() {
            var payload = {
                model: '/product_transactions',
                id: this.$route.params.id,
                update: 'updateTransactionsList'
            }
            this.$store.dispatch("showItem", payload).then((res) => {
                // this.stats();
            });
        }
    },
    computed: {
        ...mapState(['product_transactions'])
    },
    created() {
        eventBus.$on('historyEvent', data => {
            this.getHistory()
        });
    },
};
</script>
