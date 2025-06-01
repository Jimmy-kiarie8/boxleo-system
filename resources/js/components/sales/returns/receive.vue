<template>
<el-dialog title="Receive Return" :visible.sync="dialog">
    <label for="">Receive Date</label>
    <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.received_on" type="date" placeholder="Pick a day" style="width: 100%">
    </el-date-picker>

    <div style="margin-top: 30px;"></div>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Items & Description</th>
                <th>Shipped</th>
                <th>Returned</th>
                <th>To be returned</th>
                <th>Return Quantity </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in return_order.sales.products" :key="item.id">
                <td>{{ item.product_name }}</td>
                <td>{{ item.pivot.quantity_sent }}</td>
                <td>{{ item.pivot.quantity_returned }}</td>

                <div v-for="sale_item in return_order.product_sales" :key="sale_item.id">
                   <!-- www {{ item.id }} :: kdkk {{ sale_item.product_id }} -->
                    <td v-if="item.id == sale_item.product_id">{{ sale_item.pivot.tobe_returned }}</td>
                </div>
                <td>
                    <el-input placeholder="Please input" v-model="item.returned"></el-input>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px;"></div>

    <div>
        <label for="">Notes (For Internal Use)</label>
        <el-input type="textarea" placeholder="Please input" v-model="form.comment" maxlength="30" show-word-limit></el-input>

    </div>
    <span slot="footer" class="dialog-footer">
        <el-button @click="dialog = false">Cancel</el-button>
        <el-button type="primary" @click="receive">Save</el-button>
    </span>
</el-dialog>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['return_order'],
    data() {
        return {
            dialog: false,
            form: {},

        }
    },
    methods: {
        receive() {
            this.form.return_order = this.return_order
            var payload = {
                data: this.form,
                model: 'returns_receive',
                id: this.return_order.id
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {});
        }
    },

    created() {
        eventBus.$on('openReceiveEvent', data => {
            this.dialog = true
        });
    },
    computed: {
        ...mapState(['saleorder']),
    },
    mounted() {
        // this.getOrder();
    },

}
</script>
