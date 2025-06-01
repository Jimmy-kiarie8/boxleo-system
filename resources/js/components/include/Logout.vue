<template>
<div class="text-center">
    <v-menu v-model="menu" :close-on-content-click="false" :nudge-width="200" offset-y transition="slide-y-transition">
        <!-- <v-menu v-model="menu" :close-on-content-click="false" :nudge-width="200" offset-x> -->
        <template v-slot:activator="{ on, attrs }">
            <v-btn dark icon v-bind="attrs" v-on="on" small>
                <v-icon color="white lighten-1">
                    mdi-account-circle
                </v-icon>
            </v-btn>
        </template>

        <v-card>

            <v-list dense>
                <v-list-item-group color="primary">
                    <v-list-item>
                        <v-list-item-avatar>
                            <avatar :username="user.name" style="font-size: 20px;margin: auto;padding: 0px;"></avatar>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title>{{ user.name }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>
                                <el-switch v-model="user.on_break" active-text="On break" inactive-text="Online" @change="break_update"></el-switch>
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>


                    <v-list-item v-if="user.can['Manage company']">
                        <v-list-item-content>
                            <v-list-item-title>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" slot="activator" @click="token">
                                            <v-icon small color="primary">mdi-refresh</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Generate Token</span>
                                </v-tooltip>
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
                <v-divider></v-divider>

                <router-link to="/profile" class="v-list-item v-list-item--link theme--light">
                    <div class="v-list__tile__action">
                        <v-icon>mdi-face-profile</v-icon>
                    </div>
                    <div class="v-list-item__content">
                        <div class="v-list-item__title">My Profile</div>
                    </div>
                </router-link>


                <a href="/2fa/setup" class="v-list-item v-list-item--link theme--light">
                    <div class="v-list__tile__action">
                        <v-icon>mdi-security</v-icon>
                    </div>
                    <div class="v-list-item__content">
                        <div class="v-list-item__title">Enable 2FA</div>
                    </div>
                </a>

            </v-list>

            <v-card-actions>
                <form action="/logout" method="post">
                    <input type="hidden" name="_token" :value="csrf">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">

                            <v-btn v-on="on" text slot="activator" color="primary" icon class="mx-0" type="submit">
                                <v-icon>mdi-logout</v-icon>
                            </v-btn>
                        </template>
                        <span>Logout from this device</span>
                    </v-tooltip>
                </form>

                <form action="/logoutOther" method="get">
                    <input type="hidden" name="_token" :value="csrf">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" text slot="activator" color="orange" icon class="mx-0" type="submit">
                                <v-icon>mdi-logout-variant</v-icon>
                            </v-btn>
                        </template>
                        <span>Logout other devices</span>
                    </v-tooltip>
                </form>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" text slot="activator" color="primary" icon class="mx-0" type="submit" @click="dialog = true" small>
                            <v-icon>mdi-account-box-multiple</v-icon>
                        </v-btn>
                    </template>
                    <span>Switch OU</span>
                </v-tooltip>
                <v-btn text @click="menu = false">close</v-btn>
            </v-card-actions>
        </v-card>
    </v-menu>

    <v-dialog v-model="dialog" width="500">
        <v-card>
            <v-card-title class="text-h5 white lighten-2">
                Operating unit
            </v-card-title>

            <v-card-text>
                <label for=""></label>
                <el-select v-model="form.ou" placeholder="Select" style="width: 100%">
                    <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.id">
                    </el-option>
                </el-select>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions v-if="user.can['Order filter by OU'] || user.is_vendor">
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="ou_switch">
                    Switch
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</div>
</template>

<script>
import Avatar from 'vue-avatar';

import {
    mapState
} from "vuex";
export default {
    props: ['user'],
    components: {
        Avatar
    },
    data() {
        return {
            on_break: false,
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            dialog: false,
            menu: false,
            form: {}
        }
    },
    methods: {

        break_update() {
            this.form = {
                on_break: this.user.on_break
            }
            var payload = {
                data: this.form,
                id: this.user.id,
                model: '/on_break',
            }
            // this.payload['data'] = this.form
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    // eventBus.$emit("breakEvent")
                    this.getOnline()
                });
        },
        getOnline() {
            var payload = {
                update: 'updateOnline',
                model: 'user_online',
                load: 'no',
            }
            this.$store.dispatch("getItems", payload);
        },
        token() {
            var payload = {
                model: 'af_token',
                load: 'no',
                notify: 'no'
            }
            this.$store.dispatch("postItems", payload);
        },
        getOu() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload);
        },

        ou_switch() {
            var payload = {
                model: 'ou_switch',
                data: this.form
            }
            this.$store.dispatch('postItems', payload).then((res) => {
                this.dialogTableVisible = false
            });
        }
    },
    computed: {
        ...mapState(['ous'])
    },
    mounted() {
        this.getOu();
    },
};
</script>

<style scoped>
.theme--dark.v-btn:not(.v-btn--text):not(.v-btn--text):not(.v-btn--outlined) {
    background-color: transparent !important;
}

a {
    text-decoration: none;
}

.v-avatar {
    height: 60px !important;
    width: 60px !important;
    margin-left: 0 !important;
}

.v-application--is-ltr .v-list-item__avatar:first-child {
    margin-right: 0 !important;
}
</style>
