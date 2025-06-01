<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="700px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Edit User</span>
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
                    <label for="">Agent SIP</label>
                    <el-input placeholder="Box..." v-model="form.agent_sip"></el-input>
                    <small class="has-text-danger" v-if="errors.agent_sip">{{ errors.agent_sip[0] }}</small>
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
                    <v-divider></v-divider>
                    <h3 class="text-center">Settings</h3>
                    <v-divider></v-divider>
                    <v-layout row wrap>
                        <v-flex xs6 sm4>
                            <v-checkbox v-model="form.can_call" label="Can Call"></v-checkbox>
                        </v-flex>
                        <v-flex xs6 sm4>
                            <v-checkbox v-model="form.can_receive_calls" label="Can Receive Calls"></v-checkbox>
                        </v-flex>
                        <v-flex xs6 sm4>
                            <v-checkbox v-model="form.can_receive_orders" label="Can Get Orders"></v-checkbox>
                        </v-flex>
                    </v-layout>
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
        },
        gender: [{
                value: 'Male',
                lable: 'Male',
            },
            {
                value: 'Female',
                lable: 'Female',
            },
        ]
    }),
    created() {
        eventBus.$on("openEditUser", data => {
            this.form = data
            this.dialog = true;
            this.getRole()
            this.getCountries()
        });
    },

    methods: {
        save() {
            var payload = {
                data: this.form,
                id: this.form.id,
                model: 'users'
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("userEvent")
                });
        },
        close() {
            this.dialog = false;
        },
        getCountries() {
            var payload = {
                model: 'ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload);
        },
        getRole() {
            var payload = {
                model: 'roles',
                update: 'updateRoleList'
            }
            this.$store.dispatch("getItems", payload);
        },
    },
    computed: {
        ...mapState(['ous', 'roles', 'errors', 'loading'])
    },

};
</script>
