<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="800px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Company</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex sm5 offset-sm1>

                                <label for="">Company Name</label>
                                <el-input placeholder="name" v-model="form.name"></el-input>
                            </v-flex>
                            <v-flex sm5 offset-sm1>

                                <label for="">Company Email</label>
                                <el-input placeholder="email" v-model="form.email"></el-input>
                            </v-flex>
                            <v-flex sm5 offset-sm1>

                                <label for="">Company Phone</label>
                                <el-input placeholder="+1..." v-model="form.phone"></el-input>
                            </v-flex>
                            <v-flex sm5 offset-sm1>
                                <label for="">Company Website</label>
                                <el-input placeholder="www.123.com" v-model="form.website"></el-input>
                            </v-flex>
                            <v-flex sm5 offset-sm1>
                                <label for="">Company Address</label>
                                <el-input placeholder="www.123.com" v-model="form.address"></el-input>
                            </v-flex>

                            <v-flex sm5 offset-sm1>

                                <label for="">About</label>
                                <el-input type="textarea" placeholder="Please input" v-model="form.about"></el-input>
                            </v-flex>
                            <v-flex sm5 offset-sm1>
                                <label for="">Terms&Conditions</label>
                                <el-input type="textarea" placeholder="Please input" v-model="form.terms"></el-input>
                            </v-flex>
                            <v-flex sm5 offset-sm1>
                                <label for="">Notes</label>
                                <el-input type="textarea" placeholder="Please input" v-model="form.notes"></el-input>
                            </v-flex>

                            <v-flex sm5 offset-sm1 id="select">
                                <label for="">Ou</label>
                                <country-select v-model="form.ou" :country="form.ou" topCountry="KE" />
                            </v-flex>
                            <v-flex sm5 offset-sm1 id="select">
                                <label for="">City</label>
                                <region-select v-model="form.region" :country="form.ou" :region="form.region" />
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="save" :loading="loading" :disabled="loading">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    data: () => ({
        dialog: false,
        form: {}
    }),
    created() {
        eventBus.$on("editCompanyEvent", data => {
            if (this.company) {
                this.form = this.company
            }
            this.dialog = true;
        });
    },

    methods: {
        save() {

            var payload = {
                model: 'company',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // eventBus.$emit("CurrencyEvent")
                });
        },
        close() {
            this.dialog = false;
        }
    },
    computed: {
        ...mapState(['company', 'loading'])
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
