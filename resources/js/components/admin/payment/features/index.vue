<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
                <v-card>
                    <h3 class="text-center">Features</h3>
                    <v-divider></v-divider>
                    <v-card-title>

                        <v-tooltip right>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getFeatures">
                                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                </v-btn>
                            </template>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="openCreate">Add Feature</v-btn>
                        <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                    </v-card-title>

                    <v-data-table :headers="headers" :items="features" :search="search">
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
    <Create />
    <Edit />
    <!-- <Show></Show> -->
</div>
</template>

<script>
import Create from './Create';
import Edit from './Edit';
import {
    mapState
} from 'vuex';
// import Show from './Show';
export default {
    components: {
        Create,
        Edit,
        // Show
    },
    data() {
        return {
            search: '',
            headers: [{
                    text: 'Feature Name',
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
            eventBus.$emit('openCreateFeature')
        },

        openEdit(data) {
            console.log(data);

            eventBus.$emit('openEditFeature', data)
        },

        openShow(data) {
            eventBus.$emit('openShowFeature', data)
        },
        getFeatures() {
            var payload = {
                model: 'features',
                update: 'updateFeature',
            }
            this.$store.dispatch('getItems', payload);
        },
        deleteItem(item) {
            console.log(item)
            // const index = this.$store.getters.features.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/features/${item.id}`)
                    .then(response => {
                        this.getFeatures()
                        // this.$store.getters.features.splice(index, 1);
                        //// console.log(response);
                    })
                    .catch(error => (this.errors = error.response.data.errors));
            }
        },
    },
    computed: {
        ...mapState(['features', 'loading']),
    },
    mounted() {
        eventBus.$emit("LoadingEvent");
        // this.$store.dispatch('getFeatures');
        this.getFeatures();
    },
    created() {
        eventBus.$on('featureEvent', data => {
            this.getFeatures();
        })
    },
}
</script>
