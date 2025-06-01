<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Change password</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm12>
                            <v-card-text>
                                <div>
                                    <v-text-field v-model="form.password" :append-icon="show ? 'visibility' : 'visibility_off'" :type="show ? 'text' : 'password'" name="input-10-1" label="New password" counter @click:append="show = !show"></v-text-field>
                                </div>
                                <small class="has-text-danger" v-if="errors.password">{{ errors.password[0] }}</small>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="passwordChange" :loading="loading" :disabled="loading">Update</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    data: () => ({
        dialog: false,
        show: false,
        loading: false,
        form: {
            password: ''
        },
        errors: {},
        rules: {
            required: value => !!value || 'Required.',
            min: v => v.length >= 8 || 'Min 8 characters',
            emailMatch: () => ('The email and password you entered don\'t match'),
        },
    }),
    created() {
        eventBus.$on("openPasswordEvent", data => {
            // console.log(data);

            this.dialog = true;
            this.form.id = data
        });
    },

    methods: {
        passwordChange() {
            var payload = {
                data: this.form,
                id: this.form.id,
                model: '/rider/rider_password'
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    this.form.password = '';
                    this.close();
                    // eventBus.$emit("userEvent")
                });
        },
        close() {
            this.dialog = false;
        }
    },
};
</script>
