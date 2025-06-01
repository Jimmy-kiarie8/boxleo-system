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
                    <el-input placeholder="main st" v-model="form.address"></el-input>
                </div>
                <!-- <div>
                    <label for="">Role</label>
                    <el-select v-model="form.role_id" placeholder="Select" style="width: 100%" clearable filterable>
                        <el-option v-for="item in roles" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </div> -->
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
        errors: {},
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
            this.getOus()
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
        getOus() {
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
        ...mapState(['ous', 'roles'])
    },


};
</script>
