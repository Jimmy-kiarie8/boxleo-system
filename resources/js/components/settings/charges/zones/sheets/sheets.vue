<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Google Sheets</span>
            </v-card-title>

            <v-card-text>
                <v-container grid-list-md>
                    <v-divider></v-divider>
                    <v-layout row wrap>
                        <v-flex sm12>
                            <label for="">Sheet name</label>
                            <el-input placeholder="Sheet1" v-model="form.sheet_name"></el-input>
                            <small class="has-text-danger" v-if="errors.sheet_name">{{ errors.sheet_name[0] }}</small>
                        </v-flex>
                        <v-flex sm12>
                            <label for="">Spreadsheet id</label>
                            <el-input placeholder="123#2901" v-model="form.post_spreadsheet_id"></el-input>
                            <small class="has-text-danger" v-if="errors.post_spreadsheet_id">{{ errors.post_spreadsheet_id[0] }}</small>
                        </v-flex>
                    </v-layout>
                    <v-divider></v-divider>
                    <h3 class="text-center">Settings</h3>
                    <v-divider></v-divider>
                    
                    <v-layout row wrap>
                        <v-flex xs6 sm3 v-for="zone in zones" :key="zone.id">
                            <v-checkbox v-model="selected" :label="zone.name" :value="zone.id"></v-checkbox>
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
import {
    mapState
} from 'vuex';
export default {
    data: () => ({
        dialog: false,
        selected: [],
        form: {
            sheet_name: '',
            post_spreadsheet_id: '',
            zones: [],
            edit: false
        },
        select_vendor: false
    }),
    created() {
        eventBus.$on("sheetEvent", data => {
            console.log("🚀 ~ file: sheets.vue:62 ~ created ~ data:", data)

            if (data.edit) {
                this.edit = true
                this.form = data;
                this.selected = data.zones;
            }
            this.dialog = true;
        });
    },

    methods: {
        save() {
            this.form.zones = this.selected

            if (this.edit) {
                var payload = {
                    data: this.form,
                    id: this.form.id,                    
                    model: 'zone_sheets_update',
                }
                this.$store.dispatch('patchItems', payload)
                    .then(() => {
                        // eventBus.$emit("sellerEvent")
                });
            } else {
                var payload = {
                    data: this.form,
                    model: 'zone_sheets_update',
                }
                this.$store.dispatch('postItems', payload)
                    .then(() => {
                        // eventBus.$emit("sellerEvent")
                });
            }

        },
        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload);
        },
        getZones() {
            var payload = {
                model: 'zones',
                update: 'updateZone',
            }
            this.$store.dispatch('getItems', payload);
        },
        getSheet(id) {
            var payload = {
                model: 'sheets',
                update: 'updateSheet',
                id: id
            }
            this.$store.dispatch('showItem', payload).then((response) => {
                if (response.data.length > 0) {
                    this.form.sheet_name = response.data.sheet_name
                    this.form.post_spreadsheet_id = response.data.post_spreadsheet_id
                } else {
                    this.form.sheet_name = ''
                    this.form.post_spreadsheet_id = ''
                }
            });
        },
        close() {
            this.edit = false
            this.dialog = false;
        }
    },
    computed: {
        ...mapState(['errors', 'loading', 'sheets', 'sellers', 'zones'])
    },

    mounted () {
        this.getZones();
    },
};
</script>
