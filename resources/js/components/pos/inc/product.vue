<template>
<!-- <v-toolbar absolute :color="color" dark scroll-off-screen scroll-target="#scrolling-techniques">
        <v-toolbar-side-icon></v-toolbar-side-icon>

        <v-toolbar-title>Categories</v-toolbar-title>

        <v-spacer></v-spacer>

        <VBtn color="normal">All</VBtn>

        <v-btn icon>
            <v-icon>favorite</v-icon>
        </v-btn>

        <v-btn icon>
            <v-icon>more_vert</v-icon>
        </v-btn>
    </v-toolbar> -->
<!-- <div id="scrolling-techniques" class="scroll-y" style="max-height: 100vh;"> -->
<!-- <v-container style="height: 90vh;"> -->

<v-col cols="12" sm="12">
    <el-tabs v-model="activeName" style="overflow: hidden;" @input="getCategories" v-if="categories.data">
        <el-tab-pane :label="category.category" :name="category.category" v-for="category in categories.data" :key="category.id">
            <v-row v-if="products.data.length > 0">
                <v-col cols="12" sm="3" v-for="product in products.data" :key="product.id">
                    <v-hover v-slot:default="{ hover }" open-delay="200" close-delay="200">
                        <v-card :elevation="hover ? 16 : 2" class="mx-auto" @click="addCart(product)">
                            <v-img :aspect-ratio="16/9" :src="product.image"></v-img>
                            <v-card-title>
                                <div>
                                    <small>{{ product.product_name }}</small>
                                    <div class="d-flex">
                                        <div class="grey--text text--darken-2">
                                        </div>
                                    </div>
                                </div>
                            </v-card-title>
                        </v-card>
                    </v-hover>
                </v-col>
            </v-row>
            <myEmpty v-else></myEmpty>
        </el-tab-pane>
    </el-tabs>

    <v-row v-else-if="products.data">
        <v-col cols="12" sm="3" v-for="product in products.data" :key="product.id">
            <v-hover v-slot:default="{ hover }" open-delay="200" close-delay="200">
                <v-card :elevation="hover ? 16 : 2" class="mx-auto" @click="addCart(product)">
                    <v-img :aspect-ratio="16/9" :src="product.image"></v-img>
                    <v-card-title>
                        <div>
                            <small>{{ product.product_name }}</small>
                            <div class="d-flex">
                                <div class="grey--text text--darken-2">
                                    <!-- <small>{{ product.quantity }}|</small> -->
                                    <!-- <small style="float: right;color: rgb(219, 50, 77);">${{ product.price }}</small> -->
                                </div>
                            </div>
                        </div>
                    </v-card-title>
                </v-card>
            </v-hover>
        </v-col>
    </v-row>
    <myEmpty v-else />
    <myVariants />
</v-col>
</template>

<script>
// import myCart from './cart'
import myEmpty from './empty_products'
import myVariants from './variants'
import {
    mapState
} from 'vuex'
export default {
    components: {
        myVariants,
        myEmpty
    },
    data() {
        return {
            color: '#1867c0',
            activeName: ''
        }
    },
    methods: {
        getCategories(data) {
            var payload = {
                model: 'category_products',
                update: 'updateProductsList',
                id: data,
            }
            this.$store.dispatch('showItem', payload)
        },
        addCart(product) {
            // console.log(product);
            if (product.product_variants.length > 0) {
                eventBus.$emit('selectVariantsEvent', product)
                // this.select_variants()
                return
            }

            eventBus.$emit('addCartEvent', product)
            // this.$store.dispatch('addCart', product)
        },

        active_name() {
            console.log(this.categories.length);
            if (this.categories.data) {
                const idx = Math.floor(Math.random() * this.categories.data.length);
                console.log(idx);

                this.getCategories(this.categories.data[idx].category)

                this.activeName = this.categories.data[idx].category;
                // return this.activeName;
            }
        }
    },
    computed: {
        ...mapState(['products', 'categories', 'loading']),
        // activeName() {
        //     console.log(this.categories.length);
        //     if (this.categories.data) {
        //         const idx = Math.floor(Math.random() * this.categories.data.length);
        //         console.log(idx);

        //         this.getCategories(this.categories.data[idx].category)

        //         return this.categories.data[idx].category;
        //         // return this.activeName;
        //     }
        // }
    },

    mounted () {
        setTimeout(() => {
            this.active_name()
        }, 2000);
    },
}
</script>
