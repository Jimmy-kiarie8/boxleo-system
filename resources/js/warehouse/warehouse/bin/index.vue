<template>
<v-card elevation="9">
    <v-card-title>
        <v-tooltip right>
            <template v-slot:activator="{ on }">
                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getBins">
                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                </v-btn>
            </template>
            <span>Refresh</span>
        </v-tooltip>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
        <v-spacer></v-spacer>
        <v-btn color="primary" outlined @click="openCreate">
            <v-icon dark>mdi-plus-circle</v-icon>
            Add more bins
        </v-btn>
    </v-card-title>
    <v-data-table :headers="headers" :items="bins" :search="search">
        <template v-slot:item.actions="{ item }">
            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on" @click="editItem(item)">
                        <v-icon color="primary lighten-1" small>
                            mdi-pen
                        </v-icon>
                    </v-btn>
                </template>
                <span>Edit</span>
            </v-tooltip>
            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on" @click="printSticker(item)" :href="'/sticker/'+item.id" target="_blank">
                        <v-icon color="primary lighten-1" small>
                            mdi-printer
                        </v-icon>
                    </v-btn>
                </template>
                <span>Print sticker</span>
            </v-tooltip>
            
            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on" @click="deleteItem(item)">
                        <v-icon color="pink" small>
                            mdi-delete
                        </v-icon>
                    </v-btn>
                </template>
                <span>Delete</span>
            </v-tooltip>
        </template>
    </v-data-table>
    <myCreate :form="form" />
    <myEdit  />
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex';
import myCreate from './create'
import myEdit from './edit'
export default {
    props: ['form_bin'],
    components: {
        myCreate, myEdit
    },
    data() {
        return {
            search: '',
            form: {},
            headers: [{
                    text: 'Bin',
                    align: 'start',
                    value: 'code',
                },
                {
                    text: 'Created on',
                    value: 'created_at'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ],
        }
    },
    methods: {
        getBins() {
            var payload = {
                model: 'bins',
                update: 'updateBins',
            }
            this.$store.dispatch('getItems', payload);
        },
        openCreate() {
            eventBus.$emit('CreateBinEvent', this.form_bin)
        },
        addBin() {
            eventBus.$emit('CreateBinEvent')
        },
        closeBins() {
            eventBus.$emit('displayChange', 'row')
        },
        printSticker() {

        },
        editItem(item) {
            eventBus.$emit('EditBinEvent', item)
        },
        deleteItem() {}
    },
    mounted() {
        this.getBins();
    },
    computed: {
        ...mapState(['bins'])
    },
}
</script>

<style>

</style>
