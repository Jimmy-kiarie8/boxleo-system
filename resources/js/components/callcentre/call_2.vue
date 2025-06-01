<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="400px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto">Make a call</span>
                <v-spacer></v-spacer>
                <v-btn color="primary" dark icon @click="close">
                    <v-icon dark small>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            <v-divider></v-divider>

            <v-card-text>
                <div>
                    <label for="">Phone number</label>
                    <el-select v-model="form.phone" filterable remote allow-create reserve-keyword placeholder="Please enter a keyword" :remote-method="searchLeads" :loading="loading" style="width: 100%">
                        <el-option v-for="item in lead_search" :key="item.id" :label="item.phone" :value="item.phone"></el-option>
                    </el-select>
                    <!-- <el-input placeholder="+254" v-model="form.phone"></el-input> -->

                    <!-- <small class="has-text-danger" v-if="errors.name">{{errors.name[0]}}</small> -->
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn color="red" text @click="hangup" v-if="call_inprogress">
                    <v-icon color="red">mdi-phone-hangup</v-icon> Hangup
                </v-btn>
<!--
                <v-btn color="red" text @click="transfer" v-if="call_inprogress">
                    <v-icon color="red">mdi-phone-forward</v-icon> Transfer
                </v-btn> -->
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="call" :loading="loading" :disabled="loading">
                    <v-icon color="primary">mdi-phone-outgoing-outline</v-icon> Call
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
        dialog: false,
        form: {
            phone: ''
        },
        call_inprogress: false,
    }),
    created() {
        eventBus.$on("callEvent", (data) => {
            this.form = data;
            this.dialog = true;
        });
        eventBus.$on("callHangedupEvent", (data) => {
            this.call_inprogress = false;
        });
    },

    methods: {
        call() {
            this.call_inprogress = true
            eventBus.$emit('makeCallEvent', this.form.phone);
            this.update_missed()

            return;
        },
        update_missed() {
            var payload = {
                data: this.form,
                model: '/missed_update',
            }
            this.$store.dispatch('postItems', payload)
        },
        hangup() {
            eventBus.$emit('hangupEvent', this.form.phone);
        },
        close() {
            this.dialog = false;
        },

        searchLeads(search) {
            if (search.length > 2) {
                var payload = {
                    update: "searchLead",
                    model: "searchLeads",
                    search: search,
                };
                this.$store.dispatch("searchItems", payload);
            }
        },
    },
    computed: {
        ...mapState(["errors", "loading", "lead_search"]),
    },
};
</script>
