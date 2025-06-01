<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Edit Contact</span>
            </v-card-title>
            <v-divider></v-divider>

            <v-card-text style="padding-top: 20px">
                <div>
                    <v-text-field label="Name" v-model="form.name"></v-text-field>
                    <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                </div>
                <div>
                    <v-text-field label="Email" v-model="form.email"></v-text-field>
                    <small class="has-text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                </div>
                <div>
                    <v-text-field label="Phone" v-model="form.phone"></v-text-field>
                    <!-- <small class="has-text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small> -->
                </div>
                <div>
                    <v-text-field label="Alternative Phone" v-model="form.alt_phone"></v-text-field>
                    <!-- <small class="has-text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small> -->
                </div>
                <div>
                    <v-text-field label="Address" v-model="form.address"></v-text-field>
                    <small class="has-text-danger" v-if="errors.address">{{ errors.address[0] }}</small>
                </div>
                <div>
                    <v-text-field label="City" v-model="form.city"></v-text-field>
                    <small class="has-text-danger" v-if="errors.city">{{ errors.city[0] }}</small>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn color="red darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="red darken-1" text @click="save" :loading="loading" :disabled="loading">Save</v-btn>
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
    }),
    created() {
        eventBus.$on("editEvent", data => {
            console.log("🚀 ~ file: edit.vue:57 ~ created ~ data:", data)
            this.form = data
            this.dialog = true;
        });
    },

    methods: {
        save() {
            var payload = {
                data: this.form,
                id: this.form.id,
                model: 'clients'
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    // eventBus.$emit("leadsEvent")
                    this.close()
                });
        },
        close() {
            this.dialog = false;
        },
    },
    computed: {
        ...mapState(['errors', 'loading'])
    },

};
</script>
