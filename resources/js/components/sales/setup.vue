<template>
<v-hover style="padding: 50px 0">
    <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="100%">
        <div class="text-center" v-if="!setup.products">
            <h3>No products available</h3>
            <p style="color: #000">Want to start create an order, Get started by creating some products</p>
            <v-btn color="primary" rounded @click="openProducts">Go to products page</v-btn>
        </div>
        <v-divider></v-divider>
        <div class="text-center" v-if="!setup.vendors">
            <h3>No vendors available</h3>
            <p style="color: #000">Want to start create an order, Get started by creating some vendors</p>
            <v-btn color="primary" rounded @click="openVendor">Go to vendors page</v-btn>
        </div>
    </v-card>
</v-hover>
</template>

<script>
import { mapState } from 'vuex';
export default {
    name: 'setup',
    components: {
    },
    data() {
        return {};
    },
    methods: {
        openProducts(data) {
            this.$router.push({
                name: "products",
            });
        },
        openVendor(data) {
            this.$router.push({
                name: "vendors",
            });
        },

        openCreate() {
            eventBus.$emit("openCreateProduct");
        },
    },
    computed: {
        ...mapState(['setup'])
    },
};
</script>
