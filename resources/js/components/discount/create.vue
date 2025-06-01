<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="900px">
        <v-card>
            <v-container grid-list-md>
                <v-card-title>
                    <span class="headline text-center" style="margin: auto;">OFFER DETAILS</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>

                    <el-tabs v-model="activeName" @tab-click="handleClick">
                        <el-tab-pane label="Basic Information" name="basic">
                            <MyBasic :form="form" />
                        </el-tab-pane>
                        <el-tab-pane label="Conditions" name="conditions">
                            <MyCondition :form="form" />
                        </el-tab-pane>
                        <el-tab-pane label="Reward" name="Reward">
                            <MyReward :form="form" />
                        </el-tab-pane>
                    </el-tabs>

                </v-card-text>
                <v-card-actions>
                    <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="save" :loading="loading" :disabled="loading">Save</v-btn>
                </v-card-actions>
            </v-container>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import MyBasic from './inc/Basic'
import MyCondition from './inc/Conditions'
import MyReward from './inc/Reward'
export default {
    components: {
        MyBasic, MyCondition, MyReward
    },
    data: () => ({
        activeName: 'basic',
        dialog: false,
        loading: false,
        form: {
            is_active: 'is_active',
            priority: 0,
            offer_name: '',
            offer_display_name: '',
            start_date: '',
            end_date: '',
        },
        payload: {
            model: 'discounts',
        },

        options: [{
            value: 'Fixed',
            label: 'Fixed'
        }, {
            value: 'Percentage',
            label: 'Percentage'
        }],
        form: {
            discount_type: '',
            percentage_discount: '0.0%',
            category: '',
            buy_quantity: '',
            get_quantity: '',
            products: {},
            condition_arr: [{
                condtion_1: '',
                condtion_2: '',
                condtions: '',
            }],
            fixed_price: '$ 0.00'
        },
    }),

    created() {
        eventBus.$on("openCreateDiscount", data => {
            this.dialog = true;
            this.getClients()
            this.getCategories()
        });
        eventBus.$on("addConditionEvent", data => {
            this.form.condition_arr.push({
                condtion_1: '',
                condtion_2: '',
                condtions: '',
            })
        });
    },

    methods: {
        getCategories() {
            var payload = {
                model: 'categories',
                update: 'updateCategoryList'
            }
            this.$store.dispatch("getItems", payload);
        },
        handleClick() {},

        getClients() {
            var payload = {
                model: 'clients',
                update: 'updateClientList'
            }
            this.$store.dispatch("getItems", payload);
        },
        // save() {
        //     this.payload['data'] = this.form
        //     this.$store.dispatch('postItems', this.payload)
        // },
        save() {
            var payload = {
                model: 'discounts',
                data: this.form,
            }
            this.$store.dispatch("postItems", payload);
        },
        close() {
            this.dialog = false;
        }
    },
};
</script>
