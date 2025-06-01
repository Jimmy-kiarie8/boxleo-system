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
                    
                    <router-link to="/shopify" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>fab fa-shopify</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Shopify</div>
                        </div>
                    </router-link>
                    <router-link to="/woocommerce" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>fab fa-wordpress-simple</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Woocommerce</div>
                        </div>
                    </router-link>

                    <!-- <router-link to="/orders" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-cart</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Orders</div>
                        </div>
                    </router-link> -->
                    <router-link to="/google" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-file-excel</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Google sheets</div>
                        </div>
                    </router-link>

                    <!-- <router-link to="/products" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-basket</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Products</div>
                        </div>
                    </router-link> -->

                </v-card>
            </template>
        </v-list>
    </v-navigation-drawer>

    <v-app-bar :clipped-left="clipped" app color="red darken-3" dark>
        <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
        <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
            <img :src="company.logo" alt style="width: 90px; height: 70px;border-radius: 20px;" v-if="company.logo">
            <v-btn to="/company" @click="logo_upload" v-else>Upload Logo</v-btn>
        </v-toolbar-title>
        <v-spacer />
        <!-- <v-switch v-model="$vuetify.theme.dark" inset label="Dark mode" small persistent-hint style="margin-top: 25px;margin-right: 30px;"></v-switch> -->
        <div v-if="user.can['Manage Billings']">
            <p v-if="tenant.subscriber.at_trial || tenant.subscriber.expired">
                <v-btn small elevation="4" color="primary" outlined text>
                    Trial ends on: {{ tenant.subscriber.trial_ends | moment }}
                    <a :href="app_url + '/upgrade?domain=' + tenant.enc_domain" target="_blank" style="color: #fff">Upgrade</a>
                </v-btn>
            </p>
            <p v-else-if="tenant.days_remaining < 5">
                <v-btn small elevation="4" color="primary" outlined text>
                    Subscription ends on: {{ tenant.subscriber.subscription_expire | moment }}
                    <!-- Subscription ends on: {{ tenant.days_remaining.subscription_expire | moment }} -->
                    <a :href="app_url + '/upgrade?domain=' + tenant.enc_domain" target="_blank" style="color: #fff">Make payment</a>
                </v-btn>
            </p>
        </div>
        <!-- <p v-else>Plan</p> -->

        <v-spacer />

        <!-- <v-icon @click="$vuetify.theme.dark = !$vuetify.theme.dark">mdi-moon-waning-crescent</v-icon> -->
        <!-- <Logout :user="user" />
        <Notifications :user="user" /> -->
        <!-- <myLogo /> -->
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
// import Logout from "./Logout";
// import Notifications from "./notifications";
import moment from 'moment'
// import myLogo from '../settings/company/logo.vue'
// import myDrawerOpen from '../drawer/Drawer'
import {
    mapState
} from "vuex";
export default {
    components: {
        // Logout,
        // Notifications,
        // myLogo
        // myDrawerOpen
    },
    props: ["user", 'tenant'],
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
    filters: {
        moment(date) {
            return moment(date).format('ddd, MMM Do YYYY');
        },
    },
    methods: {
        logo_upload() {
            eventBus.$emit('openLogoEvent')
        },
        openShipment() {
            this.dialog = true;
            this.getBranch();
            this.getCustomer();
            this.getDrivers();
        },

        getCustomer() {
            axios
                .get("/getCustomer")
                .then(response => {
                    this.Allcustomers = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
        },
        getDrivers() {
            axios
                .get("/getDrivers")
                .then(response => {
                    this.AllDrivers = response.data;
                })
                .catch(error => {
                    // console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        getBranch() {
            axios
                .get("/getBranchEger")
                .then(response => {
                    this.AllBranches = response.data;
                })
                .catch(error => {
                    // console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        close() {
            this.dialog = false;
        },

        showalert(data) {
            // this.message = data;
            // this.Snackcolor = "indigo";
            // this.snackbar = true;

            this.$message({
                type: 'success',
                message: data
            });
        },
        showError(data) {
            // this.message = data;
            // this.Snackcolor = "indigo";
            // this.snackbar = true;

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
        },
        loggedout() {
            window.location.href = '/login';
        },
        getApi() {
            var payload = {
                model: 'api_check',
                update: 'updatApiConnection'
            }
            this.$store.dispatch('getItems', payload)
        },

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

        eventBus.$on("reloadRequest", data => {
            this.loggedout()
        });

        eventBus.$on('playSoundEvent', data => {
            this.playSound()
        })

        eventBus.$on('orderUploadEvent', data => {
            this.$message({
                type: 'success',
                message: 'New Orders uploaded'
            });
        })
    },
    computed: {
        ...mapState(['page_loader', 'company', 'loading']),

        date_diff() {
            var diff = Math.floor((Date.now - Date.parse(this.tenant.subscriber.subscription_expire)) / 86400000);
            console.log(diff);
            return diff

            var dateA = moment(this.tenant.subscriber.subscription_expire, "MM-DD-YYYY"); // replace format by your one
            var dateB = moment(moment(), "MM-DD-YYYY");

            console.log(dateB);
            return dateA.diff(dateB)

            if (dateA.diff(dateB) > 0) {
                // do something if A is later than B
            } else {
                // do something if B is later that A
            }
        }
        // loadpage() {
        //     if(this.$store.getters.isLoading) {
        //         return this.openFullScreen()
        //     }
        //     return this.$store.getters.isLoading
        // }
    },
    mounted() {
        this.getCompany();
        this.getApi();
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
/* 
.theme--light.v-application {
    margin-top: -650px;
} */

.v-sheet.v-app-bar.v-toolbar:not(.v-sheet--outlined) {
    height: 80px !important;
    padding-top: 6px;
}

#navigation .v-list-item--link,
#navigation .v-list-item--link {
    width: 100% !important;
}
</style>
