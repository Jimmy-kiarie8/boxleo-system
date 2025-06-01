<template>
<v-card elevation="9">
    <v-card-title>
        <v-btn color="primary" icon @click="getLevels">
            <v-icon dark>mdi-refresh</v-icon>
        </v-btn>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
        <v-spacer></v-spacer>
        <v-btn color="primary" outlined @click="openCreate">
            <v-icon dark>mdi-plus-circle</v-icon>
            Add more levels
        </v-btn>
    </v-card-title>
    <v-data-table :headers="headers" :items="levels" :search="search">
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
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex';
import myCreate from './create'
export default {
    props: ['form_level'],
    components: {
        myCreate,
    },
    data() {
        return {
            search: '',
            form: {},
            headers: [{
                    text: 'Level',
                    align: 'start',
                    value: 'name',
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
        getLevels() {
            var payload = {
                model: 'levels',
                update: 'updateLevels',
            }
            this.$store.dispatch('getItems', payload);
        },
        openCreate() {
            eventBus.$emit('CreateLevelEvent', this.form_level)
        },
        addLevel() {
            eventBus.$emit('CreateLevelEvent')
        },
        closeLevels() {
            eventBus.$emit('displayChange', 'row')
        },
        editItem() {},
        deleteItem() {}
    },
    mounted() {
        this.getLevels();
    },
    computed: {
        ...mapState(['levels'])
    },
}
</script>

<style>

</style>
