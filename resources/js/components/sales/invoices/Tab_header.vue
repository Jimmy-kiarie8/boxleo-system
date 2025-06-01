<template>
<v-toolbar dense>

    <!-- <v-toolbar-title>{{ saleorder.order_no }}</v-toolbar-title> -->

    <v-btn-toggle v-model="toggle_exclusive" mandatory>

        <!-- <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon small color="grey">mdi-pencil</v-icon>
                </v-btn>
            </template>
            <span>Edit return</span>
        </v-tooltip> -->

        <v-tooltip bottom v-if="invoice.paid">
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" >
                    <v-icon small color="success">mdi-check-circle</v-icon>
                </v-btn>
            </template>
            <span>paid</span>
        </v-tooltip>
        <v-tooltip bottom v-else>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" @click="status_update('Paid')">
                    <v-icon small color="grey">mdi-check-circle</v-icon>
                </v-btn>
            </template>
            <span>Mark as paid</span>
        </v-tooltip>

        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" @click="openSend">
                    <v-icon small color="grey">mdi-mail</v-icon>
                </v-btn>
            </template>
            <span>Send to mail</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon :href="'/download_pdf/' + invoice.id" target="_blank" v-bind="attrs" v-on="on">
                    <v-icon small color="grey">mdi-adobe-acrobat</v-icon>
                </v-btn>
            </template>
            <span>Download</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" @click="confirm_delete">
                    <v-icon small color="pink">mdi-delete</v-icon>
                </v-btn>
            </template>
            <span>Delete</span>
        </v-tooltip>
        <!-- <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon small color="grey">mdi-trash-can</v-icon>
                </v-btn>
            </template>
            <span>Delete</span>
        </v-tooltip> -->
    </v-btn-toggle>
    <v-spacer></v-spacer>

    <v-btn text icon color="primary" @click="close_details">
        <v-icon>mdi-close</v-icon>
    </v-btn>

</v-toolbar>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    data() {
        return {
            toggle_exclusive: undefined,
            invoice: {},
        }
    },
    computed: {
        ...mapState(['invoices', 'packages', 'saleorder'])
    },
    methods: {
        getInvoices() {
            var payload = {
                model: 'invoices',
                update: 'updateInvoiceList'
            }
            this.$store.dispatch("getItems", payload).then((res) => {})
        },
        confirm_delete(item) {
            this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                this.deleteItem(item)
            }).catch(() => {
                this.$message({
                    type: 'error',
                    message: 'Delete canceled'
                });
            });
        },
        deleteItem(item) {
            this.$store.dispatch("deleteItem", "invoices/" + this.invoice.id).then(response => {
                this.$message({
                    type: 'success',
                    message: 'Delete completed'
                });
                this.getInvoices();
            });
        },
        print_pdf() {
            eventBus.$emit('printInvoiceEvent')
        },
        download_pdf() {
            var payload = {
                data: invoice,
                id: this.invoice.id,
                model: 'download_pdf',
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {});
        },
        status_update(status) {
            var invoice = {
                status: status
            }
            var payload = {
                data: invoice,
                id: this.invoice.id,
                model: 'inv_status',
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    this.invoice.paid = response.data
                });
        },
        close_details() {
            eventBus.$emit('closeReturnEvent')
        },
        sale_return() {
            eventBus.$emit('returnSaleEvent')
        },
        // invoice() {
        //     this.invoice.id = this.$route.params.id
        //     var payload = {
        //         data: this.invoice,
        //         model: 'invoices',
        //     }
        //     this.$store.dispatch('postItems', payload)
        //         .then(response => {
        //             eventBus.$emit('routerChangeEvent')
        //         });
        // },
        confirm() {
            var payload = {
                model: 'confirm',
                data: '',
                id: this.$route.params.id
            }
            this.$store.dispatch('patchItems', payload).then((res) => {
                eventBus.$emit('routerChangeEvent')
            })
        },

        receive() {
            eventBus.$emit('openReceiveEvent')
        },
        openSend() {
            eventBus.$emit('openSendEvent')
        }
    },
    created() {
        eventBus.$on('resizeEvent', data => {
            this.invoice = data
        });
    },
}
</script>

<style scoped>
.el-dropdown-link {
    cursor: pointer;
    color: #409EFF;
}

.el-icon-arrow-down {
    font-size: 12px;
}

.demonstration {
    display: block;
    color: #8492a6;
    font-size: 14px;
    margin-bottom: 20px;
}
</style>
