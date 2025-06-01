<template>
<div>
    <div v-if="account_created">
        <myAccount :domain="domain" />
    </div>
    <div v-loading="loading" :element-loading-text="message" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)" style="width: 100%; height: 100vh" v-else>
        <div class="container register">
            <div class="row">
                <div class="col-md-3 register-left">
                    <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt="" />
                    <h3>Welcome</h3>
                    <p>You are 30 seconds away from earning your own money!</p>
                    <input type="submit" name="" value="Login" /><br />
                </div>
                <div class="col-md-9 register-right">
                    <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Login</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h3 class="register-heading">Create New Account</h3>

                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Company</label>
                                        <input id="company" type="text" class="form-control" v-model="form.company" autocomplete="company" autofocus>
                                        <small class="has-text-danger" v-if="errors.company">{{ errors.company[0] }}</small>

                                    </div>
                                    <div class="form-group">
                                        <label for="">Full Name</label>
                                        <input id="name" type="text" class="form-control" v-model="form.name" autocomplete="name" autofocus>
                                        <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input id="email" type="email" class="form-control" v-model="form.email" autocomplete="email" autofocus>
                                        <small class="has-text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <label for="" style="width: 100%">Domain</label>

                                        <input id="id" type="text" class="form-control" v-model="form.id" autocomplete="id" autofocus>
                                        <span class="input-group-text" id="basic-addon2">{{ central_domain }}</span>
                                    </div>
                                        <small class="has-text-danger" v-if="errors.id">{{ errors.id[0] }}</small>
                                    <div class="form-group">
                                        <label for="">Password</label>
                                        <input id="password" type="password" class="form-control" v-model="form.password" autocomplete="password" autofocus>
                                        <small class="has-text-danger" v-if="errors.password">{{ errors.password[0] }}</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Confirm Password</label>
                                        <input id="password-confirm" type="password" class="form-control" v-model="form.password_confirmation" autocomplete="new-password">
                                    </div>
                                    <button class="btnRegister" @click="create">Register</button>
                                    <!-- <input type="submit" class="btnRegister" value="Register" /> -->
                                </div>
                            </div>
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
import myAccount from "./created";
export default {
    props: ['plan'],
    components: {
        myAccount,
    },
    data() {
        return {
            form: {},
            loading: false,
            message: 'Loading',
            account_created: false,
            central_domain: process.env.MIX_CENTRAL_DOMAIN,
            domain: null
        }
    },

    methods: {
        create() {
            this.form.subscription = this.plan
            var payload = {
                data: this.form,
                model: 'account'
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
        }
    },

    created() {
        eventBus.$on('CreatingAccountEvent', data => {
            if (data.step == 1) {
                this.domain = data.domain
            }
            console.log(data);
            this.message = data.message

            if (data.step == 6) {
                this.user_account(data)
                this.loading = false
                this.account_created = true
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
