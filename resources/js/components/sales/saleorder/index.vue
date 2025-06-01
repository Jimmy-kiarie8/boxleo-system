<template>
<div>
    <v-layout justify-center align-center wrap>
        <splitpanes>
            <pane min-size="20" size="30">
                <myOrders />
            </pane>
            <pane>

                <div style="width:100%" v-if="return_order">
                    <!-- <myheader /> -->
                    <myReturn />
                </div>

                <div style="width:100%" v-else>
                    <myheader :user="user" />
                    <myDetails />
                    <v-divider></v-divider>
                    <mySaleOrder :user="user" />
                    <!-- <myInvoice /> -->
                </div>
            </pane>
        </splitpanes>
    </v-layout>
</div>
</template>

<script>
import {
    Splitpanes,
    Pane
} from 'splitpanes'
import 'splitpanes/dist/splitpanes.css'
import myOrders from './orders';
import myheader from './Tab_header'
import myReturn from '../returns/create'
import {
    mapState 
} from 'vuex';
// import myInvoice from '../../printing/invoice/Invoice'
import myDetails from './details/index.vue'
// import mySaleOrder from './sale_order'
import mySaleOrder from '../../printing/template/saleorder'

export default {
    props: ['user'],
    components: {
        Splitpanes,
        Pane,
        myOrders,
        // myInvoice,
        myDetails,
        myheader,
        myReturn,
        mySaleOrder
    },
    data() {
        return {
            return_order: false
        }
    },
    methods: {
        getOrder() {
            var payload = {
                model: 'sales',
                update: 'updateSale',
                id: this.$route.params.id
            }
            this.$store.dispatch('showItem', payload)
        },
        getSales() {
            var payload = {
                model: 'sales',
                update: 'updateSaleList'
            }
            // if (!this.sales.data) {
            //     if (this.sales.data.length < 1) {
            this.$store.dispatch("getItems", payload);
            //     }
            // }

        }
    },
    mounted() {
        this.getSales();
        this.getOrder();
    },
    computed: {
        ...mapState(['sales', 'saleorder'])
    },
    created() {
        eventBus.$on('routerChangeEvent', data => {
            this.getOrder();
        });

        eventBus.$on('returnSaleEvent', data => {
            this.return_order = true
        });

        eventBus.$on('closeReturnSaleEvent', data => {
            this.return_order = false
        });
    },

}
</script>

<style scoped>
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
