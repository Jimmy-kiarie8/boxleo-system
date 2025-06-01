<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
                <v-card>
                    <h3 class="text-center">Charges</h3>
                    <v-divider></v-divider>
                    <v-card-title>

                        <!-- <v-tooltip right>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getZones">
                                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                </v-btn>
                            </template>
                            <span>Refresh</span>
                        </v-tooltip> -->
                        <v-spacer></v-spacer>
                        <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                    </v-card-title>

                    <v-data-table :headers="headers" :items="saleorder.services" :search="search"></v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</div>
</template>

<script>
import {
    mapState
} from 'vuex';
// import Show from './Show';
export default {
    components: {
        // Show
    },
    data() {
        return {
            search: '',
            charges: [],
            headers: [{
                    text: 'Order No.',
                    value: 'name'
                },
                {
                    text: 'Amount',
                    value: 'pivot.amount',
                },
                {
                    text: 'Created On',
                    value: 'created_at',
                }
            ],
        }
    },
    methods: {
    },
    computed: {
        ...mapState(['saleorder', 'loading']),
    },
    mounted() {
        eventBus.$emit("LoadingEvent");
        // this.$store.dispatch('getZones');
    },
}
</script>
