<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="400px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Google Sheets</span>
            </v-card-title>

            <v-card-text>
                <v-container grid-list-md>
                    <v-divider></v-divider>
                    <v-layout row wrap>
                        <v-flex sm12 v-if="select_vendor">
                            <label for="">Vendor</label>
                            <el-select name="client" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" v-model="form.vendor_id">
                                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                            <small class="has-text-danger" v-if="errors.vendor_id">{{ errors.vendor_id[0] }}</small>
                        </v-flex>
                        <v-flex sm12>
                            <label for="">Sheet name</label>
                            <el-input placeholder="Sheet1" v-model="form.sheet_name"></el-input>
                            <small class="has-text-danger" v-if="errors.sheet_name">{{ errors.sheet_name[0] }}</small>
                        </v-flex>
                        <v-flex sm12>
                            <label for="">Spreadsheet id</label>
                            <el-input placeholder="123 mainst" v-model="form.post_spreadsheet_id"></el-input>
                            <small class="has-text-danger" v-if="errors.post_spreadsheet_id">{{ errors.post_spreadsheet_id[0] }}</small>
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
        form: {
            vendor_id: '',
            sheet_name: '',
            post_spreadsheet_id: ''
        },
        select_vendor: false
    }),
    created() {
        eventBus.$on("openSheet", data => {
                this.dialog = true;
            if (data == 0) {
                this.select_vendor = true;
                if (this.sellers.length < 1) {
                    this.getSellers()
                }
            } else {
                this.form.vendor_id = data
                this.getSheet(data)
            }
        });
    },

    methods: {
        save() {
            var payload = {
                data: this.form,
                model: 'sheets',
            }
            this.$store.dispatch('postItems', payload)
                .then(() => {
                    // eventBus.$emit("sellerEvent")
                });
        },
        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload);
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
            this.dialog = false;
        }
    },
    computed: {
        ...mapState(['errors', 'loading', 'sheets', 'sellers'])
    },
};
</script>
