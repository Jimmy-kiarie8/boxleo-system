<template>
<div>
    <div class="col-md-12">
        <div class="card card-stats card-raised">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="statistics">
                            <div class>
                                <div class="icon icon-primary">
                                    <v-icon color="orange">business_center</v-icon>
                                </div>
                                <h3 class="info-title">
                                    <span>
                                        <b>{{ product_count }}</b>
                                    </span>
                                </h3>
                                <h6 class="stats-title">
                                    <strong>Total Sales</strong>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <v-divider vertical></v-divider>
                    <div class="col-md-3">
                        <div class="statistics">
                            <div class>
                                <div class="icon icon-success">
                                    <v-icon color="purple">account_circle</v-icon>
                                </div>
                                <h3 class="info-title">
                                    <span>
                                        <b>{{ brand_count }}</b>
                                    </span>
                                </h3>
                                <h6 class="stats-title">
                                    <strong>Vendors</strong>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <v-divider vertical></v-divider>
                    <div class="col-md-3">
                        <div class="statistics">
                            <div class>
                                <div class="icon icon-info">
                                    <v-icon color="indigo">people</v-icon>
                                </div>
                                <h3 class="info-title">
                                    <span>
                                        <b>{{ clients_count }}</b>
                                    </span>
                                </h3>
                                <h6 class="stats-title">
                                    <strong>Clients</strong>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <v-divider vertical></v-divider>
                    <div class="col-md-2">
                        <div class="statistics">
                            <div class>
                                <div class="icon icon-danger">
                                    <v-icon color="red">today</v-icon>
                                </div>
                                <h3 class="info-title">
                                    <span>
                                        <b>{{ category_count }}</b>
                                    </span>
                                </h3>
                                <h6 class="stats-title">
                                    <strong>This week sales</strong>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    methods: {
        getProducts() {
            var payload = {
                model: "/product_count",
                update: "UpdateProductsCountList"
            };
            this.$store.dispatch("getItems", payload);
        },
        getCategory() {
            var payload = {
                model: "/category_count",
                update: "updateWeekSalesCountList"
            };
            this.$store.dispatch("getItems", payload);
        },
        getBrandCount() {
            var payload = {
                model: "/brand_count",
                update: "updateSellerCountList"
            };
            this.$store.dispatch("getItems", payload);
        },
    },
    computed: {
        product_count() {
            return this.$store.getters.product_count;
        },
        brand_count() {
            return this.$store.getters.brand_count;
        },
        clients_count() {
            return this.$store.getters.clients_count;
        },
        category_count() {
            return this.$store.getters.category_count;
        }
    },
    mounted() {
        this.getProducts();
        this.getCategory();
        this.getBrandCount();
    }
};
</script>


<style scoped>
.justify-center {
    margin-top: -100px !important;
}

.card-stats {
    margin-top: -10px;
    padding: 20px 0;
}

/* .statistics {
    background: #f0f0f0 !important;
} */

.progress-Ship {
    margin-top: 100px !important;
}

.v-icon {
    font-size: 64px !important;
}

.v-icon {
    box-shadow: 0 9px 30px -6px rgba(255, 54, 54, 0.5);
    padding: 5px;
    border-radius: 50%;
}

.info-title {
    margin-top: 20px;
    color: #000 !important;
}
.stats-title {
    color: #9a9a9a !important;
}
</style>
