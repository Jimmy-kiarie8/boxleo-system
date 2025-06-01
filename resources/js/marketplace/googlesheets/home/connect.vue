<template>
<v-dialog v-model="dialog" persistent max-width="600px" transition="dialog-transition">

    <v-stepper v-model="e1" v-if="dialog">
        <v-stepper-header>
            <v-stepper-step :complete="e1 > 1" step="1">
                Your Shop
            </v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step :complete="e1 > 2" step="2">
                Connect
            </v-stepper-step>
        </v-stepper-header>

        <v-stepper-items>
            <v-stepper-content step="1">

                <v-card elevation="3">
                    <v-card-text>

                        <label for="" style="color: #52627d;">Vendor Name*</label>
                        <el-select v-model="form.vendor_id" filterable placeholder="Select" :loading="loading" style="width: 100%;">
                            <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                            </el-option>
                        </el-select>
                        <el-input placeholder="example.com" v-model="form.woocommerce_url" v-if="form.vendor_id">
                            <template slot="prepend">https://</template>
                        </el-input>
                    </v-card-text>
                    <v-card-actions>

                        <v-btn color="primary" @click="close" text>
                            Close
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="connect" text>Generate link</v-btn>
                    </v-card-actions>
                </v-card>

            </v-stepper-content>

            <v-stepper-content step="2">
                <v-card class="mb-12" height="200px">
                    <v-card-text>
                        <el-tag style="white-space: break-spaces;height: auto;">{{ link }}</el-tag>
                        <copy-to-clipboard :text="link">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" v-clipboard:copy="link" color="primary" @click="copy">
                                        <v-icon small>mdi-content-copy</v-icon>
                                    </v-btn>
                                </template>
                                <span>Copy link</span>
                            </v-tooltip>
                        </copy-to-clipboard>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on" color="primary" target="_blank" :href="link">
                                    <v-icon small>mdi-open-in-new</v-icon>
                                </v-btn>
                            </template>
                            <span>Open link</span>
                        </v-tooltip>
                    </v-card-text>
                    <v-card-actions>

                        <v-btn color="primary" @click="close" text>
                            Close
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn text @click="e1 = 1">
                            Back
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-stepper-content>

        </v-stepper-items>
    </v-stepper>

</v-dialog>
</template>

<script>
import {
    mapState
} from 'vuex'
import CopyToClipboard from 'vue-copy-to-clipboard'

export default {
    components: {
        CopyToClipboard
    },
    data() {
        return {
            dialog: false,
            e1: 1,
            form: {},
            link: null,
            intervals: [{
                    value: 0.5,
                    label: '30 Minutes'
                },
                {
                    value: 1,
                    label: '1 Hour'
                },
                {
                    value: 2,
                    label: '2 Hours'
                },
                {
                    value: 4,
                    label: '4 Hours'
                },
                {
                    value: 24,
                    label: '1 Day'
                }
            ]
        }
    },
    methods: {
        close() {
            this.dialog = false
        },
        connect() {
            var payload = {
                model: 'woocommerce_connect',
                id: this.form.vendor_id,
                data: this.form,
            }
            this.$store.dispatch('patchItems', payload).then((response) => {
                // this.open(response.data)
                this.link = response.data
                this.e1 = 2
            })
        },
        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload)
        },
        open(data) {
            this.$alert(data, 'Link Generated', {
                confirmButtonText: 'OK',
                callback: action => {
                    this.$message({
                        type: 'info',
                        message: `action: ${ action }`
                    });
                }
            });
        },
        copy() {
            this.$message({
                message: 'Link copied',
                type: 'success'
            });
        }
    },
    created() {
        eventBus.$on('woocommerceConnectEvent', () => {
            this.dialog = true
            this.getSellers()
        });
    },

    computed: {
        ...mapState(['sellers', 'loading'])
    },
}
</script>

<style>

</style>
