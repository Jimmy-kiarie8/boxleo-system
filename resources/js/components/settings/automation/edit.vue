<template>
<div>
    <v-container fluid fill-height style="margin-bottom: 50px;">
        <v-layout justify-center align-center wrap>

            <v-flex sm12>
                <v-card style="padding: 20px 20px;">
                    <v-card-title primary-title>
                        <h3 style="margin: auto">Create Workflow Rule</h3>
                    </v-card-title>
                    <v-divider></v-divider>
                    <!-- 1. Name your workflow -->
                    <v-card-text>

                        <v-layout row wrap>
                            <v-flex sm5 style="margin-left:10px">

                                <label for="">Name *</label>
                                <el-input placeholder="Please input" v-model="form.name"></el-input>
                            </v-flex>
                            <v-flex sm5 style="margin-left:20px">
                                <label for="">Module *</label>
                                <el-select v-model="form.model" placeholder="Select" filterable clearable @change="getRows" style="width: 100%">
                                    <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                                    </el-option>
                                </el-select>
                            </v-flex>
                        </v-layout>
                        <div style="margin-top: 30px"></div>
                        <v-divider></v-divider>

                        <!-- <h2>Workflow type </h2>

                        <el-select v-model="form.workflow_type" placeholder="Select" filterable clearable style="width: 100%">
                            <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                            </el-option>
                        </el-select> -->

                        <!-- 2. Choose when to Trigger -->
                        <v-layout row wrap>
                            <v-flex sm1 style="margin-left: 10px">
                                <el-radio v-model="form.trigger_when" label="1">Created</el-radio>
                                <el-radio v-model="form.trigger_when" label="2">Edited</el-radio>
                                <el-radio v-model="form.trigger_when" label="3">Created or Edited</el-radio>
                                <el-radio v-model="form.trigger_when" label="4">Deleted</el-radio>
                                <el-radio v-model="form.trigger_when" label="5">Date change</el-radio>
                            </v-flex>

                            <!-- <el-radio v-model="form.trigger_when" label="1">Everytime ( Execute the workflow whenever the Invoice is edited.)</el-radio>
                    <el-radio v-model="form.trigger_when" label="2">Just Once ( Execute the workflow when the Invoice is edited for the first time.)</el-radio> -->

                            <v-flex sm3 offset-sm1 v-if="form.trigger_when == '2' || form.trigger_when == '3'">
                                <label for="">Execute the workflow when *</label>
                                <el-select v-model="form.execute_when" placeholder="Select" filterable clearable style="width:100%">
                                    <el-option v-for="(item, index) in execute_when" :key="index" :label="item.label" :value="item.value">
                                    </el-option>
                                </el-select>
                            </v-flex>

                            <v-flex sm2 style="margin-left:10px" v-if="form.execute_when == '2' || form.execute_when == '3'">
                                <label for="">Columns</label>
                                <el-select filterable clearable v-model="form.selected_column" placeholder="Select fields" multiple>
                                    <el-option v-for="(item, item_index) in table_rows" :key="item_index" :label="item.label" :value="item.Field"></el-option>
                                </el-select>
                            </v-flex>
                        </v-layout>

                        <v-divider></v-divider>
                        <div v-if="form.trigger_when == '2' || form.trigger_when == '3' || form.trigger_when == '5'">
                            <h4>Define the criteria ( if any )</h4>
                            <div style="margin-top: 30px"></div>
                            <v-layout row wrap v-for="(items, index) in form.conditions" :key="index" style="margin-top: 20px">
                                <v-flex sm2 style="margin-left: 10px">
                                    <el-select filterable clearable v-model="items.operator" placeholder="Select" :disabled="index == 0">
                                        <el-option v-for="item in operators" :key="item.value" :label="item.value" :value="item.value"></el-option>
                                    </el-select>
                                    <!-- <el-input placeholder="Please input" v-model="items.operator"></el-input> -->
                                </v-flex>
                                <v-flex sm2 style="margin-left:10px">
                                    <el-select filterable clearable v-model="items.row" placeholder="Select" value-key="key">
                                        <el-option v-for="(item, item_index) in table_rows" :key="item_index" :label="item.label" :value="item"></el-option>
                                    </el-select>
                                </v-flex>

                                <v-flex sm2 style="margin-left:10px">
                                    <el-select filterable clearable v-model="items.condition" placeholder="Select">
                                        <el-option v-for="item in (items.row.Type == 'varchar(191)' || items.row.Type == 'varchar(255)' || items.row.Type == 'text') ? string_conditions : conditions" :key="item.value" :label="item.value" :value="item.op">
                                        </el-option>
                                    </el-select>
                                </v-flex>
                                <v-flex sm2 style="margin-left:10px" v-if="items.condition">
                                    <div v-if="form.conditions.length > 0">
                                        <div v-if="form.conditions[index]['row']['Type'] == 'tinyint(1)'">
                                            <el-radio v-model="items.value" label="true">True</el-radio>
                                            <el-radio v-model="items.value" label="false">False</el-radio>
                                        </div>

                                        <div v-else-if="form.conditions[index]['row']['Default'] == 'Inprogress'">
                                            <el-select filterable clearable v-model="items.value" placeholder="Select" v-if="form.conditions[index]['row']['Field'] == 'delivery_status'">
                                                <el-option v-for="item in statuses" :key="item.id" :label="item.status" :value="item.status">
                                                </el-option>
                                            </el-select>
                                            <el-select filterable clearable v-model="items.value" placeholder="Select" v-else>
                                                <el-option v-for="(item, status_key) in deliver_status" :key="status_key" :label="item.value" :value="item.value">
                                                </el-option>
                                            </el-select>
                                        </div>
                                        <div v-else-if="form.conditions[index]['row']['Type'] == 'datetime' || form.conditions[index]['row']['Type'] == 'timestamp'">
                                            <!-- <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="items.value" type="date" placeholder="Pick a day" style="width: 100%;">
                                            </el-date-picker> -->
                                            <el-input placeholder="Please input" v-model="items.value">
                                                <template slot="append">Days</template>
                                            </el-input> <span> from today</span>

                                        </div>

                                        <div v-else>
                                            <el-input :type="(form.conditions[index]['row']['Type'].indexOf('int') !== -1 || form.conditions[index]['row']['Type'].indexOf('decimal') !== -1) ? 'number' :  'text'" placeholder="Please input" v-model="items.value"></el-input>
                                        </div>
                                    </div>
                                </v-flex>

                                <v-flex sm1 style="margin: 5px 0 0 10px" v-if="index > 0">
                                    <v-btn text icon color="pink" @click="remove(index)">
                                        <v-icon small>mdi-delete</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                            <v-btn small text color="primary" @click="add_row" style="margin-top: 20px;">
                                <v-icon>mdi-plus</v-icon>
                                <span>Add Criteria</span>
                            </v-btn>
                        </div>

                        <div style="height: 30px"></div>

                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(action, index) in form.actions" :key="index">
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>
                                        <el-select filterable clearable v-model="action.action" placeholder="Select" style="width:100%">
                                            <el-option v-for="item in actions" :key="item.value" :label="item.value" :value="item.value">
                                            </el-option>
                                        </el-select>
                                    </td>
                                    <td>
                                        <el-select filterable clearable v-model="action.mailable_id" placeholder="Select" style="width:100%" v-if="action.action == 'Send Email'">
                                            <el-option v-for="item in templates" :key="item.id" :label="item.name" :value="item.id">
                                            </el-option>
                                        </el-select>
                                        <el-select filterable clearable v-model="action.smstemplate_id" placeholder="Select" style="width:100%" v-if="action.action == 'Send SMS'">
                                            <el-option v-for="item in sms_template" :key="item.id" :label="item.name" :value="item.id">
                                            </el-option>
                                        </el-select>
                                        <!-- Boom {{ action.update_field.index }} -->

                                        <div v-if="action.action == 'Update Order'">
                                            <el-select filterable clearable v-model="action.update_field" placeholder="Update field" value-key="key">
                                                <el-option v-for="(item, item_index) in table_rows" :key="item_index" :label="item.label" :value="item"></el-option>
                                            </el-select>
                                            <span>Update to</span>

                                            <div style="margin-left:10px; float: right" v-if="action.update_field">
                                                <div v-if="action.update_field">
                                                    <div v-if="action.update_field['Type'] == 'tinyint(1)'">
                                                        <el-radio v-model="action.value" label="true">True</el-radio>
                                                        <el-radio v-model="action.value" label="false">False</el-radio>
                                                    </div>

                                                    <div v-else-if="action.update_field['Default'] == 'Inprogress'">
                                                        <el-select filterable clearable v-model="action.value" placeholder="Select" v-if="action.update_field['Field'] == 'delivery_status'">
                                                            <el-option v-for="item in statuses" :key="item.id" :label="item.status" :value="item.status">
                                                            </el-option>
                                                        </el-select>
                                                        <el-select filterable clearable v-model="action.value" placeholder="Select" v-else>
                                                            <el-option v-for="(item, status_key) in deliver_status" :key="status_key" :label="item.value" :value="item.value">
                                                            </el-option>
                                                        </el-select>
                                                    </div>
                                                    <div v-else-if="action.update_field['Type'] == 'datetime' || action.update_field['Type'] == 'timestamp'">
                                                        <!-- <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="action.value" type="date" placeholder="Pick a day" style="width: 100%;">
                                                        </el-date-picker> -->
                                                        <el-input placeholder="Please input" v-model="action.value">
                                                            <template slot="append">Days</template>
                                                        </el-input>
                                                        from today
                                                    </div>

                                                    <div v-else>
                                                        <el-input :type="(action.update_field['Type'].indexOf('int') !== -1 || action.update_field['Type'].indexOf('decimal') !== -1) ? 'number' :  'text'" placeholder="Please input" v-model="action.value"></el-input>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <v-btn text icon color="pink" @click="remove_action(index)">
                                            <v-icon small>mdi-delete</v-icon>
                                        </v-btn>
                                    </td> 
                                </tr>
                            </tbody>
                        </table>

                        <v-btn small text color="primary" @click="add_action" style="margin-top: 20px;">
                            <v-icon>mdi-plus</v-icon>
                            <span>Add Action</span>
                        </v-btn>
                    </v-card-text>

                </v-card>
            </v-flex>
        </v-layout>

        <v-footer style="background: #e2e0e0 !important;" app>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="save">
                <v-icon>mdi-content-save</v-icon>
                <span>Save</span>
            </v-btn>
            <v-spacer></v-spacer>
        </v-footer>
    </v-container>
    <!-- <mySms /> -->
</div>
</template>

<script>
// import mySms from './sms'
import {
    mapState
} from "vuex";

export default {
    components: {
        // mySms,
    },
    data() {
        return {
            options: [{
                    value: "users",
                    label: "Users",
                },
                {
                    value: "sales",
                    label: "Orders",
                },
            ],
            message: '',
            opt: '',
            deliver_status: [{
                    value: 'Open'
                },
                {
                    value: 'Inprogress'
                },
                {
                    value: 'Shipped'
                },
                {
                    value: 'Delivered'
                },
                {
                    value: 'Confirmed'
                },
                {
                    value: 'Closed'
                },
            ],
            operators: [{
                    value: 'AND'
                },
                {
                    value: 'OR'
                }
            ],
            operators: [{
                    value: 'AND'
                },
                {
                    value: 'OR'
                }
            ],
            // conditions: [{
            //         value: 'is updated to',
            //     },
            //     {
            //         value: 'is updated from',
            //     }

            // ],
            actions: [{
                    value: 'Send SMS',
                },
                {
                    value: 'Send Email',
                },
                {
                    value: 'Update Order',
                }

            ],
            execute_when: [{
                    label: 'When any field is updated',
                    value: '1',
                },
                {
                    label: 'When any selected field is updated',
                    value: '2',
                },
                {
                    label: 'When all selected fields are updated',
                    value: '3',
                }
            ],
            form: {},
            controlOnStart: true,

            string_conditions: [{
                    value: 'is',
                    op: '='
                },
                {
                    value: 'is not',
                    op: '!='
                },
                {
                    value: 'contains',
                    op: 'LIKE'
                },
                {
                    value: "doesn't contain",
                },
                {
                    value: 'Starts with',
                    op: 'start'
                },
                {
                    value: 'Ends with',
                    op: 'end'
                },
                {
                    value: 'is empty',
                    op: 'null'
                },
                {
                    value: 'is not empty',
                    op: 'not_empty'
                }
            ],
            conditions: [{
                    value: 'is',
                    op: '='
                },
                {
                    value: 'is not',
                    op: '!='
                },
                {
                    value: 'is greater than',
                    op: '>'
                },
                {
                    value: 'is greater than or equal to',
                    op: '>='
                },
                {
                    value: 'is less than or equal to',
                    op: '<='
                },
                {
                    value: 'is less than',
                    op: '<'
                },
                {
                    value: 'is in',
                    op: 'in'
                },
                {
                    value: 'is not in',
                    op: 'in'
                }
            ],
        }
    },

    methods: {
        add_action() {
            this.form.actions.push({
                type: '',
                mail_template: '',
                sms: '',
                update_field: ''
            })
        },
        save() {
            this.form.message = this.message
            var payload = {
                data: this.form,
                id: this.$route.params.id,
                model: 'automation',
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    // this.goToSales()
                });
        },
        remove(index) {
            this.form.conditions.splice(index, 1)
        },
        remove_action(index) {
            this.form.actions.splice(index, 1)
        },
        getApp() {
            var payload = {
                model: 'app_custom',
                id: 1,
                update: 'updateTableRows'
            }

            this.$store.dispatch('showItem', payload)
        },

        getSms() {
            var payload = {
                model: 'sms_template',
                update: 'updateSmsTemplate',
            }
            this.$store.dispatch('getItems', payload);
        },
        getStatus() {
            var payload = {
                model: 'statuses',
                update: 'updateStatusList'
            }
            this.$store.dispatch("getItems", payload);
        },
        getAutomation() {
            var payload = {
                model: 'automation',
                id: this.$route.params.id,
                update: 'updateAutomationList'
            }
            this.$store.dispatch("showItem", payload).then((res) => {

                this.form = res.data
                this.getRows(res.data.model)
            });
        },
        add_row() {
            this.form.conditions.push({
                condition: '',
                row: {
                    Type: ''

                },
                operator: 'AND',
                value: ''
            })
        },

        clone({
            label
        }) {
            return {
                label,
                // id: idGlobal++
            };
        },
        pullFunction() {
            return this.controlOnStart ? "clone" : true;
        },
        start({
            originalEvent
        }) {
            this.controlOnStart = originalEvent.ctrlKey;
        },
        getRows(table) {
            this.getMails(table);
            var payload = {
                model: 'table_rows',
                search: table,
                update: 'updateTableRows'
            }

            this.$store.dispatch('searchItems', payload)
        },
        get_templates(table) {
            var payload = {
                model: 'templates',
                search: table,
                update: 'updateTableRows'
            }

            this.$store.dispatch('searchItems', payload)
        },
        getMails(table) {
            var payload = {
                model: 'mailable',
                id: table,
                update: 'updateTemplates'
            }

            this.$store.dispatch('showItem', payload)
        },

    },
    computed: {
        ...mapState(['table_rows', 'statuses', 'templates', 'sms_template', 'automations'])
    },

    mounted() {
        this.getAutomation();
        this.getStatus();
        // this.getMails();
        this.getSms();
    },

};
</script>
