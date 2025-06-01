<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
        <template v-slot:activator="{ on }">
            <v-btn color="primary" dark v-on="on">Open Dialog</v-btn>
        </template>
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Lable printing page</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark text @click="printPage">Print</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <div class="container" id="print">
                <div class="invoice-box" v-loading="loading" v-if="dialog">
                    <div class="container">
                        <table cellpadding="0" cellspacing="0" class="table table-hover">
                            <tr class="information">
                                <td colspan="4">
                                    <table>
                                        <tr>
                                            <td>
                                                <strong>Warehouse Receiving. {{ tracking_no }}</strong><br>
                                                <br> Logixsaas <br>19 Belmont Ave Unit <br><br> <b>Phone</b>: +19176727279 <br><b>Email</b>: support@logixsaas.com
                                            </td>
                                            <td>

                                                <b>Delivery Date</b>: {{ format_date }} <br>
                                                <b>From</b>: {{ client_det.name }} <br>
                                                <b>Email</b>: {{ client_det.email }} <br>
                                                <b>Going to City</b>: {{ client_det.city }} <br>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <b>Box: {{ lable_index }} of {{ lable_data.boxes.length }}</b>
                        <VDivider />
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th scope="col">Item & Description</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item" v-for="(item, index) in lable_data.selectedProducts" :key="index">
                                    <td style="text-align: left;">{{ index + 1 }}</td>
                                    <td style="text-align: left;">{{ item.product_name }} <br>
                                        <small>{{ item.description }}</small>
                                    </td>
                                    <td style="text-align: left;">
                                        {{ item.qty }}
                                    </td>
                                    <td style="text-align: left;">
                                        {{ item.price }}
                                    </td>
                                    <!-- <td style="text-align: left;">{{ item.price * item.qty }}</td> -->
                                </tr>
                            </tbody>
                            <!-- <tfoot>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>Total</b></td>
                            <td><b>{{ invoice_total }}</b></td>
                        </tfoot> -->
                        </table>
                        <v-divider></v-divider>
                        <barcode :value="tracking_no" :options="{ lineColor: '#000' }" tag="img" style="margin-left: 35%;"></barcode>
                        <VDivider />
                        <!-- <h5>Notes</h5> -->
                        <br>
                        <!-- <small>{{ invoice_data.instructions }}</small> -->
                    </div>
                </div>
            </div>
        </v-card>
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
    data() {
        return {
            image: '',
            dialog: false,
            invoice_data: [],
            loading: false,
            activeName: 'first',
            lable_data: [],
            tracking_no: '',
            lable_index: null,
            client_det: [],
        }
    },
    computed: {
        invoice_total() {
            var total = 0
            if (this.invoice_data.length > 0) {
                this.invoice_data.product.forEach(element => {
                    total = parseFloat(total) + (parseFloat(element.price) * parseFloat(element.order_qty))
                });
                return total
            }
        },
        format_date(value){
         if (value) {
           return moment(String(this.lable_data.delivery_date)).format("MMM Do YYYY")
          }
      },
    },
    methods: {
        printPage() {
            eventBus.$emit("printLableEvent")

        },
        getClient() {
            axios.get(`/clients/${this.lable_data.client_id}`, this.$data.form).then((response) => {
                // console.log(response);
                this.client_det = response.data
            }).catch((error) => {
                // console.log(error.response);

            })
        }
    },
    created() {
        eventBus.$on('lablePrintEvent', data => {
            // console.log(data);
            this.loading = true
            this.invoice_data = data
        });
        eventBus.$on('invoiceLoadEvent', data => {
            this.loading = true
            setTimeout(() => {
                this.loading = false
            }, 1000);
        });
    },
    mounted() {
        this.image = image

    },
    created() {
        eventBus.$on('sendPrintEvent', data => {
            console.log(data);
            this.lable_data = data['data']
            this.tracking_no = data['tracking_no']
            this.lable_index = data['index']
            this.dialog = true
            this.getClient()
            // this.printPage()
        })
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
