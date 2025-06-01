<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <v-flex sm12>
                <v-card style="padding: 20px 0;">
                    <el-breadcrumb separator-class="el-icon-arrow-right">
                        <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                        <el-breadcrumb-item>Riders</el-breadcrumb-item>
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
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getRiders">
                                        <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                    </v-btn>
                                </template>
                                <span>Refresh</span>
                            </v-tooltip>
                        </v-flex>
                        <v-flex sm2>
                            <h3 style="margin-left: 30px !important;margin-top: 10px;">Riders</h3>
                        </v-flex>
                        <v-flex offset-sm8 sm3>
                            <v-btn color="info" @click="openCreate" text v-if="user.can['Rider create']">New Rider</v-btn>
                        </v-flex>
                    </v-layout>
                    <v-pagination v-model="riders.current_page" :length="riders.last_page" total-visible="5" @input="next_page(riders.path, riders.current_page)" circle v-if="riders.last_page > 1"></v-pagination>
                    <v-card-title>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="riders" :search="search">
                        
                        <template v-slot:item.actions="{ item }" v-if="user.can['Vendor edit']">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="openEdit(item)" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-pen</v-icon>
                                    </v-btn>
                                </template>
                                <span>Edit {{ item.name }}</span>
                            </v-tooltip>
                            <v-tooltip bottom v-if="item.portal_active">
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="deactivatePortal(item.id)" slot="activator">
                                        <v-icon small color="success" v-if="item.portal_active">mdi-account-multiple-check-outline</v-icon>
                                    </v-btn>
                                </template>
                                <span>Deactivate account</span>
                            </v-tooltip>

                            <v-tooltip bottom v-else>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="activatePortal(item.id)" slot="activator">
                                        <v-icon small color="red">mdi-account-multiple-remove-outline</v-icon>
                                    </v-btn>
                                </template>
                                <span>Activate account</span>
                            </v-tooltip>
                            
                            <v-tooltip bottom v-if="user.can['User reset password'] && item.portal_active">
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="openPassword(item.id)" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-lock</v-icon>
                                    </v-btn>
                                </template>
                                <span>Change {{ item.name }}'s' password</span>
                            </v-tooltip>

                            <v-tooltip bottom v-if="user.can['Vendor delete']">
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" class="mx-0" @click="confirm_delete(item)" slot="activator">
                                        <v-icon small color="pink darken-2" v-if="item.active">mdi-checkbox</v-icon>
                                        <v-icon small color="pink darken-2" v-else>mdi-close-circle</v-icon>
                                    </v-btn>
                                </template>
                                <span v-if="item.active">Deactivate {{ item.name }}</span>
                                <span v-else>Activate {{ item.name }}</span>
                            </v-tooltip>
                        </template>
                    </v-data-table>

                </v-card>
            </v-flex>
            <!-- <v-flex sm12>
            </v-flex> -->
        </v-layout>
    </v-container>
    <Create></Create>
    <Edit></Edit>
    <myPassword />
</div>
</template>

<script>
import Create from "./create";
import Edit from "./edit";
import myPassword from './Password';
import {
    mapState
} from 'vuex';

export default {
    props: ['user'],
    components: {
        Create,
        Edit,
        myPassword
    },
    data() {
        return {
            form: {},
            loader: false,
            search: "",
            riders_det: {
                data: []
            },
            riders_search: [],
            temp: "",
            checkedRows: [],
            headers: [{
                    text: "Id#",
                    value: "id",
                },
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
            eventBus.$emit("openCreateRider");
        },
        openPassword(data) {
            eventBus.$emit('openPasswordEvent', data)
        },
        openEdit(data) {
            eventBus.$emit("openEditRider", data);
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

        activatePortal(id, status) {
            var payload = {
                data: {
                    status: status
                },
                id: id,
                model: '/rider/mobile_account',
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("riderEvent")
                });
        },
        deleteItem(item) {
            this.$store.dispatch("deleteItem", "riders/" + item.id).then(response => {
                this.$message({
                    type: 'success',
                    message: 'Delete completed'
                });
                this.getRiders();
            });
        },
        openShow(data) {
            eventBus.$emit("openShowRider", data);
        },
        getRiders() {
            var payload = {
                model: 'rider/riders',
                update: 'updateRidersList'
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
                    this.getRiders();
                });
        },
        selectionChanged(params) {
            this.checkedRows = params.selectedRows;
        },

        next_page(path, page) {
            var payload = {
                path: path,
                page: page,
                update: 'updateRidersList'
            }
            this.$store.dispatch("nextPage", payload);
        },
    },
    computed: {
        ...mapState(['riders', 'loading'])
    },
    mounted() {
        // this.$store.dispatch('getRiders');
        eventBus.$emit("LoadingEvent");
        this.getRiders();
    },
    created() {
        eventBus.$on("riderEvent", data => {
            this.getRiders();
        });

        eventBus.$on("responseChooseEvent", data => {
            console.log(data);
            if (data == "Excel") {
                eventBus.$emit("openEditRider");
            } else {
                eventBus.$emit("openCreateRider");
            }
        });
    },

    //   beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //       if (vm.user.can["view riders"]) {
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
