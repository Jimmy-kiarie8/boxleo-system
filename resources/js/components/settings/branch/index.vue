<template>
<div>
    <div>
        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <div class="container">
                    <v-card>
                        <h3 class="text-center">Branches</h3>
                        <v-divider></v-divider>
                        <v-card-title>

                            <v-tooltip right>
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getBranches">
                                        <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                    </v-btn>
                                </template>
                                <span>Refresh</span>
                            </v-tooltip>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="openCreate">Add Branch</v-btn>
                            <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                        </v-card-title>
                        <vue-good-table class="table-hover" :columns="columns" :rows="branches" :search-options="{ enabled: true }" :pagination-options="{enabled: true,mode: 'pages'}" :sort-options="{enabled: true, initialSortBy: {field: 'branch_name', type: 'asc'}}" v-loading="isLoading">
                            <template slot="table-row" slot-scope="props">
                                <span v-if="props.column.field == 'created_at'">
                                    <span>
                                        <el-tag type="success">{{props.formattedRow.created_at}}</el-tag>
                                    </span>
                                </span>
                                <span v-else-if="props.column.field == 'actions'">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="openEdit(props.row)" slot="activator">
                                                <v-icon small color="blue darken-2">mdi-pen</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Edit</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="deleteItem(props.row)" slot="activator">
                                                <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Delete</span>
                                    </v-tooltip>
                                </span>
                                <span v-else>
                                    {{props.formattedRow[props.column.field]}}
                                </span>
                            </template>
                        </vue-good-table>
                    </v-card>
                </div>
            </v-layout>
        </v-container>
    </div>
    <Create></Create>
    <Edit></Edit>
    <!-- <Show></Show> -->
</div>
</template>

<script>
import Create from './Create';
import Edit from './Edit';
// import Show from './Show';
export default {
    components: {
        Create,
        Edit,
        // Show
    },
    data() {
        return {
            loading: false,
            isEmpty: false,
            loader: false,
            search: '',
            selected: [],
            columns: [{
                    label: 'Branch Name',
                    field: 'branch_name'
                },
                {
                    label: 'Branch Email',
                    field: 'email'
                },
                {
                    label: 'Phone No.',
                    field: 'phone'
                },
                {
                    label: 'Created On',
                    field: 'created_at',
                    type: 'date',
                    dateInputFormat: 'YYYY-MM-DD',
                    dateOutputFormat: 'MMM Do YYYY',
                },
                {
                    label: 'Actions',
                    field: 'actions',
                    sortable: false
                }
            ],
            isPaginated: true,
            currentPage: 1,
            perPage: 5,
            items: ['Activate Branch', 'Deactivate Branch', 'Delete Branch'],
        }
    },
    methods: {
        openCreate() {
            eventBus.$emit('openCreateBranch')
            this.getOu()
        },

        openEdit(data) {
            eventBus.$emit('openEditBranch', data)
            this.getOu()
        },

        openShow(data) {
            eventBus.$emit('openShowBranch', data)
        },
        getBranches() {
            var payload = {
                model: 'branches',
                update: 'updateBranches',
            }
            this.$store.dispatch('getItems', payload);
        },
        getOu() {
            var payload = {
                model: 'ou',
                update: 'updateOuList',
            }
            this.$store.dispatch('getItems', payload);
        },
        deleteItem(item) {
            console.log(item)
            const index = this.$store.getters.branches.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/branches/${item.id}`)
                    .then(response => {
                        this.$store.getters.branches.splice(index, 1);
                        //// console.log(response);
                    })
                    .catch(error => (this.errors = error.response.data.errors));
            }
        },
    },
    computed: {
        branches() {
            return this.$store.getters.branches
        },

        isLoading() {
            return this.$store.getters.loading;
        },
    },
    mounted() {
        eventBus.$emit("LoadingEvent");
        // this.$store.dispatch('getBranches');
        this.getBranches();
    },
    created() {
        eventBus.$on('branchEvent', data => {
            this.getBranches();
        })
    },
}
</script>
