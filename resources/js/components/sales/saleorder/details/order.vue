<template>
    <div>
        <v-row no-gutters id="status-col">
            <v-col cols="12" sm="12" md="9">
                <v-card>
                    <v-card-title primary-title>
                        Status
                    </v-card-title>
                    <v-card-text>
                        <v-row no-gutters id="status-col">
                            <v-col>
                                <v-card class="pa-2" outlined tile>
                                    <b>Order Status</b>
                                    <br>
                                    <v-chip label color="primary">
                                        <v-icon left>mdi-label</v-icon>{{ saleorder.status }}
                                    </v-chip>
                                </v-card>
                            </v-col>
                            <v-col>
                                <v-card class="pa-2" outlined tile>
                                    <b>Delivery Status</b>
                                    <br>
                                    <v-chip label
                                        :color="(saleorder.delivery_status == 'Delivered') ? 'success' : (saleorder.delivery_status == 'Returned') ? 'red' : 'primary'"
                                        text-color="white">
                                        <v-icon left
                                            v-if="saleorder.delivery_status == 'Delivered'">mdi-check-circle</v-icon>
                                        <v-icon left
                                            v-else-if="saleorder.delivery_status == 'Returned'">mdi-cancel</v-icon>
                                        <v-icon left v-else>mdi-label</v-icon>
                                        {{ saleorder.delivery_status }}
                                    </v-chip>
                                </v-card>
                            </v-col>
                            <v-col>
                                <v-card class="pa-2" outlined tile>
                                    <b>Shipment Status</b>
                                    <br>
                                    <v-chip label :color="(saleorder.status == 'Closed') ? 'success' : 'red'"
                                        text-color="white">
                                        <v-icon left>mdi-label</v-icon>
                                        <span v-if="saleorder.status == 'Shipped'">Shipped</span>
                                        <span v-else-if="saleorder.status == 'Closed'">Complete</span>
                                        <span v-else>UnShipped</span>
                                    </v-chip>
                                </v-card>
                            </v-col>
                            <v-col>
                                <v-card class="pa-2" outlined tile>
                                    <b>Payment Status</b>
                                    <br>
                                    <v-chip label :color="(saleorder.paid) ? 'success' : 'red'" text-color="white">
                                        <v-icon left>mdi-label</v-icon>
                                        <span v-if="saleorder.paid">Paid</span>
                                        <span v-else>Unpaid</span>
                                    </v-chip>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>

                <v-card v-if="saleorder.client">
                    <v-card-title primary-title>
                        Customer
                    </v-card-title>
                    <v-card-text>
                        <v-row no-gutters>
                            <v-col>
                                <v-card class="pa-2" outlined tile>
                                    <b>Name</b>
                                    <br>
                                    <v-btn text color="warning">
                                        <v-icon left small>mdi-account</v-icon>{{ saleorder.client.name }}
                                    </v-btn>
                                </v-card>
                            </v-col>
                            <v-col>
                                <v-card class="pa-2" outlined tile>
                                    <b>Email</b>
                                    <br>
                                    <v-btn text>
                                        <v-icon left>mdi-mail</v-icon>{{ saleorder.client.email }}
                                    </v-btn>
                                </v-card>
                            </v-col>
                            <v-col>
                                <v-card class="pa-2" outlined tile>
                                    <b>Phone</b>
                                    <br>
                                    <v-btn text color="primary">
                                        <v-icon left>mdi-phone</v-icon>{{ saleorder.client.phone }}
                                    </v-btn>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>

                <v-card id="address" v-if="saleorder.client">
                    <v-card-title primary-title>
                        Address
                    </v-card-title>
                    <v-card-text>
                        <v-row no-gutters>
                            <v-col>
                                <v-card class="pa-2" outlined tile>
                                    <v-list dense>
                                        <v-subheader>Shipment address</v-subheader>
                                        <v-divider></v-divider>
                                        <v-list-item-group v-model="selectedItem" color="primary"
                                            v-if="saleorder.client.shipping">
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-account-circle</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>Name</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.shipping.name
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-mail</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>Email</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.shipping.email
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-phone</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>Phone</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.shipping.phone
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-map-marker</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>Address</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.shipping.address
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-map-marker-multiple</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>City</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.shipping.city
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>

                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-map-check</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>Country</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.shipping.country
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-list-item-group>
                                        <div v-else>
                                            No Address
                                        </div>
                                    </v-list>
                                </v-card>
                            </v-col>
                            <v-col>
                                <v-card class="pa-2" outlined tile>
                                    <v-list dense>
                                        <v-subheader>Billing address</v-subheader>
                                        <v-divider></v-divider>
                                        <v-list-item-group v-model="selectedItem1" color="primary"
                                            v-if="saleorder.client.billing">
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-account-circle</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>Name</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.billing.name
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-mail</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>Email</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.billing.email
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-phone</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>Phone</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.billing.phone
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-map-marker</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>Address</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.billing.address
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-map-marker-multiple</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>City</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.billing.city
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>

                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-map-check</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>Country</v-list-item-title>
                                                    <v-list-item-subtitle>{{ saleorder.client.billing.country
                                                        }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-list-item-group>

                                        <div v-else>
                                            No Address
                                        </div>
                                    </v-list>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" sm="12" md="3" id="details">

                <v-card v-if="saleorder.user">
                    <v-card-title primary-title>
                        Details
                    </v-card-title>
                    <v-card-text>
                        <v-list dense>
                            <v-list-item-group v-model="selectedItem" color="primary">
                                <v-list-item two-line>
                                    <v-list-item-icon>
                                        <v-icon>mdi-domain</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Company</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.user.company.name }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item two-line>
                                    <v-list-item-icon>
                                        <v-icon>mdi-account-multiple-check</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Vendor</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.seller.name }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-list-item two-line v-if="saleorder.riders">
                                    <v-list-item-icon>
                                        <v-icon>mdi-account-multiple-check</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Rider</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.riders[0].name }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-list-item two-line>
                                    <v-list-item-icon>
                                        <v-icon>mdi-cart</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Payment method</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.payment_method }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-list-item two-line>
                                    <v-list-item-icon>
                                        <v-icon>mdi-cart</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Payment code</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.mpesa_code }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-list-item two-line>
                                    <v-list-item-icon>
                                        <v-icon>mdi-checkbox-marked</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Channel</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.platform }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-list-item two-line>
                                    <v-list-item-icon>
                                        <v-icon>mdi-cash-multiple</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Currency</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.user.ou.currency }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item two-line>
                                    <v-list-item-icon>
                                        <v-icon>mdi-pen</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Reference number</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.waybill_no }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item two-line>
                                    <v-list-item-icon>
                                        <v-icon>mdi-calendar-range</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Delivery date</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.delivery_date }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item two-line>
                                    <v-list-item-icon>
                                        <v-icon>mdi-calendar</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Delivered On</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.delivered_on }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item two-line>
                                    <v-list-item-icon>
                                        <v-icon>mdi-calendar-arrow-right</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Dispatched On</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.dispatched_on }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item><v-list-item two-line>
                                    <v-list-item-icon>
                                        <v-icon>mdi-alarm-check</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>TAT</v-list-item-title>
                                        <v-list-item-subtitle>{{ saleorder.tat }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <v-card style="padding-bottom: 30px">

            <v-card-title primary-title>
                Line items
            </v-card-title>
            <v-card-text>
                <v-row no-gutters>
                    <v-col>
                        <el-table :data="saleorder.products" style="width: 100%">
                            <el-table-column label="Image" width="180" align="center">
                                <template slot-scope="scope">
                                    <img :src="scope.row.images.image" style="width: 30px" v-if="scope.row.images" />
                                    <img src="/images/noimage.png" style="width: 30px" />
                                </template>
                            </el-table-column>
                            <el-table-column prop="product_name" label="Variant" width="180">
                            </el-table-column>
                            <el-table-column prop="pivot.price" label="price">
                            </el-table-column>
                            <el-table-column prop="pivot.quantity_tobe_delivered" label="Quantity">
                            </el-table-column>
                            <el-table-column prop="pivot.quantity_delivered" label="Delivered">
                            </el-table-column>
                            <el-table-column prop="pivot.quantity_returned" label="Returned">
                            </el-table-column>
                        </el-table>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <v-row no-gutters>
            <v-col cols="9">
                    <v-row no-gutters>

                        <v-col cols="6">
                            <v-card>

                            <v-list dense>
                                <v-list-item-group v-model="selectedItem" color="primary">
                                    <v-list-item two-line>
                                        <v-list-item-icon>
                                            <v-icon>mdi-basket</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>Line Items</v-list-item-title>
                                            <v-list-item-subtitle>{{ saleorder.products.length }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item two-line>
                                        <v-list-item-icon>
                                            <v-icon>mdi-cash</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>Subtotal</v-list-item-title>
                                            <v-list-item-subtitle>{{ saleorder.sub_total }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item two-line>
                                        <v-list-item-icon>
                                            <v-icon>mdi-minus-box-multiple</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>Discounts</v-list-item-title>
                                            <v-list-item-subtitle>{{ saleorder.discount }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item two-line>
                                        <v-list-item-icon>
                                            <v-icon>mdi-cash-multiple</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>Cod</v-list-item-title>
                                            <v-list-item-subtitle>{{ saleorder.total_price }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                            </v-card>

                        </v-col>


                        <v-col cols="6">
                            <v-card>
                            <v-list dense>
                                <v-list-item-group v-model="selectedItem" color="primary">
                                    <v-list-item two-line>
                                        <v-list-item-icon>
                                            <v-icon>mdi-ship-wheel</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>Shipping charges </v-list-item-title>
                                            <v-list-item-subtitle>{{ saleorder.shipping_charges
                                                }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item two-line>
                                        <v-list-item-icon>
                                            <v-icon>mdi-weight</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>Weight</v-list-item-title>
                                            <v-list-item-subtitle>{{ saleorder.weight }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item two-line>
                                        <v-list-item-icon>
                                            <v-icon>mdi-box</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>Boxes</v-list-item-title>
                                            <v-list-item-subtitle>{{ saleorder.boxes }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item two-line>
                                        <v-list-item-icon>
                                            <v-icon>mdi-map</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>Distance</v-list-item-title>
                                            <v-list-item-subtitle>{{ saleorder.distance }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-card>

                        </v-col>
                    </v-row>


            </v-col>
            <v-col cols="3">

                <v-card id="details">
                    <v-card-title primary-title>
                        Customer Notes
                    </v-card-title>
                    <v-card-text>
                        <b>Notes</b>
                        <br />

                        <v-list nav dense v-if="saleorder.documents" subheader two-line>
                            <v-subheader inset>Order Documents</v-subheader>

                            <v-list-item-group v-model="selectedItem" color="primary">
                                <v-list-item v-for="(item, i) in saleorder.documents" :key="i">
                                    <v-list-item-icon>
                                        <v-icon>mdi-file</v-icon>
                                    </v-list-item-icon>

                                    <v-list-item-content>
                                        <v-list-item-title>{{ item.file_name }}</v-list-item-title>
                                        <v-list-item-subtitle>{{ item.created_at }}</v-list-item-subtitle>
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-btn icon :href="item.path" target="_blank">
                                            <v-icon color="primary">mdi-download</v-icon>
                                        </v-btn>
                                    </v-list-item-action>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>


                    </v-card-text>

                </v-card>
            </v-col>
        </v-row>

    </div>
</template>

<script>
export default {
    props: ['saleorder'],
    data() {
        return {
            selectedItem: 0,
            selectedItem1: 0,
        }
    },
}
</script>

<style scoped>
p,
small {
    font-size: 13px;
    color: #555 !important;
}

h2 {
    color: #000 !important;
}

#status-col .col {
    text-align: center;
}

.v-chip.v-size--default {
    margin-top: 10px;
}

.v-list-item__avatar {
    height: 40px !important;
    min-width: 40px !important;
}

#address .col {
    padding: 0 5px;
}

.v-card {
    margin-top: 20px
}

#details {
    padding-left: 10px;
}


.v-sheet.v-card {
    box-shadow: none !important;
}
</style>
