<template>
<div>
    <section class="pricing-two" style="padding: 0">
        <div class="container">
            <div class="section-title color-two text-center">
                <h3 class="sub-title wow pixFadeUp">Pricing Plan</h3>
                <div style="margin: auto">
                    <v-switch v-model="form.anual" label="Anual" color="red" inset></v-switch>

                </div>

                <!-- <h2 class="title wow pixFadeUp" data-wow-delay="0.3s">No Hidden Charges! Choose<br>your Plan.</h2> -->
            </div>
            <!-- <nav class="pricing-tab color-two wow pixFadeUp" data-wow-delay="0.4s"><span class="monthly_tab_title tab-btn">Monthly </span>
                <span class="pricing-tab-switcher"></span>
                <span class="annual_tab_title tab-btn">Annual</span>
            </nav> -->
            <div class="row advanced-pricing-table">
                <div class="col-lg-4" v-for="(plan, key) in plans" :key="plan.id">

                    <div class="pricing-table style-two price-two wow pixFadeLeft" data-wow-delay="0.5s">
                        <!-- <div class="pricing-table style-two price-two wow pixFadeLeft" data-wow-delay="0.5s" v-if="key == 0" :class="(key == 0) ? '' : (key == 1) ? 'color-two' : 'color-three'"> -->
                        <!-- <div class="pricing-table color-two style-two price-two featured wow pixFadeLeft"
                            data-wow-delay="0.7s"  v-if="(key == 0) ? '' : (key == 1) ? 'color-two' : 'color-three'"> -->

                        <!-- <div class="trend" v-if="key == 1">
                            <p>Popular</p>
                        </div> -->
                        <!-- <div class="pricing-table color-three style-two price-two wow pixFadeLeft"
                                data-wow-delay="0.9s" v-else>
                                @endif -->
                        <div class="pricing-header pricing-amount" style="text-align: center">
                            <div class="monthly_price" v-if="form.anual">
                                <h2 class="price">$ {{ plan.amount * 12 * 99/100 }}</h2>
                                <p>per year</p>
                            </div>
                            <div class="monthly_price" v-else>
                                <h2 class="price">$ {{ plan.amount }}</h2>
                                <p>per month</p>
                            </div>
                            <h3 class="price-title">{{ plan.plan }} Plan</h3>
                        </div>

                        <v-expansion-panels class="mb-6">
                            <v-expansion-panel>
                                <v-expansion-panel-header expand-icon="mdi-menu-down">
                                    Features
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>

                                    <ul class="price-feture">
                                        <li class=""><i class="mdi mdi-check-circle"></i>
                                            Users <b>{{ plan.users }}</b></li>
                                        <li> <i class="mdi mdi-check-circle"></i> Client portal <b>{{ plan.portals }}</b>
                                        </li>
                                        <li> <i class="mdi mdi-check-circle"></i> Warehouses <b>{{ plan.warehouses }}</b>
                                        </li>
                                        <li> <i class="mdi mdi-check-circle"></i> ous <b>{{ plan.ous }}</b>
                                        </li>
                                        <li> <i class="mdi mdi-check-circle"></i> Shopify integrations
                                            <b>{{ plan.shopify_integrations }}</b></li>
                                        <li> <i class="mdi mdi-check-circle"></i> Wordpress integrations
                                            <b>{{ plan.wordpress_integrations }}</b></li>
                                        <li> <i class="mdi mdi-check-circle"></i> Api integrations
                                            <b>{{ plan.api_integrations }}</b></li>
                                        <li> <i class="mdi mdi-check-circle"></i> Automations
                                            <b>{{ plan.automations }}</b>
                                        </li>
                                        <li> <i class="mdi mdi-check-circle"></i> Sms <b>{{ plan.sms }}</b></li>
                                        <li> <i class="mdi mdi-check-circle"></i> Email <b>{{ plan.email }}</b></li>
                                        <li> <i class="mdi mdi-check-circle"></i> Shipping Lables <b>{{ plan.lables }}</b>
                                        </li>
                                        <li> <i class="mdi mdi-check-circle"></i> Tracking <b>{{ plan.tracking }}</b></li>
                                        <li><i class="mdi mdi-close-circle"></i> Hotline Support 24/7</li>
                                        <li><i class="mdi mdi-close-circle"></i> No Updates</li>
                                    </ul>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                        <div class="action text-left">
                            <button class="pix-btn btn-outline-two" @click="choose_plan(plan)">Upgrade</button>
                            <!-- <a href="/upgradeplan?plan={{ plan.id }}?domain={{ $domain }}" class="pix-btn btn-outline-two">Upgrade</a> -->
                        </div>

                        <!-- <my-paypal :plan="{{ $plan }}" /> -->

                    </div>
                </div>
                <!-- @endforeach -->
            </div>
        </div>
    </section>
    <!-- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem iure nesciunt nisi dolorem deserunt sunt in ducimus sed alias, placeat quia quod odit, nihil rem hic dolor mollitia aspernatur neque!    {{ Object.keys(form.plan).length }} -->

</div>
</template>

<script>
export default {
    props: ['plans', 'domain', 'form'],
    data() {
        return {
            switch1: true,

            // loaded: false,
            // showpaypal: false,
            // loadding: false,
            // paidFor: false,
            // product: {
            //     price: 3.99,
            //     description: "backlink from"
            // },
            // form: {
            //     plan: {}
            // },
            // prefer_plan: null
        }
    },
    methods: {
        choose_plan(plan) {
            eventBus.$emit('upgradePlanEvent', plan)
            // this.form.plan = plan
        }
    },
}
</script>
