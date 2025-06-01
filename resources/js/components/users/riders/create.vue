<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="700px">
            <v-card>
                <v-card-title>
                    <span class="headline text-center" style="margin: auto;">Create Rider</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex sm12>
                                <div>
                                    <label for="">Rider Name</label>
                                    <el-input placeholder="John Doe" v-model="form.name"></el-input>
                                    <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                                </div>
                                <div>
                                    <label for="">Email Address</label>
                                    <el-input placeholder="john@gmail.com" v-model="form.email"></el-input>
                                    <small class="has-text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                                </div>
                                <div>
                                    <label for="">Phone Number</label>
                                    <el-input placeholder="+1..." v-model="form.phone"></el-input>
                                    <small class="has-text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small>
                                </div>
                                <div>
                                    <label for="">Address</label>
                                    <el-input placeholder="Main st" v-model="form.address"></el-input>
                                    <small class="has-text-danger" v-if="errors.address">{{ errors.address[0] }}</small>
                                </div>
                                <div>
                                    <label for="">Rate per delivery</label>
                                    <el-input placeholder="100" v-model="form.rate_per_delivery"></el-input>
                                    <small class="has-text-danger" v-if="errors.rate_per_delivery">{{ errors.rate_per_delivery[0] }}</small>
                                </div>

                                <div>
                                    <label for="">Zone</label>
                                    <el-select v-model="form.zone_id" filterable clearable placeholder="Select"
                                        style="width: 100%;">
                                        <el-option v-for="item in zones" :key="item.id" :label="item.name"
                                            :value="item.id">
                                        </el-option>
                                    </el-select>
                                </div>

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
import { mapState } from 'vuex';
export default {
    data: () => ({
        dialog: false,
        form: {
            rate_per_delivery: 10
        },
        // payload: {
        //     model: 'riders',
        // },
    }),
    created() {
        eventBus.$on("openCreateRider", data => {
            this.dialog = true;
            this.getZones()
        });
    },

    methods: {
        save() {
            var payload = {
                model: 'rider/riders',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("riderEvent")
                });
        },
        close() {
            this.dialog = false;
        },
        getZones() {
            var payload = {
                model: 'zones',
                update: 'updateZone',
            }
            this.$store.dispatch('getItems', payload);
        },
    },
    computed: {
        ...mapState(['errors', 'loading', 'zones'])
    },
};
</script>
