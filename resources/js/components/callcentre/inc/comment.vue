<template>
<div class="text-center">
    <v-dialog
    v-model="dialog"
    width="500"
    >
    <v-card>
        <v-card-title class="text-h5 primary lighten-2">
        Comment
        </v-card-title>

        <v-card-text style="padding-top: 20px">
            <v-textarea v-model="form.comment" label="Comment" auto-grow outlined rows="3" row-height="45"></v-textarea>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" text @click="save">Save</v-btn>
        </v-card-actions>
    </v-card>
    </v-dialog>
</div>
</template>

<script>
import {
    mapState
} from "vuex";
export default {
    data () {
        return {
            dialog: false,
            form: {
                comment: '',
                lead_id: null,
            }
        }
    },

    created() {
        eventBus.$on("commentEvent", data => {
            console.log("🚀 ~ file: edit.vue:57 ~ created ~ data:", data)
            this.form.lead_id = data.id
            this.dialog = true;
        });
    },

    methods: {
        save() {
            var payload = {
                data: this.form,
                id: this.form.lead_id,
                model: 'comment'
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("leadsEvent")
                    this.close()
                });
        },
        close() {
            this.dialog = false;
        },
    },
    computed: {
        ...mapState(['errors', 'loading', 'lead'])
    },
}
</script>
