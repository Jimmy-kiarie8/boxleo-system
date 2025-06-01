<template>
<v-row justify="center">
    <v-dialog v-model="dialog" persistent max-width="600">
        <v-card>
            <v-card-title class="headline">Custome View
                <v-spacer></v-spacer>
                <v-btn text icon color="primary" small @click="dialog = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-layout row wrap>
                    <v-flex sm6>
                        <label for="">Fields</label>
                    </v-flex>

                    <v-flex sm6>
                        <label for="">Labels</label>
                    </v-flex>
                </v-layout>
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

            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary darken-1" text @click="dialog = false">Close</v-btn>
                <v-btn color="primary darken-1" text @click="save">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-row>
</template>

<script>
import draggable from 'vuedraggable'
import {
    mapState
} from "vuex";
export default {
    components: {
        draggable,
    },
    data() {
        return {
            dialog: false,
            form: {},
            table_column: []
        }
    },

    methods: {
        save() {
            var payload = {
                model: 'columns',
                data: this.table_column
            }
            this.$store.dispatch("postItems", payload)
        },
        getColumns() {
            var payload = {
                model: 'table_column',
                update: 'updateColumnList'
            }
            this.$store.dispatch("getItems", payload)
                .then((res) => {
                    this.table_column = res.data
                });
        }
    },
    created() {
        eventBus.$on('openCustomViewEvent', data => {
            this.dialog = true
        });
    },

    mounted() {
        this.getColumns();
    },

    computed: {
        // ...mapState(['table_column'])
    },
}
</script>
