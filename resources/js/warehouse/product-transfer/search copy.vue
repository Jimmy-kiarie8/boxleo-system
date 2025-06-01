<template>
    <v-card class="pa-8 d-flex justify-center flex-wrap">
        <v-responsive max-width="550">

            <v-autocomplete :items="searchResults" v-model="selectedProduct" append-inner-icon="mdi-magnify"
                auto-select-first class="flex-full-width" density="comfortable" item-props menu-icon=""
                placeholder="Search product by name or code" prepend-inner-icon="mdi-list-box" rounded chips
                theme="light" variant="solo" @update:search="search">
                <template v-slot:item="{ props, item }">
                    <v-list-item @click="addProduct(item.raw)" v-bind="props" :title="item?.raw?.product_name"
                        :subtitle="item?.raw?.code"></v-list-item>
                </template>
            </v-autocomplete>

            <v-table>
                <thead>
                    <tr>
                        <th class="text-left">
                            Name
                        </th>
                        <th class="text-left">
                            Calories
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in selectedProducts" :key="item.id">
                        <td>{{ item.product_name }}</td>
                        <td>!!</td>
                    </tr>
                </tbody>
            </v-table>

        </v-responsive>
    </v-card>
</template>

<script>
export default {
    data: () => ({
        selectedProduct: null,
        selectedProducts: [],
        data_search: '',
        dialog: false,
        searchResults: []
    }),


    methods: {
        async search(query) {
            if (query.length > 2) {

                try {
                    // Make an Axios request to your API or any other data source
                    const response = await axios.get(`product-search/${query}`);
                    // Assuming your API returns an array of search results
                    console.log("🚀 ~ search ~ response:", response)
                    this.searchResults = response.data.data;
                } catch (error) {
                    console.error('Error fetching search results', error);
                }
            }
        },
        addProduct(data) {
            // this.$emit('product-event', data);

            // return;
            // Check if the product is not already in the selectedProducts array
            if (!this.selectedProducts.some(product => product.id === this.data.id)) {
                this.selectedProducts.push(data);
            }

            // Clear the selected product after adding it to the array
            // this.selectedProduct = null;
        },
        // ... Your existing methods ...
    },
}
</script>
