<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="700px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create User</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <div>
                    <label for="">Full Name</label>
                    <el-input placeholder="John Doe" v-model="form.name"></el-input>
                    <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                </div>
                <div>
                    <label for="">Email Address</label>
                    <el-input placeholder="john@gmail.com" v-model="form.email"></el-input>
                    <small class="has-text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                </div>
                <div>
                    <label for="">Phone Number</label>
                    <el-input placeholder="+1..." v-model="form.phone"></el-input>
                    <small class="has-text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small>
                </div>
                <div>
                    <label for="">Address</label>
                    <el-input placeholder="main st" v-model="form.address"></el-input>
                    <small class="has-text-danger" v-if="errors.address">{{ errors.address[0] }}</small>
                </div>

                <div>
                    <label for="">Ou</label>
                    <el-select v-model="form.ou_id" placeholder="Select" style="width: 100%">
                        <el-option v-for="item in ous" :key="item.id" :label="item.ou" :value="item.id">
                        </el-option>
                    </el-select>
                    <small class="has-text-danger" v-if="errors.ou_id">{{ errors.ou_id[0] }}</small>
                </div>
                <div>
                    <label for="">Role</label>
                    <el-select v-model="form.role_id" placeholder="Select" style="width: 100%" clearable filterable>
                        <el-option v-for="item in roles" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                    <small class="has-text-danger" v-if="errors.role_id">{{ errors.role_id[0] }}</small>
                </div>
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
import {
    mapState
} from 'vuex';
export default {
    data: () => ({
        dialog: false,
        form: {},
        payload: {
            model: 'users',
        }
    }),
    created() {
        eventBus.$on("openCreateUser", data => {
            this.dialog = true;
            this.getRole()
            this.getCountries()
        });
    },

    methods: {
        save() {
            this.payload['data'] = this.form
            this.$store.dispatch('postItems', this.payload)
                .then(response => {
                    eventBus.$emit("userEvent")
                });
        },
        close() {
            this.dialog = false;
        },
        getRole() {
            var payload = {
                model: 'roles',
                update: 'updateRoleList'
            }
            this.$store.dispatch("getItems", payload);
        },
        getCountries() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload);
        },
    },
    computed: {
        ...mapState(['roles', 'ous', 'loading', 'errors'])
    },
};
</script>
