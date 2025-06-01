<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Missed calls</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-data-table :headers="headers" :items="missedcalls.data" :search="search" :loading="loading">
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" icon class="mx-0" slot="activator" @click="call(item.callerNumber)">
                                    <v-icon small color="success">mdi-phone</v-icon>
                                </v-btn>
                            </template>
                            <span>Call back</span>
                        </v-tooltip>
                    </template>
                </v-data-table>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    data: () => ({
        dialog: false,
        form: {},
        search: '',

        headers: [{
                text: "Phone",
                value: "callerNumber"
            },
            {
                text: "Missed On",
                value: "created_at",
            },
            {
                text: "Actions",
                value: "actions",
                sortable: false
            }
        ]
    }),
    created() {
        eventBus.$on("missedCallEvent", () => {
            this.dialog = true;
            this.getMissed()
        });
    },

    methods: {
        call(phone) {
            var data = {
                phoneNumber: phone
            }
            eventBus.$emit('callEvent', data);
        },
        close() {
            this.dialog = false;
        },
        getMissed() {
            var payload = {
                update: 'updateMissed',
                model: 'missed'
            }

            this.$store.dispatch("getItems", payload);
        },
    },
    computed: {
        ...mapState(['errors', 'loading', 'lead', 'missedcalls'])
    },

};
</script>
