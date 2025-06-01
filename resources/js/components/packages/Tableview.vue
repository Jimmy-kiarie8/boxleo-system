<template>
<v-card style="width: 99%; padding: 10px">
    <VCardTitle primary-title>
        <v-pagination v-model="sales.current_page" :length="sales.last_page" total-visible="5" @input="next_page(sales.path, sales.current_page)" circle v-if="sales.last_page > 1"></v-pagination>
    </VCardTitle>
    <v-card-text>
        <v-data-table :headers="headers" :items="sales.data" :items-per-page="5" class="elevation-1" :loading="loading">
            <template v-slot:item.delivery_date="{ item }">
                <el-tag type="success">{{ item.delivery_date }}</el-tag>
            </template>
        </v-data-table>
    </v-card-text>
</v-card>
</template>

<script>
// import myLable from '../printing/Lable'
import moment from 'moment'
import PackagePrint from './print/PackagePrint'
import {
    mapState
} from 'vuex'
export default {
    // props: ['user'],
    components: {
        // myLable,
        PackagePrint
    },
    data() {
        return {
            checkedRows: [],
            package_show: false,
            pkg_columns: [{
                label: 'Packages',
                field: 'order_no',
            }, ],
            sort: {
                start_date: new Date(),
                end_date: '',
            },
            headers: [{
                    text: 'Order Date',
                    value: 'created_at',
                },
                {
                    text: 'Order',
                    value: 'order_no',
                },
                {
                    text: 'Order Status',
                    value: 'delivery_status',
                },
                {
                    text: 'Shipment Date',
                    value: 'delivery_date',
                    // type: 'date',
                },
                {
                    text: 'Client Name',
                    value: 'client.name',
                }
            ],
        }
    },
    computed: {
        ...mapState(['sales', 'loading']),
        // packages() {
        //     return this.$store.getters.packages
        // },
        // loading() {
        //     return this.$store.getters.loading;
        // },
        // orders() {
        //     return this.$store.getters.orders;
        // },
        // clients() {
        //     return this.$store.getters.clients
        // },
    },
    methods: {
        getPackage_list() {
            this.$store.dispatch('getPackage_list')
        },
        mark_delivered(data) {
            axios.post(`orderDelivered/${data.id}`)
                .then((response) => {
                    this.$store.dispatch('alertEvent', 'Order updated')
                })
        },
        shipLable(packaged) {
            eventBus.$emit('printPackageEvent', packaged)
        },
        submit() {
            this.$refs.form_data.submit()
        },

        printPage() {
            // Get HTML to print from element
            const prtHtml = document.getElementById('print').innerHTML;
            console.log(prtHtml);

            // Get all stylesheets HTML
            let stylesHtml = '';
            for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
                stylesHtml += node.outerHTML;
            }

            // Open the print window
            const WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

            WinPrint.document.write(`<!DOCTYPE html>
            <html>
            <head>
                ${stylesHtml}
            </head>
            <body>
                ${prtHtml}
            </body>
            </html>`);

            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        },
        delete_package(item) {
            this.$confirm('This will permanently delete the package. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                axios.delete(`/packages/${item.id}`)
                    .then((response) => {
                        this.$message({
                            type: 'success',
                            message: 'Delete completed'
                        });
                        this.getPackage_list()
                    })
                    .catch((error) => {

                    });
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: 'Delete canceled'
                });
            });
        },
        downloadPackage() {
            this.$refs.form.submit()
        },
        picking_list() {
            this.$refs.picking_form.submit()
        },
        export_dispatch() {
            this.$refs.dispatch_form.submit()
        },
        openPackage(data) {
            eventBus.$emit('openPackageEvent', data.row)
            this.package_show = true
        },
        selectionChanged(params) {
            this.checkedRows = params.selectedRows
        },
        filter_packages() {
            this.$store.dispatch('filter_packages', this.sort)
        },
        packed(item, payload) {
            // console.log(payload);
            axios.patch(`/packages/${item}`, {
                    form: payload
                })
                .then((response) => {
                    this.$message({
                        type: 'success',
                        message: 'Updated'
                    });
                    this.getPackage_list()
                })
                .catch((error) => {

                });
        },
        tomorrow() {
            const date = new Date();
            date.setTime(date.getTime() + 3600 * 1000 * 24);
            this.sort.end_date = date
        },

        getSellers() {
            var payload = {
                model: '/seller/sellers',
                update: 'updateSellerList'
            }
            this.$store.dispatch("getItems", payload);
        },
    },
    mounted() {
        // this.filter_packages();
        this.tomorrow();
        this.getSellers();
    },
    created() {
        eventBus.$on('printPackageEvent', data => {
            this.package_show = true
        });
    },
    filters: {
        moment(date) {
            return moment(date).format('MMMM Do YYYY');
        },
        moment2(date) {
            return moment(date).format('MMMM Do YYYY', 'h:mm:ss a');
        },
    },
}
</script>

<style scoped>
.v-toolbar__content,
.v-toolbar__extension {
    height: auto !important;
}

.v-card>:last-child:not(.v-btn):not(.v-chip) {
    margin-top: 100px;
}

.v-avatar {
    height: 10px !important;
    width: 10px !important;
    margin-left: 40% !important;
}

.el-header {
    background-color: #B3C0D1;
    color: #333;
    line-height: 60px;
}
</style>
