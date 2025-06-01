<template>
<v-card elevation="9">
    <v-card-title>
        <v-btn color="primary" @click="getProducts" icon small>
            <v-icon dark>mdi-refresh</v-icon>
        </v-btn>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
        <v-spacer></v-spacer>
    </v-card-title>
    <v-data-table :headers="headers" :items="products" :search="search">
        <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2" @click="openProduct(item.id)" color="primary">
                mdi-eye
            </v-icon>
            <v-btn class="" color="primary" icon @click="openTransfer">
                <v-icon small>mdi-transfer</v-icon>
            </v-btn>
        </template>
    </v-data-table>
    <myTransfer :form="form" />
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex';
// import myTransfer from '../../warehouse/products/transfer'
export default {
    props: ['form_level'],
    components: { 
        myTransfer,
    },
    data() {
        return {
            search: '',
            form: {},
            headers: [{
                    text: 'Product name',
                    align: 'start',
                    value: 'name',
                },
                {
                    text: 'Created on',
                    value: 'created_at'
                },
                {
                    text: 'Actions',
                    value: 'actions'
                },
            ],
        }
    },
    methods: {
        getProducts() {
            var payload = {
                model: 'products',
                update: 'updateProductsList',
            }
            this.$store.dispatch('getItems', payload);
        },
        showDetails(id) {
            var payload = {
                model: 'products',
                update: 'updateProduct',
                id: id
            }
            this.$store.dispatch('showItem', payload);
        },
        openProduct(data) {
            // console.log(data);

            this.$router.push({
                name: "product_details",
                params: {
                    id: data
                }
            });

        },
        openTransfer() {
            eventBus.$emit('productTransferEvent')
        }
    },
    mounted() {
        this.getProducts();
    },
    computed: {
        ...mapState(['products', 'single_product'])
    },
}
</script>

<style>

</style>
