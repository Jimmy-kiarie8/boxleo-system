<template>
    <v-app id="inspire" class="topbar">
        <v-system-bar window color="red" dark v-if="!isOnline">
            <v-icon>mdi-wifi-off</v-icon>
            <span>No Internet Connection</span>
            <v-spacer></v-spacer>
            <v-icon>mdi-wifi-off</v-icon>
        </v-system-bar>
        <v-app-bar app color="dark" elevation="0">
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

            <v-toolbar-title>{{ user.company.name }} <b v-if="!user.is_vendor">{{ user.ou.ou }}</b></v-toolbar-title>

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
            <mySearch />

            <v-spacer></v-spacer>
            <!--      <div v-if="user.can['Manage Billings']">
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
                    <a :href="app_url + '/upgrade?domain=' + tenant.enc_domain" target="_blank" style="color: #fff">Make payment</a>
                </v-btn>
            </p>
        </div> -->
            <v-spacer></v-spacer>
            <Logout :user="user" />
            <myNotifications />
            <!-- <v-btn icon>
            <v-icon>mdi-bell</v-icon>
        </v-btn> -->
        </v-app-bar>

        <v-navigation-drawer v-model="drawer" app>
            <v-sheet color="theme--dark" class="pa-4" style="text-align: center">
                <img :src="user.company.logo" alt="" id="logo">

                <div class="subheading">{{ user.name }}</div>
                <div class="body-1">{{ user.email }}</div>
            </v-sheet>

            <v-divider></v-divider>
            <v-list nav>
                <v-list-group prepend-icon="mdi-view-dashboard"
                    v-if="user.roles[0].name == 'Super admin' || user.roles[0].name == 'Admin'">
                    <template v-slot:activator>
                        <v-list-item-title>Dashboard</v-list-item-title>
                    </template>
                    <router-link to="/" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-view-dashboard</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Dashboard</div>
                        </div>
                    </router-link>
                    <router-link to="/call-center" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-phone-check</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Call center Dashboard</div>
                        </div>
                    </router-link>
                    <router-link to="/agent-dashboard" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-bike-fast</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Agent Dashboard</div>
                        </div>
                    </router-link>
                </v-list-group>

                <router-link to="/agent-dashboard" class="v-list-item v-list-item--link theme--light"
                    v-else-if="user.roles[0].name == 'Agent'">
                    <div class="v-list__tile__action">
                        <v-icon>mdi-bike-fast</v-icon>
                    </div>
                    <div class="v-list-item__content">
                        <div class="v-list-item__title">Agent Dashboard</div>
                    </div>
                </router-link>

                <router-link to="/call-center" class="v-list-item v-list-item--link theme--light"
                    v-else-if="user.roles[0].name == 'Call center' || user.roles[0].name == 'Follow UP'">
                    <div class="v-list__tile__action">
                        <v-icon>mdi-phone-check</v-icon>
                    </div>
                    <div class="v-list-item__content">
                        <div class="v-list-item__title">Call center Dashboard</div>
                    </div>
                </router-link>

                <router-link to="/" class="v-list-item v-list-item--link theme--light" v-else>
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
                    <router-link to="/sales" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Order view'] && user.roles[0].name != 'Call center'">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-cart</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Orders</div>
                        </div>
                    </router-link>
                    <router-link to="/callcentre" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Order view'] && (user.roles[0].name == 'Call center' || user.roles[0].name == 'Follow UP')">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-phone</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Call center</div>
                        </div>
                    </router-link>
                    <router-link to="/invoices" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Invoices view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-receipt</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Invoices</div>
                        </div>
                    </router-link>
                    <!--<router-link to="/tracking" class="v-list-item v-list-item--link theme--light" v-if="user.can['Order view']">
                    <div class="v-list__tile__action">
                        <v-icon>mdi-progress-check</v-icon>
                    </div>
                    <div class="v-list-item__content">
                        <div class="v-list-item__title">Orders Progress Tracking</div>
                    </div>
                </router-link> -->
                    <router-link to="/dispatch" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Order dispatch'] && user.roles[0].name != 'Agent'">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-send</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Dispatch</div>
                        </div>
                    </router-link>

                    <router-link to="/status_update" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Order update delivered or returned'] || user.can['Update Awaiting Return']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-moped</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Bulk update</div>
                        </div>
                    </router-link>
                    <router-link to="/dispatch_filter" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Order dispatch']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-moped</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Dispatch List</div>
                        </div>
                    </router-link>
                    <router-link to="/mobile" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Order update delivered or returned']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-cellphone-sound</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Delivery App Updates</div>
                        </div>
                    </router-link>

                    <router-link to="/transactions" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Mpesa transactions']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-cash-100</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Mpesa transactions</div>
                        </div>
                    </router-link>
                </v-list-group>

                <router-link to="/returns" class="v-list-item v-list-item--link theme--light"
                    v-if="user.can['Return view']">
                    <div class="v-list__tile__action">
                        <v-icon>mdi-clipboard-arrow-left</v-icon>
                    </div>
                    <div class="v-list-item__content">
                        <div class="v-list-item__title">Returns</div>
                    </div>
                </router-link>

                <router-link to="/clients" class="v-list-item v-list-item--link theme--light"
                    v-if="user.can['Clients view']">
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
                    <router-link to="/products" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Products view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-basket</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Products</div>
                        </div>
                    </router-link>

                    <router-link to="/shipments" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-check-underline-circle</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Shipment Request</div>
                        </div>
                    </router-link>
                </v-list-group>

                <v-list-group prepend-icon="mdi-warehouse" v-if="user.can['Manage inventory'] && !user.is_vendor">
                    <template v-slot:activator>
                        <v-list-item-title>Warehouse</v-list-item-title>
                    </template>
                    <a href="/warehouse" target="_blank" class="v-list-item v-list-item--link theme--light">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-warehouse</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Warehouse Managent</div>
                        </div>
                    </a>

                    <router-link to="/warehouse" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Warehouse view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-warehouse</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Warehouses</div>
                        </div>
                    </router-link>
                </v-list-group>

                <router-link to="/vendors" class="v-list-item v-list-item--link theme--light"
                    v-if="user.can['Vendors view']">
                    <div class="v-list__tile__action">
                        <v-icon>mdi-account-multiple</v-icon>
                    </div>
                    <div class="v-list-item__content">
                        <div class="v-list-item__title">Vendors</div>
                    </div>
                </router-link>

                <router-link to="/clients" class="v-list-item v-list-item--link theme--light"
                    v-if="user.can['Client create']">
                    <div class="v-list__tile__action">
                        <v-icon>mdi-account</v-icon>
                    </div>
                    <div class="v-list-item__content">
                        <div class="v-list-item__title">Customers</div>
                    </div>
                </router-link>
                <v-list-group prepend-icon="mdi-account-circle" v-if="user.can['Manage user']">
                    <template v-slot:activator>
                        <v-list-item-title>Users</v-list-item-title>
                    </template>
                    <router-link to="/users" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['User view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-account-circle</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Users</div>
                        </div>
                    </router-link>
                    <router-link to="/roles" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Role view']">
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
                    <router-link to="/api" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Manage API']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-domain</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Api integrations</div>
                        </div>
                    </router-link>
                    <router-link to="/marketplace" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Manage Marketplace']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-shopping</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Marketplace</div>
                        </div>
                    </router-link>
                    <router-link to="/riders" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Rider view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-bike</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Riders</div>
                        </div>
                    </router-link>
                    <router-link to="/status" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Status view']">
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
                    <router-link to="/services" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Service view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-face-agent</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Services</div>
                        </div>
                    </router-link>
                    <router-link to="/zones" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Zone view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-zodiac-gemini</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Zones</div>
                        </div>
                    </router-link>
                    <router-link to="/zone-sheets" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Zone view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-google-spreadsheet</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Zone Sheets</div>
                        </div>
                    </router-link>
                </v-list-group>

                <v-list-group prepend-icon="mdi-boom-gate-outline" v-if="user.can['Manage automation']">
                    <template v-slot:activator>
                        <v-list-item-title>Automation</v-list-item-title>
                    </template>
                    <router-link to="/automation" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Automation view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-coffee-maker</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Automation</div>
                        </div>
                    </router-link>
                    <router-link to="/sms_template" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Sms view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-message-processing</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Sms template</div>
                        </div>
                    </router-link>
                    <router-link to="/mails" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Email view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-email</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Email template</div>
                        </div>
                    </router-link>
                </v-list-group>

                <v-list-group prepend-icon="mdi-book" v-if="user.can['Manage reports'] || user.can['Report view']">
                    <template v-slot:activator>
                        <v-list-item-title>Reports</v-list-item-title>
                    </template>
                    <router-link to="/report" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Report view']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-file-chart</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Report</div>
                        </div>
                    </router-link>
                    <router-link to="/analysis" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Report view'] && !user.is_vendor">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-chart-areaspline</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Report analysis</div>
                        </div>
                    </router-link>
                    <router-link to="/custom_report" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Custom Report'] && !user.is_vendor">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-book-plus</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Custom Report</div>
                        </div>
                    </router-link>
                    <router-link to="/remittance" class="v-list-item v-list-item--link theme--light"
                        v-if="user.can['Remittance']">
                        <div class="v-list__tile__action">
                            <v-icon>mdi-chart-timeline-variant-shimmer</v-icon>
                        </div>
                        <div class="v-list-item__content">
                            <div class="v-list-item__title">Remittance</div>
                        </div>
                    </router-link>
                </v-list-group>

                <router-link to="/packages" class="v-list-item v-list-item--link theme--light"
                    v-if="user.can['Print waybills']">
                    <div class="v-list__tile__action">
                        <v-icon>mdi-gift</v-icon>
                    </div>
                    <div class="v-list-item__content">
                        <div class="v-list-item__title">Waybills</div>
                    </div>
                </router-link>

            </v-list>
        </v-navigation-drawer>

    </v-app>
</template>

<script>
import mySearch from './search.vue';
import Logout from "./Logout";
import myNotifications from "./notifications";

export default {
    components: {
        mySearch,
        Logout, myNotifications
    },
    props: ['user', 'tenant'],
    data: () => ({
        app_url: process.env.MIX_APP_URL,
        selectedItem: 0,
        drawer: true,
        isOnline: true,
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

        setupOnlineListener() {
            window.addEventListener('online', this.updateOnlineStatus);
            window.addEventListener('offline', this.updateOnlineStatus);
            this.updateOnlineStatus(); // Initial check
        },
        removeOnlineListener() {
            window.removeEventListener('online', this.updateOnlineStatus);
            window.removeEventListener('offline', this.updateOnlineStatus);
        },
        updateOnlineStatus() {
            this.isOnline = navigator.onLine;
        }
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

    mounted() {
        this.setupOnlineListener();
    },
    beforeUnmount() {
        this.removeOnlineListener();
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
