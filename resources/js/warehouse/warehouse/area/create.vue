<template>
<div class="text-center">
    <v-dialog v-model="dialog" width="500">
        <v-card>
            <v-card-title class="headline">
                <p>Create warehouse area</p>
            </v-card-title>
    <v-divider
      class=""
    ></v-divider>
            <v-card-text>
                <v-layout row wrap>
                    <v-flex sm12 id="select">
                        <label for="">Area Code</label>
                        <el-input placeholder="Area 1" v-model="form.name"></el-input>
                    </v-flex>
                    <v-flex sm12>
                        <label for="">Area Code</label>
                        <el-input placeholder="A1" v-model="form.code"></el-input>
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
                model: 'areas',
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
        eventBus.$on('CreateAreaEvent', data => {
            console.log(data);
            this.form.warehouse_id = data.id
            this.dialog = true
        });
    },
}
</script>
