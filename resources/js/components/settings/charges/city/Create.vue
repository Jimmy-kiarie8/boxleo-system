<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="400px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create City</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm12>
                            <label for="">Zone</label>
                            <el-select v-model="form.zone_id" clearable placeholder="Select a city" style="width:100%">
                                <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.zone_id">{{ errors.zone_id[0] }}</small>
                        </v-flex>
                        <v-flex sm12>
                            <label for="">City</label>
                            <el-input placeholder="" v-model="form.name"></el-input>
                            <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
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
        eventBus.$on("openCreateCity", data => {
            this.dialog = true;
        });
    },

    methods: {
        save() {

            var payload = {
                model: 'cities',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("cityEvent")
                });
        },
        close() {
            this.dialog = false;
        }
    },
    computed: {
        ...mapState(['zones', 'errors'])
    },
};
</script>
