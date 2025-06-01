<template>
<v-card>
    <v-card-title>
    <v-btn text icon color="primary" @click="getCalls()">
        <v-icon>mdi-refresh</v-icon>
    </v-btn>
    <v-spacer></v-spacer>
    <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
    ></v-text-field>

    </v-card-title>
    <v-data-table :headers="headers" :items="calls.data" :search="search">

        <template v-slot:item.dialDestinationNumber="props">
            {{ props.item.dialDestinationNumber }}
            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on"  @click="makecall(props.item, 'phone')" color="primary" style="float: right;">
                        <v-icon small>mdi-phone</v-icon>
                    </v-btn>
                </template>
                <span>Call</span>
            </v-tooltip>

        </template>
        <template v-slot:item.actions="{ item }" v-if="user.can['Order view']">

    <v-tooltip bottom v-if="!user.is_vendor && user.can['Recordings']">
        <template v-slot:activator="{ on }">
            <v-btn v-on="on" icon class="mx-0" @click="playAudio(item.id)" slot="activator">
                <v-icon small color="red darken-2">mdi-play-circle</v-icon>
            </v-btn>
        </template>
        <span>Recordings</span>
    </v-tooltip>
    </template>
    </v-data-table>


    <v-bottom-sheet inset v-model="playing">
        <v-card tile>
            <audio controls :src="proxyUrl" style="width: 100% !important;" v-if="playingAudio">
            Your browser does not support the <code>audio</code> element.
            </audio>
        </v-card>
    </v-bottom-sheet>
</v-card>
</template>

  <script>
import { mapState } from 'vuex';

  export default {
    props: ['user'],
    data () {
      return {
        search: '',
        headers: [
          { text: 'Created At', value: 'callStartTime'},
          { text: 'Caller No.', value: 'callerNumber' },
          { text: 'Phone', value: 'dialDestinationNumber' },
          { text: 'call Session State', value: 'callSessionState' },
          { text: 'Duration In Seconds', value: 'durationInSeconds' },
          { text: 'Amount', value: 'amount' },
          { text: 'Caller Carrier', value: 'callerCarrierName' },
          { text: 'Call Status', value: 'call_status' },
          { text: "Actions", value: "actions", sortable: false}
        ],

        playing: false,
        playing_audio: null,
        playingAudio: null,
        proxyUrl: null
      }
    },
    methods: {
        getCalls() {
            var payload = {
                update: 'updateCalls',
                model: 'calls',
            }
            this.$store.dispatch('getItems', payload)
                .then(response => {
                    console.log("🚀 ~ file: call-list.vue:46 ~ getCalls ~ response:", response)
                });
        },

        makecall(item) {
            let phone = item.dialDestinationNumber;
            var data = {
                call: 'redial',
                phoneNumber: phone,
                order: item,
                data: item
            }
            eventBus.$emit('callEvent', data);
        },
        playAudio(audioUrl) {
            this.proxyUrl = `/audio/${audioUrl}`;
            this.playing = true;
            this.playingAudio = this.proxyUrl;
        },
    },
    computed: {
        ...mapState(['calls'])
    },
    mounted () {
        // this.getCalls();
    },
  }
</script>