<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <v-flex sm12>
                <v-card style="padding: 20px 0;">
                    <el-breadcrumb separator-class="el-icon-arrow-right">
                        <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                        <el-breadcrumb-item>Menus</el-breadcrumb-item>
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
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getMenus">
                                        <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                    </v-btn>
                                </template>
                                <span>Refresh</span>
                            </v-tooltip>
                        </v-flex>
                        <v-flex sm2>
                            <h3 style="margin-left: 30px !important;margin-top: 10px;">Menus</h3>
                        </v-flex>
                        <v-flex offset-sm8 sm2>
                            <v-btn color="info" @click="openCreate" text>Create Menu</v-btn>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex sm12>
                <v-pagination v-model="menu.current_page" :length="menu.last_page" total-visible="5" @input="next_page(menu.path, menu.current_page)" circle v-if="menu.last_page > 1"></v-pagination>
            </v-flex>
            <v-flex sm12>
                <v-data-table :headers="headers" :items="menu.data" :search="search">
                        <template v-slot:item.actions="{ item }">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="openEdit(item)" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-pen</v-icon>
                                    </v-btn>
                                </template>
                                <span>Edit {{ item.name }}</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" class="mx-0" @click="confirm_delete(item)" slot="activator">
                                        <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Delete {{ item.name }}</span>
                            </v-tooltip>
                        </template>
                </v-data-table>


            </v-flex>
        </v-layout>
    </v-container>
    <Create />
    <Edit />
</div>
</template>

<script>
import { mapState } from 'vuex';
import Create from "./create";
import Edit from "./edit";

export default {
    props: ['user'],
    components: {
        Create,
        Edit,
    },
    data() {
        return {
            form: {},
            loader: false,
            search: "",
            payload: {
                model: 'menu',
                update: 'updateMenuList'
            },
            menu_det: {
                data: []
            },
            menu_search: [],
            temp: "",
            checkedRows: [],
            headers: [{
                    text: "Id#",
                    value: "id",
                    type: "number"
                },
                {
                    text: "Menu",
                    value: "menu"
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
            eventBus.$emit("openCreateMenu");
        },
        openEdit(data) {
            console.log(data);
            eventBus.$emit("openEditMenu", data);
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
            this.$store.dispatch("deleteItem", "menu/" + item.id).then(response => {
                this.$message({
                    type: 'success',
                    message: 'Delete completed'
                });
                this.getMenus();
            });
        },
        openShow(data) {
            eventBus.$emit("openShowMenu", data);
        },
        getMenus() {
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
                    this.getMenus();
                });
        },
        selectionChanged(params) {
            this.checkedRows = params.selectedRows;
        },

        next_page(path, page) {
            var payload = {
                path: path,
                page: page,
                update: 'updateMenusList'
            }
            this.$store.dispatch("nextPage", payload);
        },
    },
    computed: {
        ...mapState(['menu', 'loading'])
        // menu() {
        //     return this.$store.getters.menu;
        // },
        // isLoading() {
        //     return this.$store.getters.loading;
        // }
    },
    mounted() {
        // this.$store.dispatch('getMenus');
        eventBus.$emit("LoadingEvent");
        this.getMenus();
    },
    created() {
        eventBus.$on("menuEvent", data => {
            this.getMenus();
        });

        eventBus.$on("responseChooseEvent", data => {
            console.log(data);
            if (data == "Excel") {
                eventBus.$emit("openEditMenu");
            } else {
                eventBus.$emit("openCreateMenu");
            }
        });
    },

    //   beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //       if (vm.user.can["view menu"]) {
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
