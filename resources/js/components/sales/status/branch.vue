<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="400px">
        <v-card>
            <v-card-title>
                <span class="headline" style="margin: auto;">Order Details</span>
            </v-card-title>
            <VDivider />
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex sm12>
                            <label class="col-md-5 col-lg-5">Branch</label>
                            <el-select v-model="form.branch_id" filterable clearable placeholder="Select" style="width: 100%;">
                                <el-option v-for="item in branches" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-flex>
                        <v-flex sm12>
                            <label for="" style="color: #52627d;">Comments</label>
                            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" placeholder="comments" v-model="form.comment" maxlength="500" show-word-limit>
                            </el-input>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                <VSpacer />
                <v-btn color="primary" @click="updateBranch" :loading="loading" :disabled="loading">Update</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    name: 'orderBranch',
    data: () => ({
        loading: false,
        dialog: false,
        form: [],
    }),
    created() {
            this.getBranch();
        eventBus.$on('orderBranchEvent', data => {
            this.dialog = true
            this.form = data
        })
    },
    methods: {
        getBranch() {
            if (this.branches.length < 1) {
                var payload = {
                    model: 'branch/branches',
                    update: 'updateBranchesList',
                }
                this.$store.dispatch("getItems", payload);
            }
        },
        updateBranch() {

            var payload = {
                model: 'branch/branch_update',
                id: this.form.id,
                data: this.form
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("saleEvent")
                });
        },
    },
    mounted() {},
    computed: {
        ...mapState(['branches'])
    },
}
</script>
