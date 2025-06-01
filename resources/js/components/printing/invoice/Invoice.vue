<template>
<div style="width: 100%; padding: 0 10px;">
    <div class="invoice-box page-break" v-loading="loading">
        <div class="container" style="width: 100%">
                <div class="ribbon" v-if="saleorder.status === 'Closed'">
                    <div class="ribbon-inner ribbon-success">{{ saleorder.status }}</div>
                </div>
                <div class="ribbon" v-else-if="saleorder.status === 'Confirmed'  || saleorder.status === 'Shipped'">
                    <div class="ribbon-inner ribbon-open">{{ saleorder.status }}</div>
                </div>
                <div class="ribbon" v-else>
                    <div class="ribbon-inner ribbon-draft">{{ saleorder.status }}</div>
                </div>

            <table cellpadding="0" cellspacing="0" class="table table-hover">
                <tr class="top">
                    <td colspan="4">
                        <table>
                            <tr>
                                <td class="title">
                                    <img :src="company.logo" style="width:100%; max-width:200px;">
                                </td>
                                <td>
                                    <strong style="font-size: 20px;">SalesOrder </strong>
                                    # {{ saleorder.order_no }} <br> <br>
                                    <div v-if="saleorder.invoiced">
                                    <b>Balance Due</b>
                                    KES {{ saleorder.invoice.balance }}
                                    </div>
                                    <br>
                                    <b>Order Date :</b> {{ saleorder.created_at | moment }}<br> Shipment Date : {{ saleorder.pickup_date }}
                                    <br>
                                    <b>Delivery Method : </b> {{ saleorder.delivery_method }}
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
                                    <b>Sender Details</b> <br>
                                    {{ company.name }}<br> {{ company.address }}<br> {{ company.email }} <br> {{ company.phone }}
                                </td>
                                <td>
                                    <b>Client Details</b> <br>

                                    {{ saleorder.client.name }}<br> {{ saleorder.client.address }}<br> {{ saleorder.client.email }} <br> {{ saleorder.client.phone }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <el-table :data="saleorder.products" style="width: 100%">
                <el-table-column prop="product_name" label="Item" width="180">
                </el-table-column>
                <el-table-column prop="price" label="Price" width="180">
                </el-table-column>
                <el-table-column label="Qty ordered">
                    <template slot-scope="scope">
                        <small>{{ scope.row.pivot.quantity_tobe_delivered }} Ordered</small><br>
                        <small>{{ scope.row.pivot.quantity_sent }} Shipped</small><br>
                        <small style="color: rgb(33, 95, 33);">{{ scope.row.pivot.quantity_delivered }} Delivered</small><br>
                        <small style="color: red;">{{ scope.row.pivot.quantity_returned }} Returned</small>

                    </template>
                </el-table-column>
            </el-table>
            <!-- <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th scope="col">Item & Description</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="item" v-for="(item, index) in saleorder.products" :key="index">
                        <td style="text-align: center;">{{ index + 1 }}</td>
                        <td style="text-align: center;">{{ item.product_name }} <br>
                        </td>
                        <td style="text-align: center;">
                            {{ item.quantity }}
                        </td>
                        <td style="text-align: center;">
                            {{ item.price }}
                        </td>
                        <td style="text-align: center;">{{ parseFloat(item.price) * parseFloat(item.quantity) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: center;"><b>Total</b></td>
                    <td><b>{{ saleorder.sub_total }}</b></td>
                </tfoot>
            </table> -->
            <v-divider></v-divider>
            <h5 v-if="saleorder.instructions">Notes</h5>
            <br>
            <small>{{ saleorder.instructions }}</small>
        </div>
    </div>
</div>
</template>

<script>
import moment from 'moment'
import {
    image
} from "./image";
import {
    mapState
} from 'vuex';
export default {
    name: 'orderinvoice',
    props: ['company'],
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
            image: '',
            invoice_data: [],
            loading: false,
            activeName: 'first'
        }
    },
    computed: {
        ...mapState(['saleorder']),
        total() {
            return this.items.reduce(
                (acc, item) => acc + item.price * item.quantity,
                0
            );
        },
        invoice_total() {
            var total = 0
            if (this.invoice_data.length > 0) {
                this.invoice_data.product.forEach(element => {
                    console.log(element);
                    alert(element.price)
                    total = parseFloat(total) + (parseFloat(element.price) * parseFloat(element.order_qty))
                });
                return total
            }
        }
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
    },
    filters: {
        currency(value) {
            return value.toFixed(2);
        },
    },
    created() {
        eventBus.$on('invoiceEvent', data => {
            // console.log(data);
            this.loading = true
            setTimeout(() => {
                this.loading = false
            }, 1000);
            this.invoice_data = data
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
            return moment(date).format('MMMM Do YYYY');
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
    margin: -42px;
    position: relative;
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

.page-break {
    page-break-after: always;
}
</style>
