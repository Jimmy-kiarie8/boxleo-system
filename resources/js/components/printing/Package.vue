<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Package printing page</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <!-- <v-btn dark text @click="printPage">Print</v-btn> -->
                    <v-tooltip bottom>
                        <v-btn icon class="mx-0" @click="printPage" slot="activator">
                            <v-icon small color="white darken-2">print</v-icon>
                        </v-btn>
                        <span>Download</span>
                    </v-tooltip>
                    <!-- <v-btn dark text @click="download">Download</v-btn> -->
                    <v-tooltip bottom>
                        <v-btn icon class="mx-0" @click="download" slot="activator">
                            <!-- <v-icon small color="blue darken-2">download</v-icon> -->
                            <i class="fas fa-download"></i>
                        </v-btn>
                        <span>Download</span>
                    </v-tooltip>
                </v-toolbar-items>
            </v-toolbar>
            <div class="container" id="print">
                <div class="invoice-box" v-loading="loading" v-if="dialog">
                    <div class="invoice-box" v-loading="loading">
                        <div class="container" style="width: 100%">
                            <table cellpadding="0" cellspacing="0" class="table table-hover">
                                <tr class="top">
                                    <td colspan="4">
                                        <table>
                                            <tr>
                                                <td class="title">
                                                    <img :src="image" style="width:100%; max-width:200px;">
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
                                                    Order Date : {{ orders.created_at | moment }}<br> Sales Order# : {{ orders.order_no }}
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
        </v-card>
        <form action="/packageDownload" method="post" ref="form_data" target="_blank">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="inventory" :value="serialize_data">
        </form>
    </v-dialog>
</v-layout>
</template>

<script>
import moment from 'moment'
import {
    image
} from "./invoice/image";
export default {
    // props: ['image'],
    name: 'printPackage',
    data() {
        return {
            items: [{
                    description: "Website design",
                    quantity: 1,
                    price: 300
                },
                {
                    description: "Website design",
                    quantity: 1,
                    price: 75
                },
                {
                    description: "Website design",
                    quantity: 1,
                    price: 10
                }
            ],
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            dialog: false,
            image: '',
            client: [],
            orders: [],
            receiveorders: [],
            package_data: [],
            loading: false,
            activeName: 'first'
        }
    },
    computed: {
        total() {
            return this.items.reduce(
                (acc, item) => acc + item.price * item.quantity,
                0
            );
        },
        invoice_total() {
            var total = 0
            if (this.package_data.length > 0) {
                this.package_data.product.forEach(element => {
                    console.log(element);
                    alert(element.price)
                    total = parseFloat(total) + (parseFloat(element.price) * parseFloat(element.order_qty))
                });
                return total
            }
        },

        serialize_data() {
            return JSON.stringify(this.package_data)
            // return JSON.stringify(this.form);
            //     this.$refs.form.submit()
            // eventBus.$emit('printPackageEvent', packaged)
        },
    },
    methods: {
        handleClick(tab, event) {
            console.log(tab, event);
            this.loading = true
            setTimeout(() => {
                this.loading = false
            }, 1000);
        },
        addRow() {
            this.items.push({
                description: "",
                quantity: 1,
                price: 0
            });
        },
        loadPage() {
            this.loading = true
            setTimeout(() => {
                this.loading = false
            }, 1000);
        },
        download() {
            this.$refs.form_data.submit()
        },
        printPage() {
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
        },

    },
    filters: {
        currency(value) {
            return value.toFixed(2);
        },
    },
    created() {
        eventBus.$on('printPackageEvent', data => {
            // console.log(data);
            this.dialog = true
            // setTimeout(() => {
            //     this.loading = false
            // }, 1000);
            this.package_data = data
            this.receiveorders = data.order.receiveorders
            this.orders = data.order
            this.client = data.client
        });
        eventBus.$on('invoiceLoadEvent', data => {
            this.loading = true
            setTimeout(() => {
                this.loading = false
            }, 1000);
        });
        // eventBus.$on('invoiceLoadEvent', data => {
        //     this.loading = true
        //     setTimeout(() => {
        //         this.loading = false
        //     }, 1000);
        // });
    },
    mounted() {
        // console.log(image);
        this.image = image
    },

    filters: {
        moment(date) {
            return moment(date).format('Do MMMM YYYY');
        },
        moment2(date) {
            return moment(date).format('MMMM Do YYYY', 'h:mm:ss a');
        },
    },
}
</script>

<style scoped>
.invoice-box {
    max-width: 800px;
    margin: auto;
    padding: 30px;
    border: 1px solid #eee;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
    font-size: 16px;
    line-height: 24px;
    font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    color: #555;
}

.invoice-box table {
    width: 100%;
    line-height: inherit;
    text-align: left;
}

.invoice-box table td {
    padding: 5px;
    vertical-align: top;
}

.invoice-box table tr td:nth-child(n + 2) {
    text-align: right;
}

.invoice-box table tr.top table td {
    padding-bottom: 20px;
}

.invoice-box table tr.top table td.title {
    font-size: 45px;
    line-height: 45px;
    color: #333;
}

.invoice-box table tr.information table td {
    padding-bottom: 40px;
}

.invoice-box table tr.heading td {
    background: #eee;
    border-bottom: 1px solid #ddd;
    font-weight: bold;
}

.invoice-box table tr.details td {
    padding-bottom: 20px;
}

.invoice-box table tr.item td {
    border-bottom: 1px solid #eee;
}

.invoice-box table tr.item.last td {
    border-bottom: none;
}

.invoice-box table tr.item input {
    padding-left: 5px;
}

.invoice-box table tr.item td:first-child input {
    margin-left: -5px;
    width: 100%;
}

.invoice-box table tr.total td:nth-child(2) {
    border-top: 2px solid #eee;
    font-weight: bold;
}

.invoice-box input[type="number"] {
    width: 60px;
}

@media only screen and (max-width: 600px) {
    .invoice-box table tr.top table td {
        width: 100%;
        display: block;
        text-align: center;
    }

    .invoice-box table tr.information table td {
        width: 100%;
        display: block;
        text-align: center;
    }
}

/** RTL **/
.rtl {
    direction: rtl;
    font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial,
        sans-serif;
}

.rtl table {
    text-align: right;
}

.rtl table tr td:nth-child(2) {
    text-align: left;
}

.ribbon {
    margin: -27px;
    position: absolute !important;
    top: -5px;
    left: -5px;
    overflow: hidden;
    width: 96px;
    height: 94px;
    border-bottom-right-radius: 92px;
}

.ribbon .ribbon-draft {
    background: #94a5a6;
    border-color: #788e8f;
}

.ribbon .ribbon-success {
    background: #1fcd6d;
    border-color: #18a155;
}

.ribbon .ribbon-open {
    background-color: #2c96dd;
    border-color: #1e7ab8;
}

.ribbon .ribbon-inner {
    text-align: center;
    color: #FFF;
    top: 24px;
    left: -31px;
    width: 135px;
    padding: 3px;
    position: relative;
    transform: rotate(-45deg);
}

.ribbon .ribbon-inner:before {
    left: 0;
    border-left: 2px solid transparent;
}

.ribbon .ribbon-inner:after,
.ribbon .ribbon-inner:before {
    content: "";
    border-top: 5px solid transparent;
    border-left: 5px solid;
    border-left-color: inherit;
    border-right: 5px solid transparent;
    border-bottom: 5px solid;
    border-bottom-color: inherit;
    position: absolute;
    top: 20px;
    transform: rotate(-45deg);
}

.ribbon .ribbon-inner:after {
    right: -4px;
    top: 22px;
    border-bottom: 3px solid transparent;
}

.ribbon .ribbon-inner:after,
.ribbon .ribbon-inner:before {
    content: "";
    border-top: 5px solid transparent;
    border-left: 5px solid;
    border-left-color: inherit;
    border-right: 5px solid transparent;
    border-bottom: 5px solid;
    border-bottom-color: inherit;
    position: absolute;
    top: 20px;
    transform: rotate(-45deg);
}
</style>
