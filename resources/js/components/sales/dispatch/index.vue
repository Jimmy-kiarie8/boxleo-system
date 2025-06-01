<template>
    <div>
        <v-container fluid fill-height>
            <v-layout justify-center align-center wrap>
                <v-flex sm12>
                    <v-card style="padding: 20px 0;">
                        <el-breadcrumb separator-class="el-icon-arrow-right">
                            <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                            <el-breadcrumb-item>Dispatch</el-breadcrumb-item>
                        </el-breadcrumb>
                    </v-card>
                </v-flex>
                <v-flex sm12>
                    <v-card style="padding: 10px 0;">
                        <v-layout row wrap style="margin-left: 5px">
                            <v-flex sm2>
                                <label for="">Zone from</label>
                                <el-select v-model="form.zone_from" placeholder="Select" filterable clearable>
                                    <el-option v-for="item in zones" :key="item.id" :label="item.name"
                                        :value="item.id"></el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm2>
                                <label for="">Zone to</label>
                                <el-select v-model="form.zone_to" placeholder="Select" filterable clearable>
                                    <el-option v-for="item in zones" :key="item.id" :label="item.name"
                                        :value="item.id"></el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm2 style="margin-left: 5px">
                                <label for="">Rider</label>
                                <el-select v-model="form.rider_id" placeholder="Select" filterable clearable>
                                    <el-option v-for="item in riders" :key="item.id" :label="item.name"
                                        :value="item.id"></el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm2 style="margin-left: 5px">
                                <label for="">Courier</label>
                                <el-select v-model="form.courier_id" placeholder="Select" filterable clearable>
                                    <el-option v-for="item in couriers" :key="item.id" :label="item.name"
                                        :value="item.id"></el-option>
                                </el-select>
                            </v-flex>
                            <v-flex sm2 style="margin-left: 5px; margin-top: 30px" v-if="user.can['Order dispatch']">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn icon v-bind="attrs" v-on="on" @click="dispatch" color="primary">
                                            <v-icon>mdi-update</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Dispatch</span>
                                </v-tooltip>

                            </v-flex>
                            <v-flex sm2 style="margin-left: 5px; margin-top: 30px" v-if="user.can['Order dispatch']">
                                
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn icon v-bind="attrs" v-on="on" @click="printList" color="primary">
                                            <v-icon>mdi-printer</v-icon> Picking list
                                        </v-btn>
                                    </template>
                                    <span>Print</span>
                                </v-tooltip>
                            </v-flex>
                            <v-spacer></v-spacer>
                            <!-- <el-switch v-model="auto_zone" active-text="Auto zone location"
                                inactive-text="Manual zone location" active-color="#13ce66" inactive-color="#ff4949"
                                style="margin: 20px 0"></el-switch> -->
                            <hr>

                            <el-radio v-model="radio" label="1">Scan one by one</el-radio>
                            <el-radio v-model="radio" label="2">Input waybills</el-radio>
                            <v-flex sm6 style="margin-right: 20px">
                                <v-text-field v-model="form.search" append-icon="mdi-magnify" label="Scan"
                                    @keyup.enter="add_order" v-if="radio == 1" ref="focusMe"></v-text-field>
                                <div v-else>
                                    <label for="">Input waybills separated by commas or a new line</label>
                                    <el-input type="textarea" :rows="3" placeholder="Please input" v-model="data">
                                    </el-input>
                                    <v-spacer></v-spacer>

                                    <v-btn small elevation="3" color="primary" text @click="get_waybills">Search</v-btn>
                                </div>
                            </v-flex>

                            <v-flex sm6 v-if="invalidOrders.length > 0">
                                <div class="text-center">
                                    <h6>Order status with invalid statuses</h6>
                                    <v-chip class="ma-2" v-for="item in invalidOrders">
                                        {{ item.order_no }} : {{ item.delivery_status }}
                                    </v-chip>

                                </div>
                            </v-flex>

                            <v-flex sm6 v-if="invalidOrders.length > 0">
                                <div class="text-center">
                                    <h6>Orders already scanned</h6>
                                    <v-chip class="ma-2" v-for="item in existingOrders">
                                        {{ item.order_no }} : {{ item.delivery_status }}
                                    </v-chip>

                                </div>

                            </v-flex>
                            <!-- Return -->
                        </v-layout>
                        <br>

                        <div v-if="hasDuplicates" style="color: red;">
                            Duplicate order numbers detected!
                        </div>
                        <v-divider></v-divider>

                        <v-layout row>
                            <v-flex sm1 style="margin-left: 10px;">
                                <v-tooltip right>
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="reset">
                                            <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Reset</span>
                                </v-tooltip>
                            </v-flex>
                            <v-flex sm2>
                                <h3 style="margin-left: 30px !important;margin-top: 10px;">Dispatch</h3>
                            </v-flex>
                        </v-layout>
                        <v-flex sm12>
                            <v-data-table :headers="headers" :items="orders" :search="search" :loading="loading">
                                <template v-slot:item.actions="{ item, index }">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="remove(index)" slot="activator">
                                                <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Remove</span>
                                    </v-tooltip>
                                </template>
                            </v-data-table>

                        </v-flex>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
import {
    mapState
} from 'vuex';

export default {
    props: ['user'],
    data() {
        return {
            hasDuplicates: false,
            radio: '1',
            search: '',
            auto_zone: false,
            invalidOrders: [],
            existingOrders: [],
            data: '',
            form: {
                search: '',
                zone_from: null,
                zone_to: null,
                rider_id: null,
            },
            orders: [],
            headers: [{
                text: "Created On",
                value: "created_at"
            },
            {
                text: "Order no",
                value: "order_no"
            },
            {
                text: "Client Name",
                value: "client_name"
            },
            {
                text: "Client Address",
                value: "client_address"
            },
            {
                text: "Client Phone",
                value: "client_phone"
            },
            {
                text: "Delivery Status",
                value: "delivery_status"
            },
            {
                text: "Total",
                value: "total_price"
            },
            {
                text: "Actions",
                value: "actions",
                sortable: false
            }
            ]
        }
    },

    watch: {
        data(newValue) {
            this.checkForDuplicates();
        }
    },
    methods: {

        checkForDuplicates() {
            // Split the input into an array of order numbers
            let orderNumbers = this.data.split(/\r?\n/).filter(Boolean);

            // Create a Set to track unique order numbers
            let uniqueOrderNumbers = new Set();

            // Reset the duplicate flag
            this.hasDuplicates = false;

            // Check for duplicates
            for (let orderNumber of orderNumbers) {
                if (uniqueOrderNumbers.has(orderNumber)) {
                    this.hasDuplicates = true;
                    break;
                } else {
                    uniqueOrderNumbers.add(orderNumber);
                }
            }

            if (this.hasDuplicates) {
                console.warn("Duplicate order numbers detected!");
            }
        },

        reset() {
            this.orders = []
            this.invalidOrders = []
            this.existingOrders = []
        },
        dispatch() {
            var data = {
                orders: this.orders,
                zone_from: this.form.zone_from,
                zone_to: this.form.zone_to,
                rider_id: this.form.rider_id,
            }
            var payload = {
                model: 'dispatch_orders',
                data: data
            }
            this.$store.dispatch('postItems', payload).then((response) => {
                console.log(response);
                this.reset()
                this.$refs.focusMe.focus()
                // this.orders.push(response.data)
            }).catch((error) => {
                console.log(error);
            })
        },
        printList() {
            console.log('printList');
            
            // Create simplified order objects with only necessary data
            const simplifiedOrders = this.orders.map(order => {
                return {
                    id: order.id,
                    seller_id: order.seller_id,
                    seller: order.seller ? { name: order.seller.name } : null,
                    products: order.products.map(product => {
                        return {
                            id: product.id,
                            product_name: product.product_name,
                            pivot: {
                                seller_id: product.pivot ? product.pivot.seller_id : null,
                                quantity: product.pivot ? product.pivot.quantity : 1
                            }
                        };
                    })
                };
            });
            
            var data = {
                orders: simplifiedOrders,
                zone_from: this.form.zone_from,
                zone_to: this.form.zone_to,
                rider_id: this.form.rider_id,
            }
            
            // Use POST method via form submission to handle large data
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/dispatch_print_list';
            form.target = '_blank';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            // Add data as JSON
            const ordersInput = document.createElement('input');
            ordersInput.type = 'hidden';
            ordersInput.name = 'orders';
            ordersInput.value = JSON.stringify(simplifiedOrders);
            form.appendChild(ordersInput);
            
            // Add other form fields
            const zoneFromInput = document.createElement('input');
            zoneFromInput.type = 'hidden';
            zoneFromInput.name = 'zone_from';
            zoneFromInput.value = this.form.zone_from;
            form.appendChild(zoneFromInput);
            
            const zoneToInput = document.createElement('input');
            zoneToInput.type = 'hidden';
            zoneToInput.name = 'zone_to';
            zoneToInput.value = this.form.zone_to;
            form.appendChild(zoneToInput);
            
            const riderIdInput = document.createElement('input');
            riderIdInput.type = 'hidden';
            riderIdInput.name = 'rider_id';
            riderIdInput.value = this.form.rider_id;
            form.appendChild(riderIdInput);
            
            // Submit the form
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        },
        add_order() {
            var exists = false

            this.orders.forEach(element => {
                if (element.order_no == this.form.search) {
                    exists = true
                    this.$message({
                        type: 'error',
                        message: 'Order already scanned'
                    });
                }
            });

            if (!exists) {
                var payload = {
                    model: 'scan',
                    update: 'updateSaleList',
                    search: this.form.search
                }
                this.$store.dispatch('searchItems', payload).then((response) => {
                    // console.log(response);
                    this.form.search = ''

                    if (response.data.geocode && this.auto_zone) {
                        this.form.zone_to = response.data.geocode
                    }
                    this.orders.push(response.data)
                }).catch((error) => {
                    console.log(error);
                })
            }
        },
        remove(index) {
            this.orders.splice(index, 1)
        },

        getZones() {
            var payload = {
                model: 'zones',
                update: 'updateZone',
            }
            this.$store.dispatch('getItems', payload);
        },
        getRiders() {
            var payload = {
                model: 'rider/riders',
                update: 'updateRidersList'
            }
            this.$store.dispatch("getItems", payload);
        },
        getCouriers() {
            var payload = {
                model: 'couriers',
                update: 'updateCouriersList'
            }
            this.$store.dispatch("getItems", payload);
        },
        get_waybills() {
            var payload = {
                model: 'get_dispatch',
                update: 'UpdateEmpty',
                data: {
                    data: this.data
                }
            }
            this.$store.dispatch('filterItems', payload).then((response) => {
                console.log(response);
                response.data.valid_orders.forEach(element => {

                    const orderExists = this.orders.some(order => order.order_no === element.order_no);

                    if (!orderExists) {
                        // If the order doesn't exist, add it to newValidOrders
                        // newValidOrders.push(element);
                        // Optionally, add it to this.orders as well
                        this.orders.push(element);
                    } else {
                        this.existingOrders.push(element);

                    }

                });

                response.data.invalid_orders.forEach(element => {
                    this.invalidOrders.push(element)
                });
            }).catch((error) => {
                console.log(error);
            })
        }
    },
    computed: {
        ...mapState(['zones', 'loading', 'riders', 'couriers'])
    },
    mounted() {
        //if (this.courier.length < 1) {
            this.getCouriers();
        //}
        if (this.zones.length < 1) {
            this.getZones();
        }
        if (this.riders.length < 1) {
            this.getRiders();
        }

    },
};
</script>

<style scoped></style>
