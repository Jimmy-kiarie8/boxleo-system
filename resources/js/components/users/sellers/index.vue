<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <v-flex sm12>
                <v-card style="padding: 20px 0;">
                    <el-breadcrumb separator-class="el-icon-arrow-right">
                        <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                        <el-breadcrumb-item>Vendors</el-breadcrumb-item>
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
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getSellers">
                                        <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                    </v-btn>
                                </template>
                                <span>Refresh</span>
                            </v-tooltip>
                        </v-flex>
                        <v-flex sm2>
                            <h3 style="margin-left: 30px !important;margin-top: 10px;">Vendors</h3>
                        </v-flex>
                        <v-flex offset-sm5 sm3>
                            <v-btn-toggle dense background-color="primary" dark>
                                <v-btn @click="openCreate" v-if="user.can['Vendor create']">New Vendor</v-btn>
                                <v-btn @click="openUpload" v-if="user.can['Client create']">Upload</v-btn>

                            </v-btn-toggle>
                            <!-- <v-btn color="info" @click="openCreate" text v-if="user.can['Vendor create']">New Vendor</v-btn> 
                       <v-btn color="info" @click="openUpload" text v-if="user.can['Client create']">Upload Vendors</v-btn> -->
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex sm12>
                <v-pagination v-model="sellers.current_page" :length="sellers.last_page" total-visible="5" @input="next_page(sellers.path, sellers.current_page)" circle v-if="sellers.last_page > 1"></v-pagination>
            </v-flex>
            <v-flex sm12>
                <v-card>
                    <v-card-title>
                        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="sellers.data" :search="search" :single-select="singleSelect" item-key="id" show-select class="elevation-1" v-model="selected" :loading="loading" > 
                        <template v-slot:top>
                            <v-tooltip right v-if="user.can['Vendor edit'] && selected.length > 0">
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="updateService" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-toolbox-outline</v-icon>
                                    </v-btn>
                                </template>
                                <span>Update Charges</span>
                            </v-tooltip>
                        </template>
                        <template v-slot:item.actions="{ item }" v-if="user.can['Vendor edit']">
                            <v-tooltip bottom>
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
                            <!-- <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="shopifyClient(item)" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-shopping</v-icon>
                                    </v-btn>
                                </template>
                                <span>Link Shopify shop</span>
                            </v-tooltip> -->

                            <v-tooltip bottom v-if="user.roles[0].name == 'Super admin'">
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" class="mx-0" :href="`/impersonate/take/${item.id}/seller`" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-account-arrow-left</v-icon>
                                    </v-btn>
                                </template>
                                <span>Impersonate {{ item.name }}</span>
                            </v-tooltip>

                            <v-tooltip bottom v-if="item.portal_active">
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="deactivatePortal(item.id)" slot="activator">
                                        <v-icon small color="success" v-if="item.portal_active">mdi-account-multiple-check-outline</v-icon>
                                    </v-btn>
                                </template>
                                <span>Deactivate Portal</span>
                            </v-tooltip>

                            <v-tooltip bottom v-else>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="activatePortal(item.id)" slot="activator">
                                        <v-icon small color="red">mdi-account-multiple-remove-outline</v-icon>
                                    </v-btn>
                                </template>
                                <span>Activate Portal</span>
                            </v-tooltip>
                            
                            <v-tooltip bottom v-if="user.can['User reset password'] && item.portal_active">
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="openPassword(item.id)" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-lock</v-icon>
                                    </v-btn>
                                </template>
                                <span>Change {{ item.name }}'s' password</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="openSheet(item.id)" slot="activator">
                                        <v-icon color="primary" small>mdi-file-excel</v-icon>
                                    </v-btn>
                                </template>
                                <span> {{ item.name }}'s' Google-sheet details</span>
                            </v-tooltip>

                            <v-tooltip bottom v-if="user.can['Vendor delete']">
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" class="mx-0" @click="confirm_delete(item)" slot="activator">
                                        <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Delete {{ item.name }}</span>
                            </v-tooltip>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
    <Create :user="user" />
    <Edit />
    <myUpload />
    <PermUser />
    <myShopify />
    <mySheet />
    <myPassword />
    <myServices :selectedVendors="selected" />
</div>
</template>

<script>
import Create from "./create";
import Edit from "./edit";
import myUpload from "./excel";
import PermUser from './Permission';
import myShopify from './shopify.vue'
import mySheet from './sheets';
import myPassword from './Password'
import myServices from './services.vue'
import { mapState } from "vuex";

export default {
    props: ['user'],
    components: {
        Create,
        Edit,
        myServices,
        myUpload,
        PermUser,
        myShopify, mySheet, myPassword
    },
    data() {
        return {
            form: {},
            singleSelect: false,
            selected: [],
            loader: false,
            search: "",
            payload: {
                model: '/seller/sellers',
                update: 'updateSellerList'
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
            eventBus.$emit("openCreateSeller");
        },
        openUpload() {
            eventBus.$emit("openExcelUploadEvent");
        },
        openEdit(data) {
            eventBus.$emit("openEditSeller", data);
        },
        shopifyClient(data) {
            eventBus.$emit("shopifyClientSeller", data);
        },
        openPassword(data) {
            eventBus.$emit('openPasswordEvent', data)
        },
        activatePortal(id) {
            var payload = {
                data: {
                    status: 'activated'
                },
                id: id,
                model: '/portal_status',
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("sellerEvent")
                });
        },
        deactivatePortal(id) {
            var payload = {
                data: {
                    status: 'deactivated'
                },
                id: id,
                model: '/portal_status',
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("sellerEvent")
                });
        },
        openPerm(id) {
            console.log(id);
            var payload = {
                model: 'seller/getVendorPerm',
                update: 'updateUserPermission',
                id: id
            }

            this.$store.dispatch("showItem", payload).then((response) => {
                eventBus.$emit('permEvent', {
                    data: response.data,
                    id: id
                });
            });
        },


        updateService() {
            eventBus.$emit("openServiceUpdate", this.selected);
        },
        openShow(data) {
            eventBus.$emit("openShowSeller", data);
        },
        openSheet(data) {
            eventBus.$emit("openSheet", data);
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
            this.$store.dispatch("deleteItem", "sellers/" + item.id).then(response => {
                this.$message({
                    type: 'success',
                    message: 'Delete completed'
                });
                this.getSellers();
            });
        },
        openShow(data) {
            eventBus.$emit("openShowSeller", data);
        },
        getSellers() {
            this.$store.dispatch("getItems", this.payload);
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
                    this.getSellers();
                });
        },
        selectionChanged(params) {
            this.checkedRows = params.selectedRows;
        },

        next_page(path, page) {
            var payload = {
                path: path,
                page: page,
                update: 'updateSellersList'
            }
            this.$store.dispatch("nextPage", payload);
        },
    },
    computed: {
        ...mapState(['loading', 'sellers'])
    },
    mounted() {
        // this.$store.dispatch('getSellers');
        eventBus.$emit("LoadingEvent");
        this.getSellers();
    },
    created() {
        eventBus.$on("sellerEvent", data => {
            this.getSellers();
        });

        eventBus.$on("responseChooseEvent", data => {
            console.log(data);
            if (data == "Excel") {
                eventBus.$emit("openEditSeller");
            } else {
                eventBus.$emit("openCreateSeller");
            }
        });
    },

    //   beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //       if (vm.user.can["view sellers"]) {
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
