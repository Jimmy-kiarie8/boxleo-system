<template>
<div>
    <v-layout row>
        <v-flex sm12 v-for="(item, index) in form.condition_arr" :key="index">
            <v-layout row wrap>
                <v-flex sm8>
                    if <span>
                        <el-select v-model="item.condtion_1" placeholder="Select">
                            <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                            </el-option>
                        </el-select> is

                        <el-select v-model="item.condtion_2" placeholder="Select">
                            <el-option v-for="item in options_2" :key="item.value" :label="item.label" :value="item.value">
                            </el-option>
                        </el-select>
                    </span>
                </v-flex>
                <v-flex sm4>
                    <el-select v-model="item.condtions" placeholder="Select Condition">
                        <el-option v-for="item in options_3" :key="item.value" :label="item.label" :value="item.value">
                        </el-option>
                    </el-select>
                </v-flex>
                <v-flex sm12 v-if="item.condtions == 'Coupon'">
                    <label for="">When the coupon equals</label>
                    <el-input placeholder="coupon code" v-model="item.coupon_code"></el-input>
                </v-flex>
                <v-flex sm12 v-if="item.condtions == 'product_list'">
                    <label for="">Product in Category</label>
                    <el-select v-model="item.categories" placeholder="Select Category" multiple filterable clearable style="width: 100%">
                        <el-option v-for="item in categories.data" :key="item.id" :label="item.category" :value="item.id">
                        </el-option>
                    </el-select>
                </v-flex>
                <v-flex sm12 v-if="item.condtions == 'user_list'">
                    <label for="">User in list</label>
                    <el-select v-model="item.clients" placeholder="Select Clients" multiple filterable clearable style="width: 100%">
                        <el-option v-for="item in clients.data" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </v-flex>

                <v-flex sm12>
                   <v-divider></v-divider>
                   <v-divider></v-divider>
                </v-flex>
            </v-layout>
        </v-flex>

    </v-layout>

    <v-flex sm12>
        <VBtn text icon color="primary" @click="add_condition">
            <VIcon>add</VIcon>
        </VBtn>
    </v-flex>
</div>
</template>

<script>
export default {
    props: ['form'],
    data() {
        return {
            options: [{
                value: 'Any',
                label: 'Any'
            }, {
                value: 'All',
                label: 'All'
            }],
            options_2: [{
                value: 'true',
                label: 'True'
            }, {
                value: 'false',
                label: 'False'
            }],

            options_3: [{
                value: 'Coupon',
                label: 'Coupon'
            }, {
                value: 'product_list',
                label: 'Product in List'
            }, {
                value: 'user_list',
                label: 'User list'
            }, {
                value: 'customer_group',
                label: 'Customer Group'
            }, ],

        }
    },
    methods: {
        add_condition() {
            eventBus.$emit('addConditionEvent')
        }
    },
    computed: {
        categories() {
            return this.$store.getters.categories;
        },
        clients() {
            return this.$store.getters.clients;
        },
    },
}
</script>

<style scoped>
label {
    color: #5d5252 !important;
}

/*
.wrap {
    background: #f9f9f9;
}

.wrap:nth-child(odd) {
    background: #fceff3;
} */
</style>
