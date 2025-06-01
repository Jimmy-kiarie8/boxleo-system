<template>
<v-app id="inspire">
    <!-- <v-navigation-drawer
      v-model="drawer"
      app
    >

    </v-navigation-drawer> -->
    <v-navigation-drawer v-model="drawer" :clipped="clipped" app>

        <v-list dense id="navigation">
            <v-img :aspect-ratio="16/9" src="/storage/app/app_image.jpeg">
                <v-layout pa-2 column fill-height class="lightbox white--text">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <div class="subheading">{{ user.name }}</div>
                        <div class="body-1">{{ user.email }}</div>
                    </v-flex>
                </v-layout>
            </v-img>
            <template>
                <v-card>
                    <router-link to="/" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-view-dashboard</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Dashboard</div>
                        </div>
                    </router-link>
                    <router-link to="/warehouses" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-warehouse</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Warehouses</div>
                        </div>
                    </router-link>
                    <router-link to="/levels" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-oil-level</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Levels</div>
                        </div>
                    </router-link>
                    <router-link to="/bin" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-delete-circle</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Bin</div>
                        </div>
                    </router-link>
                    <router-link to="/receive" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-audio-video</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Receive</div>
                        </div>
                    </router-link>
                    <router-link to="/dispatch" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-disc-player</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Dispatch</div>
                        </div>
                    </router-link>
                    <router-link to="/products" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-basket</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Products</div>
                        </div>
                    </router-link>
                    <!-- 
                    <v-list-group prepend-icon="mdi-clock-outline" v-if="user.can['Manage company']">
                        <template v-slot:activator>
                            <v-list-item-title>Company</v-list-item-title>
                        </template>

                        <router-link to="/company" class="v-list-item v-list-item--link theme--light">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-office-building</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Company</div>
                            </div>
                        </router-link>

                    </v-list-group> -->

                </v-card>
            </template>
        </v-list>
    </v-navigation-drawer>

    <v-app-bar :clipped-left="clipped" app color="red darken-3" dark>
        <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
        <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
            <!-- <img :src="company.logo" alt style="width: 90px; height: 70px;border-radius: 20px;"> -->
        </v-toolbar-title>
        <v-spacer />
        <!-- <v-switch v-model="$vuetify.theme.dark" inset label="Dark mode" small persistent-hint style="margin-top: 25px;margin-right: 30px;"></v-switch> -->

        <!-- <p v-if="tenant.subscriber.at_trial || tenant.subscriber.expired"> trial ends on {{ tenant.subscriber.trial_ends }}
            <a :href="app_url + '/upgrade?domain=' + tenant.enc_domain" target="_blank">Upgrade</a>
        </p> -->
        <!-- <p v-else>Plan</p> -->

        <v-spacer />

        <!-- <v-icon @click="$vuetify.theme.dark = !$vuetify.theme.dark">mdi-moon-waning-crescent</v-icon> -->
        <Logout :user="user" />
        <!-- <Notifications :user="user" /> -->

        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn icon v-on="on" class="mx-0" @click="refresh_dash" slot="activator">
                    <v-icon small color="white darken-2">mdi-refresh</v-icon>
                </v-btn>
            </template>
            <span>Refresh dashboard</span>
        </v-tooltip>
        <!-- <v-btn text icon color="primary" @click="playSound">
            <v-icon>mdi-play-circle</v-icon>
        </v-btn> -->
        <!-- <VDivider vertical style="margin-top: 0px;" /> -->
    </v-app-bar>

    <v-main>
        <!--  -->
    </v-main>
</v-app>
</template>

<script>
import Logout from "../../components/include/Logout";
// import Notifications from "./notifications"; 
// import myDrawerOpen from '../drawer/Drawer'
import {
    mapState
} from "vuex";
export default {
    components: {
        Logout,
        // Notifications
        // myDrawerOpen
    },
    props: ["user"],
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            role: "",
            Snackcolor: '',
            color: "#e41937",
            clipped: false,
            dialog: false,
            mini_drawer: true,
            drawer: true,
            drawerRight: false,
            right: null,
            mode: "",
            AllBranches: [],
            Allcustomers: [],
            AllDrivers: [],
            snackbar: false,
            timeout: 5000,
            message: "Success",
            fullscreenLoading: false,
            icon: "",
            app_url: process.env.MIX_APP_URL
        };
    },
    methods: {

        close() {
            this.dialog = false;
        },

        showalert(data) {
            this.$message({
                type: 'success',
                message: data
            });
        },
        showError(data) {
            this.$message({
                type: 'error',
                message: data
            });
        },
        openFullScreen() {
            this.loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
        },
        closeFullScreen() {
            this.loading.close();
        },
        getCompany() {
            var payload = {
                model: 'company',
                update: 'updateCompany',
            }
            this.$store.dispatch('getItems', payload);
        },

        playSound(sound) {
            var audio = new Audio('http://soundbible.com/mp3/Air%20Plane%20Ding-SoundBible.com-496729130.mp3');
            audio.play();
        },

        refresh_dash() {
            eventBus.$emit('Refreshdashboard')
        }

    },
    created() {
        // this.playSound()

        eventBus.$on("progressEvent", data => {
            this.$refs.topProgress.start();
        });
        eventBus.$on("StoprogEvent", data => {
            this.$refs.topProgress.done();
        });
        eventBus.$on("alertRequest", data => {
            this.showalert(data)
        });
        eventBus.$on("errorEvent", data => {
            this.showError(data)
        });
        eventBus.$on("LoadingEvent", data => {
            // this.openFullScreen(data)
        });
        eventBus.$on("stopLoadingEvent", data => {
            // this.closeFullScreen(data)
        });

    },
    computed: {
        ...mapState(['page_loader', 'company', 'loading'])
        // loadpage() {
        //     if(this.$store.getters.isLoading) {
        //         return this.openFullScreen()
        //     }
        //     return this.$store.getters.isLoading
        // }
    },
    mounted() {
        // this.getCompany();
    },
};
</script>

<style scoped>
.v-expansion-panel__container:hover {
    border-radius: 10px !important;
    width: 90% !important;
    margin-left: 15px !important;
    background: #e3edfe !important;
    color: #1a73e8 !important;
}

.theme--light {
    background-color: #212120 !important;
    /* background: url('storage/logo1.jpg') !important; */
    color: #fff !important;
}

.v-toolbar[data-booted=true] {
    transition: .2s cubic-bezier(.4, 0, .2, 1);
    z-index: 100 !important;
}

.theme--light[data-v-67cb1297] {
    z-index: 101 !important;
}

.theme--dark.v-btn:not(.v-btn--text):not(.v-btn--text):not(.v-btn--outlined) {
    background-color: transparent !important;
}

.theme--light.v-application {
    /* margin-top: -650px; */
}

.v-sheet.v-app-bar.v-toolbar:not(.v-sheet--outlined) {
    /* height: 94px !important; */

}

#navigation .v-list-item--link,
#navigation .v-list-item--link {
    width: 100% !important;
}
</style>
