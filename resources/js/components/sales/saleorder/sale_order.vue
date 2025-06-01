<template>
<v-layout row wrap>
    <v-flex sm5 offset-sm1>
        <h4>SALES ORDER</h4>
        <div>
            <span>Sales <b>Order# {{ saleorder.order_no }}</b></span>
            <div style="border-left: 3px solid orange;">
                <div style="margin-left:30px;">
                    <div class="text item">
                        <v-layout row wrap>
                            <v-flex sm4>
                                <label for="">Order</label>
                            </v-flex>
                            <v-flex sm4>
                                <el-tag type="success">{{ saleorder.status }}</el-tag>
                            </v-flex>
                        </v-layout>
                    </div>

                    <div class="text item">
                        <v-layout row wrap>
                            <v-flex sm4>
                                <label for="">Invoice</label>
                            </v-flex>
                            <v-flex sm4>
                                <el-tag type="success">{{ saleorder.invoiced }}</el-tag>
                            </v-flex>
                        </v-layout>
                    </div>

                    <div class="text item">
                        <v-layout row wrap>
                            <v-flex sm4>
                                <label for="">Shipment</label>
                            </v-flex>
                            <v-flex sm4>
                                <el-tag type="success">{{ saleorder.delivery_status }}</el-tag>
                            </v-flex>
                        </v-layout>
                    </div>
                </div>
            </div>
            <div style="margin-top:20px;">
                <div class="text item">
                    <div class="text item">
                        <v-layout row wrap>
                            <v-flex sm6>
                                <label>Order Date</label>
                            </v-flex>
                            <v-flex sm4>
                                <el-tag>{{ saleorder.created_at }}</el-tag>
                            </v-flex>
                        </v-layout>
                    </div>

                </div>
                <div class="text item">

                    <div class="text item">
                        <v-layout row wrap>
                            <v-flex sm6>
                                <label>Delivery Date</label>
                            </v-flex>
                            <v-flex sm4>
                                <el-tag>{{ saleorder.delivery_date }}</el-tag>
                            </v-flex>
                        </v-layout>
                    </div>

                </div>
            </div>

        </div>
    </v-flex>
    <v-flex sm5 offset-sm1>
        <h4>SHIPPING ADDRESS</h4>

        <div class="text item">
            main st
        </div>

        <div class="text item">
            Nairobi
        </div>
        <div class="text item">
            999-211-333-888
        </div>

    </v-flex>

    <v-flex sm10 offset-sm1>

        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th scope="col">Item & Description</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Status</th>
                    <th scope="col">Price</th>
                    <!-- <th scope="col">Total</th> -->
                </tr>
            </thead>
            <tbody>
                <tr class="item" v-for="(item, index) in saleorder.products" :key="index">
                    <td style="text-align: center;">{{ index + 1 }}</td>
                    <td style="text-align: center;">{{ item.product_name }} <br>
                        <!-- <small>{{ item.description }}</small> -->
                    </td>
                    <td style="text-align: center;">{{ item.quantity }}</td>
                    <td style="text-align: center;background: #effbec;">
                        <small>{{ item.pivot.quantity }} Ordered</small><br>
                        <small>{{ item.pivot.sent }} Packed</small><br>
                        <small>{{ item.pivot.quantity_delivered }} Delivered</small><br>
                        <small style="color: red">{{ item.pivot.quantity_returned }} Returned</small><br>
                    </td>
                    <td style="text-align: center;">
                        {{ item.price }}
                    </td>
                    <!-- <td style="text-align: center;">{{ parseFloat(item.price) * parseFloat(item.quantity) }}</td> -->
                </tr>
            </tbody>
            <tfoot>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: center;"><b>Total</b></td>
                <!-- <td><b>{{ invoice_total }}</b></td> -->
                <td><b>{{ saleorder.sub_total }}</b></td>
                <!-- <td style="text-align: center;"><b>{{ saleorder.amount }}</b></td> -->
            </tfoot>
        </table>
    </v-flex>
</v-layout>
</template>

<script>
import moment from 'moment'
import {
    mapState
} from 'vuex';
export default {
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
}
</script>

<style scoped>
.layout.wrap {
    flex-wrap: wrap;
    color: rgba(0, 0, 0, 0.87) !important;
}

label {
    color: #666 !important;
}
</style>
