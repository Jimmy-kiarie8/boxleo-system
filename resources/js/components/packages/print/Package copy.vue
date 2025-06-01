<template>
<div class="container" id="print">
    <div class="invoice-box">
        <div class="invoice-box" v-loading="loading">
            <div class="container" style="width: 100%">
                <table cellpadding="0" cellspacing="0" class="table table-hover">
                    <tr class="top">
                        <td colspan="4">
                            <table>
                                <tr>
                                    <td class="title">
                                        <!-- <img :src="image" style="width:100%; max-width:200px;"> -->
                                    </td>
                                    <td>
                                        <strong style="font-size: 20px;">PACKAGE</strong> <br>
                                        Package Order# {{ package_data.package_no }} <br> <br>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr class="information">
                        <td colspan="4">
                            <table>
                                <tr>
                                    <td>
                                        <b>Ship To</b> <br>

                                        <br>
                                        {{ client.name }}.<br> {{ client.address }}<br> {{ client.client_email }} <br> {{ client.phone }}
                                    </td>
                                    <td>
                                        <br>
                                        Order Date : {{ orders.created_at  }}<br> Sales Order# : {{ orders.order_no }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th scope="col">Item & Description</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Amout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="item" v-for="(item, index) in receiveorders" :key="index">
                            <td style="text-align: center;">{{ index + 1 }}</td>
                            <td style="text-align: center;">{{ item.products['product_name'] }} <br>
                                <small>{{ item.products['description'] }}</small>
                            </td>
                            <td style="text-align: center;">
                                {{ item.order_qty }}
                            </td>
                            <td style="text-align: center;">
                                {{ orders.amount }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <td></td>
                        <td></td>
                        <td style="text-align: center;"><b>Total</b></td>
                        <!-- <td><b>{{ invoice_total }}</b></td> -->
                        <!-- <td v-if="package_data != undefined"><b>{{ invoice_total }}</b></td> -->
                        <td style="text-align: center;"><b>{{ orders.amount }}</b></td>
                    </tfoot>
                </table>
                <v-divider></v-divider>
                <h5 v-if="package_data.instructions">Notes</h5>
                <br>
                <small>{{ package_data.instructions }}</small>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import image from '../../invoice/image'
export default {
    data() {
        return {
            image: '',
            client: [],
            orders: [],
            receiveorders: [],
            package_data: [],
            loading: false,
        }
    },
    created() {
        eventBus.$on('openPackageEvent', data => {
            this.package_data = data
            this.receiveorders = data.order.receiveorders
            this.orders = data.order
            this.client = data.client
        });
    },
}
</script>

<style>

</style>
