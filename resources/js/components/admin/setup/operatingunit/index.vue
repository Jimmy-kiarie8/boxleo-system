<template>
<div>
    <v-card-text>
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex sm12 v-if="ous.length > 0">
                    <v-flex sm12>
                        <label for="">Ou</label>
                        <el-select v-model="form.ou_select" clearable placeholder="Select operating unit" style="width:100%" @change="update_ou" value-key="id">
                            <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item">
                            </el-option>
                        </el-select>
                        <small class="has-text-danger" v-if="errors.ou_id">{{ errors.ou_id[0] }}</small>
                    </v-flex>
                </v-flex>

                <v-flex sm12>
                    <label for="">Code</label>
                    <el-input placeholder="HQ" v-model="form.ou_code"></el-input>
                    <small class="has-text-danger" v-if="errors.ou_code">{{ errors.ou_code[0] }}</small>
                </v-flex>
                <v-flex sm12 id="select">
                    <label for="">Operating Unit</label>
                    <el-input placeholder="" v-model="form.ou"></el-input>
                    <small class="has-text-danger" v-if="errors.ou">{{ errors.ou[0] }}</small>
                    <!-- <country-select v-model="form.ou" :country="form.ou" topCountry="KE" /> -->
                </v-flex>
                <v-flex sm12>
                    <label for="">Currency code</label>
                    <el-input placeholder="USD" v-model="form.currency_code"></el-input>
                    <small class="has-text-danger" v-if="errors.currency_code">{{ errors.currency_code[0] }}</small>
                </v-flex>
            </v-layout>
        </v-container>
    </v-card-text>
</div>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['setup_details'],
    data: () => ({
        dialog: false,
        loading: false,
        form: {
            ou_select: null
        },
    }),
    created() {
        eventBus.$on("operatingCreateEvent", data => {
            this.save()
        });
    },

    methods: {
        save() {
            var payload = {
                model: 'ous',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("nextStepEvent")
                    eventBus.$emit("ouEvent", response.data.id)
                    this.setup_details.push(response.data)
                });
        },
        update_ou(ou) {
            console.log(ou);
            this.form = ou
        },
        close() {
            this.dialog = false;
        },
    },

    computed: {
        ...mapState(['ous', 'errors'])
    },
};
</script>
