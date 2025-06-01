<template>
<div>
   <!-- <splitpanes>
        <pane min-size="20" size="30">
            <myProducts />
        </pane>

        <pane>
            <div style="width:100%">
                <myInc />
            </div>
        </pane>
    </splitpanes> -->

    <myInc />

</div>
</template>

<script>
// import {
//     Splitpanes,
//     Pane
// } from 'splitpanes'
// import 'splitpanes/dist/splitpanes.css'
// import myProducts from './products';
import myInc from './inc';
import {
    mapState
} from 'vuex';
// import myDetails from './details'
export default {
    components: {
        // Splitpanes,
        // Pane,
        // myProducts,
        myInc
    },
    methods: {
        getProduct() {
            var payload = {
                model: 'products',
                update: 'updateProduct',
                id: this.$route.params.id
            }
            this.$store.dispatch('showItem', payload)
        },
        getProducts() {
            var payload = {
                model: 'products',
                update: 'updateProductsList'
            }
            // if (!this.sales.data) {
            //     if (this.sales.data.length < 1) {
            this.$store.dispatch("getItems", payload);
            //     }
            // }

        }
    },
    mounted() {
        this.getProducts();
        this.getProduct();
    },
    computed: {
        ...mapState(['products', 'single_product'])
    },
    created() {
        eventBus.$on('routerChangeEvent', data => {
            this.getProduct();
        });
    },
}
</script>

<style>
.splitpanes__pane {
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: Helvetica, Arial, sans-serif;
    /* color: red; */
    /* font-size: 5em; */
}

.splitpanes--vertical>.splitpanes__splitter {
    background: #333 !important;
    /* width: 4px; */
    width: 7px;
    border-left: 1px solid #eee;
    margin-left: -1px;
}

.splitpanes__splitter:before {
    margin-left: -2px;
}

.splitpanes__splitter:after {
    margin-left: 1px;
}
</style>
