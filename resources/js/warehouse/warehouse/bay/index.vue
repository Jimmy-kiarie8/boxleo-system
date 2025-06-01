<template>
<v-card elevation="1" width="100%">
    <v-card-text>
        <v-card-title primary-title>
            <v-tooltip bottom color="primary">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on" @click="closeBays">
                        <v-icon dark>mdi-arrow-left-circle</v-icon>
                    </v-btn>
                </template>
                <span>Back to Row</span>
            </v-tooltip>
            <v-spacer></v-spacer>
            <v-btn color="primary" outlined @click="openCreate">
                <v-icon dark>mdi-plus-circle</v-icon>
                Add more bays
            </v-btn>
        </v-card-title>
        <v-divider></v-divider>

        <v-tooltip bottom color="primary">
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" @click="getBays">
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
                        <th scope="col">Bay</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, key) in bays" :key="item.id">
                        <th scope="bay">{{ key + 1 }}</th>
                        <td>{{ item.name }}</td>

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
    props: ['form_bay'],
    components: {
        myCreate,
    },
    data() {
        return {
            form: {}
        }
    },
    methods: {
        getBays() {
            var payload = {
                model: 'bays',
                update: 'updateBays',
                id: this.form_bay.row_id
            }
            this.$store.dispatch('showItem', payload);
        },
        openCreate() {
            eventBus.$emit('CreateBayEvent', this.form_bay)
        },
        closeBays() {
            eventBus.$emit('displayChange', 'row')
        },
        openRows(item) {
            eventBus.$emit('displayChange', 'level')
            eventBus.$emit('openShowLevel', item.id)
        }
    },
    mounted() {
        this.getBays();
    },
    computed: {
        ...mapState(['bays'])
    },
}
</script>

<style>

</style>
