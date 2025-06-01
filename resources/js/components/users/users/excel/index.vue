<template>
<v-dialog v-model="dialog" persistent width="800px">
    <v-card>

        <v-stepper v-model="e1">
            <v-stepper-header>
                <v-stepper-step :complete="e1 > 1" step="1">Upload Details</v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step :complete="e1 > 2" step="2">Upload users</v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content step="1">
                    <!-- <el-radio v-model="upload_type" label="1">Users with single users</el-radio>
                    <el-radio v-model="upload_type" label="2">Users with multiple users</el-radio> -->
                    <myFileUpload :form="form" :user="user" />

                    <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="goToStep2">
                        <v-icon>mdi-chevron-right</v-icon>
                        <span>Next</span>
                    </v-btn>

                    <v-btn text @click="close">Cancel</v-btn>
                </v-stepper-content>

                <v-stepper-content step="2">
                    <myDisplayUsers :users="users" :e1="e1" />
                    <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="importUsers">
                        <v-icon>mdi-import</v-icon>
                        <span>Import</span>
                    </v-btn>

                    <v-btn text @click="e1 = 1">Back</v-btn>
                    <v-btn text @click="close">Close</v-btn>
                </v-stepper-content>

            </v-stepper-items>
        </v-stepper>

    </v-card>
</v-dialog>
</template>

<script>
import myFileUpload from './fileUpload'
import myDisplayUsers from './DisplayUser'
import myMapping from './mapping'
export default {
    name: 'excel',
    props: ['user'],
    components: {
        myFileUpload,
        myDisplayUsers,
        myMapping
    },
    data() {
        return {
            dialog: false,
            loading: false,
            e1: 1,
            form: {
                vendor_id: '',
                warehouse_id: '',
                platform: 'Excel'
            },
            users: [],
            upload_type: '1',
            options: 'Users with single users'
        }
    },
    methods: {
        goToStep2() {
            this.form.platform = 'Excel'
            this.loading = true;
            eventBus.$emit('fileUploadEvent')
        },
        importUsers() {
            var model = 'usersUpload'
            this.form.users = this.users.users

            var payload = {
                data: this.form,
                model: model
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("MenuEvent")
                });
        },
        close() {
            this.dialog = false
        }
    },
    created() {
        eventBus.$on('openExcelUploadEvent', data => {
            this.dialog = true
        })
        eventBus.$on('userResponseEvent', data => {
            this.users = data
            this.loading = false;
            this.e1 = 2
        })
    },
    computed: {
        // validate() {
        //     var valid = true
        //     if (this.e1 == 2) {
        //         for (let index = 0; index < this.users.users.length; index++) {
        //             const element = this.users.users[index];

        //             if (!element.user.id || !element.entry.user_id || !element.entry.quantity || !element.entry.address || !element.entry.user_name || !element.entry.phone) {
        //                 valid = false
        //                 break;
        //             } else {
        //                 valid = true
        //                 // return true
        //             }
        //         }
        //     }
        //     return valid;
        // }
    },
}
</script>
