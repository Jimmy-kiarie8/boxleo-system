<template>
    <div>
        <v-container fluid fill-height>
            <v-layout justify-center align-center wrap>
                <v-flex sm12>
                    <v-card style="padding: 20px 0;">
                        <el-breadcrumb separator-class="el-icon-arrow-right">
                            <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                            <el-breadcrumb-item>Users</el-breadcrumb-item>
                        </el-breadcrumb>
                    </v-card>
                </v-flex>
                <v-flex sm12>
                    <!-- <myFilter :form="form" :user="user" style></myFilter> -->
                </v-flex>
                <v-flex sm12>
                    <v-card style="padding: 10px 0;">
                        <v-layout row>
                            <v-flex sm1 style="margin-left: 10px;">
                                <v-tooltip right>
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getUsers">
                                            <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Refresh</span>
                                </v-tooltip>
                            </v-flex>
                            <v-flex sm2>
                                <h3 style="margin-left: 30px !important;margin-top: 10px;">Users</h3>
                            </v-flex>
                            <v-flex offset-sm8 sm3 v-if="user.can['User create']">
                                <v-btn color="info" @click="openCreate" text>New User</v-btn>
                                <v-btn color="info" @click="openUpload" text>Upload Users</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </v-flex>
                <v-flex sm12>
                    <v-pagination v-model="users.current_page" :length="users.last_page" total-visible="5"
                        @input="next_page(users.path, users.current_page)" circle
                        v-if="users.last_page > 1"></v-pagination>
                </v-flex>
                <v-flex sm12>
                    <v-card>
                        <v-card-title>
                            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                                hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="users.data" :search="search">
                            <template v-slot:item.active="{ item }">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" slot="activator">
                                            <v-icon small color="success" v-if="item.active">mdi-check-circle</v-icon>
                                            <v-icon small color="red" v-else>mdi-cancel</v-icon>
                                        </v-btn>
                                    </template>
                                    <span v-text="(item.active) ? 'Active' : 'Inactive'"></span>
                                </v-tooltip>
                            </template>

                            <template v-slot:item.actions="{ item }">
                                <v-tooltip bottom v-if="user.can['User edit']">
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="openEdit(item)" slot="activator">
                                            <v-icon small color="blue darken-2">mdi-pen</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Edit {{ item.name }}</span>
                                </v-tooltip>
                                <v-tooltip bottom v-if="user.can['User permissions']">
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="openPerm(item.id)" slot="activator">
                                            <v-icon small color="orange darken-2">mdi-dialpad</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Edit {{ item.name }}'s permisions</span>
                                </v-tooltip>
                                <v-tooltip bottom v-if="user.can['User reset password']">
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="openPassword(item.id)"
                                            slot="activator">
                                            <v-icon small color="blue darken-2">mdi-lock</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Change {{ item.name }}'s' password</span>
                                </v-tooltip>

                                <v-tooltip bottom v-if="user.roles[0].name == 'Super admin'">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" class="mx-0" :href="`/impersonate/take/${item.id}/web`"
                                            slot="activator">
                                            <v-icon small color="blue darken-2">mdi-account-arrow-left</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Impersonate {{ item.name }}</span>
                                </v-tooltip>
                                <v-tooltip bottom v-if="user.can['User delete']">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" class="mx-0" @click="confirm_delete(item)"
                                            slot="activator">
                                            <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Delete {{ item.name }}</span>
                                </v-tooltip>

                                <v-tooltip bottom v-if="item.active">
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="userActive(item.id, false)"
                                            slot="activator">
                                            <v-icon small color="red"
                                                v-if="item.active">mdi-account-multiple-remove-outline</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Deactivate User</span>
                                </v-tooltip>

                                <v-tooltip bottom v-else>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="userActive(item.id, true)"
                                            slot="activator">
                                            <v-icon small color="success">mdi-account-multiple-check-outline</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Activate User</span>
                                </v-tooltip>


                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="break_update(item.id, item.on_break)"
                                            slot="activator">
                                            <v-icon small color="primary" v-if="item.on_break">mdi-phone-check</v-icon>
                                            <v-icon small color="red" v-else>mdi-phone-cancel</v-icon>
                                        </v-btn>
                                    </template>
                                    <span v-text="(!item.on_break) ? 'On Break' : 'Active'"></span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-card>

                </v-flex>
            </v-layout>
        </v-container>
        <Create />
        <Edit />
        <myPassword />
        <myUpload />
        <PermUser />
    </div>
</template>

<script>
import Create from "./create";
import Edit from "./edit";
import myPassword from './Password'
import PermUser from './Permission.vue'
import { mapState } from 'vuex';
import myUpload from './excel'

export default {
    props: ['user'],
    components: {
        Create,
        Edit,
        PermUser,
        myPassword, myUpload
    },
    data() {
        return {
            form: {},
            loader: false,
            search: "",
            users_det: {
                data: []
            },
            users_search: [],
            temp: "",
            checkedRows: [],
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
                    text: "Status.",
                    value: "active"
                },
                {
                    text: "Created On",
                    value: "created_at",
                    // type: "date",
                    // dateInputFormat: "YYYY-MM-DD",
                    // dateOutputFormat: "Do MMMM YYYY"
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
        break_update(id, on_break) {
            this.form = {
                on_break: on_break
            }
            var payload = {
                data: this.form,
                id: id,
                model: '/on_break',
            }
            // this.payload['data'] = this.form
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    // eventBus.$emit("breakEvent")
                    this.getUsers()
                });
        },
        userActive(id, status) {
            var payload = {
                data: {
                    status: status
                },
                id: id,
                model: '/user-active',
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    this.getUsers();
                });
        },
        openCreate() {
            eventBus.$emit("openCreateUser");
        },
        openUpload() {
            eventBus.$emit("openExcelUploadEvent");
        },
        openEdit(data) {
            eventBus.$emit("openEditUser", data);
        },
        openPerm(id) {
            console.log(id);
            var payload = {
                model: 'getUserPerm',
                update: 'updateUserPermission',
                id: id
            }

            this.$store.dispatch("showItem", payload).then((response) => {
                eventBus.$emit('permEvent', {
                    data: response.data,
                    id: id
                });
            });

            // axios.post(`/getUserPerm/${item.id}`)
            //     .then((response) => {
            //         eventBus.$emit('permEvent', response.data);
            //     })
            //     .catch((error) => {
            //         this.errors = error.response.data.errors
            //     })
            // this.permEdit = true
        },
        openPassword(data) {
            eventBus.$emit('openPasswordEvent', data)
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
            this.$store.dispatch("deleteItem", "users/" + item.id).then(response => {
                this.$message({
                    type: 'success',
                    message: 'Delete completed'
                });
                this.getUsers();
            });
        },
        openShow(data) {
            eventBus.$emit("openShowUser", data);
        },
        getUsers() {
            var payload = {
                model: 'users',
                update: 'updateUsersList'
            }
            this.$store.dispatch("getItems", payload);
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
                    this.getUsers();
                });
        },
        selectionChanged(params) {
            this.checkedRows = params.selectedRows;
        },

        next_page(path, page) {
            var payload = {
                path: path,
                page: page,
                update: 'updateUsersList'
            }
            this.$store.dispatch("nextPage", payload);
        },
    },
    computed: {
        ...mapState(['users', 'loading', 'user_permissions', 'permissions']),
    },
    mounted() {
        // this.$store.dispatch('getUsers');
        eventBus.$emit("LoadingEvent");
        this.getUsers();
    },
    created() {
        eventBus.$on("userEvent", data => {
            this.getUsers();
        });

        eventBus.$on("responseChooseEvent", data => {
            console.log(data);
            if (data == "Excel") {
                eventBus.$emit("openEditUser");
            } else {
                eventBus.$emit("openCreateUser");
            }
        });
    },

    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can["User view"]) {
                next();
            } else {
                next("/");
            }
        });
    }
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

.v-main__wrap,
.v-main {
    padding: 0 !important;
}
</style>
