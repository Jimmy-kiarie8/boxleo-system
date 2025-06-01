<template>
<div class="container">
    <v-card style="padding: 20px; width: 80%;margin: auto" id="print">
        <v-layout row wrap>
            <v-flex sm6>
                <img :src="user.company.logo" style="width: 150px" />
            </v-flex>
            <v-flex sm3 offset-sm3>
                Invoice: {{ invoice.invoice_no }} <br />
                <strong>Due Date{{ invoice.due_date }}</strong> <br />
                <span> <strong>Status:</strong> {{ invoice.status }}</span>
            </v-flex>

        </v-layout>
        <v-card-text>
            <v-layout row wrap>
                <v-flex sm5>
                    <h6 class="mb-3">Shipping address:</h6>
                    <div>
                        <strong>{{ invoice.sale.client.name }}</strong>
                    </div>
                    <div>{{ invoice.sale.client.address }}</div>
                    <div>{{ invoice.sale.client.phone }}</div>
                </v-flex>
                <v-flex sm5 offset-sm2>
                    <h6 class="mb-3">Billing address:</h6>
                    <div>
                        <strong>{{ invoice.sale.client.name }}</strong>
                    </div>
                    <div>{{ invoice.sale.client.address }}</div>
                    <div>{{ invoice.sale.client.phone }}</div>
                </v-flex>
            </v-layout>

            <v-layout row wrap>
                <v-flex sm12>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>Item & Description</th>
                                <th class="right">Unit Cost</th>
                                <th class="center">Qty</th>
                                <th class="right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in invoice.sale.products" :key="item.id">
                                <td class="center">{{ index + 1 }}</td>
                                <td class="left strong">{{ item.product_name }}</td>
                                <td class="left">{{ item.pivot.price }}</td>

                                <td class="left">{{ item.pivot.quantity }}</td>
                                <td class="right">{{ parseInt(item.pivot.quantity) * parseInt(item.pivot.price) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-flex sm6>

                </v-flex>

                <v-flex sm5>
                    <table class="table table-clear">
                        <tbody>
                            <tr>
                                <td class="left">
                                    <strong>Subtotal</strong>
                                </td>
                                <td class="right">{{ total }}</td>
                            </tr>

                            <tr>
                                <td class="left">
                                    <strong>Discount</strong>
                                </td>
                                <td class="right">
                                    <strong>{{ discount }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong>Total</strong>
                                </td>
                                <td class="right">
                                    <strong>{{ invoice.total }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </v-flex>

            </v-layout>

        </v-card-text>
    </v-card>
</div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            invoice: {}
        }
    },
    created() {
        eventBus.$on('resizeEvent', data => {
            this.invoice = data
        });
        eventBus.$on('printInvoiceEvent', data => {
            this.print_invoice()
        });
    },

    methods: {
        print_invoice() {
            // Get HTML to print from element
            const prtHtml = document.getElementById('print').innerHTML;

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
        }
    },

    computed: {
        total() {
            var total = 0
            for (let index = 0; index < this.invoice.sale.products.length; index++) {
                const element = this.invoice.sale.products[index];
                console.log(element);

                total += parseInt(element.pivot.price) * parseInt(element.pivot.quantity)
            }
            return total
        },
        discount() {
            return parseInt(this.total) - parseInt(this.invoice.total)
        }
    },
}
</script>
