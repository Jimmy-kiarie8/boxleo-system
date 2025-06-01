<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="400px">
        <v-card>
            <v-card-title>
                <span class="headline" style="margin: auto;">Assign Agent</span>
            </v-card-title>
            <VDivider />
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex sm12>
                            <label class="col-md-5 col-lg-5">Agent</label>
                            <el-select v-model="form.user_id" filterable clearable placeholder="Select" style="width: 100%;">
                                <el-option v-for="item in zone_agents" :key="item.id" :label="item.name" :value="item.id"></el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.user_id">{{ errors.user_id[0] }}</small>
                        </v-flex>
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
    name: 'assign',
    data: () => ({
        dialog: false,
        form: {
            user_id: null,
        },
        selected: []
    }),
    created() {
        eventBus.$on('assignEvent', data => {
            this.dialog = true
            this.selected = data
        })
    },
    methods: {
        getUsers() {
            var payload = {
                model: 'zone_agents',
                update: 'updateZoneAgent',
            }
            this.$store.dispatch("getItems", payload);
        },
        update() {
            // this.form.zone_from = this.zone_data.zone_from
            // this.form.zone_to = this.zone_data.zone_to
            const ids = this.selected.map(item => item.id);

            this.form.ids = ids;

            var payload = {
                model: 'assign_zone_agent',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("saleEvent", 'mult')
                    // this.close()
                    eventBus.$emit("saleEvent")
                });
        },
        close() {
            this.dialog = false
        }
    },
    mounted() {
        this.getUsers();
    },
    computed: {
        ...mapState(['users', 'errors', 'loading', 'zone_agents'])
    },
}
</script>
