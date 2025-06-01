<template>
<div style="padding: 0">
    <v-layout justify-center align-center wrap>
        <splitpanes>
            <pane min-size="30" :size="table_size">
                <myTable />
            </pane>
            <pane :size="edit_size">
                <div style="width: 100%;margin-top: 50px">
                    <myHeader />
                    <myInvoice :user="user" />
                    <!-- <myDetails :invoice_order="invoice_order" /> -->
                </div>
            </pane>
        </splitpanes>

        <el-dialog title="Mail to" :visible.sync="dialog" v-if="dialog">
            <el-input placeholder="Please input" v-model="invoice_order.sale.client.email"></el-input>

            <span slot="footer" class="dialog-footer">
                <el-button @click="dialog = false">Cancel</el-button>
                    <v-btn small elevation="2" color="primary" text  @click="status_update('mail')">Send</v-btn>
                
                <el-button type="primary" @click="status_update('mail')" :loading="loading">Confirm</el-button>
            </span>
        </el-dialog>

        <!-- <myreceive :invoice_order="invoice_order" /> -->
    </v-layout>
</div>
</template>

<script>
import {
    Splitpanes,
    Pane
} from 'splitpanes'
import {
    mapState
} from 'vuex'
import myTable from './table'
import myInvoice from './invoice'
// import myEdit from './edit'
import myHeader from './Tab_header'
// import myreceive from './receive'
// import myDetails from './details'

export default {
    props: ['user'],
    components: {
        myTable,
        Splitpanes,
        Pane,
        myInvoice,
        myHeader,
        // myreceive,
        //  myDetails
    },
    data() {
        return {
            search: '',
            edit_size: 0,
            table_size: 100,
            invoice_order: {},
            dialog: false
        }
    },
    methods: {
        handleClick(value) {
            this.headers = [{
                text: 'Created On',
                align: 'start',
                value: 'created_at',
            }]

        },
        editOrder(id) {
            console.log(id);

            this.$router.push({
                name: "edit_returns",
                params: {
                    id: id
                }
            });

        },
        status_update() {
            // var invoice_order = {
            //     status: status
            // }
            var payload = {
                data: this.invoice_order,
                id: this.invoice_order.id,
                model: 'main_inv',
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {});
        },
    },
    mounted() {
    },
    created() {
        eventBus.$on('resizeEvent', data => {
            this.table_size = '50'
            this.edit_size = '70'
            this.invoice_order = data
        });

        eventBus.$on('closeReturnEvent', data => {
            this.table_size = '100'
            this.edit_size = '0'

        });
        eventBus.$on('openSendEvent', data => {
            this.dialog = true

        });
    },
    computed: {
        ...mapState(['loading'])
    },
}
</script>
