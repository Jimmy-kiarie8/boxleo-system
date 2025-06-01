<template>
<div>
    <div>
        <v-container fluid fill-height class="container">
            <v-layout justify-center align-center wrap>
                <!-- <div class="hide-overflow" style="position: relative;padding:20px">
                    <v-row>
                        <v-col cols="9" sm="9">
                            <v-text-field label="Search a product here..." prepend-icon="search" v-model="search" @keyup.enter="filteredList"></v-text-field>
                        </v-col>
                        <myProduct v-show="products_show" style="padding-left: 30px"></myProduct>
                        <myCustomer v-show="client_show"></myCustomer>
                        <v-col cols="12" sm="4" style="z-index: 0; float: right; padding-right: 70px">
                            <myCart></myCart>
                        </v-col>
                    </v-row>
                </div>

                <v-flex sm12 v-show="products_show">
                    <v-pagination v-model="products.current_page" :length="products.last_page" total-visible="5" @input="next_page(products.path, products.current_page)" circle v-if="products.last_page > 1"></v-pagination>
                </v-flex> -->

                <splitpanes>
                    <pane min-size="30" size="70">
                        <myProduct v-show="products_show" style="padding-left: 30px" />
                        <myCustomer v-show="client_show" />
                    </pane>
                    <pane size="30">
                        <div style="width: 100%">
                            <myCart></myCart>
                        </div>
                    </pane>
                </splitpanes>

            </v-layout>
        </v-container>
    </div>
</div>
</template>

<script>
import {
    Splitpanes,
    Pane
} from 'splitpanes'
import myProduct from './inc/product'
import myCustomer from './inc/customers'
import myCart from './inc/cart'
import myEmpty from './inc/empty_products'
import {
    mapState
} from 'vuex'
export default {
    props: ['user'],
    components: {
        Splitpanes,
        Pane,
        myCart,
        myCustomer,
        myProduct,
        myEmpty
    },
    data() {
        return {
            form: {},
            products_show: true,
            client_show: false,
            loader: false,
            search: "",
            payload: {
                model: 'products',
                update: 'updateProductsList'
            },
            products_search: [],
            keys: ["name", "id"],
            searchResults: [],
        };
    },
    methods: {
        openCreate() {
            eventBus.$emit("openCreateProduct");
        },
        openEdit(data) {
            eventBus.$emit("openEditProduct", data.row);
        },
        updateQty(data) {
            eventBus.$emit("updateQtyEvent", data);
        },

        confirm_delete(item) {
            this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                this.deleteItem(item)
            }).catch(() => {
                this.$message({
                    type: 'error',
                    message: 'Delete canceled'
                });
            });
        },

        openShow(data) {
            eventBus.$emit("openShowProduct", data);
        },
        getItems() {
            this.$store.dispatch("getItems", this.payload);
        },

        getCategories() {
            var payload = {
                model: 'categories',
                update: 'updateCategoryList'
            }
            this.$store.dispatch("getItems", payload);
        },
        getClients() {
            var payload = {
                model: 'clients',
                update: 'updateClientList'
            }
            this.$store.dispatch("getItems", payload);
        },
        getDiscount() {
            var payload = {
                model: 'discounts',
                update: 'updateDiscountList'
            }
            this.$store.dispatch("getItems", payload);
        },
        updateSelected(url) {
            // alert('test')
            if (this.checkedRows.length < 1) {
                eventBus.$emit("errorEvent", "Please select atleast one row");
                return;
            }

            this.$store
                .dispatch("updateSelected", {
                    url,
                    checked: this.checkedRows
                })
                .then(response => {
                    eventBus.$emit("alertRequest", "Updated");
                    this.checkedRows = [];
                    this.getItems();
                });
        },
        selectionChanged(params) {
            this.checkedRows = params.selectedRows;
        },
        openCustomers() {
            this.products_show = false
            this.client_show = true
        },
        openProduct() {
            this.products_show = true
            this.client_show = false
        },

        next_page(path, page) {
            var payload = {
                path: path,
                page: page,
                update: 'updateProductsList'
            }
            this.$store.dispatch("nextPage", payload);
        },
        productChange() {
            this.$search(this.term, this.products, this.options).then(results => {
                this.searchResults = results
            })
        },

        filteredList() {
            // this.$store.dispatch('page_loader', true)
            var payload = {
                search: this.search,
                model: 'product_search',
                update: 'updateProductsList'
            }
            this.$store.dispatch("searchItems", payload).then((response) => {
                // this.$store.dispatch('page_loader', false)
            }).catch((error) => {
                // this.$store.dispatch('page_loader', false)
            })
        },
    },
    computed: {

        products() {
            return this.$store.getters.products;
        },

        isLoading() {
            return this.$store.getters.loading;
        },
        ...mapState(['page_loader'])

    },
    mounted() {
        // this.$store.dispatch('getItems');
        eventBus.$emit("LoadingEvent");
        this.getClients();
        this.getDiscount();
        this.getItems();
        this.getCategories();
    },
    created() {
        eventBus.$on("productEvent", data => {
            this.getItems();
        });

        eventBus.$on("clientEvent", data => {
            this.getClients();
        });
        eventBus.$on("openProductEvent", data => {
            this.openProduct();
        });

        eventBus.$on("openPageEvent", data => {
            if (data == 'product') {
                this.openProduct()
            } else if (data == 'customer') {
                this.openCustomers()
            }
        });

        eventBus.$on("responseChooseEvent", data => {
            console.log(data);
            if (data == "Excel") {
                eventBus.$emit("openEditProduct");
            } else {
                eventBus.$emit("openCreateProduct");
            }
        });

        // eventBus.$on("selectClient", data => {
        //     this.form.client_id = data.id
        //     this.customer_name = data.name
        // });

    },

    //   beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //       if (vm.user.can["view products"]) {
    //         next();
    //       } else {
    //         next("/");
    //       }
    //     });
    //   }
};
</script>

<style scoped>
.el-input--prefix .el-input__inner {
    border-radius: 50px !important;
}

.v-toolbar__content,
.v-toolbar__extension {
    height: auto !important;
}

#address_tab span {
    font-style: inherit;
    font-weight: inherit;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 200px;
    overflow: hidden;
    display: block;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
</style>
