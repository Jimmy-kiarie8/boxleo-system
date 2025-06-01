<template>
<div class="container" id="print">
    <v-card>
        <v-layout row wrap>

            <v-col cols="10" offset="1">
                <div class="row">
                    <div class="col-md-4"> <img class="img" alt="Invoce Template" :src="user.company.logo" /> </div>
                    <div class="col-md-8 text-right">
                        <h4 style="color: #F81D2D;"><strong>{{ user.company.name }}</strong></h4>
                        <address>{{ user.company.address }}</address>
                        <p>{{ user.company.phone }}</p>
                        <p>{{ user.company.email }}</p>
                    </div>
                </div> <br />
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2>Sale Order</h2>
                        <h5>{{ saleorder.order_no }}</h5>
                    </div>
                </div>
                <v-divider></v-divider>

                <v-row row wrap>

                    <v-col sm="4">
                        <h5>Address</h5> <br>

                        Order: <b> {{ saleorder.order_no }} </b> <br>
                        Invoiced:  <v-avatar style="cursor: pointer;margin-left: 10px !important;" :color="(saleorder.invoiced) ? 'green' : 'red'" small></v-avatar>

                         <br>
                        Status: <b> {{ saleorder.delivery_status }} </b>
                    </v-col>
                    <v-col sm="3" offset="5">
                        <h5>Address</h5>
                        <div class="text item">
                            {{ saleorder.client.name }}
                        </div>

                        <div class="text item">
                            {{ saleorder.client.address }}
                        </div>
                        <div class="text item">
                            {{ saleorder.client.phone }}
                        </div>
                    </v-col>
                </v-row>

                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <h5>Description</h5>
                                </th>

                                <th scope="col">Quantity</th>
                                <th scope="col">Status</th>
                                <th>
                                    <h5>Amount</h5>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in saleorder.products" :key="index">
                                <td>{{ item.product_name }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>
                                    <small>{{ item.pivot.quantity }} Ordered</small><br>
                                    <small>{{ item.pivot.sent }} Packed</small><br>
                                    <small style="color: #215f21">{{ item.pivot.quantity_delivered }} Delivered</small><br>
                                    <small style="color: red">{{ item.pivot.quantity_returned }} Returned</small><br>
                                </td>
                                <td>{{ user.ou.currency_code }} {{ item.price }} </td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>
                                    <!-- <p> <strong>Shipment and Taxes:</strong> </p> -->
                                    <p> <strong>Total Amount: </strong> </p>
                                    <!-- <p> <strong>Discount: </strong> </p>
                                    <p> <strong>Payable Amount: </strong> </p> -->
                                </td>
                                <td>
                                    <p> <strong>{{ user.ou.currency_code }} {{ saleorder.sub_total }} </strong> </p>
                                    <!-- <p> <strong>{{ user.ou.currency_code }} 82,900</strong> </p>
                                    <p> <strong>{{ user.ou.currency_code }} 3,000 </strong> </p>
                                    <p> <strong>{{ user.ou.currency_code }} 79,900</strong> </p> -->
                                </td>
                            </tr>
                            <tr style="color: #F81D2D;">
                                <td class="text-right">
                                    <h4><strong>Total:</strong></h4>
                                </td>
                                <td class="text-left">
                                    <h4><strong>{{ user.ou.currency_code }} {{ saleorder.sub_total }} </strong></h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <div class="col-md-12">
                        <p><b>Note :</b> {{ user.company.notes }}</p> <br />
                        <p><b>Terms: </b>{{ user.company.terms }}</p>
                    </div>
                </div>
            </v-col>
        </v-layout>

    </v-card>
</div>
</template>

<script>
import moment from 'moment'
import {
    mapState
} from 'vuex';
export default {
    props: ['user'],
    filters: {
        moment(date) {
            return moment(date).format('MMMM Do YYYY');
        },
        moment2(date) {
            return moment(date).format('MMMM Do YYYY', 'h:mm:ss a');
        },
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
        printPage() {
            let printContents, popupWin;
            printContents = document.getElementById('print').innerHTML;
            popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
            popupWin.document.open();
            popupWin.document.write(`
          <html>
            <head>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
            </head>

        <body onload="window.print();window.close()">${printContents}</body>
          </html>`);
            popupWin.document.close();
        }

    },

    created() {
        eventBus.$on('SaleorderprintPage', () => {
            alert(1)
            this.printPage()
        });
    },
}
</script>

<style>
.main thead {
    background: #1E1F23;
    color: #fff
}

.img {
    height: 100px
}

h1 {
    text-align: center
}
</style>
