<template>
<v-container grid-list-sm>

    <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="Not assigned" name="1">
            <assigned :assigned="'unassigned'" />
        </el-tab-pane>
        <el-tab-pane label="Assigned" name="2">
            <assigned :assigned="'assigned'" />
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
        // unassigned
    },
    data() {
        return {
            activeName: '1'
        }
    },

    methods: {
        handleClick(item) {
            console.log(item.name);

            if (item.name == '1') {
                this.getProducts()
            } else {
                this.getAssigned()
            }
        },
        getProducts() {
            var payload = {
                model: '/shopify_api_products',
                update: 'updateShopifyProducts',
            }
            this.$store.dispatch('getItems', payload)
                .then((response) => {
                    console.log(response);
                }).catch((error) => {
                    console.log(error);
                })
        },
        getAssigned() {
            var payload = {
                model: '/products_assigned',
                update: 'updateShopifyProducts',
            }
            this.$store.dispatch('getItems', payload)
                .then((response) => {
                    console.log(response);
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
        ...mapState(['shopify_products', 'loading'])
    },

    mounted() {
        this.getProducts();

        // this.getOu();
        this.getWarehouses();
    },


}
</script>
