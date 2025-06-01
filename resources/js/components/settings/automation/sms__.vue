<template>
<v-row justify="center">
    <v-dialog v-model="dialog" persistent max-width="400px">

        <v-card>
            <v-card-title>
                <span class="headline">Sms Template</span>
                <v-spacer></v-spacer>
                <v-btn text icon color="primary" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="5" sm="5" md="5">
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
                                        <el-option v-for="(item, item_index) in table_rows" :key="item_index" :label="item.label" :value="item.label"></el-option>
                                    </el-select>
                                </v-col>
                                <v-col cols="12" sm="12" md="12">
                                    <v-textarea label="Message" v-model="form.sms"></v-textarea>
                                </v-col>
                            </v-row>
                        </v-col>
                        <v-col cols="5" offset="1">
                            <v-card style="padding: 20px;margin-top: 30px;background: #0000000f">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint eligendi enim a nobis iusto accusantium vitae id provident maiores aspernatur explicabo, dolores dolorem error molestias? Provident reprehenderit voluptatibus perferendis ratione.
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">
                    Close
                </v-btn>
                <v-btn color="blue darken-1" text @click="close">
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-row>
</template>

<script>
import { mapState } from 'vuex'
export default {
    data() {
        return {
            dialog: false,
        }
    },
    methods: {
        close() {
            this.dialog = false
        }
    },
    computed: {
        ...mapState(['table_rows'])
    },
}
</script>
