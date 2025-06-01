<template>
<div style="width:80%;margin: auto;">
    <div>
    <!-- {{  parseFloat(this.form.plan.amount * this.currencies.rates.USD).toFixed(1) }} -->
    <template>
        <v-card class="overflow-hidden">
            <v-app-bar absolute color="#ffffff" dark shrink-on-scroll prominent fade-img-on-scroll scroll-target="#scrolling-techniques-3" style="height: 260px !important;" v-if="!paybutton_clicked">
                <!-- <template v-slot:img="{ props }">
                    <v-img v-bind="props" gradient="to top right, rgba(100,115,201,.7), rgba(25,32,72,.7)"></v-img>
                </template> -->
                <myPlan :plan="form.plan" :tenant="tenant" :payment_total="payment_total" :form="form" v-if="form.plan" />

                <div class="text-center" v-else style="margin: auto;">
                    <h3>No plan selected</h3>
                    <p style="color: #000">Please choose the plan that fits your business</p>
                    <!-- <v-btn color="primary" rounded @click="openCreate">Create Product</v-btn> -->
                    <!-- <v-spacer></v-spacer> -->
                    <v-btn color="primary" rounded @click="upgrade = true">Upgrade</v-btn>
                </div>
                <!-- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore id animi ullam quasi vel odio excepturi exercitationem omnis fugiat, tempore similique, ipsum sit eveniet aut officiis! Molestiae cumque consequatur voluptatum. -->
            </v-app-bar>
            <v-sheet id="scrolling-techniques-3" class="overflow-y-auto">
                <v-container style="">
                    <v-stepper v-model="e6" vertical style="margin-top: 250px">
                        <v-stepper-step :complete="e6 > 1" step="1">
                            Confirm plan
                            <small>Plan upgrade</small>
                        </v-stepper-step>

                        <v-stepper-content step="1" v-if="!paybutton_clicked">
                            <!-- <v-card class="mb-12" style="padding: 20px"> -->
                            <!-- {{ tenant.subscriber }} -->
                            <!-- <div v-if="tenant.subscriber && !upgrade">
                            <myPlan :plan="tenant.subscriber.plan" />
                        </div> -->
                            <div>
                                <myPricing :plans="plans" :domain="domain" :form="form" v-if="upgrade" />
                                <!-- <myPlan :plan="form.plan" /> -->
                            </div>
                            <!-- </v-card> -->
                            <v-btn color="primary" @click="goTo2">
                                Continue
                            </v-btn>
                            <!-- <v-btn text>
                                Cancel
                            </v-btn> -->
                        </v-stepper-content>

                        <v-stepper-step :complete="e6 > 2" step="2">
                            Plan Details
                        </v-stepper-step>

                        <v-stepper-content step="2"  v-if="!paybutton_clicked">
                            <v-card color="" class="mb-12">

                                <div class="container" style="padding: 30px 0;">
                                    <!-- <div class="container" v-if="form.plan"> -->
                                    <div class="row text-center">
                                        <div class="w-100">
                                            <v-layout row wrap>
                                                <v-flex sm3>
                                                    <h4>Selected Plan</h4>
                                                </v-flex>
                                                <v-flex sm3>
                                                    <h4>{{ form.plan.plan }}</h4>
                                                </v-flex>
                                                <v-flex sm3>
                                                    <h4>K{{ payment_total }}</h4>
                                                </v-flex>
                                            </v-layout>
                                            <v-divider></v-divider>

                                            <table class="table table-responsive table-hover table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Addon</th>
                                                        <th scope="col">Count</th>
                                                        <th scope="col">Total charges</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(addon, index) in addons" :key="addon.id">
                                                        <th scope="row">{{ index + 1 }}</th>
                                                        <td>$ {{ addon.amount }}/{{ addon.addon }}/month</td>
                                                        <td>
                                                            <el-input placeholder="Please input" v-model="addon.count" type="number" min="0"></el-input>
                                                        </td>
                                                        <td>{{ parseFloat(addon.amount) *  parseInt(addon.count) }}</td>
                                                    </tr>
                                                </tbody>

                                                <tfoot>
                                                    <th colspan="3">Adon charges per month</th>
                                                    <th>{{ addon_charge }}</th>
                                                </tfoot>
                                                <!-- <tfoot>
                                                    <th colspan="3">Amount to be paid per month</th>
                                                    <th>{{ addon_charge }}</th>
                                                </tfoot> -->
                                            </table>
                                            <!-- 
                                            <v-layout row wrap v-for="addon in addons" :key="addon.id">
                                                <v-flex sm4>
                                                    <p>$ {{ addon.amount }}/{{ addon.addon }}/month</p>
                                                </v-flex>
                                                <v-flex sm4>
                                                    <el-input placeholder="Please input" v-model="addon.count" type="number" min="0"></el-input>
                                                </v-flex>

                                                <v-flex sm4>
                                                    <b>{{ parseFloat(addon.amount) *  parseInt(addon.count) }}</b>
                                                </v-flex>

                                            </v-layout> -->

                                            <!-- <v-layout row wrap>
                                                <v-flex sm6></v-flex>
                                                <v-flex sm3>
                                                    <h6>Amount to be paid per month </h6>
                                                </v-flex>
                                                <v-flex sm3>
                                                    <h6>{{ addon_charge }} </h6>
                                                </v-flex>

                                            </v-layout> -->
                                            <v-divider></v-divider>

                                            <v-layout row wrap>
                                                <v-flex sm6></v-flex>
                                                <v-flex sm3>
                                                    <h5>Total amount to be paid per month </h5>
                                                </v-flex>
                                                <v-flex sm3>
                                                    <h5>{{ payment_total }} </h5>
                                                </v-flex>
                                            </v-layout>

                                            <!-- <v-btn small elevation="4" color="primary" @click="subscription">Subscription</v-btn> -->

                                        </div>
                                    </div>
                                </div>
                            </v-card>
                            <v-btn text @click="e6 = 1" color="primary">
                                <v-icon>mdi-arrow-left-circle-outline</v-icon>
                                Back
                            </v-btn>
                            <v-btn color="primary" @click="goTo3">
                                Continue
                            </v-btn>
                        </v-stepper-content>

                        <v-stepper-step :complete="e6 > 3" step="3">
                            Checkout
                        </v-stepper-step>
                        <v-stepper-content step="3">
                            <v-card color="" class="mb-12">

                                <div class="container">
                                    <!-- <div class="container" v-if="form.plan"> -->
                                    <div class="row text-center">
                                        <div class="w-100">
                                            <div class="mx-auto w-50" ref="paypal"></div>
                                        </div>
                                    </div>
                                </div>
                            </v-card>
                            <v-btn text @click="e6 = 2" color="primary">
                                <v-icon>mdi-arrow-left-circle-outline</v-icon>
                                Back
                            </v-btn>
                        </v-stepper-content>

                    </v-stepper>
                </v-container>
            </v-sheet>
        </v-card>
    </template>

</div>
</div>
</template>

<script>
import myPricing from "./pricing";
import myPlan from "./plan";
import {
    mapState
} from 'vuex';
export default {
    props: ['plans', 'domain', 'tenant'],
    components: {
        myPricing,
        myPlan
    },
    data() {
        return {
            live_client_id: process.env.PAYPAL_LIVE_CLIENT_ID,
            sandbox_client_id: process.env.MIX_PAYPAL_SANDBOX_CLIENT_ID,
            paypal_mode: process.env.MIX_PAYPAL_MODE,
            e6: 1,
            addon: {},
            upgrade: false,
            loaded: false,
            showpaypal: false,
            paybutton_clicked: false,
            loadding: false,
            paidFor: false,
            product: {
                price: 3.99,
                description: "backlink from"
            },
            form: {
                plan: {}
            },
            prefer_plan: null,
            currencies: {}
        }
    },
    created() {
        eventBus.$on('upgradeEvent', data => {
            this.e6 = 1,
                this.upgrade = true
        });

        eventBus.$on('upgradePlanEvent', data => {
            // this.upgrade = true
            this.form.plan = data

        });
        eventBus.$on('checkoutEvent', data => {
            this.goTo3()
        });
    },

    mounted: function () {
        this.getCurrency()
        this.getAddons()
        this.form.plan = this.tenant.subscriber.tenant_plan
        const script = document.createElement("script");
        // Live logixsaas

        if(this.paypal_mode === 'sandbox') {
            script.src = "https://www.paypal.com/sdk/js?client-id=" + this.sandbox_client_id;
        } else {
            script.src = "https://www.paypal.com/sdk/js?client-id=" + this.live_client_id;
        }
        script.addEventListener("load", this.setLoaded);
        document.body.appendChild(script);
    },
    methods: {
        setLoaded: function (resp) {
            this.loaded = true;
            window.paypal
                .Buttons({
                    createOrder: (data, actions) => {
                        // this.paybutton_clicked = true

                        return actions.order.create({
                            purchase_units: [{
                                description: this.form.plan.description,
                                amount: {
                                    currency_code: "USD",
                                    value: parseFloat(this.payment_total).toFixed(1)
                                    //  value: this.form.plan.amount
                                    // value: parseFloat(this.form.plan.amount * this.currencies.rates.USD).toFixed(1)
                                    // value: 1
                                    // value: parseFloat(this.payment_total * this.currencies.rates.USD).toFixed(1)
                                }
                            }]
                        });
                    },
                    onApprove: async (data, actions, resp) => {
                        // console.log('data :' + data, 'actions :' + actions, 'resp :' + resp);
                        this.loadding = true;
                        const order = await actions.order.capture();
                        this.data;
                        this.paidFor = true;
                        this.loadding = false;

                        this.subscription(order)
                        // window.location.href = "./upgrade/" + this.resp;
                    },
                    onError: err => {
                        this.paybutton_clicked = false
                        console.log(err);
                    }
                })
                .render(this.$refs.paypal);
        },
        subscription(data) {
            // console.log(data);
            this.form.data = data
            this.form.domain = this.domain
            this.form.addons = this.addons
            axios.post('renew', this.form).then((response) => {
                // console.log(response);
                window.location.href = '/thankyou-payment/' + this.tenant.id;
            }).catch((error) => {
                // console.log(error)
            })
        },
        submitDomain() {
            let data = new FormData();
            data.append('title', this.form.title);
            data.append('url', this.form.url);
            data.append('email', this.form.email);
            data.append('shortdescription', this.form.shortdescription);
            data.append('description', this.form.description);
            data.append('category_id', this.form.category_id);
            data.append('subcategory_id', this.form.subcategory_id);
            if (document.getElementById('img').files[0]) {
                data.append('img', document.getElementById('img').files[0])
            };
            axios.post('/domain', data)
                .then((response) => {
                    this.form.reset();
                    this.showpaypal = true;
                    this.resp = response.data;
                })
                .catch(error => this.form.errors.record(error.response.data));
        },
        goTo2() {
            this.e6 = 2
        },
        goTo3() {
            this.e6 = 3
        },
        getCurrency() {
            axios.get('currencies').then((response) => {
                this.currencies = response.data
            })
        },
        getAddons() {
            var data = {
                model: 'addon/' + this.domain,
                update: 'updateAddons'
            }
            this.$store.dispatch('getItems', data).then((response) => {

            })
        }
    },
    computed: {
        ...mapState(['addons']),

        addon_charge() {
            var total = 0
            this.addons.forEach(element => {

                if (!element.count) {
                    element.count = 0
                }
                total += parseFloat(element.amount) * parseInt(element.count)
            });
            return total
        },
        payment_total() {
            // return 1;

            if (this.form.anual) {
                return this.addon_charge + parseInt(this.form.plan.amount) * 12 * 99/100
            } else {
                return this.addon_charge + parseInt(this.form.plan.amount)
            }
        }
    },

}
</script>

<style >
label {
    color: #000 !important;
}
.v-input--selection-controls.v-input {
    margin-left: 100px;
}
</style>
