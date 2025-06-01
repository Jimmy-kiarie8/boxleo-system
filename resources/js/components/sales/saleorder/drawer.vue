<template>
<v-sheet id="details" v-if="drawer">
    <v-navigation-drawer v-model="drawer" absolute temporary right style="width: 70%;top: -40px;">
        <div style="width:100%">
            <myheader :user="user" />
            <myDetails />
            <v-divider></v-divider>
            <!-- <mySaleOrder :user="user" /> -->
            <!-- <myInvoice :company="user.company" /> -->
        </div>
    </v-navigation-drawer>
</v-sheet>
</template>

<script>
import {
    mapState
} from 'vuex';
import myheader from './Tab_header'
import myDetails from './details/index.vue'
import myInvoice from '../../printing/invoice/Invoice'
export default {
    props: ['user'],
    components: {
        myheader,
        myDetails,
        myInvoice
    },
    data() {
        return {
            drawer: false,
        }
    },
    methods: {
        getOrder(id) {
            var payload = {
                model: 'sales',
                update: 'updateSale',
                id: id
                // id: this.$route.params.id
            }
            this.$store.dispatch('showItem', payload)
        },
    },
    computed: {
        ...mapState(['sales', 'saleorder'])
    },
    created() {
        eventBus.$on('drawerEvent', data => {
            this.drawer = true
            this.getOrder(data);
        });

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

<style>
#details .v-overlay--absolute {
    position: fixed !important;
}

#show .v-navigation-drawer--temporary {
    position: fixed !important;
    background: #f4f6f8;
    top: 0 !important;
}
</style>
