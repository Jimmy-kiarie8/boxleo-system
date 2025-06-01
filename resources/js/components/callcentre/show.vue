<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="1000px">
        <v-card v-if="dialog">
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Call history</span>
                <v-spacer></v-spacer>

                <v-btn color="whte" dark icon @click="close">
                    <v-icon dark small>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            <v-divider></v-divider>

            <v-card-text>

                <v-list dense>
                    <v-subheader>User</v-subheader>
                    <v-list-item-group color="primary">
                        <v-list-item>
                            <v-list-item-icon>
                                <v-icon color="primary">mdi-account-circle</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>{{ order.client.name }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                    <v-list-item-group color="primary">
                        <v-list-item>
                            <v-list-item-icon>
                                <v-icon color="success">mdi-email</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>{{ order.client.email }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                    <v-list-item-group color="primary">
                        <v-list-item>
                            <v-list-item-icon>
                                <v-icon color="red">mdi-phone</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>{{ order.client.phone }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>

                    <v-list-item-group color="primary">
                        <v-list-item>
                            <v-list-item-icon>
                                <v-icon color="warning">mdi-map-marker</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>{{ order.client.address }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>

                <v-data-table :headers="headers" :items="leads.data" :search="search" :loading="loading">
                    <template v-slot:item.callerNumber="{ item }">
                        <div v-if="item.direction == 'Inbound'">
                            {{ item.dialDestinationNumber }}
                        </div>
                        <div v-else>
                            {{ item.callerNumber }}
                        </div>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <!-- <v-tooltip bottom v-if="item.recordingUrl">
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" icon class="mx-0" slot="activator" :href="item.recordingUrl" target="_blank" style="text-decolatio">
                                    <v-icon small color="red">mdi-radiobox-marked</v-icon>
                                </v-btn>

                            </template>
                            <span>Recording</span>
                        </v-tooltip> -->
                        <div class="text-center" v-if="item.recordingUrl">

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon class="mx-0" slot="activator" @click="playAudio(item.id)">
                                        <v-icon color="red">mdi-play-circle-outline</v-icon>
                                    </v-btn>
                                </template>
                                <span>Play</span>
                            </v-tooltip>
                        </div>

                        <v-chip color="primary" v-else small>No record</v-chip>

                    </template>
                </v-data-table>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
            <v-bottom-sheet inset v-model="playing">
                <v-card tile>
                    <audio controls :src="proxyUrl" style="width: 100% !important;" v-if="playingAudio">
                    Your browser does not support the <code>audio</code> element.
                    </audio>
                </v-card>
            </v-bottom-sheet>
        </v-card>
    </v-dialog>


</v-layout>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    name: 'call-details',
    data: () => ({
        dialog: false,
        form: {},
        order: null,
        search: '',

        headers: [{
                text: "Direction",
                value: "direction"
            },
            {
                text: "Agent",
                value: "callerNumber"
            },
            {
                text: "Duration In Seconds",
                value: "durationInSeconds"
            },
            {
                text: "Amount",
                value: "amount"
            },
            {
                text: "Destination Number",
                value: "destinationNumber"
            },
            {
                text: "Status",
                value: "call_status"
            },
            {
                text: "Currency Code",
                value: "currencyCode"
            },
            {
                text: "Created On",
                value: "created_at",
            },
            {
                text: "Actions",
                value: "actions",
                sortable: false
            }
        ],

            playing: false,
            playing_audio: null,
            proxyUrl: null,
            playingAudio: null
    }),
    created() {
        eventBus.$on("showEvent", data => {
            this.order = data;
            this.dialog = true;
            this.showLeads(data.id)
        });
    },

    methods: {
        playAudio(audioUrl) {
        this.proxyUrl = `/audio/${audioUrl}`;
        this.playing = true;
        this.playingAudio = this.proxyUrl;
        },

        close() {
            this.dialog = false;
        },
        showLeads(id) {
            var payload = {
                update: 'updateLead',
                id: id,
                load: 'no',
                model: 'calls'
            }

            this.$store.dispatch("showItem", payload);
        },
    },
    computed: {
        ...mapState(['errors', 'loading', 'leads'])
    },

};
</script>

<style>
a {
    text-decoration: none !important;
}
</style>
