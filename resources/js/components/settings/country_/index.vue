<template>
<div>
    <v-content>
        <v-container fluid fill-height>
            <v-layout justify-center align-center>
                <div class="container">
                    <v-card>
                        <h3 class="text-center">Ous</h3>
                        <v-divider></v-divider>
                        <v-card-title>

                            <v-tooltip right>
                                <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getCountries">
                                        <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                    </v-btn>
                                </template>
                                <span>Refresh</span>
                            </v-tooltip>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="openCreate">Add Ou</v-btn>
                            <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                        </v-card-title>

                        <v-data-table :headers="headers" :items="ous" :search="search">
                        </v-data-table>


                    </v-card>
                </div>
            </v-layout>
        </v-container>
    </v-content>
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
                    text: 'Ou Name',
                    value: 'ou'
                },
                {
                    text: 'Ou Code',
                    value: 'ou_code'
                },
                {
                    text: 'Currency Code',
                    value: 'currency_code'
                },
                {
                    text: 'Created On',
                    value: 'created_at',
                    // type: 'date',
                    // dateInputFormat: 'YYYY-MM-DD',
                    // dateOutputFormat: 'MMM Do YYYY',
                },
                {
                    // text: 'Actions',
                    // value: 'actions',
                    sortable: false
                }
            ],
            items: ['Activate Ou', 'Deactivate Ou', 'Delete Ou'],
        }
    },
    methods: {
        openCreate() {
            eventBus.$emit('openCreateOu')
        },

        openEdit(data) {
            console.log(data);

            eventBus.$emit('openEditOu', data)
        },

        openShow(data) {
            eventBus.$emit('openShowOu', data)
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
            const index = this.$store.getters.ous.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/ous/${item.id}`)
                    .then(response => {
                        this.$store.getters.ous.splice(index, 1);
                        //// console.log(response);
                    })
                    .catch(error => (this.errors = error.response.data.errors));
            }
        },
    },
    computed: {
        ...mapState(['ous', 'loading']),
    },
    mounted() {
        eventBus.$emit("LoadingEvent");
        // this.$store.dispatch('getCountries');
        this.getCountries();
    },
    created() {
        eventBus.$on('ouEvent', data => {
            this.getCountries();
        })
    },
}
</script>
