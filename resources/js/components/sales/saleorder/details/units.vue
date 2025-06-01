<template>
<div>
    <v-card style="padding-bottom: 30px">
        <v-card-title primary-title>
            Line items
        </v-card-title>
        <v-card-text>
            <v-row no-gutters>
                <v-col>
                    <el-table :data="products" style="width: 100%">
                        <el-table-column label="Image" width="180" align="center">
                            <template slot-scope="scope">
                                <img :src="scope.row.images.image" style="width: 30px" v-if="scope.row.images" />
                                <img src="/images/noimage.png" style="width: 30px" />
                            </template>
                        </el-table-column>
                        <el-table-column prop="product_name" label="Variant" width="180" align="center">
                        </el-table-column>
                        <el-table-column prop="pivot.price" label="price" align="center">
                        </el-table-column>
                        <el-table-column prop="pivot.quantity_tobe_delivered" label="Quantity" align="center">
                        </el-table-column>
                        <el-table-column prop="pivot.quantity_delivered" label="Delivered" align="center">
                        </el-table-column>
                        <el-table-column prop="pivot.quantity_returned" label="Returned" align="center">
                        </el-table-column>
                    </el-table>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
    <v-card style="padding-bottom: 30px">
        <v-card-title primary-title>
            Invoice
        </v-card-title>
        <v-card-text>
            <myInvoice />
        </v-card-text>
    </v-card>
    <v-card style="padding-bottom: 30px">
        <v-card-title primary-title>
            Packing list
        </v-card-title>
        <v-card-text>
            <myPackage />
        </v-card-text>
    </v-card>
</div>
</template>

<script>
import myPackage from '../package.vue'
import myInvoice from './invoice.vue'
export default {
    components: {
        myPackage, myInvoice
    },
    props: ['products'],
    data() {
        return {}
    },

}
</script>
