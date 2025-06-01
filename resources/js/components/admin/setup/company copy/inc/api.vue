<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="900px">
        <v-card style="padding: 0 20px;">
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">{{ api_data.site }} Connection</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-card-text>
                        <v-layout row wrap>

                            <v-flex sm6 v-if="api_data.value !='google_sheets'">
                                <label for="" style="color: #52627d;">Vendor Name*</label>
                                <el-select v-model="form.vendor_id" filterable remote reserve-keyword placeholder="type at least 3 characters" :remote-method="getSellers" :loading="loading" style="width: 100%;" @change="getApi">
                                    <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                                    </el-option>
                                </el-select>
                            </v-flex>

                            <v-flex sm6 v-if="api_data.value == 'africas_talking'">
                                <label for="">Africa's Talking Username</label>
                                <el-input placeholder="user name" v-model="form.africas_talkig_username"></el-input>
                            </v-flex>
                            <v-flex sm6 v-if="api_data.value == 'africas_talking'">
                                <label for="">Africa's Talking Api Key</label>
                                <el-input placeholder="api key" v-model="form.africas_talkig_api_key"></el-input>
                            </v-flex>

                            <v-flex sm6 v-if="api_data.value == 'twilio'">
                                <label for="">Twilio SID</label>
                                <el-input placeholder="name" v-model="form.twilio_sid"></el-input>
                            </v-flex>

                            <v-flex sm6 v-if="api_data.value == 'twilio'">
                                <label for="">Twilio Auth Token</label>
                                <el-input placeholder="Secret" v-model="form.twilio_auth_token"></el-input>
                            </v-flex>

                            <v-flex sm6 v-if="api_data.value == 'twilio'">
                                <label for="">Twilio Number</label>
                                <el-input placeholder="name" v-model="form.twilio_number"></el-input>
                            </v-flex>

                            <v-flex sm12 v-if="api_data.value =='google_sheets'">
                                <!-- <el-upload class="upload-demo" ref="upload" drag action="/google_service" :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" :auto-upload="true" style="width: 100%" :data="headers" >
                                    <input type="hidden" name="_token" :value="csrf">
                                    <i class="el-icon-upload"></i>
                                    <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                                    <div class="el-upload__tip" slot="tip">jpg/png files with a size less than 500kb</div>
                                </el-upload> -->

                                <label for="">File content
                                    <el-tooltip content="Top center" placement="top">
                                        <v-icon small color="black">mdi-help</v-icon>
                                    </el-tooltip>
                                </label>
                                <!-- <el-input placeholder="Copy path from" v-model="form.file_path"></el-input> -->
                                <!-- <el-input type="textarea" :rows="10" placeholder="input the content" v-model="form.file"></el-input>

                                {{ form.file }} -->
                                <div id="vueapp">
                                    <div class="text-danger" v-if="form.file && jsonerror">{{ jsonerror }}</div>
                                    <div class="text-success" v-if="!jsonerror">Valid JSON ✔</div>
                                    <textarea v-model="form.file" rows="10" class="form-control" id="jsonText" placeholder="paste or type JSON here..."></textarea>
                                    <!-- <pre>{{ prettyFormat }}</pre> -->
                                </div>

                                <!-- <file-manager /> -->

                                <!-- <el-button size="small" type="primary" @click="submitUpload">upload to server</el-button> -->
                            </v-flex>

                            <v-flex sm6 v-if="api_data.value =='woocommerce'">
                                <label for="">Website URL</label>
                                <el-input placeholder="woocommerce" v-model="form.woocommerce"></el-input>
                            </v-flex>

                            <v-flex sm6 v-if="api_data.value =='shopify'">
                                <label for="">Shopify Key</label>
                                <el-input placeholder="shopify key" v-model="form.shopify_key"></el-input>
                            </v-flex>

                            <v-flex sm6 v-if="api_data.value =='shopify'">
                                <label for="">Shopify Secret</label>
                                <el-input placeholder="shopify secret" v-model="form.shopify_secret"></el-input>
                            </v-flex>

                            <v-flex sm6 v-if="api_data.value =='shopify'">
                                <label for="">Shopify Redirect Url</label>
                                <el-input placeholder="shopify redirect" v-model="form.shopify_redirect"></el-input>
                            </v-flex>

                            <v-flex sm6 v-if="api_data.value =='shopify'">
                                <label for="">Shopify Access</label>
                                <el-input placeholder="shopify access" v-model="form.shopify_access"></el-input>
                            </v-flex>

                            <v-flex sm6 v-if="api_data.value =='shopify'">
                                <label for="">Shopify URL</label>
                                <el-input placeholder="shopify url" v-model="form.shopify_url"></el-input>
                            </v-flex>

                        </v-layout>
                    </v-card-text>

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
            file: ""
        },
        headers: {
            '_token': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        api_data: '',

        jsonstr: "",
        jsonerror: ""
    }),
    created() {
        eventBus.$on("ApiConnectEvent", data => {
            console.log(data);
            if (data.value == 'google_sheets') {
                this.get_json()
            }
            this.api_data = data
            this.dialog = true;
            // this.getApi(data)
        });
    },

    methods: {
        save() {
            var payload = {
                model: 'api_connect/' + this.api_data.value,
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // eventBus.$emit("CurrencyEvent")
                    // this.$refs.upload.submit();
                });
        },

        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload);
        },
        get_json() {
            var payload = {
                model: 'get_json',
                update: 'updatApi'
            }
            this.$store.dispatch('getItems', payload)
                .then(response => {
                    console.log(response);

                    if (response.data) {
                        this.form.file = JSON.stringify(response.data, null, 2)
                    }

                });
        },

        getApi(id) {
            var payload = {
                model: 'api_exist',
                id: id,
                update: 'updatApi'
            }
            this.$store.dispatch('showItem', payload)
                .then(response => {
                    console.log(response);

                    if (response.data) {
                        this.form = response.data
                    }

                });
        },
        submitUpload() {
            this.$refs.upload.submit();
        },

        close() {
            this.dialog = false;
        },
        get_data(data) {
            console.log(data);

            var payload = {
                model: 'api_exist',
                id: data.site,
                update: 'updatApi'
            }
            this.$store.dispatch('showItem', payload)
                .then(response => {
                    console.log(response);

                    if (response.data) {
                        this.form = response.data
                    }

                });
        }
    },

    filters: {
        pretty: function (value) {
            return JSON.stringify(JSON.parse(value), null, 2);
        }
    },
    computed: {
        ...mapState(['loading', 'sellers']),
        prettyFormat: function () {
            // reset error
            this.jsonerror = "";
            let jsonValue = "";
            try {
                // try to parse
                jsonValue = JSON.parse(this.form.file);
            } catch (e) {
                this.jsonerror = JSON.stringify(e.message)
                var textarea = document.getElementById("jsonText");
                if (this.jsonerror.indexOf('position') > -1) {
                    // highlight error position
                    var positionStr = this.jsonerror.lastIndexOf('position') + 8;
                    var posi = parseInt(this.jsonerror.substr(positionStr, this.jsonerror.lastIndexOf('"')))
                    if (posi >= 0) {
                        textarea.setSelectionRange(posi, posi + 1);
                    }
                }
                return "";
            }
            return JSON.stringify(jsonValue, null, 2);
        },
        },
        mounted() {
            this.getSellers();
        },
    };
</script>

<style scoped>
</style>
