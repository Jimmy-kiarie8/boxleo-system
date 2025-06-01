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

                    <v-list-group prepend-icon="mdi-cart-arrow-up">
                        <template v-slot:activator>
                            <v-list-item-title>Orders</v-list-item-title>
                        </template>
                        <router-link to="/sales" class="v-list-item v-list-item--link theme--light" v-if="user.can['Order view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-cart</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Orders</div>
                            </div>
                        </router-link>
                        <router-link to="/invoices" class="v-list-item v-list-item--link theme--light" v-if="user.can['Invoices view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-receipt</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Invoices</div>
                            </div>
                        </router-link>
                        <router-link to="/tracking" class="v-list-item v-list-item--link theme--light" v-if="user.can['Order view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-progress-check</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Orders Progress Tracking</div>
                            </div>
                        </router-link>
                        <router-link to="/dispatch" class="v-list-item v-list-item--link theme--light" v-if="user.can['Order dispatch']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-send</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Dispatch</div>
                            </div>
                        </router-link>

                        <router-link to="/status_update" class="v-list-item v-list-item--link theme--light" v-if="user.can['Order update delivered or returned']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-moped</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Deliveries&Returns</div>
                            </div>
                        </router-link>
                        <router-link to="/dispatch_filter" class="v-list-item v-list-item--link theme--light" v-if="user.can['Order dispatch']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-moped</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Dispatch List</div>
                            </div>
                        </router-link>
                        
                        <router-link to="/transactions" class="v-list-item v-list-item--link theme--light" v-if="user.can['Order update delivered or returned']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-cash-100</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Mpesa transactions</div>
                            </div>
                        </router-link>
                    </v-list-group>

                    <router-link to="/returns" class="v-list-item v-list-item--link theme--light" v-if="user.can['Return view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-clipboard-arrow-left</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Returns</div>
                        </div>
                    </router-link>

                    <router-link to="/clients" class="v-list-item v-list-item--link theme--light" v-if="user.can['Clients view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-account-group</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Clients</div>
                        </div>
                    </router-link>

                    <v-list-group prepend-icon="mdi-basket" v-if="user.can['Manage inventory']">
                        <template v-slot:activator>
                            <v-list-item-title>Inventory</v-list-item-title>
                        </template>
                        <router-link to="/products" class="v-list-item v-list-item--link theme--light" v-if="user.can['Products view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-basket</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Products</div>
                            </div>
                        </router-link>
                        <!-- <router-link to="/menu" class="v-list-item v-list-item--link theme--light">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-dots-vertical-circle</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Main Categories</div>
                            </div>
                        </router-link>
                        <router-link to="/category" class="v-list-item v-list-item--link theme--light">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-shape-outline</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Categories</div>
                            </div>
                        </router-link>
                        <router-link to="/subcategory" class="v-list-item v-list-item--link theme--light">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-folder</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Subcategories</div>
                            </div>
                        </router-link>

                        <router-link to="/brand" class="v-list-item v-list-item--link theme--light">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-watermark</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Brands</div>
                            </div>
                        </router-link> -->
                    </v-list-group>

                    <v-list-group prepend-icon="mdi-basket" v-if="user.can['Manage inventory']">
                        <template v-slot:activator>
                            <v-list-item-title>Warehouse</v-list-item-title>
                        </template>
                        <a href="/warehouse" target="_blank" class="v-list-item v-list-item--link theme--light" v-if="tenant.subscriber.tenant_plan.warehouse_management">
                            <!-- <a href="/warehouse" target="_blank" class="v-list-item v-list-item--link theme--light" v-if="user.can['Manage Warehouse']"> -->
                            <div class="v-list__tile__action">
                                <v-icon>mdi-warehouse</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Warehouse Managent</div>
                            </div>
                        </a>

                        <router-link to="/warehouse" class="v-list-item v-list-item--link theme--light" v-if="user.can['Warehouse view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-warehouse</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Warehouses</div>
                            </div>
                        </router-link>
                    </v-list-group>

                    <router-link to="/vendors" class="v-list-item v-list-item--link theme--light" v-if="user.can['Vendors view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-account-multiple</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Vendors</div>
                        </div>
                    </router-link>
                    <v-list-group prepend-icon="mdi-account-circle" v-if="user.can['Manage user']">
                        <template v-slot:activator>
                            <v-list-item-title>Users</v-list-item-title>
                        </template>
                        <router-link to="/users" class="v-list-item v-list-item--link theme--light" v-if="user.can['User view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-account-circle</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Users</div>
                            </div>
                        </router-link>
                        <router-link to="/roles" class="v-list-item v-list-item--link theme--light" v-if="user.can['Role view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-account-multiple-check</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">User Roles</div>
                            </div>
                        </router-link>
                    </v-list-group>

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


                        <router-link to="/ou" class="v-list-item v-list-item--link theme--light">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-map</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Operating Units</div>
                            </div>
                        </router-link>
                    </v-list-group>

                    <v-list-group prepend-icon="mdi-cogs" v-if="user.can['Setting view']">
                        <template v-slot:activator>
                            <v-list-item-title>Settings</v-list-item-title>
                        </template>

                        <router-link to="/custom" class="v-list-item v-list-item--link theme--light">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-face-agent</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Custom view</div>
                            </div>
                        </router-link>
                        <router-link to="/api" class="v-list-item v-list-item--link theme--light">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-domain</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Api integrations</div>
                            </div>
                        </router-link>
                        <router-link to="/marketplace" class="v-list-item v-list-item--link theme--light">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-shopping</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Marketplace</div>
                            </div>
                        </router-link>
                        <router-link to="/riders" class="v-list-item v-list-item--link theme--light" v-if="user.can['Rider view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-bike</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Riders</div>
                            </div>
                        </router-link>
                        <router-link to="/status" class="v-list-item v-list-item--link theme--light" v-if="user.can['Status view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-backup-restore</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Status</div>
                            </div>
                        </router-link>
                    </v-list-group>

                    <v-list-group prepend-icon="mdi-toolbox-outline" v-if="user.can['Manage service']">
                        <template v-slot:activator>
                            <v-list-item-title>Services</v-list-item-title>
                        </template>
                        <router-link to="/services" class="v-list-item v-list-item--link theme--light" v-if="user.can['Service view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-face-agent</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Services</div>
                            </div>
                        </router-link>
                        <router-link to="/zones" class="v-list-item v-list-item--link theme--light" v-if="user.can['Zone view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-zodiac-gemini</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Zones</div>
                            </div>
                        </router-link>
                    </v-list-group>

                    <v-list-group prepend-icon="mdi-boom-gate-outline" v-if="user.can['Manage automation']">
                        <template v-slot:activator>
                            <v-list-item-title>Automation</v-list-item-title>
                        </template>
                        <router-link to="/automation" class="v-list-item v-list-item--link theme--light" v-if="user.can['Automation view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-coffee-maker</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Automation</div>
                            </div>
                        </router-link>
                        <router-link to="/sms_template" class="v-list-item v-list-item--link theme--light" v-if="user.can['Sms view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-message-processing</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Sms template</div>
                            </div>
                        </router-link>
                        <router-link to="/mails" class="v-list-item v-list-item--link theme--light" v-if="user.can['Email view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-email</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Email template</div>
                            </div>
                        </router-link>
                    </v-list-group>

                    <v-list-group prepend-icon="mdi-book" v-if="user.can['Manage reports']">
                        <template v-slot:activator>
                            <v-list-item-title>Reports</v-list-item-title>
                        </template>
                        <router-link to="/report" class="v-list-item v-list-item--link theme--light" v-if="user.can['Report view']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-file-chart</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Report</div>
                            </div>
                        </router-link>
                        <router-link to="/custom_report" class="v-list-item v-list-item--link theme--light" v-if="user.can['Custom Report']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-book-plus</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Custom Report</div>
                            </div>
                        </router-link>
                        <router-link to="/remittance" class="v-list-item v-list-item--link theme--light" v-if="user.can['Remittance']">
                            <div class="v-list__tile__action">
                                <v-icon>mdi-chart-timeline-variant-shimmer</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title">Remittance</div>
                            </div>
                        </router-link>
                    </v-list-group>

                    <router-link to="/packages" class="v-list-item v-list-item--link theme--light" v-if="user.can['Print waybills']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-gift</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Waybills</div>
                        </div>
                    </router-link>

                </v-card>
            </template>
        </v-list>
    </v-navigation-drawer>

    <v-app-bar :clipped-left="clipped" app color="red darken-3" dark>
        <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
        <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
            <img :src="company.logo" alt style="height: 70px;border-radius: 20px;" v-if="company.logo">
            <v-btn to="/company" @click="logo_upload" v-else>Upload Logo</v-btn>
        </v-toolbar-title>
        <v-spacer />
        <!-- <v-switch v-model="$vuetify.theme.dark" inset label="Dark mode" small persistent-hint style="margin-top: 25px;margin-right: 30px;"></v-switch> -->
        <div v-if="user.can['Manage Billings']">
            <p v-if="tenant.subscriber.at_trial">
                <v-btn small elevation="4" color="primary" outlined text>
                    Trial ends on: {{ tenant.subscriber.trial_ends | moment }}
                    <a :href="app_url + '/upgrade?domain=' + tenant.enc_domain" target="_blank" style="color: #fff">Upgrade</a>
                </v-btn>
            </p>
            <p v-else-if="tenant.subscriber.expired">
                <v-btn small elevation="4" color="primary" outlined text>
                    Account subscription expired
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
        <Logout :user="user" />
        <Notifications :user="user" />
        <myLogo />
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
import Logout from "./Logout";
import Notifications from "./notifications";
import moment from 'moment'
import myLogo from '../settings/company/logo.vue'
// import myDrawerOpen from '../drawer/Drawer'
import {
    mapState
} from "vuex";
export default {
    components: {
        Logout,
        Notifications,
        myLogo
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
            // var audio = new Audio('http://soundbible.com/mp3/Air%20Plane%20Ding-SoundBible.com-496729130.mp3');
            // audio.play();
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
