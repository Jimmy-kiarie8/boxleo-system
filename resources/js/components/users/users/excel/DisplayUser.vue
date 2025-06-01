<template>
<v-card>
    <v-card-title primary-title>

    </v-card-title>
    <v-card-text v-if="e1 == 2">
        <el-collapse accordion>

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
        </el-collapse>
        <v-data-table :headers="headers" :items="users.users" :search="search" :loading="loading">
            <!-- <v-data-table :headers="headers" :items="users" :search="search" :item-class="itemRowBackground"> -->
            <!-- <div :style="[ (!user.user.id || !user.entry.userid || !user.entry.quantity || !user.entry.address || !user.entry.username || !user.entry.phone) ? {'background': '#ff00008a'} : '']"> -->

            <template v-slot:item.actions="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="remove(item)" slot="activator">
                            <v-icon small color="pink darken-2">mdi-delete</v-icon>
                        </v-btn>
                    </template>
                    <span>Remove</span>
                </v-tooltip>
            </template>

            <!-- </div> -->
        </v-data-table>

        <!-- </div> -->

    </v-card-text>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['users', 'e1'],
    data() {
        return {
            search: '',
            valid: false,
            invalid_row: [],
            headers: [{
                    text: 'Name',
                    value: 'name'
                },
                {
                    text: 'Email',
                    value: 'email'
                },
                {
                    text: 'Phone',
                    value: 'phone'
                },
                {
                    text: 'Address',
                    value: 'address'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ]
        }
    },
    methods: {
        remove(item) {
            const index = this.users.users.indexOf(item)
            this.users.users.splice(index, 1)
        },

    },
    computed: {
        ...mapState(['loading']),
        // validate() {
        //     var valid = true
        //     for (let index = 0; index < this.users.users.length; index++) {
        //         const element = this.users.users[index];

        //         if (!element.user.id || !element.entry.userid || !element.entry.quantity || !element.entry.address || !element.entry.username || !element.entry.phone) {
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



}
</script>

<style scoped>
.el-collapse-item__header {
    color: #f00 !important;
}
</style>
