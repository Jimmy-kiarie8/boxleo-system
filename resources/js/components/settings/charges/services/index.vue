<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
                <v-card>
                    <h3 class="text-center">Services</h3>
                    <v-divider></v-divider>
                    <v-card-title>

                        <v-tooltip right>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getServices">
                                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                </v-btn>
                            </template>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="openCreate">Add Service</v-btn>
                        <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                    </v-card-title>

                    <v-data-table :headers="headers" :items="services" :search="search">
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
                                    <v-btn icon v-on="on" class="mx-0" @click="deleteItem(item)" slot="activator">
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
    <Create :form="form" />
    <Edit :form="form" />
    <myCondition  :form="form" />
</div>
</template>

<script>
import Create from './Create';
import Edit from './Edit';
import myCondition from "./condition";
import {
    mapState
} from 'vuex';
export default {
    components: {
        Create,
        Edit,
        myCondition
    },
    data() {
        return {
        form: {
            conditions: [{
                condition: '',
                row: {
                    Type: ''
                },
                operator: 'When',
                value: ''
            }],
            zone_to: []
        },
            search: '',
            headers: [{
                    text: 'Service Name',
                    value: 'name'
                },
                {
                    text: 'From',
                    value: 'service_from'
                },
                {
                    text: 'To',
                    value: 'service_to'
                },
                {
                    text: 'Charge type',
                    value: 'charges_type'
                },
                {
                    text: 'Service Charge',
                    value: 'charges'
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
            isPaginated: true,
            currentPage: 1,
            perPage: 5,
            items: ['Activate Service', 'Deactivate Service', 'Delete Service'],
        }
    },
    methods: {
        openCreate() {
            eventBus.$emit('openCreateService')
        },

        openEdit(data) {
            console.log(data);

            eventBus.$emit('openEditService', data)
        },

        openShow(data) {
            eventBus.$emit('openShowService', data)
        },
        getServices() {
            var payload = {
                model: 'services',
                update: 'updateService',
            }
            this.$store.dispatch('getItems', payload);
        },
        getCities() {
            var payload = {
                model: 'cities',
                update: 'updateCity',
            }
            this.$store.dispatch('getItems', payload);
        },
        getZones() {
            var payload = {
                model: 'zones',
                update: 'updateZone',
            }
            this.$store.dispatch('getItems', payload);
        },
        deleteItem(item) {
            console.log(item)
            const index = this.$store.getters.services.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/services/${item.id}`)
                    .then(response => {
                        this.$store.getters.services.splice(index, 1);
                        //// console.log(response);
                    })
                    .catch(error => (this.errors = error.response.data.errors));
            }
        },
    },
    computed: {
        ...mapState(['services', 'loading']),
    },
    mounted() {
        eventBus.$emit("LoadingEvent");
        this.getServices();

        // setTimeout(() => {
            this.getCities()
            this.getZones()
        // }, 2000);
    },
    created() {
        eventBus.$on('serviceEvent', data => {
            this.getServices();
        })
    },
}
</script>
