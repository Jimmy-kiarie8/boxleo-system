<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
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
                        <v-btn text color="primary" @click="openCreate">Add Warehouse</v-btn>
                        <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                    </v-card-title>

                    <v-data-table :headers="headers" :items="warehouses" :search="search">
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
                                    <v-btn v-on="on" icon class="mx-0" @click="openShow(item)" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-map-plus</v-icon>
                                    </v-btn>
                                </template>
                                <span>Virtual mapping</span>
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
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
    <Create />
    <Edit />
    <myWarehouse />

    <!-- <Show></Show> -->
</div>
</template>

<script>
import Create from './Create';
import Edit from './Edit';
import myWarehouse from './warehouse';
import {
    mapState
} from 'vuex';
export default {
    components: {
        Create,
        Edit, myWarehouse
        // Show
    },
    data() {
        return {
            search: '',
            headers: [{
                    text: 'Warehouse Code',
                    value: 'code'
                },{
                    text: 'Warehouse Name',
                    value: 'name'
                },
                {
                    text: 'Created On',
                    value: 'created_at',
                },
                {
                    text: 'Actions',
                    value: 'actions',
                    sortable: false
                }
            ],
        }
    },
    methods: {
        openCreate() {
            eventBus.$emit('openCreateWarehouse')
            this.getWarehouses();
        },

        openEdit(data) {
            this.getWarehouses();
            console.log(data);

            eventBus.$emit('openEditWarehouse', data)
        },

        openShow(data) {
            eventBus.$emit('openShowWarehouse', data)
        },
        getWarehouses() {
            var payload = {
                model: 'warehouses',
                update: 'updateWarehouseList',
            }
            this.$store.dispatch('getItems', payload);
        },
        deleteItem(item) {
            console.log(item)
            // const index = this.$store.getters.warehouses.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/warehouses/${item.id}`)
                    .then(response => {
                        this.getWarehouses()
                        // this.$store.getters.warehouses.splice(index, 1);
                        //// console.log(response);
                    })
                    .catch(error => (this.errors = error.response.data.errors));
            }
        },
    },
    computed: {
        ...mapState(['warehouses', 'loading']),
    },
    mounted() {
        eventBus.$emit("LoadingEvent");
        // this.$store.dispatch('getWarehouses');
        this.getWarehouses();
    },
    created() {
        eventBus.$on('warehouseEvent', data => {
            this.getWarehouses();
        })
    },
}
</script>
