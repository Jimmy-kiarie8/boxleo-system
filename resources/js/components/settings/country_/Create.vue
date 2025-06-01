<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Ou</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm5 offset-sm1 id="select">
                            <label for="">Ou</label>
                            <el-input placeholder="KES" v-model="form.ou"></el-input>
                            <!-- <country-select v-model="form.ou" :country="form.ou" topCountry="KE" /> -->
                        </v-flex>
                        <v-flex sm5 offset-sm1>
                            <label for="">Currency code</label>
                            <el-input placeholder="KES" v-model="form.currency_code"></el-input>
                        </v-flex>
                        <v-flex sm5 offset-sm1>
                            <label for="">Ou code</label>
                            <el-input placeholder="KES" v-model="form.ou_code"></el-input>
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
        form: {},
    }),
    created() {
        eventBus.$on("openCreateOu", data => {
            this.dialog = true;
        });
    },

    methods: {
        save() {

            var payload = {
                model: 'ous',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // eventBus.$emit("CurrencyEvent")
                });
        },
        close() {
            this.dialog = false;
        }
    }
};
</script>

<style scoped>
#select select {
    -webkit-appearance: none;
    background-color: #FFF;
    background-image: none;
    border-radius: 4px;
    border: 1px solid #DCDFE6;
    box-sizing: border-box;
    color: #606266;
    display: inline-block;
    font-size: inherit;
    height: 40px;
    line-height: 40px;
    outline: 0;
    padding: 0 15px;
    transition: border-color .2s cubic-bezier(.645, .045, .355, 1);
    width: 100%;
}
</style>
