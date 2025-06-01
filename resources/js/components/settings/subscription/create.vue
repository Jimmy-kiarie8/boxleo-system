<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Subscription plan</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm12>
                            <v-card-text>
                                <div>
                                    <label for="">Subscription Name</label>
                                    <el-input placeholder="" v-model="form.subscription"></el-input>
                                </div>
                                <div>
                                    <label for="">Subscription amount</label>
                                    <el-input placeholder="2000" v-model="form.subscription_amount"></el-input>
                                </div>

                                <div>
                                    <label for="">Subscription Description</label>
                                    <el-input textarea v-model="form.subscription_description"></el-input>
                                </div>

                                <div>
                                    <label for="">Features</label>
                                    <vue-editor v-model="form.features"></vue-editor>
                                </div>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="save" :loading="loading" :disabled="loading">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import {
    VueEditor
} from "vue2-editor";

import {
    mapState
} from "vuex";

export default {
    components: {
        VueEditor
    },
    data: () => ({
        dialog: false,
        form: {},
        errors: {},

    }),
    created() {
        eventBus.$on("openCreateSubscription", data => {
            this.dialog = true;
        });
    },

    methods: {
        save() {
            var payload = {
                model: '/plan/create',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("SubscriptionEvent")
                });
        },
        close() {
            this.dialog = false;
        }
    },

    computed: {
        ...mapState(['loading'])
    },
};
</script>
