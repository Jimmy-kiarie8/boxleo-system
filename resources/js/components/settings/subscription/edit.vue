<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="400px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Subscription</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm12>
                            <v-card-text>
                                <div>
                                    <label for="">Subscription</label>
                                    <el-input placeholder="USD" v-model="form.subscription"></el-input>
                                </div>
                                <div>
                                    <label for="">Subscription code</label>
                                    <el-input placeholder="KES" v-model="form.subscription_code"></el-input>
                                </div>

                                <div>
                                    <label for="">Exchange Rate</label>
                                    <el-input placeholder="1.000" v-model="form.rate"></el-input>
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
export default {
    data: () => ({
        dialog: false,
        form: {},
    }),
    created() {
        eventBus.$on("openEditSubscription", data => {
            this.form = data
            this.dialog = true;
        });
    },

    methods: {
        save() {
            var payload = {
                model: 'subscriptions',
                data: this.form,
                id: this.form.id,
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("SubscriptionEvent")
                });
        },
        close() {
            this.dialog = false;
        }
    },
    computed: {
        loading() {
            return this.$store.getters.loading;
        },
    },
};
</script>
