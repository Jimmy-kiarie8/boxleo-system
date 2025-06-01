<template>
<div class="text-center">
    <v-dialog v-model="dialog" width="500" persistent>
        <v-card>
            <v-card-title class="headline">
                <p>Create an ASN</p>
                <v-spacer></v-spacer>
                <v-btn color="primary" icon @click="close">
                    <v-icon dark right>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-layout row wrap>

                    <v-flex sm12>
                        <label for="">Vendor</label>
                        <el-select v-model="form.vendor_id" placeholder="Select" @change="getProducts" style="width:100%" clearable filterable>
                            <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id"></el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm12>
                        <label for="">Level</label>
                        <el-input placeholder="Level 1" v-model="form.name"></el-input>
                    </v-flex>
                    <v-flex sm12>
                        <label for="">Quantity</label>
                        <el-input placeholder="L1" v-model="form.quantity"></el-input>
                    </v-flex>
                </v-layout>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="submit">
                    Submit
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</div>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    data() {
        return {
            dialog: false,
            form: {}
        }
    },
    methods: {
        submit() {
            var payload = {
                model: 'asn',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // eventBus.$emit("asnEvent")
                });
        },
        close() {
            this.dialog = false
        },
        getVendors() {
            var payload = {
                model: 'sellers',
                update: 'updateSellerList',
            }
            this.$store.dispatch('getItems', payload);
        },
        getProducts(id) {
            var payload = {
                model: 'vendor_product',
                update: 'updateProductsList',
                id: id
            }
            this.$store.dispatch('showItem', payload);
        },
    },
    created() {
        eventBus.$on('CreateLevelEvent', data => {
            // console.log(data);
            // this.form.area_id = data.area_id
            this.dialog = true
            this.getVendors()
        });
    },
    computed: {
        ...mapState(['sellers', 'products'])
    },
}
</script>
