<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="750px">

        <v-card style="padding: 20px 20px;">
            <v-card-title primary-title>
                <v-btn text icon color="primary" @click="close">
                    <v-spacer></v-spacer>
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>

                <label for="">Name</label>
                <el-input placeholder="Please input" v-model="form.name"></el-input>
                <div style="margin-top: 30px"></div>
                <h3 style="text-align: center">Define the criteria ( if any )</h3>
                <hr>
                <div style="margin-top: 30px"></div>
                <v-layout row wrap v-for="(items, index) in form.conditions" :key="index" style="margin-top: 20px">
                    <v-flex sm2>
                        <el-select filterable clearable v-model="items.operator" placeholder="Select" :disabled="index == 0">
                            <el-option v-for="item in operators" :key="item.value" :label="item.value" :value="item.value"></el-option>
                        </el-select>
                        <!-- <el-input placeholder="Please input" v-model="items.operator"></el-input> -->
                    </v-flex>
                    <v-flex sm3 style="margin-left:20px">
                        <el-select filterable clearable v-model="items.row" placeholder="Select" value-key="key">
                            <el-option v-for="(item, item_index) in table_rows" :key="item_index" :label="item.label" :value="item"></el-option>
                        </el-select>
                    </v-flex>

                    <v-flex sm3>
                        <el-select filterable clearable v-model="items.condition" placeholder="Select">
                            <el-option v-for="item in conditions" :key="item.value" :label="item.value" :value="item.value">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm2 v-if="items.condition">
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
                                <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="items.value" type="date" placeholder="Pick a day" style="width: 100%;">
                                </el-date-picker>
                            </div>

                            <div v-else>
                                <el-input :type="(form.conditions[index]['row']['Type'].indexOf('int') !== -1 || form.conditions[index]['row']['Type'].indexOf('decimal') !== -1) ? 'number' :  'text'" placeholder="Please input" v-model="items.value"></el-input>
                            </div>
                        </div>
                    </v-flex>
                    <v-flex sm1 style="margin: 5px 0 0 20px" v-if="index > 0">
                        <v-btn text icon color="pink" @click="remove(index)">
                            <v-icon small>mdi-delete</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
                <v-btn small text color="primary" @click="add_row" style="margin-top: 20px;">
                    <v-icon>mdi-plus</v-icon>
                    <span>Add Criteria</span>
                </v-btn>

                <v-layout row wrap style="margin-top: 40px;padding-left: 10px;">
                    <v-flex sm12>

                        <draggable v-model="table_column">
                            <!-- <transition-group> -->
                            <v-layout row wrap v-for="item in table_column" :key="item.id" style="margin-top: 10px">
                                <v-flex sm1>
                                    <el-checkbox v-model="item.checked"></el-checkbox>
                                </v-flex>
                                <v-flex sm5>
                                    <el-select v-model="item.field" placeholder="Select">
                                        <el-option v-for="(item, index) in table_column" :key="index" :label="item.field" :value="item.field">
                                        </el-option>
                                    </el-select>
                                </v-flex>

                                <v-flex sm5>
                                    <el-input placeholder="Please input" v-model="item.label"></el-input>
                                </v-flex>
                                <v-flex sm1>
                                    <v-icon>mdi-drag</v-icon>
                                </v-flex>
                            </v-layout>
                            <!-- </transition-group> -->
                        </draggable>
                    </v-flex>
                </v-layout>

            </v-card-text>

            <v-card-actions>
                <v-btn color="primary" text rounded @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="primary" text rounded @click="save">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import {
    mapState
} from 'vuex';
import draggable from 'vuedraggable'
export default {

    components: {
        draggable,
    },
    data() {
        return {
            dialog: false,
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
                    value: 'is less than than equal to',
                    op: '<='
                },
                {
                    value: 'is less than than',
                    op: '<'
                },
                {
                    value: 'contains',
                    op: 'LIKE'
                },
                {
                    value: "doesn't contain",
                },
                {
                    value: 'is in'
                },
                {
                    value: 'is not in'
                },
                {
                    value: 'starts with'
                },

            ],
            radio: '1',
            form: {
                conditions: [{
                    condition: '',
                    row: {
                        Type: ''
                    },
                    operator: 'When',
                    value: ''
                }],
            },
            available_rows: [],
            controlOnStart: true,
            table_column: {},
        }

    },

    methods: {
        getColumns() {
            var payload = {
                model: 'table_column',
                update: 'updateColumnList'
            }
            this.$store.dispatch("getItems", payload)
                .then((res) => {
                    this.table_column = res.data
                });
        },
        save() {
            this.form.table_column = this.table_column
            var payload = {
                data: this.form,
                model: 'custom_reports',
            }
            // console.log(payload);
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // this.goToSales()
                });
        },
        remove(index) {
            this.form.conditions.splice(index, 1)
        },
        getApp() {
            var payload = {
                model: 'custom_reports',
                id: 1,
                update: 'updateTableRows'
            }

            this.$store.dispatch('showItem', payload)
        },
        getRows() {
            var payload = {
                model: 'table_rows',
                search: 'sales',
                update: 'updateTableRows'
            }

            this.$store.dispatch('searchItems', payload)
        },

        get_row() {
            axios.get('table_rows/sales').then((response) => {
                this.available_rows = response.data
            }).catch((error) => {
                console.log(error);

            })
        },

        getStatus() {
            var payload = {
                model: 'statuses',
                update: 'updateStatusList'
            }
            this.$store.dispatch("getItems", payload);
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
        close() {
            this.dialog = false
        }
    },
    computed: {
        ...mapState(['table_rows', 'statuses', 'loading'])
    },

    mounted() {
        this.getRows();
        this.get_row();
        this.getStatus();
    },
    created() {
        eventBus.$on("openCreateReport", data => {
            this.dialog = true;
            this.getColumns()
        });
    },

};
</script>
