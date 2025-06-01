<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
                <v-card>
                    <h3 class="text-center">Operating Units</h3>
                    <v-divider></v-divider>
                    <v-card-title>

                        <v-tooltip right>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getOu">
                                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                </v-btn>
                            </template>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="openCreate" v-if="user.can['OU create']">Add OU</v-btn>
                        <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                    </v-card-title>

                    <v-data-table :headers="headers" :items="ous" :search="search">
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
    props: ['user'],
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
            }, {
                text: 'Ou Code',
                value: 'ou_code'
            }, {
                text: 'Call centre Phone',
                value: 'phone'
            },  {
                text: 'Contact Phone',
                value: 'ou_phone'
            }, {
                text: 'Address',
                value: 'address'
            },{
                text: 'Currency',
                value: 'currency_code'
            },{
                text: 'Waybill terms',
                value: 'waybill_terms'
            },  {
                text: 'Created On',
                value: 'created_at',
            }, {
                text: 'Actions',
                value: 'actions',
                sortable: false
            }],
        }
    },
    methods: {
        openCreate() {
            eventBus.$emit('openCreateOu')
            this.getOu();
        },

        openEdit(data) {
            this.getOu();
            console.log(data);

            eventBus.$emit('openEditOu', data)
        },

        openShow(data) {
            eventBus.$emit('openShowOu', data)
        },
        getOu() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload);
        },
        deleteItem(item) {
            console.log(item)
            // const index = this.$store.getters.ou.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/ou/${item.id}`)
                    .then(response => {
                        this.getOu()
                        // this.$store.getters.ou.splice(index, 1);
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
        // this.$store.dispatch('getOu');
        this.getOu();
    },
    created() {
        eventBus.$on('ouEvent', data => {
            this.getOu();
        })
    },
}
</script>
