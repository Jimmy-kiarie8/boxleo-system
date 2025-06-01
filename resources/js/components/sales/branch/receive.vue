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
                <th>Quantity Sent</th>
                <th>Quantity received</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in branch_order.products" :key="item.id">
                <td>{{ item.product_name }}</td>
                <td>{{ item.pivot.quantity_sent }}</td>
                <td>
                    <div v-for="pro_item in branch_order.pivot.products" :key="pro_item.id">
                        <div v-if="item.id == pro_item.product_id">
                            <el-input placeholder="Please input" v-model="item.quantity_received"></el-input>
                        </div>
                    </div>
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
    props: ['branch_order'],
    data() {
        return {
            dialog: false,
            form: {},

        }
    },
    methods: {
        receive() {
            this.form.branch_order = this.branch_order
            var payload = {
                data: this.form,
                model: 'branch_sale',
                id: this.branch_order.id
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
