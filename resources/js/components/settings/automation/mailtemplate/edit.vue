<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="900px">

        <v-card v-if="dialog">

            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Template</span>
                <v-spacer></v-spacer>
                <v-btn text icon color="primary" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>

                <div>
                    <label for="">Name</label>
                    <el-input placeholder="Welcome email" v-model="form.name"></el-input>
                </div>
                <label for="">Module *</label>
                <el-select v-model="form.model" placeholder="Select" @change="get_rows" filterable clearable style="width:100%">
                    <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
                <div>
                    <label for="">To</label>
                    <el-select v-model="form.recipient" placeholder="Select" filterable clearable allow-create multiple style="width: 100%">
                        <el-option v-for="item in (form.model == 'users') ? user_recipients : order_recipients" :key="item.value" :label="item.label" :value="item.value">
                        </el-option>
                    </el-select>
                    <!-- <el-input placeholder="" v-if="form.recipient == 'custom'" v-model="form.custom_recipient"></el-input> -->
                </div>
                <div>
                    <label for="">Subject</label>
                    <el-input placeholder="Welcome email" v-model="form.subject"></el-input>
                </div>
                <div style="margin-top: 10px"></div>

                <label for="">Placeholder</label>
                <el-select v-model="opt" placeholder="Select" @change="get_position" filterable clearable>
                    <el-option v-for="item in table_rows" :key="item.Field" :label="item.Field" :value="item.Field">
                    </el-option>
                </el-select>

                <div v-if="dynamic_text" style="display: initial;">
                    <el-tag>{{ dynamic_text }}</el-tag>
                    <copy-to-clipboard :text="dynamic_text">
                        <v-tooltip v-model="show" bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on" v-clipboard:copy="dynamic_text" color="primary">
                                    <v-icon small>mdi-content-copy</v-icon>
                                </v-btn>
                            </template>
                            <span>Copy this text where you want the placeholder</span>
                        </v-tooltip>
                    </copy-to-clipboard>
                </div>


                <div style="margin-top: 10px"></div>
                <div v-if="form.model">
                    <tinymce id="d1" :other_options="tinyOptions" v-model="form.template" :plugins="plugins" :init="initObj" toolbar="mybutton"></tinymce>
                </div>
                <!-- <v-app>
                    <v-btn color="primary" @click="save">Save</v-btn>
                </v-app>
                <VDivider /> -->

                <!-- <div v-html="form.template"></div> -->
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
// import tinymce from "vue-tinymce-editor";
import {
    mapState
} from "vuex";
import CopyToClipboard from 'vue-copy-to-clipboard'

export default {
    components: {
        CopyToClipboard
    },
    data() {
        return {
            text: 'ssss',
            dialog: false,
            table_disable: false,
            show: true,
            initObj: {
                setup: function (editor) {
                    editor.addButton("mybutton", {
                        text: "My button",
                        icon: false,
                        onclick: function () {
                            editor.insertContent("&nbsp;<b>It's my button!</b>&nbsp;");
                        },
                    });
                },
            },

            templates: [{
                    title: "Some title 1",
                    description: "Some desc 1",
                    content: "<p>My content</p>",
                },
                {
                    title: "Some title 2",
                    description: "Some desc 2",
                    url: "templates/development.html",
                },
            ],
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "template paste textcolor colorpicker textpattern imagetools toc emoticons hr codesample",
            ],
            other_options: ["test 123"],
            editorContent: '',
            tinyOptions: {
                height: 500,
            },
            form: {
                template: "",
                name: "",
                placeholder: "",
            },
            opt: "",
            mailable: {},
            template_message: '',
            options: [{
                    value: "users",
                    label: "Users",
                },
                {
                    value: "sales",
                    label: "Orders",
                },
            ],
            dynamic_text: '',
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
            ],
            // txtContent: ''
        };

    },
    methods: {
        get_position(value) {
            this.dynamic_text = ' {%' + value + '%} '
            return

        },
        save() {
            // this.form.table_rows = this.table_rows
            var payload = {
                data: this.form,
                model: "mailable",
            };
            this.$store.dispatch("postItems", payload).then((response) => {
                // this.goToSales()
            });
        },
        get_rows(table) {

            // this.form = {
            //     template: "",
            //     name: "",
            //     placeholder: ""
            // }
            // this.table_disable = true
            var payload = {
                model: "table_rows",
                search: table,
                update: "updateTableRows",
            };

            this.$store.dispatch("searchItems", payload);
        },
        close() {
            this.dialog = false
        }
    },
    computed: {
        ...mapState(["table_rows", 'loading']),
    },
    created () {
        eventBus.$on('openEditMail', data => {
            this.dialog = true
            this.form = data
        });
    },
};
</script>
