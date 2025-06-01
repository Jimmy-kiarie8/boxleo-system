<template>
<v-toolbar dense>

    <!-- <v-toolbar-title>{{ saleorder.order_no }}</v-toolbar-title> -->

    <v-btn-toggle v-model="toggle_exclusive" mandatory>

        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon small color="grey">mdi-pencil</v-icon>
                </v-btn>
            </template>
            <span>Edit return</span>
        </v-tooltip>

        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" @click="status_update('Approved')">
                    <v-icon small color="grey">mdi-check-circle</v-icon>
                </v-btn>
            </template>
            <span>Approve</span>
        </v-tooltip>

        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" @click="status_update('Rejected')">
                    <v-icon small color="grey">mdi-close-circle</v-icon>
                </v-btn>
            </template>
            <span>Decline</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon small color="grey">mdi-trash-can</v-icon>
                </v-btn>
            </template>
            <span>Delete</span>
        </v-tooltip>

        <el-button type="primary" @click="receive">Receive</el-button>

    </v-btn-toggle>
    <v-spacer></v-spacer>

    <v-btn text icon color="primary" @click="close_details">
        <v-icon>mdi-close</v-icon>
    </v-btn>

</v-toolbar>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    props: ['return_order'],
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
        status_update(status) {
            var form = {
                status: status
            }
            var payload = {
                data: form,
                id: this.return_order.id,
                model: 'return_status_update',
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {});
        },
        close_details() {
            eventBus.$emit('closeReturnEvent')
        },
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

        receive() {
            eventBus.$emit('openReceiveEvent')
        }
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
