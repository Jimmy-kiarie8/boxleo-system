<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto">Queued calls</span>
                <v-spacer></v-spacer>
                <v-btn color="primary" dark icon @click="close">
                    <v-icon dark small>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            <v-divider></v-divider>

            <v-tooltip right>
                <template v-slot:activator="{ on }">
                    <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getQueues">
                        <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                    </v-btn>
                </template>
                <span>Refresh</span>
            </v-tooltip>
            <v-card-text>
                <v-data-table :headers="headers" :items="queue" :loading="loading">
                    <!--
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" icon class="mx-0" slot="activator" :href="item.recordingUrl" target="_blank" style="text-decolatio">
                                    <v-icon small color="success">mdi-phone</v-icon>
                                </v-btn>
                            </template>
                            <span>Call</span>
                        </v-tooltip>
                    </template> -->
                </v-data-table>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="call" :disabled="loading">
                    <v-icon color="success">mdi-phone</v-icon> Next Call
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import {
    mapState
} from "vuex";
export default {
    data: () => ({
        headers: [{
            text: 'Phone',
            value: 'phoneNumber'
        }],
        dialog: false,
        form: {
            phone: ''
        },
        call_inprogress: false,
    }),
    created() {
        eventBus.$on("queueEvent", () => {
            this.dialog = true;
        });
    },

    methods: {
        call() {
            // this.call_inprogress = true
            // eventBus.$emit('makeCallEvent', this.form.phone);
            var payload = {
                data: {},
                model: '/queue_call',
            }
            this.$store.dispatch('postItems', payload)
        },
        hangup() {
            eventBus.$emit('hangupEvent', this.form.phone);
        },
        close() {
            this.dialog = false;
        },

        getQueues() {
                var payload = {
                    update: "updateQueues",
                    model: "queue",
                };
                this.$store.dispatch("getItems", payload);
        },
    },
    computed: {
        ...mapState(["errors", "loading", "queue"]),
    },
};
</script>
