<template>
<div>
    <div>
        <v-layout justify-center align-center wrap>
            <v-card style="width: 100%;    min-height: 100px;padding-top: 20px">
                <v-layout row wrap>
                    <v-flex sm2 style="margin-left: 25px;">
                        <label for="">Vendor</label>
                        <el-select v-model="form.vendor_id" multiple placeholder="Client" clearable filterable>
                            <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm3>
                        <div class="block">
                            <label style="float: left">Start date</label>
                            <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.start_date" type="date" placeholder="Pick a day" style="width:100%">
                            </el-date-picker>
                        </div>
                                <small class="has-text-danger" v-if="errors.start_date" style="font-size: 10px;">{{ errors.start_date[0] }}</small>

                    </v-flex>
                    <v-flex sm3>
                        <div class="block">
                            <label style="float: left">End date</label>
                            <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" v-model="form.end_date" type="date" placeholder="Pick a day" style="width:100%">
                            </el-date-picker>
                        </div>
                                <small class="has-text-danger" v-if="errors.end_date" style="font-size: 10px;">{{ errors.end_date[0] }}</small>
                    </v-flex>
                    <v-flex sm2 offset-sm-1>
                        <v-btn-toggle dense background-color="primary" style="margin-top: 18px">
                            <v-btn  @click="getPackage_list">
                            <v-icon>mdi-magnify</v-icon>
                            </v-btn>
                        </v-btn-toggle>
                        <!-- <v-btn text icon color="primary"  @click="getPackage_list" style="margin-top: 30px">
                            <v-icon>mdi-magnify</v-icon>
                        </v-btn> -->
                        <!-- <v-btn color="info" style="margin-top: 40px;" @click="getPackage_list" text>Filter</v-btn> -->
                    </v-flex>
                    <v-spacer></v-spacer>
                    <v-flex sm3 style="padding-bottom: 30px;margin-left: 30px;" v-if="sales.data">
                        <v-btn-toggle v-model="toggle_exclusive" mandatory style="margin-top: 20px;">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click="downloadPackage" color="primary">
                                        <v-icon small color="grey">mdi-adobe-acrobat</v-icon>
                                    </v-btn>
                                </template>
                                <span>Download waybills</span>
                            </v-tooltip>

                             <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click="downloadSticker" color="primary">
                                        <v-icon small color="grey">mdi-barcode-scan</v-icon>
                                    </v-btn>
                                </template>
                                <span>Download stickers</span>
                            </v-tooltip>

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click="export_dispatch" color="primary">
                                        <v-icon small color="grey">mdi-send</v-icon>
                                    </v-btn>
                                </template>
                                <span>Download Dispatch list</span>
                            </v-tooltip>

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click="picking_list" color="primary">
                                        <v-icon small color="grey">mdi-package</v-icon>
                                    </v-btn>
                                </template>
                                <span>Packing List</span>
                            </v-tooltip>

                        </v-btn-toggle>
                    </v-flex>

                    <!-- <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" text slot="activator" color="orange" icon class="mx-0" type="submit" style="margin-top: 40px;" @click="downloadPackage">
                                        <v-icon color="blue darken-2" small>picture_as_pdf</v-icon>
                                    </v-btn>
                                </template>
                                <span>Download saleorders pdf</span>
                            </v-tooltip> -->
                    <!-- <v-flex sm2>
                            <v-btn color="primary" style="margin-top: 40px;" @click="picking_list" text>Download Pickinglist</v-btn>
                        </v-flex>
                        <v-flex sm2>

                            <v-btn color="primary" style="margin-top: 40px;" @click="export_dispatch" text>Dispatch List</v-btn>
                        </v-flex>
                        <v-flex sm2 style="margin-left: 30px;">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" text slot="activator" color="orange" icon class="mx-0" type="submit" style="margin-top: 40px;" @click="downloadPackage">
                                        <v-icon color="blue darken-2" small>picture_as_pdf</v-icon>
                                    </v-btn>
                                </template>
                                <span>Download saleorders pdf</span>
                            </v-tooltip>
                        </v-flex> -->
                </v-layout>
                <v-spacer></v-spacer>
                <!-- <v-toolbar-title>
                        <v-tooltip bottom>
                            <v-btn icon slot="activator" class="mx-0" @click="kanban = false">
                                <v-icon color="blue darken-2">list</v-icon>
                            </v-btn>
                            <span>Table view</span>
                        </v-tooltip>
                    </v-toolbar-title>
                    <v-toolbar-title>
                        <v-tooltip bottom>
                            <v-btn icon slot="activator" class="mx-0" @click="kanban = true">
                                <v-icon color="blue darken-2">view_carousel</v-icon>
                            </v-btn>
                            <span>Kanban view</span>
                        </v-tooltip>
                    </v-toolbar-title> -->
            </v-card>
            <!-- <Kanbanview v-show="kanban"></Kanbanview> -->
            <Tableview />
        </v-layout>
    </div>


    <form action="/sticker_download" method="post" ref="sticker_download" target="_blank">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="packages" :value="serialize_data">
    </form>
    <form action="/package_download" method="post" ref="form" target="_blank">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="packages" :value="serialize_data">
    </form>
    <form action="/picking_list" method="post" ref="picking_form" target="_blank">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="packages" :value="serialize_data">
    </form>

    <form action="/export_dispatch" method="post" ref="dispatch_form" target="_blank">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="packages" :value="serialize_data">
    </form>
</div>
</template>

<script>
// import Kanbanview from './Kanbanview'
import Tableview from './Tableview'
import {
    mapState
} from "vuex";
export default {
    components: {
        // Kanbanview,
        Tableview
    },
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            kanban: false,
            toggle_exclusive: 2,
            form: {},
        }
    },
    methods: {
        getPackage_list() {
            var payload = {
                model: 'package_list',
                update: 'updateSaleList',
                data: this.form
            }
            this.$store.dispatch('filterItems', payload)
        },
        downloadPackage() {
            this.$refs.form.submit()
        },
        downloadSticker() {
            this.$refs.sticker_download.submit()
        },
        picking_list() {
            this.$refs.picking_form.submit()
        },
        export_dispatch() {
            this.$refs.dispatch_form.submit()
        },
        async serialize_d(packaged) {
            // alert('test')
            return this.serialize_data = JSON.stringify(packaged)
            // return JSON.stringify(this.form);
            //     this.$refs.form.submit()
            // eventBus.$emit('printPackageEvent', packaged)
        },
    },

    computed: {
        ...mapState(['sellers', 'sales', 'errors']),
        serialize_data() {
            var start_date = this.form.start_date
            var end_date = this.form.end_date
            var client = this.form.vendor_id
            var dates_ = {
                'start_date': start_date,
                'end_date': end_date,
                'client': client,
            }
            return JSON.stringify(dates_)
        },
        serialize_pick() {
            return JSON.stringify(this.packages)
        },
    },
}
</script>
