<template>
<v-item-group>
    <h3 class="text-center">Pricing</h3>
    <VDivider/>
    <v-container grid-list-md>
        <table class="table table-hover table-striped">
            <thead>
                <th>Buying price</th>
                <th>Selling price</th>
                <th>Quantity</th>
                <th>Reorder point</th>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" v-model="sku_values.buying_price" class="form-control"></td>
                    <td><input type="text" v-model="sku_values.price" class="form-control"></td>
                    <td v-if="!product.virtual">{{ quantity }}</td>
                    <td v-else><input type="text" v-model="sku_values.quantity" class="form-control"></td>
                    <td><input type="text" v-model="sku_values.reorder_point" class="form-control"></td>
                </tr>
            </tbody>
        </table>
    </v-container>
</v-item-group>
</template>

<script>
export default {
    props: ['product', 'sku_values', 'warehouse_arr'],
    data() {
        return {
        }
    },

    computed: {
        quantity() {
            var qty = 0
            for (let index = 0; index < this.warehouse_arr.length; index++) {
                const element = this.warehouse_arr[index];
                qty += parseInt(element.onhand);
            }
            return qty
        }
    },
}
</script>
