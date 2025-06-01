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
        </div>
        <div style="height: 10px;"></div>
        <label for="">Warehouse</label>
        <el-select name="warehouse" filterable placeholder="type at least 3 characters" :loading="loading" style="width: 100%;" v-model="form.warehouse_id">
            <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item.id">
            </el-option>
        </el-select> 

        <v-card-text>
            <v-layout wrap>
                <el-upload class="upload-demo" drag action="/get_products" :auto-upload="false" :data="form" ref="upload" :headers="headers" :on-success="handleSuccess" :before-upload="beforeUpload">
                    <i class="el-icon-upload"></i>
                    <div class="el-upload__text">
                        Drop file here or <em>click to upload</em>
                    </div>
                    <div class="el-upload__tip" slot="tip">
                        xlsx/csv files with a size less than 500kb
                    </div>
                </el-upload>

                <!-- <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="onPickFile">
                    <v-icon>mdi-upload</v-icon>
                    <span>Choose file</span>
                </v-btn> -->
                <v-tooltip bottom style="margin-left: 30px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on" href="/downloads/product.xlsx">
                            <v-icon color="primary lighten-1">
                                mdi-download
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Download Template</span>
                </v-tooltip>

                <v-spacer></v-spacer>

                <!-- <input type="file" @change="GetProducts" style="display: none" ref="fileInput"> -->
                <!-- <v-btn text color="primary" @click="upload" :disabled="loading" :loading="loading">Upload</v-btn> -->
            </v-layout>
        </v-card-text>
    </v-container>
</v-card>
</template>

<script>
import {
    mapState
} from "vuex";
export default {
    props: ["user", "form"],
    data() {
        return {
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            filePlaced: false,
            dialog: false,
            loading: false,
            products_upload: {}
        };
    },
    methods: {
        close() {
            this.dialog = false;
        },

        getSellers() {
            var payload = {
                model: "/seller/sellers",
                update: "updateSellerList"
            };
            this.$store.dispatch("getItems", payload);
        },

        getWarehouses() {
            var payload = {
                model: "/warehouses",
                update: "updateWarehouseList"
            };
            this.$store.dispatch("getItems", payload);
        },
        previewFiles() {},

        handleSuccess(res, file) {
            this.$store.dispatch('page_loader', false)
            eventBus.$emit('productResponseEvent', res)
            this.products_upload = res
        },

        beforeUpload(file) {
            this.$store.dispatch("page_loader", true);
            console.log(file);
            const isJPG = file.type === "image/jpeg";
        },

        upload() {
            this.$refs.upload.submit();

            // if (this.upload_type == '1') {
            //     var model = 'importProduct'
            // } else {
            //     var model = 'importProductSheet'
            // }
            /*
                  var model = 'get_products'

                  var payload = {
                      model: model,
                      data: this.file
                  }
                  this.$store.dispatch('postItems', payload)
                      .then(response => {

                          this.getProducts()

                          this.products_upload = response.data
                          eventBus.$emit('productResponseEvent', response.data)

                          // this.loading = false;
                          // console.log(response);
                          eventBus.$emit("alertRequest", 'Successifully uploaded');
                          this.filePlaced = false;

                          // eventBus.$emit("MenuEvent")
                      });
                      */
        }
    },
    computed: {
        ...mapState(["sellers", "warehouses"])
    },
    created() {
        eventBus.$on("fileUploadEvent", data => {
            this.upload();
        });
    },
    mounted() {
        this.getSellers();
        this.getWarehouses();
    }
};
</script>
