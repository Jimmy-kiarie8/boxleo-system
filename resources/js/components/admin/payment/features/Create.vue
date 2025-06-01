<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Feature</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm12>
                            <label for="">Name</label>
                            <el-input placeholder="" v-model="form.name"></el-input>
                            <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Users</label>
                            <el-input placeholder="" v-model="form.users"></el-input>
                            <small class="has-text-danger" v-if="errors.users">{{ errors.users[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Portals</label>
                            <el-input placeholder="" v-model="form.portals"></el-input>
                            <small class="has-text-danger" v-if="errors.portals">{{ errors.portals[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Orders per month</label>
                            <el-input placeholder="" v-model="form.orders"></el-input>
                            <small class="has-text-danger" v-if="errors.orders">{{ errors.orders[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Shipping Lables</label>
                            <el-input placeholder="" v-model="form.lables"></el-input>
                            <small class="has-text-danger" v-if="errors.lables">{{ errors.lables[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Tracking</label>
                            <el-input placeholder="" v-model="form.tracking"></el-input>
                            <small class="has-text-danger" v-if="errors.tracking">{{ errors.tracking[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Warehouses</label>
                            <el-input placeholder="" v-model="form.warehouses"></el-input>
                            <small class="has-text-danger" v-if="errors.warehouses">{{ errors.warehouses[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Api Integration</label>
                            <el-input placeholder="" v-model="form.api_integrations"></el-input>
                            <small class="has-text-danger" v-if="errors.api_integrations">{{ errors.api_integrations[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Shopify Integration</label>
                            <el-input placeholder="" v-model="form.shopify_integrations"></el-input>
                            <small class="has-text-danger" v-if="errors.shopify_integrations">{{ errors.shopify_integrations[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Wordpress Integration</label>
                            <el-input placeholder="" v-model="form.wordpress_integrations"></el-input>
                            <small class="has-text-danger" v-if="errors.wordpress_integrations">{{ errors.wordpress_integrations[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Automations</label>
                            <el-input placeholder="" v-model="form.automations"></el-input>
                            <small class="has-text-danger" v-if="errors.automations">{{ errors.automations[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Sms</label>
                            <el-input placeholder="" v-model="form.sms"></el-input>
                            <small class="has-text-danger" v-if="errors.sms">{{ errors.sms[0] }}</small>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Email</label>
                            <el-input placeholder="" v-model="form.email"></el-input>
                            <small class="has-text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
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
    mapState
} from 'vuex';
export default {
    data: () => ({
        dialog: false,
        loading: false,
        form: {},
    }),
    created() {
        eventBus.$on("openCreateFeature", data => {
            this.dialog = true;
        });
    },

    methods: {
        save() {

            var payload = {
                model: 'features',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("featureEvent")
                });
        },
        close() {
            this.dialog = false;
        },
    },

    computed: {
        ...mapState(['errors'])
    },
};
</script>
