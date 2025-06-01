<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
                <v-card>
                    <h3 class="text-center">Sms Templates</h3>
                    <v-divider></v-divider>
                    <v-card-title>

                        <v-tooltip right>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getSms">
                                    <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                </v-btn>
                            </template>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="openCreate">Add Sms Templates</v-btn>
                        <!-- <v-btn text color="primary" @click="openEdit(selected)">Edit</v-btn> -->
                    </v-card-title>

                    <v-data-table :headers="headers" :items="sms_template" :search="search">
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
import Create from './create';
import Edit from './edit';
// import Show from './Show';
import {
    mapState
} from 'vuex';
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
                    text: 'Template Name',
                    value: 'name'
                },{
                    text: 'Module',
                    value: 'model'
                },{
                    text: 'Sms',
                    value: 'sms'
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
            eventBus.$emit('openCreateSms')
        },

        openEdit(data) {
            console.log(data);

            eventBus.$emit('openEditSms', data)
        },

        openShow(data) {
            eventBus.$emit('openShowSms', data)
        },
        getSms() {
            var payload = {
                model: 'sms_template',
                update: 'updateSmsTemplate',
            }
            this.$store.dispatch('getItems', payload);
        },
        deleteItem(item) {
            console.log(item)
            // const index = this.$store.getters.Sms.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/sms/${item.id}`)
                    .then(response => {
                        this.getSms()
                        // this.$store.getters.Sms.splice(index, 1);
                        //// console.log(response);
                    })
                    .catch(error => (this.errors = error.response.data.errors));
            }
        },
    },
    computed: {
        ...mapState(['sms_template', 'loading']),
    },
    mounted() {
        eventBus.$emit("LoadingEvent");
        // this.$store.dispatch('getSms');
        this.getSms();
    },
    created() {
        eventBus.$on('SmsEvent', data => {
            this.getSms();
        })
    },
}
</script>
