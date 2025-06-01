<template>
<v-dialog v-model="dialog" persistent max-width="700px">
    <v-layout justify-center align-center wrap>
        <v-flex sm12>
            <v-card style="padding: 20px 20px;">
                <v-card-title primary-title>
                    <v-spacer></v-spacer>
                    <v-btn text icon color="primary" @click="dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-layout row wrap v-for="(items, index) in form.conditions" :key="index" style="margin-top: 20px">
                    <v-flex sm2 >
                        <el-select filterable clearable v-model="items.operator" placeholder="Select" :disabled="index == 0">
                            <el-option v-for="item in operators" :key="item.value" :label="item.value" :value="item.value"></el-option>
                        </el-select>
                        <!-- <el-input placeholder="Please input" v-model="items.operator"></el-input> -->
                    </v-flex>
                    <v-flex sm2 style="margin-left:10px">
                        <el-select filterable clearable v-model="items.row" placeholder="Select" value-key="key" style="width:100%">
                            <el-option v-for="(item, item_index) in table_rows" :key="item_index" :label="item.label" :value="item"></el-option>
                        </el-select>
                    </v-flex>

                    <v-flex sm3 style="margin-left:10px">
                        <el-select filterable clearable v-model="items.condition" placeholder="Select" style="width:100%">
                            <el-option v-for="item in conditions" :key="item.value" :label="item.value" :value="item.value">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm3 style="margin-left:10px" v-if="items.condition">
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

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" text @click="dialog = false">Done</v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
    </v-layout>
</v-dialog>
</template>

<script>
import {
    mapState
} from "vuex";

export default {
    props: ['form'],
    data() {
        return {
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
            conditions: [{
                    value: 'is updated to',
                },
                {
                    value: 'is updated from',
                }

            ],
            controlOnStart: true,
            dialog: false
        }
    },
    methods: {
        save() {
            this.form.message = this.message
            var payload = {
                data: this.form,
                model: 'services'
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // this.goToSales()
                });
        },
        text_update(text) {
            console.log(text);
            this.message = this.message + ' {{ ' + text + ' }} '
            this.opt = ''
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
    },
    computed: {
        ...mapState(['table_rows', 'statuses'])
    },

    mounted() {
        this.getRows();
        this.get_row();
        this.getStatus();
    },

    created () {
        eventBus.$on('OpenConditionEvent', data => {
            this.dialog = true
        });
    },

};
</script>
