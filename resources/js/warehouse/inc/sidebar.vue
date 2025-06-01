<template>
<v-app id="inspire" class="topbar">
    <v-app-bar app color="dark" elevation="0">
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

        <v-toolbar-title>{{ user.company.name }}</v-toolbar-title>

        <v-spacer></v-spacer>
        <!-- <div data-v-e83acb18="" class="v-input mx-1 hidden-xs-only v-input--hide-details v-input--dense theme--dark v-text-field v-text-field--single-line v-text-field--filled v-text-field--is-booted v-text-field--enclosed v-text-field--placeholder v-text-field--rounded">
            <div class="v-input__control">
                <div class="v-input__slot">
                    <div class="v-input__prepend-inner">
                        <div class="v-input__icon v-input__icon--prepend-inner"><i aria-hidden="true" class="v-icon notranslate mdi mdi-magnify theme--dark"></i></div>
                    </div>
                    <div class="v-text-field__slot"><input id="input-196" placeholder="Search (press &quot;ctrl + /&quot; to focus)" type="text" class=""></div>
                </div>
            </div>
        </div> -->

        <!-- <v-text-field class="v-input mx-1 hidden-xs-only v-input--hide-details v-input--dense theme--dark v-text-field v-text-field--single-line v-text-field--filled v-text-field--is-booted v-text-field--enclosed v-text-field--placeholder v-text-field--rounded" dense prepend-inner-icon="mdi-magnify" placeholder="search an order"></v-text-field> -->
        <!-- <mySearch /> -->

        <v-spacer></v-spacer>
       <!--- <div v-if="user.can['Manage Billings']">
            <p v-if="tenant.subscriber.at_trial || tenant.subscriber.expired">
                <v-btn small elevation="4" color="primary" outlined text>
                    Trial ends on: {{ tenant.subscriber.trial_ends | moment }}
                    <a :href="app_url + '/upgrade?domain=' + tenant.enc_domain" target="_blank" style="color: #fff">Upgrade</a>
                </v-btn>
            </p>
            <p v-else-if="tenant.days_remaining < 7">
                <v-btn small elevation="4" color="primary" outlined text>
                    Subscription ends on: {{ tenant.subscriber.subscription_expire | moment }}
                    <a :href="app_url + '/upgrade?domain=' + tenant.enc_domain" target="_blank" style="color: #fff">Make payment</a>
                </v-btn>
            </p>
        </div> -->
        <v-spacer></v-spacer>
        <!-- <Logout :user="user" /> -->
        <v-btn icon>
            <v-icon>mdi-bell</v-icon>
        </v-btn>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" app>
        <v-sheet color="theme--dark" class="pa-4" style="text-align: center">
            <img :src="user.company.logo" alt="" id="logo">

            <div class="subheading">{{ user.name }}</div>
            <div class="body-1">{{ user.email }}</div>
        </v-sheet>

        <v-divider></v-divider>
        <v-list nav>

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
                    <router-link to="/shipments" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-check</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Shipment Request</div>
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
        </v-list>
    </v-navigation-drawer>

</v-app>
</template>

<script>
// import mySearch from './search.vue';
// import Logout from "./Logout";

export default {
    components: {
        // mySearch,
        // Logout
    },
    props: ['user', 'tenant'],
    data: () => ({
            app_url: process.env.MIX_APP_URL,
        selectedItem: 0,
        drawer: true,
        links: [
            ['mdi-inbox-arrow-down', 'Inbox'],
            ['mdi-send', 'Send'],
            ['mdi-delete', 'Trash'],
            ['mdi-alert-octagon', 'Spam'],
        ],
        items: [{
                text: 'My Files',
                icon: 'mdi-folder'
            },
            {
                text: 'Shared with me',
                icon: 'mdi-account-multiple'
            },
            {
                text: 'Starred',
                icon: 'mdi-star'
            },
            {
                text: 'Recent',
                icon: 'mdi-history'
            },
            {
                text: 'Offline',
                icon: 'mdi-check-circle'
            },
            {
                text: 'Uploads',
                icon: 'mdi-upload'
            },
            {
                text: 'Backups',
                icon: 'mdi-cloud-upload'
            },
        ],
    }),

    methods: {
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
    },

    created() {
        // this.playSound()
        eventBus.$on("alertRequest", data => {
            this.showalert(data)
        });
        eventBus.$on("errorEvent", data => {
            this.showError(data)
        });
    },
}
</script>

<style>
.topbar .v-toolbar__content,
.topbar .v-toolbar__extension {
    width: 96%;
    margin: 20px 0 0 3vw;
    box-shadow: 0 3px 10px -2px rgb(85 85 85 / 8%), 0 2px 20px 0 rgb(85 85 85 / 6%), 0 1px 30px 0 rgb(85 85 85 / 3%);
    background: #1e1e1e;
    border-radius: 10px;
}

.topbar .v-btn--icon,
.topbar .v-toolbar__title,
.topbar .v-list-item .v-list-item__content,
.topbar .v-icon.v-icon,
.topbar .v-list-item .v-list-item__subtitle,
.topbar .v-list-item .v-list-item__title {
    color: #fff !important;

}

.topbar .v-toolbar__content,
.topbar .v-toolbar__extension {
    max-height: 85px !important;
}

.topbar .theme--light.v-navigation-drawer {
    background-color: #1e1e1e;
    height: 100% !important;
}

.topbar #logo {
    width: 100px;
    border-radius: 50%;
}

.topbar .v-input__control {
    margin-top: 25px;
}

.router-link-exact-active {
    background: #244167 !important;
}
</style>
