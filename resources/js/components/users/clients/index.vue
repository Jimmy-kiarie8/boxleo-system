<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <v-flex sm12>
                <v-card style="padding: 20px 0;">
                    <el-breadcrumb separator-class="el-icon-arrow-right">
                        <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                        <el-breadcrumb-item>Clients</el-breadcrumb-item>
                    </el-breadcrumb>
                </v-card>
            </v-flex>
                <v-flex sm9>
                    <v-text-field v-model="client_item.search" label="Search" required prepend-icon="mdi-magnify" @keyup.enter="search_client"></v-text-field>
                </v-flex>
            <v-flex sm12>
                <v-pagination v-model="clients.current_page" :length="clients.last_page" total-visible="5" @input="next_page(clients.path, clients.current_page)" circle v-if="clients.last_page > 1"></v-pagination>
            </v-flex>
            <v-flex sm12>
                <v-card>
                    <v-card-title>
                        Customers
                        <v-tooltip right>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getClients">
                                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                </v-btn>
                            </template>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-btn color="info" @click="openCreate" text v-if="user.can['Client create']">New Customers</v-btn>
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="clients.data" :search="search" :loading="loading">
                        <template v-slot:item.actions="{ item }" v-if="user.can['Client edit']">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="openEdit(item)" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-pen</v-icon>
                                    </v-btn>
                                </template>
                                <span>Edit {{ item.name }}</span>
                            </v-tooltip>
                            <v-tooltip bottom v-if="user.can['Client delete']">
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" class="mx-0" @click="confirm_delete(item)" slot="activator">
                                        <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Delete {{ item.name }}</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="openShow(item.id)" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-eye</v-icon>
                                    </v-btn>
                                </template>
                                <span>{{ item.name }}'s' Details</span>
                            </v-tooltip>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
    <Create />
    <Edit />
    <Show />
</div>
</template>

<script>
import { mapState } from 'vuex';
import Create from "./create";
import Edit from "./edit";
import Show from "./show";

export default {
    props: ['user'],
    components: {
        Create,
        Edit, Show
    },
    data() {
        return {
            form: {},
            loader: false,
            search: "",
            payload: {
                model: 'clients',
                update: 'updateClientList'
            },
            clients_det: {
                data: []
            },
            clients_search: [],
            temp: "",
            checkedRows: [],
            client_item: {
                search: ''
            },
            headers: [
                // {
                //     text: "Id#",
                //     value: "id",
                //     type: "number"
                // },
                {
                    text: "Name",
                    value: "name"
                },
                {
                    text: "Email",
                    value: "email"
                },
                {
                    text: "Phone No.",
                    value: "phone"
                },
                {
                    text: "Address",
                    value: "address"
                },
                {
                    text: "Created On",
                    value: "created_at",
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ]
        };
    },
    methods: {
        openCreate() {
            eventBus.$emit("openCreateClient");
        },
        openEdit(data) {
            eventBus.$emit("openEditClient", data);
        },
        openShow(data) {
            eventBus.$emit("openShowClient", data);
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
        openPassword(data) {
            eventBus.$emit('openPasswordEvent', data)
        },

        deleteItem(item) {
            this.$store.dispatch("deleteItem", "clients/" + item.id).then(response => {
                this.$message({
                    type: 'success',
                    message: 'Delete completed'
                });
                this.getClients();
            });
        },
        getClients() {
            this.$store.dispatch("getItems", this.payload);
        },
        search_client() {
            var payload = {
                model: 'client_search',
                update: 'updateClientList',
                search: this.client_item.search
            }
            this.$store.dispatch('searchItems', payload)
        },
        updateSelected(url) {
            // alert('test')
            if (this.checkedRows.length < 1) {
                eventBus.$emit("errorEvent", "Please select atleast one row");
                return;
            }

            this.$store
                .dispatch("updateSelected", {
                    url,
                    checked: this.checkedRows
                })
                .then(response => {
                    eventBus.$emit("alertRequest", "Updated");
                    this.checkedRows = [];
                    this.getClients();
                });
        },
        selectionChanged(params) {
            this.checkedRows = params.selectedRows;
        },

        next_page(path, page) {
            var payload = {
                path: path,
                page: page,
                update: 'updateClientList'
            }
            this.$store.dispatch("nextPage", payload);
        },
    },
    computed: {
        ...mapState(['clients', 'loading'])
    },
    mounted() {
        // this.$store.dispatch('getClients');
        eventBus.$emit("LoadingEvent");
        this.getClients();
    },
    created() {
        eventBus.$on("clientEvent", data => {
            this.getClients();
        });

        eventBus.$on("responseChooseEvent", data => {
            console.log(data);
            if (data == "Excel") {
                eventBus.$emit("openEditClient");
            } else {
                eventBus.$emit("openCreateClient");
            }
        });
    },

    //   beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //       if (vm.user.can["view clients"]) {
    //         next();
    //       } else {
    //         next("/");
    //       }
    //     });
    //   }
};
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
