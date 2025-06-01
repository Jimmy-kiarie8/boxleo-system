<template>
<div>
    <v-container fluid fill-height id="orders">
        <v-card style="padding: 20px 20px;" v-if="ready" width="100%">
            <div v-if="setup_loaded && (!setup.products || !setup.vendors)">
                <mySetup />
            </div>
            <v-layout justify-center align-center wrap v-else>
                <v-flex sm12>
                    <el-breadcrumb separator-class="el-icon-arrow-right">
                        <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                        <el-breadcrumb-item>M-pesa</el-breadcrumb-item>
                    </el-breadcrumb>
                </v-flex>
                <v-divider></v-divider>
                <v-flex sm12>
                    <v-text-field v-model="trans_item.search" label="Search transaction" required prepend-icon="mdi-magnify" @keyup.enter="search_trans"></v-text-field>
                </v-flex>
                <v-flex sm12>
                    <el-date-picker v-model="form.date" type="daterange" align="right" start-placeholder="Start Date" end-placeholder="End Date" style="width: 50%;" format="yyyy/MM/dd" value-format="yyyy-MM-dd">
                        </el-date-picker>

                        <v-btn-toggle dense background-color="primary" dark style="margin-top: 22px;">
                            <v-btn @click="filter">
                                <v-icon>mdi-magnify</v-icon>
                            </v-btn>
                        </v-btn-toggle>
                </v-flex>
                <v-flex sm12 style="margin-top: 30px">
                    <v-pagination v-model="transactions.current_page" :length="transactions.last_page" total-visible="5" @input="next_page(transactions.path, transactions.current_page)" circle v-if="transactions.last_page > 1"></v-pagination>
                </v-flex>
                <v-flex sm12>

                    <v-tooltip right>
                        <template v-slot:activator="{ on }">
                            <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getTransactions">
                                <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                            </v-btn>
                        </template>
                        <span>Refresh</span>
                    </v-tooltip>
                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Quick Search" single-line hide-details></v-text-field>
                    <v-data-table :headers="headers" :items="transactions.data" :search="search" item-key="id" class="elevation-1" :loading="loading" style="font-size: 10px !important">

                        <template v-slot:item.created_at="{ item }">
                            <el-tag type="warning">{{ item.created_at }}</el-tag>
                        </template>
                        <template v-slot:item.mpesa_code="{ item }">
                            <el-tag>{{ item.mpesa_code }}</el-tag>
                        </template>

                        <template v-slot:item.code_used="{ item }">
                            <el-tooltip :content="(item.code_used) ? 'Code matched' : 'Code not matched'" placement="top">
                                <v-avatar style="cursor: pointer" :color="(item.code_used) ? 'green' : 'red'" small></v-avatar>
                            </el-tooltip>
                        </template>

                        <template v-slot:item.actions="{ item }" v-if="user.can['Order view']">
                            <v-tooltip bottom v-if="item.code_used">
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="openShow(item.TransID)" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-eye-outline</v-icon>
                                    </v-btn>
                                </template>
                                <span>Quick view</span>
                            </v-tooltip>
                        </template>

                    </v-data-table>
                </v-flex>
            </v-layout>
        </v-card>

        <v-sheet :color="`grey ${theme.isDark ? 'darken-2' : 'lighten-4'}`" class="px-3 pt-3 pb-3" v-else style="width: 100vw;">
            <v-skeleton-loader class="mx-auto" max-width="900" type="table"></v-skeleton-loader>
        </v-sheet>
    </v-container>
    <myShow />
</div>
</template>

<script>
import {
    mapState
} from 'vuex'
import myShow from './show'
export default {
    props: ['user', 'tenant'],
    components: {myShow},
    inject: ['theme'],
    data() {
        return {
            search: '',
            form: {},
            trans_item: {
                search: ''
            },
            ready: false,
            headers: [{
                    text: "Created On",
                    value: "created_at"
                },
                {
                    text: "Account No.",
                    value: "BillRefNumber"
                },
                {
                    text: "Transaction Id",
                    value: "TransID"
                },
                {
                    text: "Transaction time",
                    value: "TransTime"
                },
                {
                    text: "Amount",
                    value: "TransAmount"
                },
                {
                    text: "Phone number",
                    value: "MSISDN"
                },
                {
                    text: "Name",
                    value: "name"
                },
                {
                    text: "Code matched",
                    value: "code_used"
                },
                {
                    text: "Transaction Type",
                    value: "TransactionType"
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],
            snack: false,
            setup_loaded: false,
            default_filter: true

        };
    },
    methods: {
        filter() {
            var payload = {
                model: 'transactions-filter',
                update: 'updateTransactions',
                data: this.form
            }
            this.$store.dispatch('filterItems', payload)
                .then(() => {
                    this.ready = true
                });
        },
        getTransactions() {
            var payload = {
                model: 'transactions',
                update: 'updateTransactions',
            }
            this.$store.dispatch('getItems', payload)
                .then(() => {
                    this.ready = true
                });
        },
        search_trans() {

            var payload = {
                model: 'transaction_search',
                update: 'updateTransactions',
                search: this.trans_item.search
            }
            this.$store.dispatch('searchItems', payload)
        },
        next_page(path, page) {
            var data = {
                path: path,
                page: page,
            }
            eventBus.$emit('refreshNextEvent', data)
        },
        openShow(data) {
            eventBus.$emit('mpesaTransaction', data)
        }
    },
    computed: {
        ...mapState(['transactions', 'loading']),
    },
    mounted() {
        this.getTransactions();
    },

    //   beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //       if (vm.user.can["view sales"]) {
    //         next();
    //       } else {
    //         next("/");
    //       }
    //     });
    //   }
};
</script>

<style>
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

.theme--light.v-data-table>.v-data-table__wrapper>table>tbody>tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {
    cursor: pointer !important;
}

#orders td {
    padding: 0 0 0 15px !important;
}

#orders .v-data-table>.v-data-table__wrapper>table {
    width: 150% !important;
}

#address_tab span {
    font-style: inherit;
    font-weight: inherit;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 180px;
    overflow: hidden;
    display: block;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
</style>
