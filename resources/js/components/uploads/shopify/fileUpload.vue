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

                <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="onPickFile">
                    <v-icon>mdi-upload</v-icon>
                    <span>Get file</span>
                </v-btn>
                <v-spacer></v-spacer>

                <input type="file" @change="GetOrders" style="display: none" ref="fileInput">
                <!-- <v-btn text color="primary" @click="upload" :disabled="loading" :loading="loading">Upload</v-btn> -->

            </v-layout>
        </v-card-text>
    </v-container>
</v-card>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    props: ['user', 'form'],
    data() {
        return {
            filePlaced: false,
            dialog: false,
            loading: false,
            orders_upload: {},
        }
    },
    methods: {
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
        previewFiles() {},

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

        upload() {
            this.loading = true;
            var data = {
                'file': this.file,
                'id': this.product_id,
            }
            axios
                .post('importOrder', this.file)
                .then(response => {

                    this.orders_upload = response.data
                    eventBus.$emit('googleResponseEvent', response.data)

                    this.loading = false;
                    // console.log(response);
                    eventBus.$emit("alertRequest", 'Successifully uploaded');
                    this.filePlaced = false;

                    // this.close()
                })
                .catch(error => {
                    this.loading = false
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    }
                    this.errors = error.response.data.errors;
                });
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
        // this.getSellers()
        // this.getWarehouses()
    },
}
</script>
