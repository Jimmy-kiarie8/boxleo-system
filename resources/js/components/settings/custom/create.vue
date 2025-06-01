<template>
<div>
    <v-container fluid fill-height style="margin-bottom: 50px;">
        <v-layout justify-center align-center wrap>
            <v-flex sm12>
                <v-card style="padding: 20px 20px;">
                    <el-breadcrumb separator-class="el-icon-arrow-right">
                        <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                        <el-breadcrumb-item>Custom View</el-breadcrumb-item>
                    </el-breadcrumb>

                    <v-btn small text icon color="primary" @click="getApp">
                        <v-icon>mdi-refresh</v-icon>
                    </v-btn>
                </v-card>
            </v-flex>
            <v-flex sm12>
                <v-card style="padding: 20px 20px;">

                    <label for="">Name</label>
                    <el-input placeholder="Please input" v-model="form.name"></el-input>
                    <div style="margin-top: 30px"></div>
                    <h3 style="text-align: center">Define the criteria ( if any )</h3>
                    <hr>
                    <div style="margin-top: 30px"></div>
                    <v-layout row wrap v-for="(items, index) in form.conditions" :key="index" style="margin-top: 20px">
                        <v-flex sm2 style="margin-left: 30px">
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

                    <div style="margin-top: 30px"></div>

                    <!-- <el-transfer :titles="['AVAILABLE COLUMNS', 'SELECTED COLUMNS']" filterable v-model="form.data" :data="table_rows"></el-transfer> -->
                    <v-layout row wrap>
                        <v-flex sm5 style="margin-left: 30px">
                            <v-card>
                                <v-card-title primary-title>
                                    <h3>Available Columns</h3>
                                </v-card-title>
                                <v-card-text style="max-height: 300px; overflow-y: scroll !important;">
                                    <draggable class="dragArea list-group" :list="available_rows" :clone="clone" :group="{ name: 'people', pull: pullFunction }" @start="start">
                                        <div class="list-group-item" v-for="(element, index) in available_rows" :key="index">
                                            {{ element.label }}
                                            <v-icon style="float: right">mdi-drag</v-icon>
                                        </div>
                                    </draggable>
                                </v-card-text>
                            </v-card>
                        </v-flex>
                        <v-flex sm5 offset-sm1>
                            <v-card>
                                <v-card-title primary-title>
                                    <h3>Selected Columns</h3>
                                </v-card-title>
                                <v-card-text style="max-height: 300px; overflow-y: scroll !important;">
                                    <draggable class="dragArea list-group" :list="form.selected_columns" group="people">
                                        <div class="list-group-item" v-for="(element, index) in form.selected_columns" :key="index">
                                            {{ element.label }}
                                            <v-icon style="float: right">mdi-drag</v-icon>
                                        </div>
                                    </draggable>
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-layout>

                    <div style="margin-top: 30px"></div>

                    <h3>Share this with:</h3>
                    <div style="margin-top: 30px"></div>

                    <el-radio v-model="radio" label="1">Option A</el-radio>
                    <el-radio v-model="radio" label="2">Option B</el-radio>
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
</div>
</template>

<script>
import {
    mapState
} from "vuex";
import draggable from 'vuedraggable'

export default {

    components: {
        draggable
    },
    data() {
        return {
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
                selected_columns: []
            },
            available_rows: [],
            controlOnStart: true

        }
    },

    methods: {
        save() {
            var payload = {
                data: this.form,
                model: 'app_custom',
            }
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
                model: 'app_custom',
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
        }
    },
    computed: {
        ...mapState(['table_rows', 'statuses'])
    },

    mounted() {
        this.getRows();
        this.get_row();
        this.getStatus();
    },

};
</script>
