<template>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="4">
                <table class="table table-hover table-striped">
                    <tr>
                        <td class="title">
                            <img :src="logo" style="width:100%; max-width:250px;">
                        </td>

                        <td>
                            Receipt #: 123<br> Created: {{ date }}<br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="4">
                <table class="table table-hover table-striped">
                    <tr>
                        <td>
                            Sparksuite, Inc.<br> 12345 Sunny Road<br> Sunnyville, CA 12345
                        </td>

                        <td>
                            Acme Corp.<br> John Doe<br> john@example.com
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- <tr class="heading">
            <td colspan="3">Payment Method</td>
            <td>Check #</td>
        </tr> -->

        <!-- <tr class="details">
            <td colspan="3">Check</td>
            <td>1000</td>
        </tr> -->

        <tr class="heading">
            <td>Item</td>
            <td>Unit Cost</td>
            <td>Quantity</td>
            <td>Price</td>
        </tr>

        <tr class="item" v-for="(item, index) in cart.cart" :key="index">
            <td>{{item.product_name}}</td>
            <td>KES {{item.price}}</td>
            <td>{{item.ordered}}</td>
            <td>KES {{ item.price * item.ordered | currency }}</td>
        </tr>

        <!-- <tr>
            <td colspan="4">
                <button class="btn-add-row" @click="addRow">Add row</button>
            </td>
        </tr> -->

        <tr class="total">
            <td colspan="3"></td>
            <td>Total: KES {{ total | currency }}</td>
        </tr>
    </table>
</div>
</template>

<script>
export default {
    props: ['cart'],
    data() {
        return {
            logo: process.env.MIX_LOGO,
            date: new Date().toLocaleString(),
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
            ]
        }
    },
    created() {
        eventBus.$on('printReceiptEvent', data => {})
    },
    computed: {
        total() {
            return this.cart.cart.reduce(
                (acc, item) => acc + item.price * item.ordered,
                0
            );
        }
    },
    filters: {
        currency(value) {
            return value.toFixed(2);
        }
    },
    methods: {
        addRow() {
            this.items.push({
                description: "",
                quantity: 1,
                price: 0
            });
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
}
</script>

<style>
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

.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}

.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}

.table tbody+tbody {
    border-top: 2px solid #dee2e6;
}

.table-sm th,
.table-sm td {
    padding: 0.3rem;
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.table-bordered thead th,
.table-bordered thead td {
    border-bottom-width: 2px;
}

.table-borderless th,
.table-borderless td,
.table-borderless thead th,
.table-borderless tbody+tbody {
    border: 0;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}

.table-hover tbody tr:hover {
    color: #212529;
    background-color: rgba(0, 0, 0, 0.075);
}

.table-primary,
.table-primary>th,
.table-primary>td {
    background-color: #b8daff;
}

.table-primary th,
.table-primary td,
.table-primary thead th,
.table-primary tbody+tbody {
    border-color: #7abaff;
}

.table-hover .table-primary:hover {
    background-color: #9fcdff;
}

.table-hover .table-primary:hover>td,
.table-hover .table-primary:hover>th {
    background-color: #9fcdff;
}

.table-secondary,
.table-secondary>th,
.table-secondary>td {
    background-color: #d6d8db;
}

.table-secondary th,
.table-secondary td,
.table-secondary thead th,
.table-secondary tbody+tbody {
    border-color: #b3b7bb;
}

.table-hover .table-secondary:hover {
    background-color: #c8cbcf;
}

.table-hover .table-secondary:hover>td,
.table-hover .table-secondary:hover>th {
    background-color: #c8cbcf;
}

.table-success,
.table-success>th,
.table-success>td {
    background-color: #c3e6cb;
}

.table-success th,
.table-success td,
.table-success thead th,
.table-success tbody+tbody {
    border-color: #8fd19e;
}

.table-hover .table-success:hover {
    background-color: #b1dfbb;
}

.table-hover .table-success:hover>td,
.table-hover .table-success:hover>th {
    background-color: #b1dfbb;
}

.table-info,
.table-info>th,
.table-info>td {
    background-color: #bee5eb;
}

.table-info th,
.table-info td,
.table-info thead th,
.table-info tbody+tbody {
    border-color: #86cfda;
}

.table-hover .table-info:hover {
    background-color: #abdde5;
}

.table-hover .table-info:hover>td,
.table-hover .table-info:hover>th {
    background-color: #abdde5;
}

.table-warning,
.table-warning>th,
.table-warning>td {
    background-color: #ffeeba;
}

.table-warning th,
.table-warning td,
.table-warning thead th,
.table-warning tbody+tbody {
    border-color: #ffdf7e;
}

.table-hover .table-warning:hover {
    background-color: #ffe8a1;
}

.table-hover .table-warning:hover>td,
.table-hover .table-warning:hover>th {
    background-color: #ffe8a1;
}

.table-danger,
.table-danger>th,
.table-danger>td {
    background-color: #f5c6cb;
}

.table-danger th,
.table-danger td,
.table-danger thead th,
.table-danger tbody+tbody {
    border-color: #ed969e;
}

.table-hover .table-danger:hover {
    background-color: #f1b0b7;
}

.table-hover .table-danger:hover>td,
.table-hover .table-danger:hover>th {
    background-color: #f1b0b7;
}

.table-light,
.table-light>th,
.table-light>td {
    background-color: #fdfdfe;
}

.table-light th,
.table-light td,
.table-light thead th,
.table-light tbody+tbody {
    border-color: #fbfcfc;
}

.table-hover .table-light:hover {
    background-color: #ececf6;
}

.table-hover .table-light:hover>td,
.table-hover .table-light:hover>th {
    background-color: #ececf6;
}

.table-dark,
.table-dark>th,
.table-dark>td {
    background-color: #c6c8ca;
}

.table-dark th,
.table-dark td,
.table-dark thead th,
.table-dark tbody+tbody {
    border-color: #95999c;
}

.table-hover .table-dark:hover {
    background-color: #b9bbbe;
}

.table-hover .table-dark:hover>td,
.table-hover .table-dark:hover>th {
    background-color: #b9bbbe;
}

.table-active,
.table-active>th,
.table-active>td {
    background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover {
    background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover>td,
.table-hover .table-active:hover>th {
    background-color: rgba(0, 0, 0, 0.075);
}

.table .thead-dark th {
    color: #fff;
    background-color: #343a40;
    border-color: #454d55;
}

.table .thead-light th {
    color: #495057;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.table-dark {
    color: #fff;
    background-color: #343a40;
}

.table-dark th,
.table-dark td,
.table-dark thead th {
    border-color: #454d55;
}

.table-dark.table-bordered {
    border: 0;
}

.table-dark.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(255, 255, 255, 0.05);
}

.table-dark.table-hover tbody tr:hover {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.075);
}

@media (max-width: 575.98px) {
    .table-responsive-sm {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive-sm>.table-bordered {
        border: 0;
    }
}

@media (max-width: 767.98px) {
    .table-responsive-md {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive-md>.table-bordered {
        border: 0;
    }
}

@media (max-width: 991.98px) {
    .table-responsive-lg {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive-lg>.table-bordered {
        border: 0;
    }
}

@media (max-width: 1199.98px) {
    .table-responsive-xl {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive-xl>.table-bordered {
        border: 0;
    }
}

.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table-responsive>.table-bordered {
    border: 0;
}
</style>
