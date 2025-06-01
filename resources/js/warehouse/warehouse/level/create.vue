<template>
<div class="text-center">
    <v-dialog v-model="dialog" width="500">
        <v-card>
            <v-card-title class="headline">
                <p>Create warehouse levels</p>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-layout row wrap>
                    <v-flex sm12>
                        <label for="">Level</label>
                        <el-input placeholder="Level 1" v-model="form.name"></el-input>
                    </v-flex>
                    <v-flex sm12>
                        <label for="">Level Code</label>
                        <el-input placeholder="L1" v-model="form.code"></el-input>
                    </v-flex>
                </v-layout>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="submit">
                    Submit
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</div>
</template>

<script>
export default {
    // props: ['form'],
    data() {
        return {
            dialog: false,
            form: {},
            warehouse: {}
        }
    },
    methods: {
        submit() {
            var payload = {
                model: 'levels',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // eventBus.$emit("warehouseEvent")
                });
        },
        close() {
            this.dialog = false
        }
    },
    created() {
        eventBus.$on('CreateLevelEvent', data => {
            // console.log(data);
            // this.form.area_id = data.area_id
            this.dialog = true
        });
    },
}
</script>
