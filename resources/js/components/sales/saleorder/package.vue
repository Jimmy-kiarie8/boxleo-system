<template>
<div>
    <div v-if="saleorder.packed">
        <div class="el-table el-table--fit el-table--enable-row-hover el-table--enable-row-transition" style="width: 100%;">

            <div class="el-table__header-wrapper">
                <table cellspacing="0" cellpadding="0" border="0" class="el-table__header" style="width: 1280px;">

                    <thead class="has-gutter">
                        <tr class="">
                            <th colspan="1" rowspan="1" class="el-table_1_column_1 is-center is-leaf el-table__cell">
                                Picklist
                            </th>
                            <th colspan="1" rowspan="1" class="el-table_1_column_2 is-leaf el-table__cell is-center">
                                Orders
                            </th>
                            <th colspan="1" rowspan="1" class="el-table_1_column_3 is-leaf el-table__cell is-center">
                                Line Items	
                            </th>
                            <th colspan="1" rowspan="1" class="el-table_1_column_5 is-leaf el-table__cell is-center">
                                Status
                            </th>
                            <th colspan="1" rowspan="1" class="el-table_1_column_6 is-leaf el-table__cell is-center">
                                Created at	
                            </th>
                            <th class="el-table__cell gutter" style="width: 0px; display: none;"></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="el-table__body-wrapper is-scrolling-none">
                <table cellspacing="0" cellpadding="0" border="0" class="el-table__body" style="width: 1280px;">
                    <tbody>
                        <tr class="el-table__row">
                            <td rowspan="1" colspan="1" class="el-table_1_column_1 is-center  el-table__cell">
                                {{ saleorder.package.package_no }}
                            </td>
                            <td rowspan="1" colspan="1" class="el-table_1_column_2  is-center el-table__cell">
                                1
                            </td>
                            <td rowspan="1" colspan="1" class="el-table_1_column_3  is-center el-table__cell">
                                {{ saleorder.products.length }}
                            </td>
                            <td rowspan="1" colspan="1" class="el-table_1_column_5  is-center el-table__cell">
                                <el-tag>{{ saleorder.package.status }}</el-tag>
                            </td>
                            <td rowspan="1" colspan="1" class="el-table_1_column_6  is-center el-table__cell">
                                {{ saleorder.package.created_at }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="el-table__column-resize-proxy" style="display: none;"></div>
        </div>
    </div>

    <div style="text-align: center" v-else>
        <div v-if="!package_i">
            <p>No Packages created so far </p>
            <span>
                <v-btn text small color="primary" @click="package_item">Generate Package</v-btn>
            </span>
        </div>
    </div>
</div>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    props: ['package'],
    data() {
        return {
            form: {},
            package_i: false,
            dialog: false,
        }
    },
    methods: {
        // package_item() {
        //     this.package_i = true
        //     this.dialog = true
        // },

        package_item() {
            this.form.id = this.saleorder.id
            var payload = {
                model: 'packages',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // eventBus.$emit("routerChangeEvent")
                });
        },
    },

    computed: {
        ...mapState(['saleorder'])
    },
}
</script>
