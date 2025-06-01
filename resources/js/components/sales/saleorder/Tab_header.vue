<template>
<v-toolbar dense>

    <v-toolbar-title>{{ saleorder.order_no }}</v-toolbar-title>

    <v-spacer></v-spacer>
    <v-btn-toggle v-model="toggle_exclusive" mandatory>
        <v-btn color="grey" small @click="openEdit(saleorder)" v-if="user.can['Order edit']">
            <v-icon>mdi-pencil</v-icon>
        </v-btn>
        <!-- <v-btn color="grey" small @click="Saleorderprint">
            <v-icon>mdi-adobe-acrobat</v-icon>
        </v-btn> -->
        <v-btn color="grey" small @click="Saleorderprint">
            <v-icon>mdi-printer</v-icon>
        </v-btn>
    </v-btn-toggle>

    <v-btn text small v-if="!saleorder.confirmed">Mark as Confirmed</v-btn>

    <el-dropdown trigger="click">
        <span class="el-dropdown-link">
            Create<i class="el-icon-arrow-down el-icon--right"></i>
        </span>
        <el-dropdown-menu slot="dropdown">
            <el-dropdown-item icon="el-icon-shopping-bag-1">Package</el-dropdown-item>
            <el-dropdown-item icon="el-icon-circle-check">Shipment</el-dropdown-item>
            <el-dropdown-item icon="el-icon-printer" v-if="!saleorder.invoiced">
                <el-button type="text" @click="invoice">Instant Invoice</el-button>
            </el-dropdown-item>
            <el-dropdown-item icon="el-icon-printer">
                <el-button type="text" @click="sale_return">Sale Return</el-button>

            </el-dropdown-item>
        </el-dropdown-menu>
    </el-dropdown>
    <el-dropdown trigger="click">
        <span class="el-dropdown-link">
            More<i class="el-icon-arrow-down el-icon--right"></i>
        </span>
        <el-dropdown-menu slot="dropdown">
            <el-dropdown-item icon="el-icon-plus">Package</el-dropdown-item>
            <el-dropdown-item icon="el-icon-circle-plus">Shipment</el-dropdown-item>
            <el-dropdown-item icon="el-icon-circle-plus">Instant Invoice</el-dropdown-item>
        </el-dropdown-menu>
    </el-dropdown>

</v-toolbar>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    props: ['user'],
    data() {
        return {
            toggle_exclusive: undefined,
            form: {}
        }
    },
    computed: {
        ...mapState(['invoices', 'packages', 'saleorder'])
    },
    methods: {
        sale_return() {
            eventBus.$emit('returnSaleEvent')
        },
        invoice() {
            this.form.id = this.$route.params.id
            var payload = {
                data: this.form,
                model: 'invoices',
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit('routerChangeEvent')
                });
        },
        confirm() {
            var payload = {
                model: 'confirm',
                data: '',
                id: this.$route.params.id
            }
            this.$store.dispatch('patchItems', payload).then((res) => {
                eventBus.$emit('routerChangeEvent')
            })
        },
        Saleorderprint() {
            eventBus.$emit('SaleorderprintPage')
        },
        openEdit(data) {
            var id = data.id
            this.$router.push({
                name: "editOrder",
                params: {
                    id: id
                }
            });

            this.getWarehouses()

        },
    },

}
</script>

<style scoped>
.el-dropdown-link {
    cursor: pointer;
    color: #409EFF;
}

.el-icon-arrow-down {
    font-size: 12px;
}

.demonstration {
    display: block;
    color: #8492a6;
    font-size: 14px;
    margin-bottom: 20px;
}
</style>
