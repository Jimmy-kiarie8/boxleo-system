<template>
<el-card class="box-card" style="line-height: 2;">
    <strong style="font-size: 27px;">SALES RETURN</strong>
    <div>RMA# <b>{{ return_order.rma }}</b></div>
    <div style="border-left: 3px solid orange;">
        <span> <small style="margin-left: 10px; color: #666;">Return Status</small>
            <el-tag style="margin-left: 30px;">Approved</el-tag>
        </span>

    </div>
    <div><label for="">DATE</label> <span style="margin-left: 30px;">{{ return_order.return_date }}</span></div>
    <div><label for="">REASON</label> <span style="margin-left: 30px;">{{ return_order.comment }}</span></div>

    <div class="text item">

    </div>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Items & Description</th>
                <th>Shipped</th>
                <th>Returned</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in return_order.sales.products" :key="item.id">
                <td>{{ item.product_name }}</td>
                <td>{{ item.pivot.quantity_sent }}</td>
                <td>{{ item.pivot.quantity_returned }}</td>
            </tr>
        </tbody>
    </table>
</el-card>
</template>

<script>
export default {
    props: ['return_order'],

}
</script>
