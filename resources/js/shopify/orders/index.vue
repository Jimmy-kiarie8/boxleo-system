<template>
<v-container grid-list-sm>

    <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="Not assigned" name="1">
            <assigned :assigned="'unassigned'"  v-if="activeName == '1'"/>
        </el-tab-pane>
        <el-tab-pane label="Assigned" name="2">
            <assigned :assigned="'assigned'"   v-if="activeName == '2'"/>
        </el-tab-pane>
    </el-tabs>

</v-container>
</template>

<script>
import {
    mapState
} from 'vuex'
import assigned from './assigned';
// import unassigned from './unassigned';
export default {
    components: {
        assigned,

    },
    data() {
        return {
            activeName: '1'
        }
    },

    methods: {
        handleClick(item) {

            if (item.name == '1') {
                this.getOrders()
            } else {
                this.getAssigned()
            }
        },
        getOrders() {
            var payload = {
                model: '/orders',
                update: 'updateOrders',
            }
            this.$store.dispatch('getItems', payload)
                .then((response) => {
                    // console.log(response);
                }).catch((error) => {
                    console.log(error);
                })
        },
        getAssigned() {
            var payload = {
                model: '/orders_assigned',
                update: 'updateOrders',
            }
            this.$store.dispatch('getItems', payload)
                .then((response) => {
                    // console.log(response);
                }).catch((error) => {
                    console.log(error);
                })
        },
        getOu() {
            var payload = {
                model: '/ous',
                update: 'updateOu',
            }
            this.$store.dispatch('getItems', payload)
                .then((res) => {});
        },
        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList',
            }
            this.$store.dispatch('getItems', payload)
                .then((res) => {});
        },
    },
    computed: {
        ...mapState(['orders', 'loading'])
    },

    mounted() {
        this.getOrders();

        this.getOu();
        this.getWarehouses();
    },


}
</script>
