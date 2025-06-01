<template>
<div>
        <v-container fluid fill-height>
            <v-layout justify-center align-center>
                <v-card style="width: 100%;">
                    <v-card-title>
                        Roles
                        <v-btn @click="openAdd" text color="primary">Add Roles</v-btn>
                        <!-- <v-spacer></v-spacer> -->
                        <v-tooltip right>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getRoles">
                                    <v-icon color="blue darken-2" small>refresh</v-icon>
                                </v-btn>
                            </template>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-flex sm12>
                        <!-- <vue-good-table class="table-hover" :columns="columns" :rows="roles" :search-options="{ enabled: true }" :pagination-options="{enabled: true,mode: 'pages'}" v-loading="isLoading">
                            <template slot="table-row" slot-scope="props">
                                <span v-if="props.column.field == 'created_at'">
                                    <span>
                                        <el-tag type="success">{{props.row.created_at}}</el-tag>
                                    </span>
                                </span>
                                <span v-else-if="props.column.field == 'actions'">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="openEdit(props.row)" slot="activator">
                                                <v-icon small color="blue darken-2">edit</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Edit</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="deleteItem(props.item)" slot="activator">
                                                <v-icon small color="pink darken-2">delete</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Delete</span>
                                    </v-tooltip>
                                </span>
                                <span v-else>
                                    {{props.formattedRow[props.column.field]}}
                                </span>
                            </template>
                        </vue-good-table> -->

                        <v-data-table :headers="headers" :items="roles" :search="search">
                            <template v-slot:item.actions="{ item }">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="openEdit(item)" slot="activator">
                                            <v-icon small color="blue darken-2">edit</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Edit {{ item.name }}</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" class="mx-0" @click="confirm_delete(item)" slot="activator">
                                            <v-icon small color="pink darken-2">delete</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Delete {{ item.name }}</span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-card>
            </v-layout>
        </v-container>
    <myCreate />
    <myEdit />
</div>
</template>

<script>
import myCreate from './create'
import myEdit from './Edit'
export default {
    props: ['user'],
    components: {
        myCreate,
        myEdit
    },
    data() {
        return {
            loader: false,
            headers: [{
                    text: 'Id#',
                    value: 'id',
                },
                {
                    text: 'Role',
                    value: 'name',
                },
                {
                    text: 'Description',
                    value: 'description',
                },
                {
                    text: 'Created On',
                    value: 'created_at',
                    // type: 'date',
                    // dateInputFormat: 'YYYY-MM-DD',
                    // dateOutputFormat: 'MMM Do YYYY',
                },
                {
                    text: 'Actions',
                    value: 'actions',
                },
            ],
            search: '',
        }
    },
    methods: {
        openShow(key) {

        },
        openAdd() {
            eventBus.$emit('openCreateRoleEvent')
        },
        openEdit(item) {
            eventBus.$emit('openEditRoleEvent', item)
            this.$store.dispatch('getPermissions', item.guard_name)
        },

        deleteItem(item) {
            if (confirm('Are you sure you want to delete this item?')) {
                this.message = 'Deleting...'
                this.color = 'indigo'
                this.snackbar = true
                // this.timeout = 20000
                this.color = 'indigo'
                axios.delete(`/roles/${item}`)
                    .then((response) => {
                        this.snackbar = false
                        this.AllRoles.splice(index, 1)
                        this.message = 'deleted successifully'
                        this.color = 'red'
                        this.snackbar = true
                    })
                    .catch((error) => {
                        this.snackbar = false
                        this.message = 'something went wrong'
                        this.color = 'red'
                        this.snackbar = true
                        this.errors = error.response.data.errors
                    })
            }
        },

        getRoles() {
            this.$store.dispatch('getRoles')
                .then(() => {
                    this.loader = false
                })
        },
    },
    mounted() {
        this.loader = true
        this.getRoles()
    },
    computed: {
        roles() {
            return this.$store.getters.roles;
        },
        isLoading() {
            return this.$store.getters.loading;
        },
    },
    //   beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //       if (vm.user.can["view roles"]) {
    //         next();
    //       } else {
    //         next("/");
    //       }
    //     });
    //   }
}
</script>
