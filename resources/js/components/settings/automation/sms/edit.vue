<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline">Edit Sms Template</span>
            </v-card-title>

            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="editedItem.name" label="Template Name"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="editedItem.model" label="Module"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="12">
                            <v-textarea v-model="editedItem.sms" label="SMS Content"></v-textarea>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                <v-btn color="blue darken-1" text @click="save">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    data() {
        return {
            dialog: false,
            editedItem: {
                name: '',
                model: '',
                sms: ''
            },
            defaultItem: {
                name: '',
                model: '',
                sms: ''
            }
        }
    },
    created() {
        eventBus.$on('openEditSms', data => {
            this.editedItem = Object.assign({}, data)
            this.dialog = true
        })
    },
    methods: {
        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
            })
        },
        save() {
            axios.put(`/sms_update/${this.editedItem.id}`, this.editedItem)
                .then(response => {
                    this.close()
                    eventBus.$emit('SmsEvent')
                })
                .catch(error => {
                    console.error(error)
                })
        }
    }
}
</script> 