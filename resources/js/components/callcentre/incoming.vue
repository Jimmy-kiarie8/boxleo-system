<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="800px" transition="dialog-top-transition">
            <v-card class="overflow-hidden" v-if="dialog">
                <v-app-bar absolute color="indigo darken-2" dark shrink-on-scroll prominent
                    scroll-target="#scrolling-techniques">
                    <span class="headline text-center" style="margin: auto;">Incoming call from
                        <v-chip class="ma-2" color="primary">
                            {{ caller }}
                        </v-chip></span>
                    <v-spacer></v-spacer>
                    <v-btn color="whte" dark icon @click="editDetails">
                        <v-icon dark small>mdi-pen</v-icon>
                    </v-btn>
                    <v-btn color="whte" dark icon @click="close">
                        <v-icon dark small>mdi-close</v-icon>
                    </v-btn>
                </v-app-bar>
                <v-sheet id="scrolling-techniques" class="overflow-y-auto" max-height="600">
                    <v-container style="height: 300px;margin-top: 110px;">
                        <v-list dense>
                            <v-list-item-group color="primary">
                                <v-list-item>
                                    <v-list-item-icon>
                                        <!-- <v-icon color="primary">mdi-account-circle</v-icon> -->
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>{{ callHistory.name }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <!-- <v-divider></v-divider> -->
                            </v-list-item-group>
                            <v-list-item-group color="primary">
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon color="success">mdi-email</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>{{ callHistory.email }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <!-- <v-divider></v-divider> -->
                            </v-list-item-group>
                            <v-list-item-group color="primary">
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon color="red">mdi-phone</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>{{ callHistory.phone }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <!-- <v-divider></v-divider> -->
                            </v-list-item-group>

                            <v-list-item-group color="primary">
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon color="warning">mdi-map-marker</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>{{ callHistory.address }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>
                        <v-data-table :headers="headers" :items="callHistory.sales" :search="search" :loading="loading">

                        </v-data-table>
                    </v-container>
                </v-sheet>

                <v-card-actions>
                    <v-btn color="red" text @click="hangup">
                        <v-icon color="red">mdi-phone-hangup</v-icon> Hangup
                    </v-btn>

                    <!-- <v-btn color="red" text @click="unhold" v-if="onhold">
                    <v-icon color="red">mdi-phone</v-icon> Resume
                </v-btn> -->

                    <!-- <v-btn color="red" text @click="hold" v-else>
                    <v-icon color="red">mdi-pause</v-icon> Hold
                </v-btn> -->

                    <v-btn color="teal" text @click="transfer">
                        <v-icon color="teal">mdi-phone-forward</v-icon> Transfer
                    </v-btn>

                    <v-spacer></v-spacer>
                    <v-btn color="success" text @click="answer">
                        <v-icon color="success">mdi-phone-check</v-icon> Answer
                    </v-btn>
                </v-card-actions>
            </v-card>

            <div class="text-center">
                <v-dialog v-model="transfer_dialog" width="500">
                    <v-card>
                        <v-card-title class="text-h5 primary lighten-2">
                            Transfer to
                        </v-card-title>

                        <v-card-text>
                            <v-list dense>
                                <v-list-item-group v-model="selectedItem" color="primary">
                                    <v-list-item v-for="(item, i) in agents" :key="i"
                                        @click="trnasfer_call(item.agent_sip)">
                                        <v-list-item-icon>
                                            <v-icon>mdi-account-circle</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title v-text="item.name"></v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-card-text>

                        <v-divider></v-divider>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" text @click="transfer_dialog = false">
                                Cancel
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </div>

        </v-dialog>
    </v-layout>
</template>
<script>
import {
    mapState
} from 'vuex';

export default {
    data: () => ({
        selectedItem: 0,
        dialog: false,
        transfer_dialog: false,
        form: {},
        search: '',
        caller: null,
        onhold: false,

        headers: [{
            text: "Order no",
            value: "order_no",
        },
        {
            text: "Product Name",
            value: "product_name",
        },
        {
            text: "Total Price",
            value: "total_price",
        },
        {
            text: "Delivery status",
            value: "delivery_status",
        },
        {
            text: "Created On",
            value: "created_at",
        },
        ],
        headers_2: [{
            text: "Direction",
            value: "direction"
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
            value: "status"
        },
        {
            text: "Currency Code",
            value: "currencyCode"
        },
        {
            text: "Created On",
            value: "created_at",
        }
        ]
    }),
    created() {
        eventBus.$on("incomingEvent", data => {
            // console.log("🚀 ~ file: incoming.vue:219 ~ created ~ data:", data)
            this.dialog = true;
            this.caller = data.from
            this.searchLeads(data.from)
        });
        eventBus.$on("callHangedupEvent", () => {
            this.dialog = false;
        });

        eventBus.$on("holdEvent", () => {
            this.onhold = true;
        });
        eventBus.$on("unholdEvent", () => {
            this.onhold = false;
        });
    },

    methods: {

        searchLeads(search) {
            if (search.length > 2) {
                var payload = {
                    update: "searchLead",
                    model: "searchLeads",
                    load: "no",
                    search: search,
                };
                this.$store.dispatch("searchItems", payload);
            }
        },
        toggleAudio(data) {
            var audio = document.getElementById("audio-player");
            if (data) {
                audio.play();
            } else {
                audio.pause();
            }
        },
        mutePage() {
            navigator.mediaDevices.getUserMedia({
                audio: {
                    autoGainControl: false,
                    channelCount: 2,
                    echoCancellation: false,
                    latency: 0,
                    noiseSuppression: false,
                    sampleRate: 48000,
                    sampleSize: 16,
                    volume: 0
                }
            });

            console.log(navigator.mediaDevices)
        },
        hangup() {
            eventBus.$emit('hangupEvent')

            // this.close()
        },
        answer() {
            // this.mutePage()
            eventBus.$emit('answerEvent')
        },
        hold() {
            eventBus.$emit('holdEvent')
        },
        unhold() {
            eventBus.$emit('unholdEvent')
        },
        trnasfer_call(agent) {
            console.log(agent);
            var form = {
                sessionId: '',
                phoneNumber: this.caller,
                callLeg: '',
                transfer_to: agent,
                holdMusicUrl: '',
            }
            var payload = {
                'data': form,
                'model': 'transfer'
            }
            this.$store.dispatch("postItems", payload);

        },
        transfer() {
            this.transfer_dialog = true
        },
        close() {
            this.dialog = false;
        },
        getLead(id) {
            var payload = {
                update: "updateLead",
                model: "lead_phone",
                load: 'no',
                id: id
            };
            this.$store.dispatch("showItem", payload);
        },

        getAgents() {
            var payload = {
                'update': 'updateAgents',
                'model': 'agents'
            }
            this.$store.dispatch("getItems", payload);
        },
        editDetails(data) {
            eventBus.$emit('editEvent', this.lead);

        },
    },
    mounted() {
        // if (this.agents.length < 1) {
        //     this.getAgents()
        // }
        // this.getLead('+254743895505')
    },
    computed: {
        ...mapState(['errors', 'loading', 'callHistory', 'agents'])
    },

};
</script>
