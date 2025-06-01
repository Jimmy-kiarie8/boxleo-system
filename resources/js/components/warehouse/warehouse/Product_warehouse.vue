<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <v-flex sm12>
                <v-card style="padding: 20px 0;">
                    <el-breadcrumb separator-class="el-icon-arrow-right">
                        <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                        <el-breadcrumb-item :to="{ path: '/createwarehouse' }">Warehouses</el-breadcrumb-item>
                        <el-breadcrumb-item>{{ product_warehouse.name }}</el-breadcrumb-item>
                    </el-breadcrumb>
                </v-card>
            </v-flex>
            <v-flex sm12>
                <v-card style="padding: 10px 0;">
                    <v-layout row>
                        <v-flex sm1 style="margin-left: 10px;">
                            <v-tooltip right>
                                <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getProdWarehouse">
                                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                </v-btn>
                                </template>
                                <span>Refresh</span>
                            </v-tooltip>
                        </v-flex>
                        <v-flex sm1>
                            <h3 style="margin-left: 30px !important;margin-top: 10px;">Products</h3>
                        </v-flex>
                        <v-flex sm2>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex sm12>
                <vue-good-table class="table-hover" :columns="columns" :rows="product_warehouse.products" :search-options="{ enabled: true }" :pagination-options="{enabled: true,mode: 'pages'}" v-loading="isLoading" @on-row-click="openEdit">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'created_at'">
                            <span>
                                <el-tag type="success">{{props.formattedRow.created_at}}</el-tag>
                            </span>
                        </span>
                        <span v-else-if="props.column.field == 'lot'">
                            <span>
                                <el-tooltip content="lot" placement="top" v-if="props.row.lot">
                                    <v-avatar style="cursor: pointer" color="green" small></v-avatar>
                                </el-tooltip>
                                <el-tooltip content="Not lot" placement="top" v-else>
                                    <v-avatar style="cursor: pointer" color="red" small></v-avatar>
                                </el-tooltip>
                            </span>
                        </span>
                        <span v-else-if="props.column.field == 'has_serial'">
                            <span>
                                <el-tooltip content="has serial" placement="top" v-if="props.row.has_serial">
                                    <v-avatar style="cursor: pointer" color="green" small></v-avatar>
                                </el-tooltip>
                                <el-tooltip content="has no serial" placement="top" v-else>
                                    <v-avatar style="cursor: pointer" color="red" small></v-avatar>
                                </el-tooltip>
                            </span>
                        </span>
                        <span v-else-if="props.column.field == 'dangerous'">
                            <span>
                                <el-tooltip content="Dangerous" placement="top" v-if="props.row.dangerous">
                                    <v-avatar style="cursor: pointer" color="green" small></v-avatar>
                                </el-tooltip>
                                <el-tooltip content="Not dangerous" placement="top" v-else>
                                    <v-avatar style="cursor: pointer" color="red" small></v-avatar>
                                </el-tooltip>
                            </span>
                        </span>
                        <span v-else>
                            {{props.formattedRow[props.column.field]}}
                        </span>
                    </template>
                </vue-good-table>
            </v-flex>
        </v-layout>
    </v-container>
</div>
</template>

<script>
export default {
    data() {
        return {
            form: {},
            loader: false,
            isEmpty: false,
            isBordered: true,
            isStriped: true,
            isNarrowed: false,
            isHoverable: true,
            isFocusable: true,
            hasMobileCards: true,
            isPaginated: true,
            currentPage: 1,
            perPage: 5,
            search: '',
            products_det: {
                data: []
            },
            products_search: [],
            temp: '',
            product_warehouse: [],
            checkedRows: [],

            columns: [{
                    label: 'Id#',
                    field: 'id',
                    type: 'number',
                },
                {
                    label: 'Product Name',
                    field: 'product_name',
                },
                {
                    label: 'Sku Number',
                    field: 'sku_no',
                },
                {
                    label: 'Barcode',
                    field: 'bar_code',
                },
                {
                    label: 'Onhand',
                    field: 'onhand',
                    type: 'number'
                },
                {
                    label: 'Lot Product',
                    field: 'lot',
                    sortable: false,
                },
                {
                    label: 'Has Serial',
                    field: 'has_serial',
                    sortable: false,
                },
                {
                    label: 'Dangerous Goods',
                    field: 'dangerous',
                    sortable: false,
                },
                {
                    label: 'Created On',
                    field: 'created_at',
                    type: 'date',
                    dateInputFormat: 'YYYY-MM-DD',
                    dateOutputFormat: 'Do MMMM YYYY',
                },
            ],
        }
    },
    methods: {
        openCreate() {
            eventBus.$emit('openCreateProduct')
        },
        openExcel() {
            eventBus.$emit('openExcelProduct')
        },
        openUpload() {
            eventBus.$emit('openChooseupload')
        },
        openEdit(data) {
            console.log(data.row.id)
            // router.push({ name: 'editProducts', params: { id: data.id } })
            this.$router.push({
                name: "editProducts",
                params: {
                    id: data.row.id
                }
            });
            // eventBus.$emit('openEditProduct', data)
        },

        openShow(data) {
            eventBus.$emit('openShowProduct', data)
        },
        getProdWarehouse() {
            // this.$store.dispatch('getProdWarehouse');
            axios.get(`warehouses/${this.$route.params.id}`)
                .then((response) => {
                    this.product_warehouse = response.data
                })

        },
        filter(data) {
            this.$store.dispatch('filterProd_table', data)
        },
        filteredList() {
            // console.log(this.products.data.filter(product => {
            //     return product.product_name.toLowerCase().includes(this.search.toLowerCase())
            // }));
            if (this.search.length < 1) {
                this.products
                return
            } else {
                this.products_det.data = this.products.data.filter(product => {
                    return product.product_name.toLowerCase().includes(this.search.toLowerCase())
                })
            }
        },
        updateSelected(url) {
            // alert('test')
            if (this.checkedRows.length < 1) {
                eventBus.$emit('errorEvent', 'Please select atleast one row')
                return
            }

            this.$store.dispatch('updateSelected', {
                    url,
                    checked: this.checkedRows
                })
                .then((response) => {
                    eventBus.$emit('alertRequest', 'Updated')
                    this.checkedRows = []
                    this.getProdWarehouse()
                })
        },
        selectionChanged(params) {
            this.checkedRows = params.selectedRows
        },
    },
    computed: {
        isLoading() {
            return this.$store.getters.loading;
        },
    },
    mounted() {
        // this.$store.dispatch('getProdWarehouse');
        this.getProdWarehouse();
    },

}
</script>

<style scoped>
.el-input--prefix .el-input__inner {
    border-radius: 50px !important;
}

.v-toolbar__content,
.v-toolbar__extension {
    height: auto !important;
}
.v-avatar {
    height: 10px !important;
    width: 10px !important;
    margin-left: 40% !important;
}
</style>
