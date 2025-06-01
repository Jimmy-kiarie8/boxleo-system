<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
                <v-card>
                    <h3 class="text-center">Zones</h3>
                    <v-divider></v-divider>
                    <v-card-title>

                        <v-tooltip right>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getZones">
                                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                </v-btn>
                            </template>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="openCreate">Add Zone</v-btn>
                        <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                    </v-card-title>

                    <v-data-table :headers="headers" :items="zones" :search="search" :single-select="singleSelect" item-key="id" show-select class="elevation-1" v-model="selected" :loading="loading" >
                        <template v-slot:top>
                            <v-tooltip right v-if="selected.length > 0">
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="assignOrders" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-clipboard-account</v-icon>
                                    </v-btn>
                                </template>
                                <span>Assign zone to ...</span>
                            </v-tooltip>
                        </template>
                        <template v-slot:item.actions="{ item }">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="openEdit(item)" slot="activator">
                                        <v-icon small color="blue darken-2">mdi-pen</v-icon>
                                    </v-btn>
                                </template>
                                <span>Edit {{ item.name }}</span>
                            </v-tooltip>
                            <v-tooltip bottom  v-if="item.default_zone">
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="default_zone(item.id)" slot="activator">
                                        <v-icon small color="success">mdi-check-circle</v-icon>
                                    </v-btn>
                                </template>
                                <span>Default zone</span>
                            </v-tooltip>

                            <v-tooltip bottom v-else>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" @click="default_zone(item.id)" slot="activator">
                                        <v-icon small color="success darken-2">mdi-cancel</v-icon>
                                    </v-btn>
                                </template>
                                <span>Mark default</span>
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
    <Create />
    <Edit />
    <Assign />
</div>
</template>

<script>
import Create from './Create';
import Edit from './Edit';
import {
    mapState
} from 'vuex';
import Assign from './assign.vue';
export default {
    components: {
        Create,
        Edit,
        Assign
    },
    data() {
        return {
            search: '',
            singleSelect: false,
            selected: [],
            headers: [{
                    text: 'Zone Name',
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
            eventBus.$emit('openCreateZone')
            this.getCountries();
        },

        assignOrders() {
            eventBus.$emit("assignEvent", this.selected);
        },
        openEdit(data) {
            this.getCountries();
            console.log(data);

            eventBus.$emit('openEditZone', data)
        },

        openShow(data) {
            eventBus.$emit('openShowZone', data)
        },
        getZones() {
            var payload = {
                model: 'zones',
                update: 'updateZone',
            }
            this.$store.dispatch('getItems', payload);
        },
        default_zone(id) {
            var payload = {
                model: 'zones_default',
                data: {},
                id: id,
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("zoneEvent")
                });
        },
        getCountries() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload);
        },
        deleteItem(item) {
            console.log(item)
            // const index = this.$store.getters.zones.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/zones/${item.id}`)
                    .then(response => {
                        this.getZones()
                        // this.$store.getters.zones.splice(index, 1);
                        //// console.log(response);
                    })
                    .catch(error => (this.errors = error.response.data.errors));
            }
        },
    },
    computed: {
        ...mapState(['zones', 'loading']),
    },
    mounted() {
        eventBus.$emit("LoadingEvent");
        // this.$store.dispatch('getZones');
        this.getZones();
    },
    created() {
        eventBus.$on('zoneEvent', data => {
            this.getZones();
        })
    },
}
</script>
