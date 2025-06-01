<template>
<v-card>
    <v-card-title primary-title> </v-card-title>
    <v-card-text v-if="e1 == 2">
        <el-collapse accordion>
            <el-collapse-item name="1" v-if="orders.exist_orders.length > 0">
                <template slot="title" style="color: red">
                    <span><b style="color: red">{{ orders.exist_orders.length }} Orders Exists</b><i class="header-icon el-icon-info"></i></span>
                </template>
                <div>
                    <v-list dense two-line>
                        <v-list-item-group color="primary">
                            <v-list-item v-for="(item, i) in orders.exist_orders" :key="i">
                                <v-list-item-content>
                                    <v-list-item-title>{{ item.order_no }} </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </div>
            </el-collapse-item>
    </el-collapse>

        <!-- <v-layout wrap v-loading="loading_file" element-loading-text="Loading..." element-loading-spinner="el-icon-loading">
            <v-flex sm12>
                <el-upload class="upload-demo" drag action="/get_orders" :auto-upload="false" :data="form" ref="upload" :headers="headers" :on-success="handleSuccess" :on-error="handleError" :before-upload="beforeUpload" :on-change="previewFiles">
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
                </v-tooltip>
            </v-flex>

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

                        </v-edit-dialog>
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

                    <template v-slot:item.product_name="props">
                        <el-select v-model="props.item.product_name" placeholder="Select" filterable clearable>
                            <el-option v-for="prod in products.data" :key="prod.id" :label="prod.product_name" :value="prod.id">
                            </el-option>
                        </el-select>
                    </template>
                </v-data-table>
            </v-flex>
        </v-layout> -->

        <v-data-table :headers="headers" :items="orders.sales" :search="search" :loading="loading" :item-class= "row_classes">
            <template v-slot:item.product_name="{ item }">
                <v-layout row v-for="(product, index) in item.products" :key="index" style="padding: 10px 0">
                    <v-flex sm8>
                        <el-select v-model="product.id" placeholder="Select" filterable clearable @change="product_select(item)">
                            <el-option v-for="item in products.data" :key="item.id" :label="item.product_name" :value="item.id">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm4>
                        <el-input placeholder="Please input" v-model="product.quantity"></el-input>
                    </v-flex>
                </v-layout>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon class="mx-0" @click="remove(item)" slot="activator">
                            <v-icon small color="pink darken-2">mdi-delete</v-icon>
                        </v-btn>
                    </template>
                    <span>Remove</span>
                </v-tooltip>
            </template>

            <template v-slot:item.address="{ item }">
                <el-input type="textarea" placeholder="Please input" rows="3" v-model="item.address"></el-input>
            </template>
            <template v-slot:item.client_name="{ item }">
                <el-input placeholder="Please input" v-model="item.client_name"></el-input>
            </template>
            <template v-slot:item.phone="{ item }">
                <el-input placeholder="Please input" v-model="item.phone"></el-input>
            </template>

            <template v-slot:item.status="{ item }">
                <el-select v-model="item.status" placeholder="Select" filterable clearable>
                    <el-option v-for="item in statuses" :key="item.id" :label="item.status" :value="item.status"></el-option>
                </el-select>
            </template>
            <template v-slot:item.delivery_date="{ item }">
                <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="item.delivery_date" type="date" placeholder="Pick a day" style="width: width: 140px;" :picker-options="pickerOptions"></el-date-picker>
            </template>
            <template v-slot:item.notes="{ item }">
                <el-input type="textarea" placeholder="Please input" v-model="item.notes" rows="3"></el-input>
            </template>
        </v-data-table>

    </v-card-text>
</v-card>
</template>

<script>
import {
    mapState
} from "vuex";
import readXlsxFile from 'read-excel-file'
export default {
    props: ["orders", "e1", "form", 'upload_type'],
    data() {
        return {
            search: "",
            valid: false,
            // table_valid: true,
            invalid_row: [],

            headers: [{
                    text: "Order id",
                    value: "order_no",
                },
                {
                    text: "Product name",
                    value: "product_name",
                },
                {
                    text: "Client name",
                    value: "client_name",
                },
                {
                    text: "Address",
                    value: "address",
                },
                {
                    text: "Phone",
                    value: "phone",
                },
                {
                    text: "Status",
                    value: "status",
                },
                {
                    text: "Delivery date",
                    value: "delivery_date",
                },
                {
                    text: "Special instructions",
                    value: "notes",
                },
                {
                    text: "Total price",
                    value: "cod_amount",
                },
                {
                    text: "Actions",
                    value: "actions",
                },
            ],

            pickerOptions: {
                disabledDate(time) {
                    return time.getTime() + 3600 * 1000 * 24 < Date.now();
                },
                shortcuts: [{
                        text: "Today",
                        onClick(picker) {
                            picker.$emit("pick", new Date());
                        },
                    },
                    {
                        text: "Tomorrow",
                        onClick(picker) {
                            const date = new Date();
                            date.setTime(date.getTime() + 3600 * 1000 * 24);
                            picker.$emit("pick", date);
                        },
                    },
                ],
            },
        };
    },
    methods: {
        row_classes(item) {
            
        //     console.log('***********')
        //     console.log(item.products)
        //     console.log('***********')
        // for (let i = 0; i < item.products.length; i++) {
        //         var element = this.item.products[index];
        //     // item.products.forEach((val) => {
        //     //         console.log(val)
        //             if (!element.id) {
        //                 eventBus.$emit('invalidEvent')
        //                 return "not_valid";
        //                 // valid = false
        //             }
        //     //         })

        // }

            console.log(item.products)
            if (!item.product_exists) {
                // this.table_valid = false
                eventBus.$emit('invalidEvent')
                return "not_valid";
            } else {
                eventBus.$emit('validEvent')
            }
        },

        save() {
            this.snack = true
            this.snackColor = 'success'
            this.snackText = 'Data saved'
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
            // this.getProducts()
            this.orders_upload = res
        },
        beforeUpload(file) {
            this.$store.dispatch('page_loader', true)
            console.log(file);
            const isJPG = file.type === 'image/jpeg';
        },

        upload() {
            this.$refs.upload.submit();
        },



        // getProducts() {
        //     var payload = {
        //         model: "products",
        //         update: "updateProductsList",
        //     };
        //     this.$store.dispatch("getItems", payload);
        // },
        remove(item) {
            const index = this.orders.sales.indexOf(item);
            this.orders.sales.splice(index, 1);
        },
        getStatus() {
            var payload = {
                model: "statuses",
                update: "updateStatusList",
            };
            this.$store.dispatch("getItems", payload);
        },

        product_select(item) {
            var editedIndex = this.orders.sales.indexOf(item)

            var editedItem = Object.assign({}, item)

            editedItem.product_exists = true

            Object.assign(this.orders.sales[editedIndex], editedItem)
        },

        checkvalidate() {

            console.log(this.upload_type)
            console.log(this.upload_type)
            console.log(this.upload_type)
            console.log(this.upload_type)
            console.log(this.upload_type)
            console.log(this.upload_type)
            var valid = true;

                for (let index = 0; index < this.orders.sales.length; index++) {
                    var element = this.orders.sales[index];
                    console.log(element.products);

                    console.log(element.products[index].id)

                    if (!element.products[index].id || element.products[index].id == '') {
                        valid = false
                    }

                }

                /* element.products.forEach((item) => {
                    console.log(item)
                    if (!item.id) {
                        valid = false
                    }
                }); */
                /* 
                                for (let i = 0; i < element.products.length; i++) {
                                    const item = element.products[i];
                                    // console.log(i)
                                    if (!item.id || item.id == '') {
                                        valid = false
                                    }
                                }
            } */
                /* 
                                if (element.order_no) {
                                    element.products.forEach((item) => {
                                        if (!item.id) {
                                            valid = false
                                            // break;
                                        } else {
                                            valid = true
                                        }
                                    });
                                } else {
                                    valid = false;
                                    break;
                                } */
                /* 
                        if (!element.products.id || !element.order_no) {
                            valid = false
                            break;
                        } else {
                            valid = true
                        } */

            // return valid;
            // console.log(valid);
        },
    },
    computed: {
        ...mapState(["products", "loading", "statuses"]),
        validate() {
            error_arr = []
            var valid = true;

            console.log(this.upload_type)
            console.log(this.upload_type)
            console.log(this.upload_type)
            console.log(this.upload_type)
            console.log(this.upload_type)
            console.log(this.upload_type)

            for (let index = 0; index < this.orders.sales.length; index++) {
                var element = this.orders.sales[index];
                if (element.order_no) {
                    element.products.forEach((item) => {
                        if (!item.id) {
                            // eventBus.$emit('validEvent')
                            valid = false
                        }
                    });
                }
                if (!element.products[index].id || element.products[index].id == '') {
                    // eventBus.$emit('validEvent')
                    valid = false
                }
            }
            if(this.upload_type == '2') {
                valid = true;
            }
            eventBus.$emit('validEvent', valid)
            return valid;
        },
    },
    mounted() {
        this.getStatus();
        // this.getProducts();
    },

    
};
</script>

<style >
.el-collapse-item__header {
    color: #f00 !important;
}

.not_valid {
  background-color: #f00;
}
</style>
