<template>
<v-card style="overflow-x: hidden;">
    <v-card-title>
        Upload Excel Shipments
    </v-card-title>
    <v-container grid-list-md>
        <div v-if="!user.is_vendor">
            <label for="">Vendor</label>
            <el-select name="client" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" v-model="form.vendor_id">
                <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                </el-option>
            </el-select>
            <small class="has-text-danger" v-if="errors.vendor_id">{{ errors.vendor_id[0] }}</small>
        </div>
        <div style="height: 10px;"></div>
        <div v-if="tenant.subscriber.tenant_plan.warehouse_management || tenant.subscriber.tenant_plan.inventory_management">
            <label for="">Warehouse</label>
            <el-select name="warehouse" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" v-model="form.warehouse_id">
                <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
                </el-option>
            </el-select>
            <small class="has-text-danger" v-if="errors.warehouse_id">{{ errors.warehouse_id[0] }}</small>
        </div>
        <v-card-text>
            <v-layout wrap v-loading="loading_file" element-loading-text="Loading..." element-loading-spinner="el-icon-loading">

                <!-- <el-upload class="upload-demo" drag action="/get_orders" :auto-upload="false" :data="form" ref="upload" :headers="headers" :on-success="handleSuccess" :on-error="handleError" :before-upload="beforeUpload" :on-change="previewFiles">
                    <i class="el-icon-upload"></i>
                    <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                    <div class="el-upload__tip" slot="tip">xlsx files with a size less than 500kb</div>
                </el-upload>
                <v-tooltip bottom style="margin-left: 30px" v-if="upload_type == '1'">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on" href="/downloads/order_template.xlsx" target="_blank">
                            <v-icon color="primary lighten-1">
                                mdi-download
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Download Template</span>
                </v-tooltip>

                <v-tooltip bottom style="margin-left: 30px" v-else>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on" href="/downloads/order_template2.xlsx" target="_blank">
                            <v-icon color="primary lighten-1">
                                mdi-download
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Download Template</span>
                </v-tooltip> -->

                <!-- <v-btn color="info" @click="previewFiles">View data</v-btn> -->

                <v-spacer></v-spacer>

                <!-- <input type="file" @change="GetOrders" style="display: none" ref="fileInput"> -->
                <!-- <v-btn text color="primary" @click="upload" :disabled="loading" :loading="loading">Upload</v-btn> -->

                <!-- <v-layout row wrap v-if="e_body.length>0">
                    <v-flex sm12>
                        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
                        <v-data-table :headers="e_header" :items="e_body" :search="search">
                            <template v-slot:item.order_id="props">
                                <v-edit-dialog :return-value.sync="props.item.order_id" @save="save">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <div v-on="on">{{ props.item.order_id }}</div>
                                        </template>
                                        <span>Click to edit </span>
                                    </v-tooltip>
                                    <template v-slot:input>
                                        <v-text-field v-model="props.item.order_id" label="Edit" single-line counter autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>

                            <template v-slot:item.address="props">
                                <v-edit-dialog :return-value.sync="props.item.address" @save="save">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <div v-on="on">{{ props.item.address }}</div>
                                        </template>
                                        <span>Click to edit </span>
                                    </v-tooltip>
                                    <template v-slot:input>
                                        <v-text-field v-model="props.item.address" label="Edit" single-line counter autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>

                            <template v-slot:item.phone="props">
                                <v-edit-dialog :return-value.sync="props.item.phone" @save="save">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <div v-on="on">{{ props.item.phone }}</div>
                                        </template>
                                        <span>Click to edit </span>
                                    </v-tooltip>
                                    <template v-slot:input>
                                        <v-text-field v-model="props.item.phone" label="Edit" single-line counter autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>
                            <template v-slot:item.client_name="props">
                                <v-edit-dialog :return-value.sync="props.item.client_name" @save="save">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <div v-on="on">{{ props.item.client_name }}</div>
                                        </template>
                                        <span>Click to edit </span>
                                    </v-tooltip>
                                    <template v-slot:input>
                                        <v-text-field v-model="props.item.client_name" label="Edit" single-line counter autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>

                            <template v-slot:item.quantity="props">
                                <v-edit-dialog :return-value.sync="props.item.quantity" @save="save">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <div v-on="on">{{ props.item.quantity }}</div>
                                        </template>
                                        <span>Click to edit </span>
                                    </v-tooltip>
                                    <template v-slot:input>
                                        <v-text-field v-model="props.item.quantity" label="Edit" single-line counter autofocus></v-text-field>
                                    </template>

                                    <template v-slot:item.total_amount="props">
                                        <v-edit-dialog :return-value.sync="props.item.total_amount" @save="save">
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <div v-on="on">{{ props.item.total_amount }}</div>
                                                </template>
                                                <span>Click to edit </span>
                                            </v-tooltip>
                                            <template v-slot:input>
                                                <v-text-field v-model="props.item.total_amount" label="Edit" single-line counter autofocus></v-text-field>
                                            </template>
                                        </v-edit-dialog>
                                    </template>

                                    <template v-slot:item.special_instructions="props">
                                        <v-edit-dialog :return-value.sync="props.item.special_instructions" @save="save">
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <div v-on="on">{{ props.item.special_instructions }}</div>
                                                </template>
                                                <span>Click to edit </span>
                                            </v-tooltip>
                                            <template v-slot:input>
                                                <v-text-field v-model="props.item.special_instructions" label="Edit" single-line counter autofocus></v-text-field>
                                            </template>
                                        </v-edit-dialog>
                                    </template>
                                </v-edit-dialog>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout> -->

            </v-layout>
        </v-card-text>
    </v-container>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex'
import readXlsxFile from 'read-excel-file'

export default {
    props: ['user', 'form', 'upload_type', 'tenant'],
    data() {
        return {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            filePlaced: false,
            dialog: false,
            loading: false,
            loading_file: false,
            orders_upload: {},
            errors: [],
            search: '',

            e_header: [{
                    text: "order_id",
                    value: "order_id"
                },
                {
                    text: "Client name",
                    value: "client_name",
                },
                {
                    text: "address",
                    value: "address"
                },
                {
                    text: "phone",
                    value: "phone"
                },
                {
                    text: "email",
                    value: "email"
                },
                {
                    text: "special Instructions",
                    value: "special_instructions"
                },
                {
                    text: "Product name",
                    value: "product_name"
                },
                {
                    text: "Status",
                    value: "status"
                },
                {
                    text: "Delivery date",
                    value: "delivery_date"
                },
                {
                    text: "Total amount",
                    value: "total_amount",
                    type: "number"
                },
                {
                    text: "Quantity",
                    value: "quantity",
                    type: "number"
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],
            e_body: [],
            new_arr: []
        }
    },
    methods: {
        previewFiles() {
            const schema = {
                'Waybill number': {
                    // JSON object property name.
                    prop: 'waybill_no',
                    type: String
                },
                'order date': {
                    prop: 'order_date',
                    type: String,
                },
                // Nested object example.
                // 'COURSE' here is not a real Excel file column name,
                // it can be any string — it's just for code readability.
                'Order ID': {
                    // Nested object path: `row.course`
                    prop: 'order_id',
                    // Nested object schema:
                    type: String,
                    required: true

                },
                'Client Name': {
                    prop: 'client_name',
                    required: true,
                    // A custom `type` can be defined.
                    // A `type` function only gets called for non-empty cells.
                    type: String,
                },
                'address': {
                    prop: 'address',
                    type: String,
                },
                'phone': {
                    prop: 'phone',
                    type: String,
                    required: true
                },
                'city': {
                    prop: 'city',
                    type: String,
                },
                'status': {
                    prop: 'status',
                    type: String,
                },
                'delivery date': {
                    prop: 'delivery_date',
                    type: Date,
                },
                'phone': {
                    prop: 'phone',
                    type: String,
                    required: true
                },
                'product name': {
                    prop: 'product_name',
                    type: String,
                    required: true
                },
                'Quantity': {
                    prop: 'quantity',
                    type: String,
                    required: true
                },
                'Special Instructions': {
                    prop: 'special_instructions',
                    type: String,
                },
                'email': {
                    prop: 'email',
                    type: String,
                },
                'Total Amount': {
                    prop: 'total_amount',
                    type: Number,
                    required: true
                }
            }
            this.loading_file = true
            let xlsxfile = event.target.files ? event.target.files[0] : null;
            readXlsxFile(xlsxfile, {
                schema
            }).then((rows) => {
                this.loading_file = false
                this.e_body = rows.rows
            })
        },
        save() {
            this.snack = true
            this.snackColor = 'success'
            this.snackText = 'Data saved'
        },
        close() {
            this.dialog = false
        },

        getSellers() {
            var payload = {
                model: '/seller/sellers',
                update: 'updateSellerList'
            }
            this.$store.dispatch("getItems", payload);
        },

        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
        },

        onPickFile() {
            this.$refs.fileInput.click();
        },
        onFilePicked(event) {
            this.filePlaced = true;
            const files = event.target.files;
            let filename = files[0].name;
            if (filename.lastIndexOf(".") <= 0) {
                return alert("please upload a valid orders");
            }
            const fileReader = new FileReader();
            fileReader.addEventListener("load", () => {
                this.avatar = fileReader.result;
            });
            fileReader.readAsDataURL(files[0]);
            this.orders = files[0];
        },
        GetOrders(e) {
            let orders = e.target.files[0];
            // this.read(orders);
            let reader = new FileReader();
            reader.readAsDataURL(orders);
            reader.onload = e => {
                this.avatar = e.target.result;
            };
            this.filePlaced = true;
            let form = new FormData();
            form.append("orders", orders);
            this.file = form;
            console.log(e.target.files);
        },
        handleError(err, file) {
            // console.log(err.message.errors);
            // this.errors = err.response.data.errors
            this.$store.dispatch('page_loader', false)
            this.$message.error("Something went wrong. Please check your excel file! Make sure you've choosen the vendor and warehouse");
        },

        handleSuccess(res, file) {
            // this.$store.dispatch('page_loader', false)
            eventBus.$emit('orderResponseEvent', res)
            this.getProducts()
            this.orders_upload = res
        },
        beforeUpload(file) {
            this.$store.dispatch('page_loader', true)
            console.log(file);
            const isJPG = file.type === 'image/jpeg';

        },

        upload() {
            this.$refs.upload.submit();

            // if (this.upload_type == '1') {
            //     var model = 'importOrder'
            // } else {
            //     var model = 'importOrderSheet'
            // }
            /*
            var model = 'get_orders'

            var payload = {
                model: model,
                data: this.file
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {

                    this.getProducts()

                    this.orders_upload = response.data
                    eventBus.$emit('orderResponseEvent', response.data)

                    // this.loading = false;
                    // console.log(response);
                    eventBus.$emit("alertRequest", 'Successifully uploaded');
                    this.filePlaced = false;

                    // eventBus.$emit("MenuEvent")
                });
                */
        },
        getProducts() {
            var vendor_id = (this.user.is_vendor) ? this.user.id : this.form.vendor_id
            var payload = {
                model: 'client_products',
                update: 'updateProductsList',
                id: vendor_id
            }
            this.$store.dispatch("showItem", payload);
        },
    },
    computed: {
        ...mapState(['sellers', 'warehouses'])
    },
    created() {
        eventBus.$on('fileUploadEvent', data => {
            this.upload()
        })
    },
    mounted() {
        this.getSellers()
        this.getWarehouses()
    },
}
</script>
