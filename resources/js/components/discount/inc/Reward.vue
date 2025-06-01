<template>
<v-layout row wrap>
    <v-flex sm6>
        <label for="">Discount type</label>
        <el-select v-model="form.discount_type" placeholder="Select" style="width:100%" clearable filterable>
            <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
            </el-option>
        </el-select>
    </v-flex>
    <VFlex sm6>
        <label for="">Amount</label>
        <el-input placeholder="0" v-model="form.amount" type="number"></el-input>
    </VFlex>
</v-layout>
</template>

<script>
export default {
    props: ['form'],
    data() {
        return {
            options: [{
                value: 'percentage',
                label: 'Percentage'
            }, {
                value: 'fixed_amount',
                label: 'Fixed amount'
            }, {
                value: 'fixed_price',
                label: 'Fixed price'
            }]
        }
    },
}
</script>
