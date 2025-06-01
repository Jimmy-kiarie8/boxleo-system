<template>
<v-card style="padding: 20px; width: 100%;">
    <v-layout justify-center align-center wrap style="margin-top: 0 !important;">
        <v-flex sm6>

            <v-card style="padding: 15px 0;">
                <!-- <v-btn small elevation="3" color="red" @click="hangup">Hangup</v-btn> -->

                <!-- <a href="zoiper://+254731090832">Zoiper</a> -->
                <v-list dense>

                    <v-list-item-group color="teal">
                        <v-list-item @click="call('new_call')">
                            <v-list-item-icon>
                                <v-icon color="success">mdi-phone</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Make a new Call</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                    <v-list-item-group color="teal">
                        <v-list-item @click="call('agent_call')">
                            <v-list-item-icon>
                                <v-icon color="info">mdi-face-agent</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Call an agent</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                    <v-list-item-group color="teal">
                        <v-list-item @click="missed_call()">
                            <v-list-item-icon>
                                <v-icon color="red">mdi-phone-missed</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Missed Calls</v-list-item-title>
                                <v-list-item-subtitle>{{ agent_data.today_missed }}</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>

                    <v-list-item-group color="red">
                        <v-list-item @click="queue()">
                            <v-list-item-icon>
                                <v-icon color="danger">mdi-phone-missed</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Check Queue</v-list-item-title>
                                <v-list-item-subtitle>...</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
            </v-card>
        </v-flex>

        <v-flex sm5 offset-sm1>
            <v-card style="padding: 15px 0;">
                <v-layout row wrap>
                    <v-flex sm5 style="text-align: center">
                      <!--  <v-avatar color="error" style="height: 100px !important; width: 100px !important;margin-left: 0 !important;margin-top: 20px !important;">
                            <span class="white--text text-h5">{{ user.initials }}</span>
                        </v-avatar> -->

                        <img :src="user.profile_photo_url" style="border-radius: 50%;height: 103px;" />
                        <br>
                        <div>
                            <p>{{ user.name }}</p>
                            <p>{{ user.email }}</p>

                            <myOnline v-if="online" />
                            <myOffline v-else />
                        </div>

                    </v-flex>
                    <v-divider vertical style="margin: 0;"></v-divider>
                    <v-flex sm6 style="margin-left: 10px">
                        <v-list dense>
                            <v-subheader>User</v-subheader>

                            <v-list-item-group color="teal">
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon color="error">mdi-connection</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Calls connected</v-list-item-title>
                                        <v-list-item-subtitle>{{ agent_data.today_answered }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                            <v-list-item-group color="teal">
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon color="success">mdi-phone-in-talk</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Calls made</v-list-item-title>
                                        <v-list-item-subtitle>{{ agent_data.today_calls }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                            <v-list-item-group color="teal">
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon color="red">mdi-phone-cancel</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Calls rejected</v-list-item-title>
                                        <v-list-item-subtitle>{{ agent_data.today_unanswered }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>

                            <v-list-item-group color="teal">
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon color="warning">mdi-phone-incoming</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Incoming Calls</v-list-item-title>
                                        <v-list-item-subtitle>{{ agent_data.today_incoming }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>
                    </v-flex>
                </v-layout>
            </v-card>
        </v-flex>
        <v-divider></v-divider>


        <el-tabs v-model="activeName" style="width: 100%;">
            <el-tab-pane label="Orders" name="first">
                <mySales :user="user" :tenant="tenant" />
            </el-tab-pane>
            <el-tab-pane label="Calls" name="second">
                <myCalls :user="user" />
            </el-tab-pane>
        </el-tabs>

    </v-layout>
    <myMissed />
    <myQueue />
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex'
import myMissed from './missed.vue'
// import myCall from './call.vue'
import myQueue from './queue.vue'
import myOnline from './online.vue'
import myOffline from './offline.vue'
import mySales from '../sales/index.vue'
import myCalls from './call-list.vue'

// var client = null
export default {
    props: ['user', 'tenant'],
    name: 'callcenter',
    components: {
        myMissed,
        myQueue,
        myOnline,
        myOffline,
        mySales,
        myCalls
    },
    data() {
        return {
            search: '',
            activeName: 'first',
            // online: false,
            headers: [{
                    text: "Name",
                    value: "name"
                },
                {
                    text: "Email",
                    value: "email"
                },
                {
                    text: "Phone No.",
                    value: "phone"
                },
                {
                    text: "Address",
                    value: "address"
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
            ]
        }
    },
    methods: {
        toggleAudio(data) {
            var audio = document.getElementById("audio-player");
            if (data) {
                audio.play();
            } else {
                audio.pause();
            }
        },
        getOnline() {
            var payload = {
                update: 'updateOnline',
                model: 'user_online',
                load: 'no',
            }
            this.$store.dispatch("getItems", payload);
        },

        editDetails(data) {
            eventBus.$emit('editEvent', data);

        },
        openDetails(data) {
            eventBus.$emit('showEvent', data);

        },
        call(data) {
            console.log(data);
            // return
            if (data == 'new_call') {
                data = {
                    call: 'new',
                    phone: ''
                }
            } else if (data == 'agent_call') {
                data = {
                    call: 'agent',
                    phone: ''
                }
            }
            eventBus.$emit('callEvent', data);
        },
        missed_call() {
            eventBus.$emit('missedCallEvent');
        },

        queue() {

            var payload = {
                'data': {},
                'model': 'dequeue'
            }
            this.$store.dispatch("postItems", payload);


            // var payload = {
            //     model: 'dequeue',
            //     update: 'updateAgentData',
            // }
            // this.$store.dispatch('getItems', payload)

            // if (data == 'new_call') {
            //     data = {
            //         call: 'new',
            //         phone: '+254711082150'
            //     }
            // }
            // eventBus.$emit('callEvent', data);
        },


        getStats() {
            var payload = {
                model: 'agent_data',
                update: 'updateAgentData',
            }
            this.$store.dispatch('getItems', payload)
        },

    },
    created() {
        // eventBus.$on('connectionEvent', (data) => {
        //     // this.getOnline()
        //     this.online = data
        // });
    },

    computed: {
        ...mapState(['loading', 'agent_data', 'online'])
    },
    mounted() {
        this.getStats();
    },
}
</script>

<style scoped>
</style>
