<template>
<div class="container" id="print">
    <div class="invoice-box">
        <div class="invoice-box" v-loading="loading">
            <VCard>
                <table cellpadding="0" cellspacing="0" class="table table-hover">
                    <tr class="top">
                        <td colspan="4">
                            <table style="width: 100%">
                                <tr>
                                    <td class="title">
                                        <!-- <img :src="image" style="width:100%; max-width:200px;"> -->
                                    </td>
                                    <td align="right">
                                        <strong style="font-size: 20px;">PACKAGE</strong> <br>
                                        Package Order# {{ package_data.package_no }} <br> <br>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr class="information">
                        <td colspan="4">
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        <b>Ship To</b> <br>

                                        <br>
                                        {{ client.name }}.<br> {{ client.address }}<br> {{ client.client_email }} <br> {{ client.phone }}
                                    </td>
                                    <td align="right">
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
            </VCard>
        </div>
    </div>
</div>
</template>

<script>
import {
    image
} from './image'
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
    methods: {
        printPage() {
            // Get HTML to print from element
            const prtHtml = document.getElementById('print').innerHTML;
            console.log(prtHtml);

            // Get all stylesheets HTML
            let stylesHtml = '';
            for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
                stylesHtml += node.outerHTML;
            }

            // Open the print window
            const WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

            WinPrint.document.write(`<!DOCTYPE html>
            <html>
            <head>
                ${stylesHtml}
            </head>
            <body>
                ${prtHtml}
            </body>
            </html>`);

            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        },
    },
    created() {
        eventBus.$on('openPackageEvent', data => {
            this.package_data = data
            this.receiveorders = data.order.receiveorders
            this.orders = data.order
            this.client = data.client
        });
        eventBus.$on('packPrintEvent', data => {
            this.printPage()
        });
    },

    mounted() {
        this.image = image
    },
}
</script>

<style>

</style>
