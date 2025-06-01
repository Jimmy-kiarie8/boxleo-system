<template>
<v-item-group>
    <h3 class="text-center">INVENTORY</h3>
    <VDivider />
    <v-container grid-list-md>
        <h3>Track inventory for this product</h3>
        <v-layout wrap>
            <v-switch v-model="product.active" label="Active" color="primary"></v-switch>
            <!-- <v-switch v-model="product.active" label="Inactive" v-else></v-switch> -->
        </v-layout>
        <v-layout wrap>
            <v-switch v-model="product.virtual" :label="(product.virtual) ? 'Virtual product. (Inventory for this product won\'t be tracked)' : 'Virtual product. (Inventory for this product will now be tracked)'" color="primary"></v-switch>
            <!-- <v-switch v-model="product.virtual" label="Virtual product. (Inventory for this product won't be tracked)" v-else></v-switch> -->
        </v-layout>

        <v-radio-group v-model="product.stock_management" :mandatory="true" v-if="!product.virtual">
            <v-radio label="Inventory management" value="1"></v-radio>
            <v-radio label="Warehouse management" value="2"></v-radio>
        </v-radio-group>
    </v-container>
</v-item-group>
</template>

<script>
export default {
    props: ['product'],
    data() {
        return {
            active: false,
            switch_status: true,
            plan: {}
        }
    },
    methods: {
        activeStatus() {
            this.active = true
        },
        inactiveStatus() {
            this.active = false
        },

        getPlan() {
            axios.get('tenantplan/' + this.tenant.subscriber.plan_id).then((res) => {
                this.plan = res.data
            })
        }
    },
}
</script>
