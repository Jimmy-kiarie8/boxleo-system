<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="700px">
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
                                <label for="">Branch Name</label>
                                <el-input placeholder="John Doe" v-model="form.name"></el-input>
                            </div>
                            <div>
                                <label for="">Email Address</label>
                                <el-input placeholder="john@gmail.com" v-model="form.email"></el-input>
                            </div>
                            <div>
                                <label for="">Phone Number</label>
                                <el-input placeholder="+1..." v-model="form.phone"></el-input>
                            </div>
                            <div>
                                <label for="">Address</label>
                                <el-input placeholder="Main st" v-model="form.address"></el-input>
                            </div>
                            <div>
                                <label for="">Delivery Chages</label>
                                <el-input placeholder="200" v-model="form.delivery_charges"></el-input>
                            </div>
                            <div>
                                <label for="">Return Chages</label>
                                <el-input placeholder="200" v-model="form.return_charges"></el-input>
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
        errors: {},
        // payload: {
        //     model: 'branches',
        // },
    }),

    created() {
        eventBus.$on("openEditBranch", data => {
            this.form = data
            this.dialog = true;
        });
    },

    methods: {
        save() {
            var payload = {
                model: 'branch/branches',
                data: this.form
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("branchEvent")
                });
        },
        close() {
            this.dialog = false;
        }
    },
    computed: {
    },
};
</script>
