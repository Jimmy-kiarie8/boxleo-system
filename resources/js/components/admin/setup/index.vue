<template>
<div class="page-wrapper bg-gra-01 p-t-180 p-b-100 font-poppins" v-loading="loading" element-loading-text="loading..." element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)">
    <div class="wrapper" style="max-width: 880px;">
        <div class="card card-3">
            <div class="card-heading" style="background: url('/storage/sell.jpg')">
                <!-- <img src=""  /> -->
            </div>
            <div>
                <v-stepper v-model="e1">
                    <v-stepper-header>
                        <v-stepper-step :complete="e1 > 1" step="1">
                            Company
                        </v-stepper-step>

                        <v-divider></v-divider>

                        <v-stepper-step :complete="e1 > 2" step="2">
                            Operating Unit
                        </v-stepper-step>

                        <v-divider></v-divider>

                        <v-stepper-step step="3">
                            Zones
                        </v-stepper-step>
                    </v-stepper-header>

                    <v-stepper-items>
                        <v-stepper-content step="1">
                            <v-card class="mb-12">
                                <myCompany :setup_details="setup_details" />
                            </v-card>

                            <v-btn color="primary" @click="goToSTep2">
                                Continue
                            </v-btn>

                        </v-stepper-content>

                        <v-stepper-content step="2">
                            <v-card class="mb-12">
                                <operatingUnit :setup_details="setup_details" />
                            </v-card>

                            <v-btn color="primary" @click="goToSTep3">
                                Next
                            </v-btn>

                            <v-btn text @click="e1 = 1">
                                Back
                            </v-btn>
                        </v-stepper-content>

                        <v-stepper-content step="3">
                            <v-card class="mb-12">
                                <myZones :setup_details="setup_details" />
                            </v-card>

                            <v-btn color="primary" @click="finish">
                                Finish
                            </v-btn>

                            <v-btn text @click="e1 = 2">
                                Back
                            </v-btn>
                        </v-stepper-content>
                    </v-stepper-items>
                </v-stepper>

            </div>
        </div>
    </div>
</div>
</template>

<script>
import operatingUnit from "./operatingunit/index";
import myCompany from "./company/edit";
import myZones from "./zones";
import { mapState } from 'vuex';
export default {
    components: {
        operatingUnit,
        myCompany,
        myZones
    },
    data() {
        return {
            e1: 1,
            // page_loader: true,
            setup_details: []
        }
    },

    methods: {
        getCountries() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload);
        },
        goToSTep2() {
            // this.e1 = 2
            eventBus.$emit('companyCreateEvent')
        },
        goToSTep3() {
            // this.e1 = 3
            eventBus.$emit('operatingCreateEvent')
        },
        finish() {
            eventBus.$emit('zoneCreateEvent')
        }
    },
    created() {
        eventBus.$on('nextStepEvent', data => {
            this.e1 += 1
        })

        eventBus.$on('finishEvent', data => {
            window.location.replace("/new_signin")
        })
    },
    mounted() {
        this.getCountries();
    },
    computed: {
        ...mapState(['loading'])
    },
}
</script>

<style scoped>
.v-stepper__content {
    padding: 0 !important;
}
</style>
