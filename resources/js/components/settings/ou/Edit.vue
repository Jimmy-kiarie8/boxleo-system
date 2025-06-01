<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="700px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Ou</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                   
                    <v-layout row wrap>
                        <v-flex sm6 id="select">
                            <label for="">Operating Unit</label>
                            <el-input placeholder="HQ" v-model="form.ou"></el-input>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Ou code</label>
                            <el-input placeholder="HQ" v-model="form.ou_code"></el-input>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Currency code</label>
                            <el-input placeholder="USD" v-model="form.currency_code"></el-input>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Call centre Phone number</label>
                            <el-input placeholder="+254..." v-model="form.phone"></el-input>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Phone Code</label>
                            <el-input placeholder="254" v-model="form.phone_code"></el-input>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Contact Phone</label>
                            <el-input placeholder="254..." v-model="form.ou_phone"></el-input>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Address</label>
                            <el-input placeholder="Nairobi, Kenya" v-model="form.address"></el-input>
                        </v-flex>

                        <v-flex sm12>
                            <label for="">Terms&Conditions</label>
                            <el-input type="textarea" placeholder="Please input" v-model="form.waybill_terms"></el-input>
                        </v-flex>
                    </v-layout>
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
import { mapState } from 'vuex';
export default {
    data: () => ({
        dialog: false,
        loading: false,
        form: {},
    }),
    created() {
        eventBus.$on("openEditOu", data => {
            this.dialog = true;
            this.form = data
        });
    },

    methods: {
        save() {

            var payload = {
                model: 'ous',
                data: this.form,
                id: this.form.id,
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("ouEvent")
                });
        },
        close() {
            this.dialog = false;
        }
    },
    computed: {
        ...mapState(['ous'])
    },
};
</script>
