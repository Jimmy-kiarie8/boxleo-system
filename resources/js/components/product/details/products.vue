<template>
<v-card>
    <v-card-title>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
    </v-card-title>
    <v-data-table :headers="headers" :items="products.data" :search="search">
        <template v-slot:item.product_name="{ item }">
            <p @click="product_route(item.id)" style="cursor: pointer">
                {{ item.product_name }}
            </p>
        </template>
    </v-data-table>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    data() {
        return {
            search: '',
            headers: [{
                text: 'Product',
                align: 'start',
                value: 'product_name',
            }, ],
        }
    },
    methods: {
        product_route(id) {
            this.$router.push({
                name: "productDetails",
                params: {
                    id: id
                }
            });
            eventBus.$emit('routerChangeEvent')
        }
    },

    computed: {
        ...mapState(['products', 'single_product'])
    },
}
</script>
