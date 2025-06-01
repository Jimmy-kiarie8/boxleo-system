<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Warehouse</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12 sm6>
                            <v-text-field label="Length*" required type="number" v-model="form.length"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-text-field label="Width*" required type="number" v-model="form.width"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-text-field label="Maximum stack height*" required type="number" v-model="form.height"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-text-field label="Non-storage purposes*" required type="number" v-model="form.non_storage"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-text-field label="Capacity" disabled required type="number" v-model="capacity"></v-text-field>
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
        form: {
            length: 0,
            width: 0,
            non_storage: 0,
            height: 0,
        }
    }),
    created() {
        eventBus.$on("openStorageWarehouse", data => {
            this.dialog = true;
            this.form = data
        });
    },

    methods: {
        save() {
            eventBus.$emit("LoadingEvent");
            this.loading = true;
            this.form.capacity = this.capacity
            axios
                .post(`dimensions/${this.form.id}`, this.$data.form)
                .then(response => {
                    this.loading = false;
                    // console.log(response);
                    // context.commit('getWarehouses', response.data)
                    this.$store.dispatch('alertEvent', 'Created')
                    //   eventBus.$emit("stopLoadingEvent");
                    this.close();
                })
                .catch(error => {
                    eventBus.$emit("stopLoadingEvent");
                    // console.log(error.response);
                    this.loading = false;
                });
        },
        close() {
            this.dialog = false;
        }
    },
    computed: {
        capacity() {
            return (parseInt(this.form.height) * parseInt(this.form.width) * parseInt(this.form.length)) - (parseInt(this.form.non_storage))
        }
    },
};
</script>
