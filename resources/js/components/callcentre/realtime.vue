<template>
<v-card>
    <v-card-text>
        <v-data-table :headers="headers" :items="realtime" :search="search" :loading="loading">
            <template v-slot:item.online="{ item }">
                <myOnline v-if="item.online" />
                <myOffline v-else />
            </template>
            <template v-slot:item.on_break="{ item }">
                <myOnline v-if="item.on_break" />
                <myOffline v-else />
            </template>
            <template v-slot:item.call_active="{ item }">
                <myOnline v-if="item.call_active" />
                <myOffline v-else />
            </template>
        </v-data-table>
    </v-card-text>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex'
import myOnline from './online.vue'
import myOffline from './offline.vue'
export default {
    components: {
        myOnline,
        myOffline
    },
    data() {
        return {
            search: '',
            headers: [{
                text: "Agent",
                value: "name"
            }, {
                text: "Calls made",
                value: "calls_count"
            }, {
                text: "Talk time (Min)",
                value: "calls_sum_duration_in_seconds"
            }, {
                text: "Connected",
                value: "today_answered"
            }, {
                text: "Not Connected",
                value: "today_unanswered"
            }, {
                text: "Missed Calls",
                value: "today_missed"
            },  {
                text: "On Break",
                value: "on_break"
            },  {
                text: "On Call",
                value: "call_active"
            },{
                text: "Online status",
                value: "online"
            }]
        }
    },

    methods: {
        getRealtime() {
            var payload = {
                update: 'updateRealtime',
                model: 'agents',
                load: 'no'
            }
            this.$store.dispatch("getItems", payload);
        },

        updateUserStatus(userData, newStatus) {
            let userId = userData.id;
            const user = this.realtime.find(user => user.id === userId);
            if (user) {
                user.online = newStatus;
                user.call_active = userData.call_active;
                user.on_break = userData.on_break;
            }
        }
    },
    created() {
        eventBus.$on('userOnlineEvent', data => {
            this.updateUserStatus(data.id, true);
            // setTimeout(() => {
            //     this.getRealtime()
            // }, 3000);
        });
        eventBus.$on('userOfflineEvent', data => {
            this.updateUserStatus(data, false);
        });
    },
    mounted() {
        this.getRealtime();
    },

    computed: {
        ...mapState(['realtime', 'loading'])
    },

}
</script>

<style>
.pulse {
    width: 10px !important;
    height: 10px !important;
    margin-left: 20px !important;
}
</style>
