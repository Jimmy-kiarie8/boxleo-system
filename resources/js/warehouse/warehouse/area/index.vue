<template>
<v-card elevation="1" width="100%">
    <v-card-text>
        <v-spacer></v-spacer>
        <v-btn color="primary" outlined @click="openCreate">
            <v-icon dark>mdi-plus-circle</v-icon>
            Add more areas
        </v-btn>
        <v-divider></v-divider>

        <v-tooltip bottom color="primary">
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" @click="getAreas">
                    <v-icon dark small>mdi-refresh</v-icon>
                </v-btn>
            </template>
            <span>Refresh</span>
        </v-tooltip>

        <p class="">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Area</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, key) in areas" :key="item.id">
                        <th scope="area">{{ key + 1 }}</th>
                        <td>{{ item.name }}</td>
                        <td>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click="openRows(item)">
                                        <v-icon color="primary lighten-1">
                                            mdi-eye
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>View rows</span>
                            </v-tooltip>
                        </td>
                    </tr>
                </tbody>
            </table>
        </p>
        <div class="text--primary">

        </div>
    </v-card-text>
    <v-card-actions>
        <v-btn text>

        </v-btn>
    </v-card-actions>
    <myCreate />
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex';
import myCreate from './create'
export default {
    props: ['warehouse'],
    components: {
        myCreate,
    },
    methods: {
        getAreas() {
            var payload = {
                model: 'areas',
                update: 'updateAreas',
                id: this.warehouse.id
            }
            this.$store.dispatch('showItem', payload);
        },
        openCreate() {
            eventBus.$emit('CreateAreaEvent', this.warehouse)
        },
        openRows(item) {
            eventBus.$emit('displayChange', 'row')
            eventBus.$emit('openShowRow', item.id)
        }
    },
    mounted() {
        this.getAreas();
    },
    computed: {
        ...mapState(['areas'])
    },
}
</script>

<style>

</style>
