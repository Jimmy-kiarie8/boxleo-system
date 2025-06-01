<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Sms template</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-row style="">
                        <v-col cols="12">
                            <label for="">Sms template name</label>
                            <el-input placeholder="" v-model="form.name"></el-input>
                            <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                        </v-col>
                        <v-col cols="12">
                            <label for="">Module *</label>
                            <el-select v-model="form.model" placeholder="Select" filterable clearable @change="get_rows" style="width:100%">
                                <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                                </el-option>
                            </el-select>
                        </v-col>

                        <v-col cols="12">
                            <label for="">To</label>
                            <el-select v-model="form.recipient" placeholder="Select" filterable clearable allow-create multiple style="width: 100%">
                                <el-option v-for="item in (form.model == 'users') ? user_recipients : order_recipients" :key="item.value" :label="item.label" :value="item.value">
                                </el-option>
                            </el-select>
                            <!-- <el-input placeholder="" v-if="form.recipient == 'custom'" v-model="form.custom_recipient"></el-input> -->
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
                                    <label for="">Options
                                        <el-tooltip placement="top">
                                            <div slot="content" style="text-align: center;">
                                                Choose this options to add dynamic attributes
                                                <br />
                                                eg. client name, product name etc
                                            </div>
                                            <!-- <el-button type="text"></el-button> -->
                                            <i class="el-icon-question"></i>

                                        </el-tooltip>
                                    </label>
                                    <el-select filterable clearable v-model="opt" placeholder="Select" @change="text_update" style="width: 100%">
                                        <el-option v-for="(item, item_index) in table_rows" :key="item_index" :label="item.label" :value="item.Field"></el-option>
                                    </el-select>
                                </v-col>
                                <v-col cols="12" sm="12" md="12">
                                    <v-textarea label="Sms" v-model="form.sms"></v-textarea>
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
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
        loading: false,
        form: {
            sms: ''
        },
        opt: '',
        options: [{
                value: "users",
                label: "Users",
            },
            {
                value: "sales",
                label: "Orders",
            },
        ],

        user_recipients: [{
                value: "Created by",
            },
            {
                value: "Owner",
            },
            {
                value: "You",
            },
            {
                value: "User",
            }
        ],
        order_recipients: [{
                value: "Created by",
            },
            {
                value: "Owner",
            },
            {
                value: "You",
            },
            {
                value: "Client",
            },
            {
                value: "Vendor",
            }
        ]

    }),
    created() {
        eventBus.$on("openCreateSms", data => {
            this.dialog = true;
        });
    },

    methods: {
        save() {

            var payload = {
                model: 'sms_template',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("SmsEvent")
                });
        },
        close() {
            this.dialog = false;
        },
        text_update(text) {
            // console.log(text);
            this.form.sms = this.form.sms + ' {%' + text + '%} '
            this.opt = ''
        },
        get_rows(table) {
            var payload = {
                model: 'table_rows',
                search: table,
                update: 'updateTableRows'
            }

            this.$store.dispatch('searchItems', payload)
        },
    },

    computed: {
        ...mapState(['errors', 'table_rows'])
    },
};
</script>
