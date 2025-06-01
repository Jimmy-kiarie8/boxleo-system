<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
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
                        <v-flex xs12 sm12>
                            <label for="">Warehouse Code</label>
                            <el-input placeholder="HQ" v-model="form.code"></el-input>
                            <small class="has-text-danger" v-if="errors.code">{{ errors.code[0] }}</small>
                        </v-flex>
                        <v-flex xs12 sm12>
                            <label for="">Warehouse Phone No.*</label>
                            <vue-tel-input v-model="form.phone" required enabledOuCode autocomplete validCharactersOnly @ou-changed="ou_change"></vue-tel-input>
                            <small class="has-text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small>
                        </v-flex>
                        <v-flex xs12 sm12>
                            <label for="">Location</label>
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
import { mapState } from 'vuex';
export default {
    data: () => ({
        dialog: false,
        loading: false,
        form: {},
    }),
    created() {
        eventBus.$on("openEditWarehouse", data => {
            this.dialog = true;
            this.form = data
        });
    },

    methods: {
        save() {

            var payload = {
                model: 'warehouses',
                data: this.form,
                id: this.form.id,
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("warehouseEvent")
                });
        },
        close() {
            this.dialog = false;
        }
    },
    computed: {
        ...mapState(['warehouses', 'errors'])
    },
};
</script>
