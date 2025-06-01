<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Branch</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
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
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="save" :loading="loading" :disabled="loading">Update</v-btn>
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
        eventBus.$on("openEditBranch", data => {
            this.dialog = true;
            this.form = data
        });
    },
    methods: {
        save() {
            eventBus.$emit("LoadingEvent");
            this.loading = true;
            axios
                .patch(`/branch/${this.form.id}`, this.$data.form)
                .then(response => {
                    this.loading = false;
                    // console.log(response);
                    // context.commit('getBranches', response.data)
                    this.$store.dispatch('alertEvent', 'Branch Updated')
                    eventBus.$emit("branchEvent", data);
                    this.close();
                })
                .catch(error => {
                    eventBus.$emit("stopLoadingEvent");
                    // console.log(error.response);
                    this.loading = false;
                });
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
