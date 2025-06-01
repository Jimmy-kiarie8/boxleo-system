<template>
<v-card elevation="1" width="100%">
    <v-card-text>
        <v-card-title primary-title>
            <v-tooltip bottom color="primary">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on" @click="closeRows">
                        <v-icon dark>mdi-arrow-left-circle</v-icon>
                    </v-btn>
                </template>
                <span>Back to area</span>
            </v-tooltip>
            <v-spacer></v-spacer>
            <v-btn color="primary" outlined @click="openCreate">
                <v-icon dark>mdi-plus-circle</v-icon>
                Add more rows
            </v-btn>
        </v-card-title>
        <v-divider></v-divider>


        <v-tooltip bottom color="primary">
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" @click="getRows">
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
                        <th scope="col">Row</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, key) in rows" :key="item.id">
                        <th scope="row">{{ key + 1 }}</th>
                        <td>{{ item.name }}</td>
                        <td>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on"  @click="openBay(item)">
                                        <v-icon color="primary lighten-1">
                                            mdi-eye
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>View bays</span>
                            </v-tooltip>
                        </td>
                    </tr>
                </tbody>
            </table>
        </p>
        <div class="text--primary">

        </div>
    </v-card-text>
    <myCreate :form="form" />
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex';
import myCreate from './create'
export default {
    props: ['form_row'],
    components: {
        myCreate,
    },
    data() {
        return {
            form: {}
        }
    },
    methods: {
        getRows() {
            var payload = {
                model: 'rows',
                update: 'updateRows',
                id: this.form_row.area_id
            }
            this.$store.dispatch('showItem', payload);
        },
        openCreate() {
            eventBus.$emit('CreateRowEvent', this.form_row)
        },
        addRow() {
            eventBus.$emit('CreateRowEvent')
        },
        closeRows() {
            eventBus.$emit('displayChange', 'area')
        },
        openBay(item) {
            console.log(item);
            eventBus.$emit('displayChange', 'bay')
            eventBus.$emit('openShowBay', item.id)
        }
    },
    mounted() {
        this.getRows();
    },
    computed: {
        ...mapState(['rows'])
    },
}
</script>

<style>

</style>
