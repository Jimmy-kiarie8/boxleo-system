<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="300px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Call Status</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <div>
                    <label for="">Call Status</label>
                    <el-select v-model="form.status" placeholder="Select" style="width: 100%" clearable filterable>
                        <el-option v-for="item in options" :key="item.value" :label="item.value" :value="item.value">
                        </el-option>
                    </el-select>
                    <small class="has-text-danger" v-if="errors.status">{{ errors.status[0] }}</small>
                </div>
            </v-card-text>
            <v-card-actions>
                <!-- <v-btn color="blue darken-1" text @click="close">Close</v-btn> -->
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
        form: {
            status: 'Complete',
            phone: null
        },
        options: [{
            value: 'Recall',
        }, {
            value: 'Number busy',
        }, {
            value: 'Unavailable',
        }, {
            value: 'Cancelled',
        }, {
            value: 'Complete',
        }, {
            value: 'Sale made',
        }, {
            value: 'Will call back',
        }]
    }),
    created() {
        eventBus.$on("statusEvent", data => {
            this.form = data
            this.dialog = true;
        });
        eventBus.$on("currentCallEvent", data => {
            this.form.phone = data
            // this.dialog = true;
        });
    },

    methods: {
        save() {
            var payload = {
                data: this.form,
                model: 'leads_status'
            }
            this.$store.dispatch('postItems', payload)
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
        ...mapState(['errors', 'loading'])
    },

};
</script>
