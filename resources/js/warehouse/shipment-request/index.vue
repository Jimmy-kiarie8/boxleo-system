<template>
    <v-card elevation="4">
        <v-card-title>
            <v-tooltip right>
                <template v-slot:activator="{ on }">
                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getShipments">
                        <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                    </v-btn>
                </template>
                <span>Refresh</span>
            </v-tooltip>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                hide-details></v-text-field>
            <v-spacer></v-spacer>
            <v-btn color="primary" outlined @click="openCreate">
                <v-icon dark>mdi-plus-circle</v-icon>
                Create a Shipment Request
            </v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="shipments.data" :search="search" :loading="loading">

            <template v-slot:item.payment_code="props" v-if="user.can['Order edit']">
                <v-edit-dialog :return-value.sync="props.item.payment_code" large persistent
                    @save="update_item(props.item, 'payment_code')" @cancel="cancel" @open="open_dialog" @close="close">
                    <span id="address_tab">
                        <el-tooltip class="item" effect="dark" :content="props.item.payment_code" placement="top-start">
                            <span>
                                {{ props.item.payment_code }}
                            </span>
                        </el-tooltip>
                    </span>
                    <template v-slot:input>
                        <div class="mt-4 title" style="color: #333">
                            Payment Code
                        </div>
                        <v-text-field v-model="props.item.payment_code" label="Edit" single-line counter
                            autofocus></v-text-field>
                    </template>
                </v-edit-dialog>
            </template>
            <template v-slot:item.approve_status="{ item }">
                <el-tooltip :content="(item.approve_status) ? 'Approved' : 'Pending'" placement="top">
                    <v-avatar style="cursor: pointer" :color="(item.approve_status) ? 'green' : 'red'" small></v-avatar>
                </el-tooltip>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-tooltip bottom v-if="!user.is_vendor">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on" @click="uploadDocument(item)">
                            <v-icon color="primary lighten-1" small>
                                mdi-upload
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Upload</span>
                </v-tooltip>
                <v-tooltip bottom v-if="!user.is_vendor">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on" @click="showDocuments(item)">
                            <v-icon color="primary lighten-1" small>
                                mdi-file
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Upload</span>
                </v-tooltip>
                <v-tooltip bottom v-if="!user.is_vendor && item.approve_status">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on" @click="receiveItems(item)">
                            <v-icon color="success" small>
                                mdi-warehouse
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Receive</span>
                </v-tooltip>
                <v-tooltip bottom v-if="!user.is_vendor && !item.approve_status">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on" @click="approveItems(item.id)">
                            <v-icon color="success" small>
                                mdi-check-underline-circle
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Approve</span>
                </v-tooltip>
            </template>
        </v-data-table>
        <myCreate :user="user" />
        <myEdit :user="user" />
        <myUpload :user="user" />
        <myFiles />
    </v-card>
</template>

<script>
import {
    mapState
} from 'vuex';
import myCreate from './create'
import myEdit from './edit'
import myUpload from './upload'
import myFiles from './show'
export default {
    props: ['form_bin', 'user'],
    components: {
        myCreate, myEdit, myUpload, myFiles
    },
    data() {
        return {
            search: '',
            loading: false,
            form: {},
            headers: [
                {
                    text: 'Waybill',
                    value: 'waybill_no',
                },
                {
                    text: 'Amount',
                    value: 'amount',
                },
                {
                    text: 'Weight',
                    value: 'weight',
                },
                {
                    text: 'Arrival Date ',
                    value: 'arrival_date',
                },
                {
                    text: 'Created on',
                    value: 'created_at'
                },
                {
                    text: 'Merchant',
                    value: 'seller.name'
                },
                {
                    text: 'Payment Code',
                    value: 'payment_code'
                },
                {
                    text: 'Approved',
                    value: 'approve_status'
                },
                {
                    text: 'Last approved by',
                    value: 'last_aproved_by'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ],
        }
    },
    methods: {
        update_item(item, update) {
            const data = {
                update: update,
                data: item
            }
            var payload = {
                data: data,
                model: 'shipment-edit',
                id: item.id
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    // this.goToSales()
                });
        },
        getShipments() {
            var payload = {
                model: 'shipments',
                update: 'updateShipments',
            }
            this.$store.dispatch('getItems', payload);
        },
        openCreate() {
            eventBus.$emit('CreateShipmentEvent', this.form_bin)
        },
        closeShipments() {
            eventBus.$emit('displayChange', 'row')
        },
        printSticker() {

        },
        receiveItems(item) {
            eventBus.$emit('editShipmentEvent', item)
        },
        approveItems(id) {
            // this.loading = true
            // axios.post(`shipment-approve/${id}`).then((res) => {
            //     this.loading = true
            //     this.$message({
            //         type: 'error',
            //         message: 'Please fill all the fields'
            //     });
            // }).catch((error) => {
            //      this.$message({
            //         type: 'error',
            //         message: 'Please fill all the fields'
            //     });
            //     this.loading = true
            // })


            var payload = {
                model: `shipment-approve/${id}`,
                data: {},
            }
            this.$store.dispatch('postItems', payload);
        },
        uploadDocument(item) {
            eventBus.$emit('uploadShipmentEvent', item)
        },
        showDocuments(item) {
            eventBus.$emit('showShipmentEvent', item.documents)
        },
        deleteItem() { }
    },
    mounted() {
        this.getShipments();
    },
    computed: {
        ...mapState(['shipments', 'loading'])
    },
}
</script>

<style scoped>
.v-sheet.v-card {
    width: 90%;
    margin: auto;
}
</style>
