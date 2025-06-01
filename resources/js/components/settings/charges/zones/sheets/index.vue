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
                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getZoneSheet">
                                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                </v-btn>
                            </template>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="googleSheet">Add Sheet</v-btn>
                        <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                    </v-card-title>

                    <v-data-table :headers="headers" :items="zoneSheets.data" :search="search" class="elevation-1" :loading="loading" >
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
    <mySheet :selected="selected"/>
</div>
</template>

<script>
import mySheet from './sheets.vue'
import {
    mapState
} from 'vuex';
export default {
    components: {
        mySheet
    },
    data() {
        return {
            search: '',
            singleSelect: false,
            selected: [],
            headers: [{
                    text: 'Zone Name',
                    value: 'sheet_name'
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
        googleSheet() {
            this.edit = false

            eventBus.$emit("sheetEvent", this.selected);
        },
        assignOrders() {
            eventBus.$emit("assignEvent", this.selected);
        },
        openEdit(data) {
            this.edit = true
            eventBus.$emit('sheetEvent', data)
        },

        openShow(data) {
            eventBus.$emit('openShowZone', data)
        },
        getZoneSheet() {
            var payload = {
                model: 'zone_sheets',
                update: 'updatezoneSheets',
            }
            this.$store.dispatch('getItems', payload);
        },
        default_zone(id) {
            return
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
        deleteItem(item) {
            return
            console.log(item)
            // const index = this.$store.getters.zones.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/zones/${item.id}`)
                    .then(response => {
                        this.getZoneSheet()
                        // this.$store.getters.zones.splice(index, 1);
                        //// console.log(response);
                    })
                    .catch(error => (this.errors = error.response.data.errors));
            }
        },
    },
    computed: {
        ...mapState(['zoneSheets', 'loading']),
    },
    mounted() {
        eventBus.$emit("LoadingEvent");
        // this.$store.dispatch('getZoneSheet');
        this.getZoneSheet();
    },
    created() {
        eventBus.$on('zoneEvent', data => {
            this.getZoneSheet();
        })
    },
}
</script>
