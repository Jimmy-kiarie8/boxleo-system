<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
                <v-card>
                    <h3 class="text-center">Plans</h3>
                    <v-divider></v-divider>
                    <v-card-title>

                        <v-tooltip right>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getPlans">
                                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                </v-btn>
                            </template>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="openCreate">Add Plan</v-btn>
                        <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                    </v-card-title>

                    <v-data-table :headers="headers" :items="plans" :search="search">
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
                    text: 'Plan Name',
                    value: 'plan'
                },

                {
                    text: 'Amount',
                    value: 'amount',
                },
                {
                    text: 'Orders',
                    value: 'orders',
                },
                {
                    text: 'Users',
                    value: 'users',
                },
                {
                    text: 'Portals',
                    value: 'portals',
                },
                {
                    text: 'Tracking',
                    value: 'tracking',
                },
                {
                    text: 'Warehouses',
                    value: 'warehouses',
                },
                {
                    text: 'Ous',
                    value: 'ous',
                },
                {
                    text: 'Shopify integrations',
                    value: 'shopify_integrations',
                },
                {
                    text: 'Wordpress integrations',
                    value: 'wordpress_integrations',
                },
                {
                    text: 'Api integrations',
                    value: 'api_integrations',
                },
                {
                    text: 'Automations',
                    value: 'automations',
                },
                {
                    text: 'Created',
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
            eventBus.$emit('openCreatePlan')
        },

        openEdit(data) {
            console.log(data);

            eventBus.$emit('openEditPlan', data)
        },

        openShow(data) {
            eventBus.$emit('openShowPlan', data)
        },
        getPlans() {
            var payload = {
                model: 'plans',
                update: 'updatePlan',
            }
            this.$store.dispatch('getItems', payload);
        },
        deleteItem(item) {
            console.log(item)
            // const index = this.$store.getters.plans.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/plans/${item.id}`)
                    .then(response => {
                        this.getPlans()
                        // this.$store.getters.plans.splice(index, 1);
                        //// console.log(response);
                    })
                    .catch(error => (this.errors = error.response.data.errors));
            }
        },
    },
    computed: {
        ...mapState(['plans', 'loading']),
    },
    mounted() {
        eventBus.$emit("LoadingEvent");
        // this.$store.dispatch('getPlans');
        this.getPlans();
    },
    created() {
        eventBus.$on('planEvent', data => {
            this.getPlans();
        })
    },
}
</script>
