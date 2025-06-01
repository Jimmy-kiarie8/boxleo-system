<template>
<div>
    <div>
        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <div class="container">
                    <v-card>
                        <h3 class="text-center">Warehouses</h3>
                        <v-divider></v-divider>
                        <v-card-title>
                            <v-tooltip right>
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getWarehouses">
                                        <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                    </v-btn>
                                </template>
                                <span>Refresh</span>
                            </v-tooltip>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="openCreate">Add warehouse</v-btn>
                            <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                        </v-card-title>
                        <!-- <vue-good-table class="table-hover" :columns="columns" :rows="warehouses" :search-options="{ enabled: true }" :pagination-options="{enabled: true,mode: 'pages'}" v-loading="isLoading" @on-row-dblclick="openProducts">
                            <template slot="table-row" slot-scope="props">
                                <span v-if="props.column.field == 'actions'">
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
                                            <v-btn v-on="on" icon class="mx-0" @click="openProducts(props)" slot="activator">
                                                <v-icon small color="blue darken-2">shopping_basket</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Products in {{ props.row.name }}</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" @click="openStorage(props.row)" slot="activator">
                                                <v-icon small color="blue darken-2">storage</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Storage</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" @click="deleteItem(props.row)" slot="activator">
                                                <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Delete</span>
                                    </v-tooltip>
                                </span>
                                <span v-else-if="props.column.field == 'created_at'">
                                    <span>
                                        <el-tag type="success">{{props.formattedRow.created_at}}</el-tag>
                                    </span>
                                </span>
                                <span v-else>
                                    {{props.formattedRow[props.column.field]}}
                                </span>
                            </template>
                        </vue-good-table> -->

                        <v-data-table :headers="headers" :items="warehouses" :search="search">
                            <template v-slot:item.actions="{ item }">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" class="mx-0" @click="openEdit(item)" slot="activator">
                                            <v-icon small color="blue darken-2">mdi-pen</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Edit</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="openProducts(props)" slot="activator">
                                            <v-icon small color="blue darken-2">shopping_basket</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Products in {{ item.name }}</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="openStorage(item)" slot="activator">
                                            <v-icon small color="blue darken-2">storage</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Storage</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="deleteItem(item)" slot="activator">
                                            <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Delete</span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-card>
                </div>
            </v-layout>
        </v-container>
    </div>
    <Create />
    <Edit />
    <!-- <MyCalc></MyCalc> -->
    <!-- <Show></Show> -->
</div>
</template>

<script>
import {
    mapState
} from 'vuex'
import Create from "./Create";
import Edit from "./Edit";
// import MyCalc from './storage/Calculate'
// import Show from './Show';
export default {
    props: ['user'],
    components: {
        Create,
        Edit,
        // MyCalc
        // Show
    },
    data() {
        return {
            loading: false,
            loader: false,
            headers: [{
                    text: 'Id#',
                    value: 'id',
                    type: 'number',
                },
                {
                    text: 'Warehouse name',
                    value: 'name',
                },
                {
                    text: 'Warehouse Phone no.',
                    value: 'phone',
                },
                {
                    text: 'Created By',
                    value: 'user_name',
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
        }
    },
    methods: {
        openCreate() {
            eventBus.$emit("openCreateWarehouse");
        },

        openEdit(data) {
            eventBus.$emit("openEditWarehouse", data);
        },
        openStorage(data) {
            eventBus.$emit("openStorageWarehouse", data);
        },

        openShow(data) {
            eventBus.$emit("openShowWarehouse", data);
        },
        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
        },
        deleteItem(item) {
            console.log(item);
            //   const index = this.$store.getters.warehouses.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/warehouses/${item.id}`)
                    .then(response => {
                        // this.$store.getters.warehouses.splice(index, 1);
                        this.getWarehouses()
                        //// console.log(response);
                    })
                    .catch(error => (this.errors = error.response.data.errors));
            }
        },
        openProducts(data) {
            console.log(data.row.id)
            // router.push({ name: 'editProducts', params: { id: data.id } })
            this.$router.push({
                name: "product_Warehouse",
                params: {
                    id: data.row.id
                }
            });
        },
    },
    computed: {
        ...mapState(['warehouses', 'isLoading'])
    },
    mounted() {
        eventBus.$emit("LoadingEvent");
        // this.$store.dispatch('getWarehouses');
        this.getWarehouses();
    },
    created() {
        eventBus.$on("warehouseEvent", data => {
            this.getWarehouses();
        });
    }
};
</script>

<style scoped>
tr:hover {
    background: red !important;
}
</style>
