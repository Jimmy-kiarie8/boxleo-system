<template>
<v-main :style="[ ($vuetify.theme.dark) ? {'background': '#1e1e1e'} : '']" style="height: 100vh !important;">
    <div id="show">
        <myDrawer :user="user" />
    </div>

    <Sidebar :drawer="drawer" :tenant="tenant" :user="user" />
    <!-- <Topbar @drawerEvent="drawer = !drawer" /> -->
    <router-view :user="user" :tenant="tenant" v-loading="page_loader" :element-loading-text="app_name + '...'" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)"></router-view>
    <!-- <autologout /> -->



    <audio id="audio-player" loop>
        <source src="/audio/ring.mp3" type="audio/mpeg">
    </audio>


    <audio id="audio-dialing" loop>
        <source src="/audio/outgoing.mp3" type="audio/mpeg">
    </audio>


    <myIncoming />
    <myCall />
</v-main>
</template>

<script>
import {
    mapState
} from 'vuex'
// // import autologout from "./include/autologout.vue";

import Sidebar from "./include/sidebar.vue";
// import Topbar from "./include/topbar.vue";
import myDrawer from './sales/saleorder/drawer'

import myIncoming from './callcentre/incoming.vue'
import myCall from './callcentre/call.vue'

import Africastalking from 'africastalking-client'
const params = {
    sounds: {
        // dialing: "/audio/outgoing.mp3",
        // ringing: "/audio/ring.mp3",
    },
};
export default {
    props: ['user', 'tenant'],
    components: {
        // Topbar,
        Sidebar,myDrawer, myIncoming, myCall
    },

    data() {
        return {
            app_name: 'Boxleo Courier',
            drawer: null,

        }
    },
    computed: {
        ...mapState(['page_loader'])
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
        toggleDialer(data) {
            var audio = document.getElementById("audio-dialing");
            if (data) {
                audio.play();
            } else {
                audio.pause();
            }
        },

        incomingCall(data) {
            this.toggleAudio(true)
            // console.log(data);
            eventBus.$emit('incomingEvent', data);
        },
        getLeads() {
            var payload = {
                'update': 'updateLeads',
                'model': 'leads'
            }
            this.$store.dispatch("getItems", payload);
        },
        makeCall(phone) {
            this.toggleDialer(true)
            const client = new Africastalking.Client(
                this.user.agent_token,
                params
            );

            client.call(phone);
        },
        answer() {

            const client = new Africastalking.Client(
                this.user.agent_token,
                params
            );

            client.answer();
        },
        hangup() {
            const client = new Africastalking.Client(
                this.user.agent_token,
                params
            );

            client.hangup();
        },
        muteAudio() {

            // console.log('mute')
            const client = new Africastalking.Client(
                this.user.agent_token,
                params
            );

            client.muteAudio();
        },
        unmuteAudio() {

            const client = new Africastalking.Client(
                this.user.agent_token,
                params
            );

            client.unmuteAudio();
        },
        hold() {

            const client = new Africastalking.Client(
                this.user.agent_token,
                params
            );

            client.hold();
        },
        unhold() {

            const client = new Africastalking.Client(
                this.user.agent_token,
                params
            );

            client.unhold();
        },

        getStats() {
            var payload = {
                model: 'agent_data',
                update: 'updateAgentData',
            }
            this.$store.dispatch('getItems', payload)
        },
        onCall(id) {
            var payload = {
                data: {},
                model: 'on_call/' + id,
            }
            this.$store.dispatch('postItems', payload)
        
        }

    },
    created() {
        const client = new Africastalking.Client(
            this.user.agent_token,
            params
        );

        client.on('incomingcall', params => {
            // this.ring()

            // console.log(params);
            // var id = this.user.id
            var id = this.user.id
            var form = {
                call_status: true
            }
            axios.post('agent_active/' + id, form)
            // alert(`Jimmy is calling you`)
            this.incomingCall(params)
        }, false);
        client.on('ready', params => {
            // console.log(params);
            eventBus.$emit('connectionEvent', true);
            console.log(`User is ready`)
        }, false);

        client.on('notready', () => {
            // console.log(params);
            console.log(`User is not ready`)
        }, false);

        client.on('closed', params => {
            // console.log(params);
            eventBus.$emit('connectionEvent', false);

            console.log(`Connection closed`)
            // window.location.reload();
        }, false);

        client.on('callaccepted', params => {
            // this.muteAudio()
            this.toggleAudio(false)
            this.toggleDialer(false)
            // this.mutePage()
            eventBus.$emit('alertRequest', 'Call received')

            this.onCall(1);
        }, false);

        client.on('hangup', hangupCause => {
            // this.muteAudio()
            this.toggleDialer(false)
            this.toggleAudio(false)
            eventBus.$emit('alertRequest', 'Hanged up')
            eventBus.$emit('statusEvent')
            // console.log(hangupCause);
            eventBus.$emit('callHangedupEvent')
            var id = this.user.id
            var form = {
                call_status: false
            }

            this.onCall(2);

            axios.post('agent_active/' + id, form).then(() => {
                // window.location.reload();
            })
            // alert(`Call hung up`)
        }, false);

        client.on('calling', (params) => {
            // console.log(params);
            // alert(`Not ready to accepted`)
            var id = this.user.id
            var form = {
                call_status: false
            }
            axios.post('agent_active/' + id, form)
        }, false);

        eventBus.$on('leadsEvent', () => {
            this.getLeads()
        });

        eventBus.$on('answerEvent', () => {
            // this.muteAudio()
            this.answer()
        });
        eventBus.$on('hangupEvent', () => {
            // this.muteAudio()
            this.hangup()
        });

        eventBus.$on('makeCallEvent', data => {
            // console.log(data);
            this.makeCall(data)

        });


        eventBus.$on('saleCallEvent', data => {
            // console.log(data);
            var item = {
                // call: 'new',
                phone: data.phone,
            }
            this.makeCall(data)
            // this.makeCall(data.phone)

        });

        eventBus.$on('holdEvent', () => {
            this.hold()
        });
        eventBus.$on('unholdEvent', () => {
            this.unhold()
        });
        eventBus.$on('breakEvent', () => {
            this.getOnline()
        });



        eventBus.$on('connectionEvent', (data) => {
            this.$store.dispatch('onlineStatus', data)
        });
    },
}
</script>

<style>
.el-loading-mask {
    position: fixed !important;
}
</style>
