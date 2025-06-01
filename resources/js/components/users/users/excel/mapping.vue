<template>
<div>
    <!-- <el-collapse accordion>
        <el-collapse-item name="1" v-if="users.exist_users.length > 0">
            <template slot="title" style="color: red">
                <span><b style="color: red">{{ users.exist_users.length }} Users Exists</b><i class="header-icon el-icon-info"></i></span>
            </template>
            <div>
                <v-list dense two-line>
                    <v-list-item-group color="primary">
                        <v-list-item v-for="(item, i) in users.exist_users" :key="i">
                            <v-list-item-content>
                                <v-list-item-title>{{ item.user_no }} </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
            </div>
        </el-collapse-item>
    </el-collapse> -->
    <v-data-table :headers="headers" :items="users.Users" :single-expand="singleExpand" :expanded.sync="expanded" item-key="id" show-expand class="elevation-1">
        <template v-slot:top>
            <v-toolbar flat>
                <v-toolbar-title>Expandable Table</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-switch v-model="singleExpand" label="Single expand" class="mt-2"></v-switch>
            </v-toolbar>
        </template>
        <template v-slot:expanded-item="{ item }">
            <!-- <tr v-for="(user, index) in users.Users" :key="index">
        </tr> -->

            <table class="table table-responsive table-hover" style="width: 400px;">
                <tbody>
                    <tr v-for="(user, index) in users.Users" :key="index" v-if="user.user_id == item.user_id">
                        <td colspan="1">
                            <el-select v-model="user.user_name" placeholder="Select" filterable clearable>
                                <el-option v-for="(item, prod) in users.data" :key="prod" :label="item.user_name" :value="item.user_name">
                                </el-option>
                            </el-select>
                        </td>
                        <td colspan="1">
                            <el-input-number v-model="user.quantity" :min="1"></el-input-number>
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>
    </v-data-table>
</div>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['users'],
    data() {
        return {
            expanded: [],
            singleExpand: false,
            headers: [{
                    text: 'User id',
                    value: 'user_id'
                },
                {
                    text: 'Quantity',
                    value: 'Users.quantity'
                },
                {
                    text: 'User name',
                    value: 'user_name'
                },
                {
                    text: 'Address',
                    value: 'address'
                },
                {
                    text: 'Phone',
                    value: 'phone'

                },
                {
                    text: 'Special instructions',
                    value: 'special_instructions'
                },
                {
                    text: 'Total price',
                    value: 'total_amount'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ]
        }
    },

    methods: {
        getStatus() {
            var payload = {
                model: 'statuses',
                update: 'updateStatusList',
            }
            this.$store.dispatch("getItems", payload);
        },
        remove(item) {
            const index = this.users.users.indexOf(item)

            this.users.users.splice(index, 1)
        },
        // itemRowBackground: function (user) {
        //     console.log(user);

        //     console.log(!user.user.id || !user.entry.user_id || !user.entry.quantity || !user.entry.address || !user.entry.user_name || !user.entry.phone);

        //     return (!user.user.id || !user.entry.user_id || !user.entry.quantity || !user.entry.address || !user.entry.user_name || !user.entry.phone) ? 'style-1' : 'style-2'
        // }
    },
    computed: {
        ...mapState(['statuses']),
        // validate() {
        //     var valid = true
        //     for (let index = 0; index < this.users.users.length; index++) {
        //         const element = this.users.users[index];

        //         if (!element.user.id || !element.entry.user_id || !element.entry.quantity || !element.entry.address || !element.entry.user_name || !element.entry.phone) {
        //             valid = false
        //             break;
        //         } else {
        //             valid = true
        //             // return true
        //         }
        //     }
        //     return valid;
        // }
    },
    mounted() {
        this.getStatus();
    },
}
</script>
