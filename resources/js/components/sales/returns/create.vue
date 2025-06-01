<template>
<v-card class="mx-auto overflow-hidden">
    <v-app-bar color="#1564c0" dark>
        <v-toolbar-title>New Sales Return</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn text icon small color="white" @click="close">
            <v-icon>mdi-close</v-icon>
        </v-btn>
    </v-app-bar>

    <v-card-text>
        <v-layout row wrap>
            <v-flex sm5 offset-sm1>
                <label for="">RMA</label>
                <el-input placeholder="Please input" v-model="form.rma"></el-input>
            </v-flex>
            <v-flex sm5 offset-sm1>
                <label for="">Date</label>
                <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.return_date" type="date" placeholder="Pick a day" style="width: 100%">
                </el-date-picker>
            </v-flex>

            <v-flex sm5 offset-sm1>
                <label for="">Warehouse Name</label>
                <el-select v-model="saleorder.warehouse_id" filterable clearable placeholder="Warehouse" style="width: 100%;">
                    <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                    </el-option>
                </el-select>
            </v-flex>
            <v-flex xs4 sm5 offset-sm1>
                <label for="">Reason</label>
                <el-input type="textarea" autosize placeholder="Please input" v-model="form.comment">
                </el-input>
            </v-flex>

            <v-flex sm10 offset-sm1 style="margin-top: 30px">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Items & Description</th>
                            <th>Shipped</th>
                            <th>Returned</th>
                            <th>Return Quantity </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in saleorder.products" :key="item.id">
                            <td>{{ item.product_name }}</td>
                            <td>{{ item.pivot.quantity_sent }}</td>
                            <td>{{ item.pivot.quantity_returned }}</td>
                            <td>
                                <!-- <el-input placeholder="Please input" v-model="item.returned" type="number" :max="item.quantity_sent"></el-input> -->
                                  <el-input-number v-model="item.returned" controls-position="right" :min="1" :max="item.pivot.quantity_sent"></el-input-number>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </v-flex>
        </v-layout>
    </v-card-text>

    <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" rounded @click="save">
            <v-icon>mdi-content-save</v-icon>
            <span>Save</span>
        </v-btn>
        <v-btn color="primary" rounded>
            <v-icon>mdi-close-circle</v-icon>
            <span>Cancel</span>
        </v-btn>
    </v-card-actions>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    data: () => ({
        form: {
            returned: 0
        },
    }),

    methods: {
        close() {
            eventBus.$emit('closeReturnSaleEvent')
        },
        save() {
            this.form.order = this.saleorder
            // this.form.sale_id = this.saleorder.id
            // this.form.warehouse_id = this.saleorder.warehouse_id
            var payload = {
                data: this.form,
                model: 'returns',
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {});
        }
    },

    mounted() {
        this.form.warehouse_id = saleorder.warehouse_id;
    },

    computed: {
        ...mapState(['warehouses', 'saleorder']),
    }
}
</script>
