<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card v-if="dialog">
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Charges</span>
                <VSpacer />
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="dialog = false" slot="activator">
                            <v-icon small color="blue darken-2">close</v-icon>
                        </v-btn>
                    </template>
                    <span>close</span>
                </v-tooltip>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <el-select v-model="form.services_id" placeholder="Select" filterable clearable style="width: 100%;" multiple>
                            <el-option v-for="item in services" :key="item.id" :label="item.name" :value="item.id"></el-option>
                        </el-select>
                    </v-layout>
                </v-container>
            </v-card-text>
            
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                <VSpacer />
                <v-btn color="primary" @click="update" :loading="loading" :disabled="loading">Update</v-btn>
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
    name: 'Charges',
    components: {
    },
    data: () => ({
        dialog: false,
        form: {
            data: null
        }
    }),
    methods: {
        update() {
            var payload = {
                data: this.form,
                model: 'charges_apply',
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {});
        },

        getServices() {

            var payload = {
                model: 'services',
                update: 'updateService'
            }
            // if (!this.sales.data) {
            //     if (this.sales.data.length < 1) {
            this.$store.dispatch("getItems", payload);
        }
    },

    created() {
        eventBus.$on('chargeEvent', data => {
            this.dialog = true;
            this.form.data = data,
            this.getServices()
        });
    },
    computed: {
        ...mapState(['services', 'loading']),
    },
}
</script>

<style>

</style>
