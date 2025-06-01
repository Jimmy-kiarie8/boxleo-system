<template>
<v-card-text>
    <v-container grid-list-md>
        <v-card-text>
            <v-layout row wrap>
                <v-flex sm6>
                    <label for="">Company Name</label>
                    <el-input placeholder="name" v-model="form.name"></el-input>
                    <small class="has-text-danger" v-if="errors.company">{{ errors.company[0] }}</small>
                </v-flex>
                <v-flex sm6>

                    <label for="">Company Email</label>
                    <el-input placeholder="email" v-model="form.email"></el-input>
                </v-flex>
                <v-flex sm6>

                    <label for="">Company Phone</label>
                    <el-input placeholder="+1..." v-model="form.phone"></el-input>
                </v-flex>
                <v-flex sm6>

                    <label for="">Company Website</label>
                    <el-input placeholder="www.123.com" v-model="form.website"></el-input>
                </v-flex>
                <v-flex sm6>

                    <label for="">Company Address</label>
                    <el-input placeholder="19 Belmont Ave Unit" v-model="form.address"></el-input>
                </v-flex>

                <v-flex sm6>

                    <label for="">About</label>
                    <el-input type="textarea" placeholder="Please input" v-model="form.about"></el-input>
                </v-flex>
                <v-flex sm6>
                    <label for="">Terms&Conditions</label>
                    <el-input type="textarea" placeholder="Please input" v-model="form.terms"></el-input>
                </v-flex>
                <v-flex sm6>
                    <label for="">Notes</label>
                    <el-input type="textarea" placeholder="Please input" v-model="form.notes"></el-input>
                </v-flex>

                <v-flex sm6 id="select">
                    <label for="">Ou</label>
                    <country-select v-model="form.ou" :country="form.ou" countryName topCountry="KE" regionName />
                </v-flex>
                <v-flex sm6 id="select">
                    <label for="">City</label>
                    <region-select v-model="form.region" :country="form.ou" :region="form.region" countryName />
                </v-flex>
            </v-layout>
        </v-card-text>
    </v-container>
</v-card-text>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['setup_details'],
    data: () => ({
        dialog: true,
        form: {
            company: '',
            tenant: '',
            email: '',
            phone: '',
            website: '',
            address: '',
            about: '',
            terms: '',
            notes: '',
            ou: '',
            region: ''
        },
        ou_name: true
    }),

    methods: {
        save() {
            var payload = {
                model: 'account',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("nextStepEvent")

                    this.setup_details.push(response.data)
                    // window.location.replace("/login")
                    // eventBus.$emit("CurrencyEvent")
                });
        },
        close() {
            this.dialog = false;
        },

        company_details() {

            var payload = {
                model: 'company_details',
                data: this.form,
            }
            axios.get('company_details')
                .then(response => {
                    this.form = response.data

                    this.tenant_sub();
                    // window.location.replace("/company_details")
                    // eventBus.$emit("CurrencyEvent")
                });
        },

        tenant_sub() {
            axios.get('tenant_sub').then((response) => {
                this.form.tenant = response.data
                this.form.company = response.data.company
            }).catch((error) => {
                console.log(error);
            })
        }
    },
    computed: {
        ...mapState(['company', 'loading', 'errors'])
    },
    mounted() {
        this.company_details();
    },
    created() {
        eventBus.$on('companyCreateEvent', data => {
            this.save()
        });
    },
};
</script>

<style scoped>
#select select {
    -webkit-appearance: none;
    background-color: #FFF;
    background-image: none;
    border-radius: 4px;
    border: 1px solid #DCDFE6;
    box-sizing: border-box;
    color: #606266;
    display: inline-block;
    font-size: inherit;
    height: 40px;
    line-height: 40px;
    outline: 0;
    padding: 0 15px;
    transition: border-color .2s cubic-bezier(.645, .045, .355, 1);
    width: 100%;
}
</style>
