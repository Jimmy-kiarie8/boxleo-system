<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Branch</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm12>
                            <div>
                                <label for="">Branch</label>
                                <el-input placeholder="Mombasa" v-model="form.branch_name"></el-input>
                            </div>
                            <div>
                                <label for="">Email Address</label>
                                <el-input placeholder="doe@gmail.com" v-model="form.email"></el-input>
                            </div>
                            <div>
                                <label for="">Phone Number</label>
                                <el-input placeholder="+1..." v-model="form.phone"></el-input>
                            </div>
                            <div>
                                <label for="">Address</label>
                                <el-input placeholder="address" v-model="form.address"></el-input>
                            </div>
                            <div>
                                <label for="">Ou</label>
                            <el-select v-model="form.ou_id" clearable placeholder="Select Ou" style="width:100%">
                                <el-option v-for="item in ous" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                            </div>
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
        form: {},
    }),
    created() {
        eventBus.$on("openCreateBranch", data => {
            this.dialog = true;
        });
    },

    methods: {
        save() {
            var data = {
                data: this.form,
                rider: this.rider
            }
            var payload = {
                model: 'branches',
                data: this.form,
                // id: this.form.id,
            }
            this.$store.dispatch('postItems', payload)

            // eventBus.$emit("LoadingEvent");
            // this.loading = true;
            // axios
            //     .post("branch", this.$data.form)
            //     .then(response => {
            //         this.loading = false;
            //         // console.log(response);
            //         // context.commit('getBranch', response.data)
            //         this.$store.dispatch('alertEvent', 'Branch added')
            //         this.$store.dispatch('getBranch')
            //         eventBus.$emit("branchEvent", data);
            //         this.close();
            //     })
            //     .catch(error => {
            //         eventBus.$emit("stopLoadingEvent");
            //         // console.log(error.response);
            //         this.loading = false;
            //     });
        },
        close() {
            this.dialog = false;
        }
    },
    computed: {
        ous() {
            return this.$store.getters.ous
        }
    },
};
</script>
