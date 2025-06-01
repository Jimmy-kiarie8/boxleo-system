<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="400px">
        <v-card v-if="dialog">
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Warehouse</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12 sm12>
                            <label for="">Warehouse</label>
                            <el-input placeholder="HQ" v-model="form.name"></el-input>
                            <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                        </v-flex>
                        <!-- <v-flex sm12>
                            <label for="">Ou</label>
                            <el-select v-model="form.ou_id" clearable placeholder="Ou" style="width: 100%;" @change="ou_filter">
                                <el-option v-for="item in ous" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-flex> -->
                        <v-flex xs12 sm12>
                            <label for="">Warehouse Phone No.*</label>
                            <vue-tel-input v-model="form.phone" required enabledOuCode autocomplete validCharactersOnly></vue-tel-input>
                        </v-flex>

                        <v-flex xs12 sm12>
                            <label for="">Location</label>
                            <!-- <GmapAutocomplete @place_changed="setPlace" class="form-control"></GmapAutocomplete> -->
                            <!-- <place-autocomplete-field v-model="form.location" placeholder="Enter an an address, zipcode, or location" label="Location" name="field1" api-key="AIzaSyCd5Lt1V-a-dC0ZAgHJY6OB3GBcxPgov0E"></place-autocomplete-field> -->
                            <el-input placeholder="HQ" v-model="form.location"></el-input>

                            <small class="has-text-danger" v-if="errors.location">{{ errors.location[0] }}</small>
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
export default {
    data: () => ({
        dialog: false,
        loading: false,
        errors: {},
        form: {},
    }),
    created() {
        eventBus.$on("openEditWarehouse", data => {
            this.dialog = true;
            this.form = data;
        });
    },

    methods: {
        save() {
            this.loading = true;
            axios
                .patch(`warehouses/${this.form.id}`, this.$data.form)
                .then(response => {
                    this.loading = false;
                    // console.log(response);
                    // context.commit('getWarehouses', response.data)
                    this.$store.dispatch('alertEvent', 'Updated')
                    this.close();
                })
                .catch(error => {
                    // console.log(error.response);
                    this.loading = false;
                });
        },
        ou_filter() {},
        close() {
            this.dialog = false;
        },
        setPlace(place) {
            this.form.location = place;
        },
    },
    computed: {
        ous() {
            return this.$store.getters.ous
        },
    },
    mounted() {
        // this.getCountries();
    },
};
</script>
