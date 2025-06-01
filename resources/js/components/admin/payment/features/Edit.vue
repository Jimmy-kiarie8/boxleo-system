<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Feature</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm12>
                            <label for="">Ou</label>
                            <el-select v-model="form.ou_id" clearable placeholder="Select Ou" style="width:100%">
                                <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-flex>
                        <v-flex sm12>
                            <label for="">Feature</label>
                            <el-input placeholder="" v-model="form.name"></el-input>
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
        eventBus.$on("openEditFeature", data => {
            this.dialog = true;
            this.form = data
        });
    },

    methods: {
        save() {

            var payload = {
                model: 'features',
                data: this.form,
                id: this.form.id,
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("featureEvent")
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
