<template>
<div style="width:60%;margin: auto">
    <myPlan :plan="form.plan" />
    <v-stepper v-model="e6" vertical>
        <v-stepper-step :complete="e6 > 1" step="1">
            Select an app
            <small>Summarize if needed</small>
        </v-stepper-step>

        <v-stepper-content step="1">
            <!-- <v-card class="mb-12" style="padding: 20px"> -->
            <!-- {{ tenant.subscriber }} -->
            <!-- <div v-if="tenant.subscriber && !upgrade">
                <myPlan :plan="tenant.subscriber.plan" />
            </div> -->
            <div>
                <myPricing :plans="plans" :domain="domain" v-if="upgrade" />
                <!-- <myPlan :plan="form.plan" /> -->
            </div>
            <!-- </v-card> -->
            <v-btn color="primary" @click="e6 = 2">
                Continue
            </v-btn>
            <!-- <v-btn text>
                Cancel
            </v-btn> -->
        </v-stepper-content>

        <v-stepper-step :complete="e6 > 2" step="2">
            Configure analytics for this app
        </v-stepper-step>

        <v-stepper-content step="2">
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
            <v-btn text @click="e6 = 1" color="primary">
                <v-icon>mdi-arrow-left-circle-outline</v-icon>
                Back
            </v-btn>
        </v-stepper-content>

    </v-stepper>
</div>
</template>

<script>
import myPricing from "./pricing";
import myPlan from "./plan";
export default {
    props: ['plans', 'domain', 'tenant'],
    components: {
        myPricing,
        myPlan
    },
    data() {
        return {
            e6: 1,
            upgrade: false,
            loaded: false,
            showpaypal: false,
            loadding: false,
            paidFor: false,
            product: {
                price: 3.99,
                description: "backlink from"
            },
            form: {
                plan: {}
            },
            prefer_plan: null
        }
    },
    created() {
        this.form.plan = this.tenant.subscriber.plan
        eventBus.$on('upgradeEvent', data => {
            this.upgrade = true
        });

        eventBus.$on('upgradePlanEvent', data => {
            // this.upgrade = true
            this.form.plan = data

        });
    },

    mounted: function () {
        const script = document.createElement("script");
        script.src =
            "https://www.paypal.com/sdk/js?client-id=Clientid";
        script.addEventListener("load", this.setLoaded);
        document.body.appendChild(script);
    },
    methods: {
        setLoaded: function (resp) {
            this.loaded = true;
            window.paypal
                .Buttons({
                    createOrder: (data, actions) => {
                        return actions.order.create({
                            purchase_units: [{
                                description: this.form.plan.description,
                                amount: {
                                    currency_code: "USD",
                                    //  value: this.form.plan.amount
                                    value: 1
                                }
                            }]
                        });
                    },
                    onApprove: async (data, actions, resp) => {
                        console.log('data :' + data, 'actions :' + actions, 'resp :' + resp);
                        this.loadding = true;
                        const order = await actions.order.capture();
                        this.data;
                        console.log('******************');
                        console.log(order);
                        console.log('******************');
                        this.paidFor = true;
                        this.loadding = false;

                        this.subscription(order)
                        // window.location.href = "./upgrade/" + this.resp;
                    },
                    onError: err => {
                        console.log(err);
                    }
                })
                .render(this.$refs.paypal);
        },
        subscription(data) {
            this.form.data = data
            this.form.domain = this.domain
            axios.post('renew', this.form).then((response) => {
                console.log(response);
                // window.location.href = $url;
            }).catch((error) => {
                console.log(error)
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
    },
}
</script>
