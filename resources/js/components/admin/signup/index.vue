<template>
<div v-loading="loading" :element-loading-text="message" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)" style="width: 100%;">
    <div v-if="account_created">
        <myAccount :domain="domain" :encrypt_domain="encrypt_domain" :sub_plan="sub_plan" />
    </div>
    <div v-else>
        <div class="page-wrapper bg-gra-01 p-t-180 p-b-100 font-poppins">
            <div class="wrapper wrapper--w780">
                <div class="card card-3">
                    <div class="card-heading" style="background: url(/home/technology.jpg);    background-position: center;">

                    </div>
                    <div class="card-body" style="text-align: center;">
                        <mySignup v-show="step == 0" :form="form" />
                        <myDomain v-show="step == 1" :form="form" />
                        <div class="p-t-10">
                            <v-spacer></v-spacer>
                            <!-- <v-btn color="primary" text rounded outlined>
                                Next
                            </v-btn> -->
                            <v-btn class="ma-2" outlined color="white" rounded @click="step_(1)" v-if="step == 0" :loading="loading" style="color: #fff;">
                                <v-icon>mdi-chevron-right</v-icon>
                                Next
                            </v-btn>
                            <v-btn class="ma-2" outlined color="white" rounded @click="create" v-if="step == 1" :disabled="loading" style="color: #fff;">
                                <v-icon>mdi-check-all</v-icon>
                                Finish
                            </v-btn>
                            <v-btn class="ma-2" outlined color="white" rounded @click="step_(-1)" v-if="step == 1" style="color: #fff;">
                                <v-icon>mdi-chevron-left</v-icon>
                                Back
                            </v-btn>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import {
    mapState
} from 'vuex';
import mySignup from "./signup";
import myDomain from "./domain";
import myAccount from "../created";
export default {
    props: ['plan'],
    components: {
        mySignup,
        myDomain,
        myAccount
    },
    data() {
        return {
            form: {},
            loading: false,
            message: 'Loading',
            account_created: false,
            central_domain: process.env.MIX_CENTRAL_DOMAIN,
            step: 0,
            domain: null,
            sub_plan: {},
            encrypt_domain: null
        }
    },

    methods: {
        validate(e) {
            // console.log(e);
            // this.form.subscription = this.plan
            var payload = {
                data: this.form,
                model: 'validate/' + this.step + '?plan=' + this.plan
            }
            this.loading = true
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    this.loading = false
                    this.step += e
                    console.log(response);
                }).catch((error) => {
                    console.log(error);
                    this.loading = false
                });
        },
        getPlan() {
            axios.get('plans/'+this.plan).then((res) => {
                this.sub_plan = res.data
            })
        },

        step_(e) {
            if (e == -1) {
                this.step += e
            } else {
                this.validate(e)
            }
        },
        create() {
            this.form.subscription = this.plan
            var payload = {
                data: this.form,
                model: 'account' + '?plan=' + this.plan
            }
            this.loading = true
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // this.loading = false
                    console.log(response);
                }).catch((error) => {
                    console.log(error);
                    this.loading = false
                });
        },
        user_account(data) {
            var payload = {
                data: data,
                model: 'user_account'
            }
            this.loading = true
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    console.log(response);
                }).catch((error) => {
                    console.log(error);
                });
        },

        detEncrypt() {
            axios.get('encrypt_domain/' + this.domain).then((res) => {
                this.encrypt_domain = res.data
            })
        }
    },

    mounted () {
        this.getPlan();
    },
    created() {
        eventBus.$on('CreatingAccountEvent', data => {
            if (data.step == 1) {
                this.domain = data.domain
            }
            // console.log(data);
            this.message = data.message

            if (data.step == 6) {
                this.user_account(data)
                this.loading = false
                this.account_created = true
                this.detEncrypt()
            }
        });
    },
    computed: {
        ...mapState(['errors'])
    },
}
</script>

<style scoped>
.has-text-danger {
    color: red;
}
</style>
