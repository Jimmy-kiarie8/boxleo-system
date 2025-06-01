<template>
    <div>
        <v-container fluid fill-height id="orders">

            <v-overlay :value="overlay"></v-overlay>

            <v-card style="padding: 20px 20px;" v-if="ready" width="100%">
                <!-- <div v-if="setup_loaded && (!setup.products || !setup.vendors)">
                    <mySetup />
                </div> -->
                <v-layout justify-center align-center wrap>
                    <v-flex sm12>
                        <el-breadcrumb separator-class="el-icon-arrow-right">
                            <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                            <el-breadcrumb-item>Sales</el-breadcrumb-item>
                        </el-breadcrumb>
                    </v-flex>
                    <v-divider></v-divider>
                    <v-flex sm8>
                        <v-text-field v-model="order_item.search" label="Search waybill(order no, phone, name)" required
                            prepend-icon="mdi-magnify" @keyup.enter="search_order"></v-text-field>
                    </v-flex>
                    <v-flex sm3 offset-sm1 id="filter">
                        <myFilter :form="filter_form" :user="user" />
                    </v-flex>

                    <v-dialog v-model="comment_dialog" width="500">
                        <v-card>
                            <v-card-title class="text-h5 grey lighten-2">
                                Comment
                            </v-card-title>
                            <v-card-text style="padding-top: 20px">
                                <el-input placeholder="Please input" v-model="comment_form.comment"></el-input>
                            </v-card-text>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" style="text-transform: none;" text @click="addComment">
                                    <v-icon>mdi-comment</v-icon>
                                    <span>Comment</span>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-flex sm12 style="margin: 30px 0">
                        <v-pagination v-model="sales.sales.current_page" :length="sales.sales.last_page"
                            total-visible="5" @input="next_page(sales.sales.path, sales.sales.current_page)" circle
                            v-if="sales.sales.last_page > 1"></v-pagination>
                    </v-flex>
                    <v-flex sm12>
                        <v-layout row wrap>
                            <v-flex sm8>
                                <el-tabs v-model="activeName" @tab-click="handleClick">
                                    <el-tab-pane label="All" name="All"></el-tab-pane>
                                    <el-tab-pane label="Follow up" name="Open"></el-tab-pane>
                                    <el-tab-pane label="New" name="New"></el-tab-pane>
                                    <el-tab-pane label="Pending" name="Pending"></el-tab-pane>
                                    <el-tab-pane label="Confirmed" name="Confirmed"></el-tab-pane>
                                    <el-tab-pane label="Undispatched" name="Undispatched"></el-tab-pane>
                                    <el-tab-pane label="Awaiting Dispatch" name="Awaiting Dispatch"></el-tab-pane>
                                    <el-tab-pane label="Awaiting Return" name="Awaiting Return"></el-tab-pane>
                                    <el-tab-pane label="Shipped" name="Shipped"></el-tab-pane>
                                    <el-tab-pane label="Closed" name="Closed"></el-tab-pane>
                                    <el-tab-pane label="Returned" name="Returned"></el-tab-pane>
                                    <el-tab-pane label="Out Of Stock" name="Out Of Stock"></el-tab-pane>
                                </el-tabs>
                            </v-flex>
                            <v-flex sm3>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="refreshSales">
                                            <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Refresh</span>
                                </v-tooltip>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="downloadSale">
                                            <v-icon color="blue darken-2" small>mdi-file-excel</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Download</span>
                                </v-tooltip>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="filter">
                                            <v-icon color="blue darken-2" small>mdi-filter</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Filter</span>
                                </v-tooltip>

                                <myOrder />

                            </v-flex>

                        </v-layout>
                    </v-flex>
                    <v-flex sm12>

                        <v-text-field v-model="search" append-icon="mdi-magnify" label="Quick Search" single-line
                            hide-details></v-text-field>
                        <v-data-table :headers="sales.columns" :items="sales.sales.data" :search="search"
                            :single-select="singleSelect" item-key="id" show-select class="elevation-1"
                            v-model="selected" :loading="loading" style="font-size: 10px !important">
                            <template v-slot:top>
                                <!-- <el-select v-model="form.app_custom_id" filterable clearable placeholder="Custom Views"
                                    @input="get_orders" style="margin-top: 10px" v-if="app_custom.length > 0">
                                    <el-option v-for="item in app_custom" :key="item.id" :label="item.name"
                                        :value="item.id">
                                    </el-option>
                                </el-select> -->
                                <v-tooltip right>
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="customView">
                                            <v-icon color="blue darken-2" small>mdi-face-agent</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Custom view</span>
                                </v-tooltip>

                                <v-tooltip right
                                    v-if="user.can['Order update status'] && selected.length > 0 && !deleted_orders">
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="orderStatus" slot="activator">
                                            <v-icon small color="blue darken-2">mdi-upload-network-outline</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Update orders Status</span>
                                </v-tooltip>

                                <v-tooltip right
                                    v-if="selected.length > 0 && !deleted_orders && user.can['Order assign']">
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="assignOrders" slot="activator">
                                            <v-icon small color="blue darken-2">mdi-clipboard-text</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Assign to an agent</span>
                                </v-tooltip>

                                <v-tooltip right
                                    v-if="selected.length > 0 && user.can['Order print edit'] && !deleted_orders">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="open(0, 0, 'mult')">
                                            <v-icon color="blue darken-2" small>mdi-printer-check</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Mark orders as printed</span>
                                </v-tooltip>
                                <v-tooltip right
                                    v-if="selected.length > 0 && user.can['Order print edit'] && !deleted_orders">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="open(1, 0, 'mult')">
                                            <v-icon color="blue darken-2" small>mdi-tray-remove</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Mark orders as not printed</span>
                                </v-tooltip>

                                <v-tooltip right v-if="user.can['Order delete']">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="deletedOrders">
                                            <v-icon color="blue darken-2" small>mdi-delete-sweep-outline</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Deleted Orders</span>
                                </v-tooltip>

                                <v-tooltip right
                                    v-if="selected.length > 0 && user.can['Order delete'] && !deleted_orders">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="deleteItems">
                                            <v-icon color="red darken-2" small>mdi-delete</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Delete selected orders</span>
                                </v-tooltip>

                                <v-tooltip right
                                    v-if="selected.length > 0 && !deleted_orders && user.can['Manage service']">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="applyCharge">
                                            <v-icon color="blue darken-2" small>mdi-cash</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Apply charges</span>
                                </v-tooltip>

                                <v-tooltip right v-if="selected.length">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="returnOrder">
                                            <v-icon color="blue darken-2" small>mdi-file-send</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Send for return</span>
                                </v-tooltip>
                            </template>
                            <template v-slot:item.created_at="{ item }">
                                <el-tag type="warning">{{ item.created_at }}</el-tag>
                            </template>
                            <template v-slot:item.delivery_date="{ item }">
                                <el-tag type="info">{{ item.delivery_date }}</el-tag>
                            </template>
                            <template v-slot:item.sub_total="{ item }">
                                <el-tag type="info">{{ item.sub_total }}</el-tag>
                            </template>
                            <template v-slot:item.total="{ item }">
                                <el-tag type="error">{{ item.total }}</el-tag>
                            </template>
                            <template v-slot:item.delivery_status="{ item }">
                                <div v-if="canUpdateStatus(item)">
                                    <v-btn :disabled="isButtonDisabled(item)"
                                        :color="getButtonColor(item.delivery_status)" text label @click="updateStatus(item)">
                                        <v-icon left>
                                            {{ getStatusIcon(item.delivery_status) }}
                                        </v-icon>
                                        {{ item.delivery_status }}
                                    </v-btn>
                                </div>
                                <div v-else>
                                    <v-btn :color="getButtonColor(item.delivery_status)" text label disabled>
                                        <v-icon left>
                                            {{ getStatusIcon(item.delivery_status) }}
                                        </v-icon>
                                        {{ item.delivery_status }}
                                    </v-btn>
                                </div>
                            </template>
                            <!-- <template v-slot:item.delivery_status="{ item }">
                            <div @click="updateStatus(item)" v-if="!user.is_vendor">
                                <v-btn text color="success" label v-if="item.delivery_status == 'Delivered'">
                                    <v-icon left>
                                        mdi-check-circle-outline
                                    </v-icon>
                                    {{ item.delivery_status  }}
                                </v-btn>
                                <v-btn text color="warning" label v-else-if="item.delivery_status == 'Dispatched'">
                                    <v-icon left>
                                        mdi-progress-check
                                    </v-icon>
                                    {{ item.delivery_status  }}
                                </v-btn>
                                <v-btn text color="primary" label v-else-if="item.delivery_status == 'Scheduled'">
                                    <v-icon left>
                                        mdi-update
                                    </v-icon>
                                    {{ item.delivery_status  }}
                                </v-btn>
                                <v-btn text label v-else-if="item.delivery_status == 'New'">
                                    <v-icon left  color="success">
                                        mdi-new-box
                                    </v-icon>
                                    {{ item.delivery_status  }}
                                </v-btn>
                                <v-btn text color="error" label v-else-if="item.delivery_status == 'Returned' || item.delivery_status == 'Cancelled'">
                                    <v-icon left>
                                        mdi-cancel
                                    </v-icon>
                                    {{ item.delivery_status  }}
                                </v-btn>
                                <v-btn text label v-else>
                                    <v-icon left>
                                        mdi-progress-alert
                                    </v-icon>
                                    {{ item.delivery_status  }}
                                </v-btn>

                            </div>

                            <div v-else>
                                <v-btn text color="success" label v-if="item.delivery_status == 'Delivered'">
                                    <v-icon left>
                                        mdi-check-circle-outline
                                    </v-icon>
                                    {{ item.delivery_status  }}
                                </v-btn>
                                <v-btn text color="warning" label v-else-if="item.delivery_status == 'Dispatched'">
                                    <v-icon left>
                                        mdi-progress-check
                                    </v-icon>
                                    {{ item.delivery_status  }}
                                </v-btn>
                                <v-btn text color="primary" label v-else-if="item.delivery_status == 'Scheduled'">
                                    <v-icon left>
                                        mdi-update
                                    </v-icon>
                                    {{ item.delivery_status  }}
                                </v-btn>
                                <v-btn text color="error" label v-else-if="item.delivery_status == 'Returned' || item.delivery_status == 'Cancelled'">
                                    <v-icon left>
                                        mdi-cancel
                                    </v-icon>
                                    {{ item.delivery_status  }}
                                </v-btn>
                                <v-btn text label v-else>
                                    <v-icon left>
                                        mdi-progress-alert
                                    </v-icon>
                                    {{ item.delivery_status  }}
                                </v-btn>
                            </div>

                        </template> -->
                            <template v-slot:item.paid="{ item }">
                                <el-tooltip :content="(item.paid) ? 'Paid' : 'Not paid'" placement="top">
                                    <v-avatar style="cursor: pointer" :color="(item.paid) ? 'green' : 'red'"
                                        small></v-avatar>
                                </el-tooltip>
                            </template>
                            <template v-slot:item.printed="{ item }">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" slot="activator"
                                            @click="open(item.printed, item.id)" v-if="user.can['Order print edit']">
                                            <v-icon small color="green" v-if="item.printed">mdi-check-circle</v-icon>
                                            <v-icon small color="grey darken-2" v-else>mdi-check-circle</v-icon>
                                        </v-btn>
                                        <v-btn v-on="on" icon class="mx-0" slot="activator" v-else>
                                            <v-icon small color="green" v-if="item.printed">mdi-check-circle</v-icon>
                                            <v-icon small color="grey darken-2" v-else>mdi-check-circle</v-icon>
                                        </v-btn>
                                    </template>
                                    <span v-if="item.printed">Mark order as not printed </span>
                                    <span v-else>Mark order as printed </span>
                                </v-tooltip>
                            </template>

                            <template v-slot:item.mpesa_code="{ item }">
                                <el-tag>{{ item.mpesa_code }}</el-tag>
                            </template>
                            <template v-slot:item.order_no="{ item }" style="padding-left: 0 !important;" id="order-no">
                                <v-btn color="primary" text @click="openOrder(item)">
                                    <v-icon :color="s_color" style="font-size: 16px;margin-right: 10px;"
                                        v-if="item.platform == 'Shopify'" small>fab fa-shopify</v-icon>
                                    <v-icon :color="w_color" style="font-size: 16px;margin-right: 10px;"
                                        v-else-if="item.platform == 'Woocommerce'" small>fab
                                        fa-wordpress-simple</v-icon>

                                    <v-icon :color="g_color" style="font-size: 16px;margin-right: 10px;"
                                        v-else-if="item.platform == 'Google Sheets'"
                                        small>mdi-google-spreadsheet</v-icon>

                                    <v-icon color="primary" style="font-size: 16px;margin-right: 10px;"
                                        v-else-if="item.platform == 'API'" small>mdi-api</v-icon>
                                    <v-icon color="primary" style="font-size: 16px;margin-right: 10px;" small
                                        v-else-if="item.platform == 'Upload'">fas fa-file-excel</v-icon>
                                    <v-icon color="red" style="font-size: 16px;margin-right: 10px;" small
                                        v-else>mdi-monitor-dashboard</v-icon>
                                    {{ item.order_no }}
                                </v-btn>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn icon v-bind="attrs" v-on="on" @click="copyText(item.order_no)"
                                            color="primary" style="float: right;">
                                            <v-icon small>mdi-content-copy</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Copy order No.</span>
                                </v-tooltip>
                            </template>
                            <template v-slot:item.delivery_date="props" v-if="user.can['Order edit'] || user.is_vendor">
                                <v-edit-dialog :return-value.sync="props.item.delivery_date" large persistent
                                    @save="update_date(props.item)" @cancel="cancel" @open="open_dialog" @close="close">
                                    <!-- <div>{{ props.item.delivery_date }}</div> -->
                                    <el-tag type="info">{{ props.item.delivery_date }}</el-tag>

                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update name
                                        </div>
                                        <el-date-picker v-model="props.item.delivery_date" type="date"
                                            placeholder="Pick a day" value-format="yyyy-MM-dd"></el-date-picker>
                                    </template>
                                </v-edit-dialog>
                            </template>

                            <template v-slot:item.delivered_on="props" v-if="user.can['Order edit'] || user.is_vendor">
                                <v-edit-dialog :return-value.sync="props.item.delivered_on" large persistent
                                    @save="update_item(props.item, 'delivered_on')" @cancel="cancel" @open="open_dialog"
                                    @close="close">
                                    <!-- <div>{{ props.item.delivered_on }}</div> -->
                                    <el-tag type="info">{{ props.item.delivered_on }}</el-tag>

                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update
                                        </div>
                                        <el-date-picker v-model="props.item.delivered_on" type="date"
                                            placeholder="Pick a day" value-format="yyyy-MM-dd"></el-date-picker>
                                    </template>
                                </v-edit-dialog>
                            </template>
                            <template v-slot:item.returned_on="props" v-if="user.can['Order edit'] || user.is_vendor">
                                <v-edit-dialog :return-value.sync="props.item.returned_on" large persistent
                                    @save="update_item(props.item, 'returned_on')" @cancel="cancel" @open="open_dialog"
                                    @close="close">
                                    <!-- <div>{{ props.item.returned_on }}</div> -->
                                    <el-tag type="info">{{ props.item.returned_on }}</el-tag>

                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update
                                        </div>
                                        <el-date-picker v-model="props.item.returned_on" type="date"
                                            placeholder="Pick a day" value-format="yyyy-MM-dd"></el-date-picker>
                                    </template>
                                </v-edit-dialog>
                            </template>

                            <template v-slot:item.pod="{ item }">
                                <!-- <img :src="item.pod.signature" alt="" v-if="item.pod" style="width: 35px;" @click="open_pod(item)"> -->
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="open_pod(item)" slot="activator">
                                            <v-icon small color="blue darken-2">mdi-eye</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>View PDO</span>
                                </v-tooltip>
                            </template>
                            <template v-slot:item.actions="{ item }" v-if="user.can['Order view']">

                                <div style="min-width: 280px">
                                    <v-tooltip bottom v-if="user.can['Recordings']">
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" @click="openCalls(item)"
                                                slot="activator">
                                                <v-icon small color="red darken-2">mdi-play-circle</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Recordings</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" @click="openShow(item)" slot="activator">
                                                <v-icon small color="blue darken-2">mdi-magnify-plus-cursor</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Quick view</span>
                                    </v-tooltip>
                                    <v-tooltip bottom v-if="user.can['Order edit'] && !deleted_orders">
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" @click="openEdit(item)" slot="activator">
                                                <v-icon small color="blue darken-2">mdi-pencil</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Edit order {{ item.order_no }}</span>
                                    </v-tooltip>
                                    <v-tooltip bottom v-if="user.can['Order update status'] && !deleted_orders">
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" @click="updateStatus(item)"
                                                slot="activator">
                                                <v-icon small color="blue darken-2">mdi-upload-network-outline</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Update order {{ item.order_no }} Status</span>
                                    </v-tooltip>

                                    <v-tooltip bottom v-if="user.can['Order update status'] && !deleted_orders">
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" @click="openComment(item.id)"
                                                slot="activator">
                                                <v-icon small color="blue darken-2">mdi-comment</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Comment</span>
                                    </v-tooltip>
                                    <v-tooltip bottom v-if="user.can['Order assign rider'] && !deleted_orders">
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" @click="assign(item, 'orderRiderEvent')"
                                                slot="activator">
                                                <v-icon small color="blue darken-2">mdi-registered-trademark</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Assign order {{ item.order_no }} to a rider</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }" v-if="user.can['Order view details']">
                                            <v-btn v-on="on" icon class="mx-0" @click="openOrder(item)"
                                                slot="activator">
                                                <v-icon small color="blue darken-2">mdi-eye</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Order {{ item.order_no }} details</span>
                                    </v-tooltip>
                                    <v-tooltip bottom
                                        v-if="user.can['Order print edit'] && !item.printed && !deleted_orders">
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" slot="activator"
                                                :href="'/pack_download/' + item.id" target="_blank">
                                                <v-icon small color="blue darken-2">mdi-printer</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Print Waybill {{ item.order_no }}</span>
                                    </v-tooltip>

                                    <v-tooltip bottom
                                        v-if="user.can['Order print edit'] && !item.printed && !deleted_orders">
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="mx-0" slot="activator"
                                                :href="'/sticker_pdf/' + item.id" target="_blank">
                                                <v-icon small color="blue darken-2">mdi-barcode-scan</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Print sticker {{ item.order_no }}</span>
                                    </v-tooltip>
                                    <v-tooltip bottom v-if="user.can['Order delete'] && !deleted_orders">
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="confirm_delete(item)"
                                                slot="activator">
                                                <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Delete order {{ item.order_no }}</span>
                                    </v-tooltip>

                                    <v-tooltip bottom v-if="user.can['Order delete'] && deleted_orders">
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" class="mx-0" @click="restore_order(item)"
                                                slot="activator">
                                                <v-icon small color="pink darken-2">mdi-history</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Delete order {{ item.order_no }}</span>
                                    </v-tooltip>


                                    <!-- <v-tooltip bottom v-if="user.roles[0]['Admin'] && !deleted_orders">
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" class="mx-0" @click="upsell(item.id)" slot="activator">
                                            <v-icon small color="pink darken-2">mdi-check-circle</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Upsell {{ item.order_no }}</span>
                                </v-tooltip> -->
                                </div>
                            </template>

                            <template v-slot:item.client_phone="props" v-if="user.can['Order edit']">
                                <v-edit-dialog :return-value.sync="props.item.client_phone" large persistent
                                    @save="update_data(props.item)" @cancel="cancel" @open="open_dialog" @close="close">
                                    <div>{{ props.item.client_phone }}</div>
                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update phone
                                        </div>
                                        <v-text-field v-model="props.item.client_phone" label="Edit" single-line counter
                                            autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn icon v-bind="attrs" v-on="on" @click="makecall(props.item, 'phone')"
                                            color="primary" style="float: right;">
                                            <v-icon small>mdi-phone</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Call</span>
                                </v-tooltip>

                            </template>

                            <template v-slot:item.alt_phone="props">
                                <span>
                                    {{ props.item.alt_phone }}
                                </span>

                                <v-tooltip bottom v-if="props.item.alt_phone">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn icon v-bind="attrs" v-on="on" @click="makecall(props.item, 'alt_phone')"
                                            color="primary" style="float: right;">
                                            <v-icon small>mdi-phone</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Call</span>
                                </v-tooltip>

                            </template>


                            <template v-slot:item.client_name="props" v-if="user.can['Order edit']">
                                <v-edit-dialog :return-value.sync="props.item.client_name" large persistent
                                    @save="update_data(props.item)" @cancel="cancel" @open="open_dialog" @close="close">
                                    <div>{{ props.item.client_name }}</div>
                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update name
                                        </div>
                                        <v-text-field v-model="props.item.client_name" label="Edit" single-line counter
                                            autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>
                            <template v-slot:item.client_address="props" v-if="user.can['Order edit']">
                                <v-edit-dialog :return-value.sync="props.item.client_address" large persistent
                                    @save="update_data(props.item)" @cancel="cancel" @open="open_dialog" @close="close">
                                    <span id="address_tab">
                                        <el-tooltip class="item" effect="dark" :content="props.item.client_address"
                                            placement="top-start">
                                            <span>
                                                {{ props.item.client_address }}
                                            </span>
                                        </el-tooltip>
                                    </span>
                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update address
                                        </div>
                                        <v-text-field v-model="props.item.client_address" label="Edit" single-line
                                            counter autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>

                            <template v-slot:item.client_city="props" v-if="user.can['Order edit']">
                                <v-edit-dialog :return-value.sync="props.item.client_city" large persistent
                                    @save="update_data(props.item)" @cancel="cancel" @open="open_dialog" @close="close">
                                    <span id="address_tab">
                                        <el-tooltip class="item" effect="dark" :content="props.item.client_city"
                                            placement="top-start">
                                            <span>
                                                {{ props.item.client_city }}
                                            </span>
                                        </el-tooltip>
                                    </span>
                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update address
                                        </div>
                                        <v-text-field v-model="props.item.client_city" label="Edit" single-line counter
                                            autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>

                            <template v-slot:item.total_price="props" v-if="user.can['Order edit']">
                                <v-edit-dialog :return-value.sync="props.item.total_price" large persistent
                                    @save="update_item(props.item, 'total_price')" @cancel="cancel" @open="open_dialog"
                                    @close="close">
                                    <el-tag type="info">{{ props.item.total_price }}</el-tag>
                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update amount
                                        </div>
                                        <v-text-field v-model="props.item.total_price" label="Edit" single-line counter
                                            autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>

                            <template v-slot:item.weight="props" v-if="user.can['Order edit']">
                                <v-edit-dialog :return-value.sync="props.item.weight" large persistent
                                    @save="update_item(props.item, 'weight')" @cancel="cancel" @open="open_dialog"
                                    @close="close">
                                    <el-tag type="info">{{ props.item.weight }}</el-tag>
                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update Weight
                                        </div>
                                        <v-text-field v-model="props.item.weight" label="Edit" single-line counter
                                            autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>

                            <template v-slot:item.shipping_charges="props" v-if="user.can['Order edit']">
                                <v-edit-dialog :return-value.sync="props.item.shipping_charges" large persistent
                                    @save="update_item(props.item, 'shipping_charges')" @cancel="cancel"
                                    @open="open_dialog" @close="close">
                                    <el-tag type="info">{{ props.item.shipping_charges }}</el-tag>
                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update Charges
                                        </div>
                                        <v-text-field v-model="props.item.shipping_charges" label="Edit" single-line
                                            counter autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>
                            <template v-slot:item.invoice_value="props" v-if="user.can['Order edit']">
                                <v-edit-dialog :return-value.sync="props.item.invoice_value" large persistent
                                    @save="update_item(props.item, 'invoice_value')" @cancel="cancel"
                                    @open="open_dialog" @close="close">
                                    <el-tag type="info">{{ props.item.invoice_value }}</el-tag>
                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update name
                                        </div>
                                        <v-text-field v-model="props.item.invoice_value" label="Edit" single-line
                                            counter autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>

                            <template v-slot:item.customer_notes="props" v-if="user.can['Order edit']">
                                <v-edit-dialog :return-value.sync="props.item.customer_notes" large persistent
                                    @save="update_item(props.item, 'customer_notes')" @cancel="cancel"
                                    @open="open_dialog" @close="close">
                                    <span id="address_tab">
                                        <el-tooltip class="item" effect="dark" :content="props.item.customer_notes"
                                            placement="top-start">
                                            <span>
                                                {{ props.item.customer_notes }}
                                            </span>
                                        </el-tooltip>
                                    </span>
                                    <template v-slot:input>
                                        <div class="mt-4 title" style="color: #333">
                                            Update Notes
                                        </div>
                                        <v-text-field v-model="props.item.customer_notes" label="Edit" single-line
                                            counter autofocus></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </template>
                            <template v-slot:item.product_name="props">
                                <div @click="openShow(props.item)">
                                    <span id="address_tab">
                                        <el-tooltip class="item" effect="dark" :content="props.item.product_name"
                                            placement="top-start">
                                            <span>
                                                {{ props.item.product_name }}
                                            </span>
                                        </el-tooltip>
                                    </span>
                                </div>

                                <v-tooltip bottom v-if="user.can['Order edit']">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn icon v-bind="attrs" v-on="on" @click="editProduct(props.item)"
                                            color="primary" style="float: right;">
                                            <v-icon small>mdi-pencil-circle</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Edit</span>
                                </v-tooltip>
                            </template>

                            <template v-slot:item.upsell="{ item }">
                                <!-- <el-tooltip :content="(item.upsell) ? 'Upsell' : 'Not upsell'" placement="top">
                                <v-avatar style="cursor: pointer" :color="(item.upsell) ? 'green' : 'red'" small></v-avatar>
                            </el-tooltip>  @click="upsell(item.id)"-->
                                <v-checkbox v-model="item.upsell" @click="upsell(item.id)"
                                    :disabled="!user.roles[0]['Admin']"></v-checkbox>

                            </template>
                        </v-data-table>

                        <!-- <el-table :data="sales.sales.data" stripe style="width: 100%">
                            <el-table-column :prop="item.field" :label="item.label" width="180" v-for="(item, index) in columns" :key="index"></el-table-column>
                        </el-table> -->

                    </v-flex>
                </v-layout>
            </v-card>

            <v-sheet :color="`grey ${theme.isDark ? 'darken-2' : 'lighten-4'}`" class="px-3 pt-3 pb-3" v-else
                style="width: 100vw;">
                <v-skeleton-loader class="mx-auto" max-width="900" type="table"></v-skeleton-loader>
            </v-sheet>
            <myShow />
            <myStatus />
            <myUpload :user="user" :tenant="tenant" />
            <myExcel :user="user" :tenant="tenant" />
            <!-- <myShopify :user="user" /> -->
            <!-- <myWoocommerce  :user="user" /> -->
            <myCustomView />
            <myContact />
            <myRider />
            <myStatusMult />
            <myCharges />
            <myAssign />
            <myPod />
            <myRecording />
        </v-container>
        <!-- <myDrawer :user="user" /> -->
    </div>
</template>



<script>
import myContact from '../callcentre/edit.vue'
import myRecording from '../callcentre/show.vue'
import myShow from './Show'
import myExcel from '../uploads/excel'
import myStatus from './status/Status'
import myUpload from '../uploads/upload'
// import myShopify from '../uploads/shopify'
// import myWoocommerce from '../uploads/woocommerce'
import myCustomView from '../settings/sales/Custom'
import myPod from './saleorder/pod'

// import myBranch from './status/branch'
import myRider from './status/rider'

import myFilter from './filter/filter'
import myOrder from './filter/upload'
import myCharges from './charge'
import myAssign from './saleorder/assign.vue'

import mySetup from './setup'
import myStatusMult from './status/status_mult'
import CopyToClipboard from 'vue-copy-to-clipboard'

import {
    mapState
} from 'vuex'
export default {
    props: ['user', 'tenant'],
    components: {
        CopyToClipboard,
        myShow,
        myUpload,
        myStatus,
        myExcel,
        myRecording,
        myCustomView,
        myAssign,
        myRider,
        myFilter,
        myOrder,
        mySetup,
        myContact,
        myStatusMult, myCharges, myPod
    },
    inject: ['theme'],
    data() {
        return {
            overlay: false,
            dynamic_text: '',
            s_color: '#95bf47',
            w_color: '#9b5c8f',
            g_color: '#34a853',
            singleSelect: false,
            selected: [],
            order_item: {
                search: ''
            },
            filter_form: {
                app_custom_id: null
            },
            form: {},
            ready: false,
            deleted_orders: false,
            search: "",
            checkedRows: [],
            activeName: 'All',
            columns: [{
                label: "Created On",
                field: "created_at",
                // type: "date",
                // dateInputFormat: "YYYY-MM-DD",
                // dateOutputFormat: "Do MMMM YYYY"
            },
            {
                label: "Client Name",
                field: "client_name"
            },
            {
                label: "Created By",
                field: "user_name"
            },
            {
                label: "Sub total",
                field: "sub_total"
            },
            {
                label: "Discount",
                field: "discount"
            },
            {
                label: "Total",
                field: "total_price"
            },
            {
                label: "Charges",
                field: "shipping_charges"
            },
            {
                label: "Printed",
                field: "printed"
            },
            {
                label: "Actions",
                field: "actions",
                sortable: false
            }
            ],
            snack: false,
            setup_loaded: true,
            tab_filter: 'all',
            default_filter: true,
            search_filter: false,
            comment_dialog: false,
            comment_form: {
                comment: ''
            },

        };
    },
    filters: {
        columnHead(value) {
            return value.split('_').join(' ').toUpperCase()
        }
    },
    methods: {

        canUpdateStatus(item) {
            const role = this.user.roles[0].name;
            console.log(role)
            const status = item.delivery_status;

            if (['Call Center', 'Follow Up'].includes(role)) {
                return ['New', 'Pending', 'Out Of Stock','Cancelled', 'Undispatched', 'Rescheduled', 'Returned', 'Scheduled'].includes(status);
            }

            if (['Operations', 'Warehouse'].includes(role)) {
                // return ['Awaiting received', 'Awaiting Return', 'Return Pending', 'Pending Approval', 'Return received'].includes(status);
                return false;
            }

            if (role === 'Finance') {
                return ['In Transit', 'Awaiting Return', 'Return Pending', 'Pending Approval', 'Return received'].includes(status);
            }

            return !this.user.is_vendor;
        },
        isButtonDisabled(item) {
            return item.delivery_status === 'Delivered';
        },
        getButtonColor(status) {
            const colorMap = {
                'Delivered': 'success',
                'Dispatched': 'warning',
                'Scheduled': 'primary',
                'New': 'teal',
                'Returned': 'error',
                'Cancelled': 'error'
            };
            return colorMap[status] || '';
        },
        getStatusIcon(status) {
            const iconMap = {
                'Delivered': 'mdi-check-circle-outline',
                'Dispatched': 'mdi-progress-check',
                'Scheduled': 'mdi-update',
                'New': 'mdi-new-box',
                'Returned': 'mdi-cancel',
                'Cancelled': 'mdi-cancel'
            };
            return iconMap[status] || 'mdi-progress-alert';
        },


        upsell(id) {
            var payload = {
                data: {},
                model: 'upsell',
                id: id
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    this.refreshSales()
                });
        },
        editProduct(data) {
            eventBus.$emit('productEvent', data);
        },

        update_item(item, update) {
            const data = {
                update: update,
                data: item
            }
            var payload = {
                data: data,
                model: 'order_edit',
                id: item.id
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    // this.goToSales()
                });
        },
        assignOrders() {
            eventBus.$emit("assignEvent", this.selected);
        },
        makecall(item, phoneType) {
            let phone = item.client.phone;
            if (phoneType == 'alt_phone') {
                phone = item.client.alt_phone
            }
            var data = {
                call: 'api',
                phoneNumber: phone,
                order: item,
                data: (item.client) ? item.client : item
            }
            eventBus.$emit('callEvent', data);
        },

        open_pod(data) {
            eventBus.$emit('openPod', data)
        },

        update_date(data) {
            var payload = {
                data: data,
                model: 'delivery_date',
                id: data.id
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    // this.goToSales()
                });
        },
        getOrderZones(item) {
            var data = item;
            var payload = {
                model: 'sale_zones/' + item.id,
                update: 'UpdateEmpty',
            }
            this.$store.dispatch('getItems', payload).then(res => {
                data.zone_to = res.data.zone_to;
                data.zone_from = res.data.zone_id;
                eventBus.$emit("orderStatusEvent", data);

            });
        },
        copyText(textToCopy) {
            const tmpTextField = document.createElement("textarea")
            tmpTextField.textContent = textToCopy
            tmpTextField.setAttribute("style", "position:absolute; right:200%;")
            document.body.appendChild(tmpTextField)
            tmpTextField.select()
            tmpTextField.setSelectionRange(0, 99999) /*For mobile devices*/
            document.execCommand("copy")
            tmpTextField.remove()
        },

        handleClick(tab, event) {
            this.tab_filter = tab.name;
            this.refreshSales();
        },
        cancel() { },

        open_dialog() {
            // this.snack = true
            // this.snackColor = 'info'
            // this.snackText = 'Dialog opened'
        },
        update_data(data) {
            var payload = {
                data: data,
                model: 'order_update',
                id: data.id
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    // this.goToSales()
                });
        },
        filter() {
            this.overlay = true
            eventBus.$emit('openFilterEvent')

        },
        close() {
        },

        open(printed, id, type) {
            this.$prompt('Please enter reason for re-printing', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
            }).then(({
                value
            }) => {
                if (type == 'mult') {
                    this.printmult_change(printed, value)
                } else {
                    this.print_change(printed, id, value)
                }
            }).catch(() => {
                // this.$message({
                //     type: 'error',
                //     message: 'error'
                // });
            });
        },
        print_change(printed, id, comment) {
            var payload = {
                model: '/print_change',
                id: id,
                data: {
                    printed: printed,
                    comment: comment
                }
            }

            this.$store.dispatch("patchItems", payload).then((response) => {
                this.refreshSales()
            })
        },
        printmult_change(printed, comment) {
            var payload = {
                model: '/print_mult_change',
                data: {
                    orders: this.selected,
                    printed: printed,
                    comment: comment
                }
            }

            this.$store.dispatch("postItems", payload).then(() => {
                this.refreshSales()
            })
        },
        search_order() {
            this.search_filter = true
            this.default_filter = false

            var payload = {
                model: 'order_search',
                update: 'updateSaleList',
                search: this.order_item.search
            }
            this.$store.dispatch('searchItems', payload)
        },
        printOrder() {

        },
        openOrder(data) {
            eventBus.$emit("drawerEvent", data.id);
            /* 
                        var id = data.id
                        this.$router.push({
                            name: "saleorder",
                            params: {
                                id: id
                            }
                        });
                        this.getWarehouses() */

        },
        openShow(data) {
            eventBus.$emit("openShowSale", data);
        },
        openCalls(data) {
            eventBus.$emit("showEvent", data);
        },
        googleUpload() {
            eventBus.$emit("GoogleUploadEvent");
        },
        // shopifyUpload() {
        //     eventBus.$emit("ShopifyUploadEvent");
        // },
        updateStatus(data) {
            if (this.user.can['Order update status']) {
                this.getOrderZones(data);
            }
        },
        openComment(id) {
            this.comment_id = id
            this.comment_dialog = true
        },

        addComment() {
            this.comment_form.sale_id = this.comment_id
            var payload = {
                model: 'comments',
                data: this.comment_form,
            }
            this.$store.dispatch("postItems", payload)
        },
        assign(data, event) {
            eventBus.$emit(event, data);
        },

        openEdit(data) {
            var id = data.id
            this.$router.push({
                name: "editOrder",
                params: {
                    id: id
                }
            });

            this.getWarehouses()

        },

        get_orders(data) {
            if (data) {
                var payload = {
                    model: '/app_custom',
                    id: data,
                    update: 'updateSaleList'
                }

                this.$store.dispatch("showItem", payload).then((response) => {
                    this.columns = response.data.columns
                })
            } else {
                this.getSales()
            }

        },
        confirm_delete(item) {
            this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                this.deleteItem(item)
            }).catch(() => {
                this.$message({
                    type: 'error',
                    message: 'Delete canceled'
                });
            });
        },
        restore_order(item) {
            this.$confirm('This will restore this order. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                this.restoreItem(item)
            }).catch(() => {
                this.$message({
                    type: 'error',
                    message: 'Restore canceled'
                });
            });
        },
        restoreItem(item) {
            var payload = {
                model: 'sales_restore',
                data: {},
                id: item.id
            }
            this.$store.dispatch("patchItems", payload).then(response => {
                // this.$message({
                //     type: 'success',
                //     message: 'Order restored'
                // });
                this.deletedOrders();
            });
        },

        customView() {
            eventBus.$emit("openCustomViewEvent");
        },
        uploadOrder(data) {
            eventBus.$emit("openOrderUploadEvent", data.row);
        },
        createOrder(data) {
            // eventBus.$emit("openEditProduct", data.row);

            this.$router.push({
                name: "create_order"
            });
        },
        deleteItem(item) {
            this.$store.dispatch("deleteItem", "sales/" + item.id).then(response => {
                this.$message({
                    type: 'success',
                    message: 'Delete completed'
                });
                this.get_orders();
            });
        },

        applyCharge() {
            eventBus.$emit('chargeEvent', this.selected)

        },

        returnOrder() {
            this.$confirm('Return this orders. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                var payload = {
                    model: 'send_returns',
                    data: this.selected
                }
                this.$store.dispatch("postItems", payload).then(response => {
                    this.refreshSales()
                    this.selected = []
                });
            }).catch(() => {

            });
        },

        deleteItems() {

            this.$confirm('This will delete the Order. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                // this.deleteItem(item)
                this.get_orders();
                var payload = {
                    model: '/sales_delete',
                    data: this.selected
                }
                this.$store.dispatch("postItems", payload).then(response => {
                    this.selected = []
                    // this.$message({
                    //     type: 'success',
                    //     message: 'Delete completed'
                    // });
                    this.get_orders();
                });
            }).catch(() => {
                this.$message({
                    type: 'error',
                    message: 'Delete canceled'
                });
            });

        },
        getSales() {
            var payload = {
                model: 'sale_filter',
                update: 'updateSaleList',
                data: this.form
            }
            this.$store.dispatch('filterItems', payload).then((res) => {
                this.ready = true
                this.deleted_orders = false
            })
            // var payload = {
            //     model: 'sales',
            //     update: 'updateSaleList'
            // }
            // this.$store.dispatch("getItems", payload).then((res) => {
            //     this.deleted_orders = false
            //     this.ready = true
            // })
        },
        refreshSales() {
            // search_filter
            this.deleted_orders = false
            if (this.search_filter) {
                var data = {
                    path: this.sales.sales.path,
                    page: this.sales.sales.current_page,
                    tab: this.tab_filter
                }
                eventBus.$emit('filterItemsEvent', data)
                this.search_filter = false;

            } else if (!this.default_filter) {
                var data = {
                    tab: this.tab_filter
                }
                this.default_filter = true
                eventBus.$emit('filterItemsEvent')
            } else {
                var data = {
                    path: this.sales.sales.path,
                    page: this.sales.sales.current_page,
                    tab: this.tab_filter
                }
                eventBus.$emit('refreshEvent', data)
            }
        },

        downloadSale() {
            // search_filter
            if (this.search_filter) {
                var data = {
                    path: this.sales.sales.path,
                    page: this.sales.sales.current_page,
                    tab: this.tab_filter
                }
                eventBus.$emit('downloadItemsEvent', data)
                this.search_filter = false;

            } else if (!this.default_filter) {
                var data = {
                    tab: this.tab_filter
                }
                this.default_filter = true
                eventBus.$emit('downloadItemsEvent')
            } else {
                var data = {
                    path: this.sales.sales.path,
                    page: this.sales.sales.current_page,
                    tab: this.tab_filter
                }
                eventBus.$emit('downloadItemsEvent', data)
            }
        },

        deletedOrders() {
            var payload = {
                model: 'deleted_sales',
                update: 'updateSaleList'
            }
            this.$store.dispatch("getItems", payload).then((res) => {
                this.deleted_orders = true
                this.ready = true
            })
        },

        get_filter(data) {
            var payload = {
                model: '/columns',
                id: this.user.id,
                update: 'updateFilterList'
            }

            this.$store.dispatch("showItem", payload).then((response) => {
                this.deleted_orders = false
                this.form.app_custom_id = response.data.id
                this.filter_form.app_custom_id = response.data.id

                if (response.data > 0) {
                    this.get_orders(response.data.id)
                }
            })
        },

        getWarehouses() {
            var payload = {
                model: '/warehouses',
                update: 'updateWarehouseList'
            }
            this.$store.dispatch("getItems", payload);
        },
        getCustom() {
            var payload = {
                model: '/app_custom',
                update: 'updateAppCustomList'
            }
            this.$store.dispatch("getItems", payload)
        },

        next_page(path, page) {
            var data = {
                path: path,
                page: page,
            }
            eventBus.$emit('refreshNextEvent', data)
        },
        getSetup() {
            var payload = {
                model: '/setup',
                update: 'updateSetup'
            }
            this.$store.dispatch("getItems", payload).then((res) => {
                this.setup_loaded = true
            });
        },
        orderStatus() {
            eventBus.$emit("multStatusEvent", this.selected);

        },
    },
    computed: {
        ...mapState(['app_custom', 'sales', 'loading', 'custom_sale', 'setup']),
    },
    mounted() {
        // this.$store.dispatch('getSales');
        eventBus.$emit("LoadingEvent");
        this.getSales();
        //this.getCustom();
        //this.getSetup();
    },
    created() {
        // this.getSales();
        // this.get_filter();
        eventBus.$on("saleEvent", data => {
            this.refreshSales()
            this.selected = []
            // this.getSales();
        });
        eventBus.$on("closeFilterEvent", data => {
            this.overlay = false
        });

        eventBus.$on("statusChangeEvent", data => {
            for (let index = 0; index < this.sales.sales.data.length; index++) {
                const element = this.sales.sales.data[index];
                if (element.id == data.id) {
                    var order = element
                }
            }
            var index = this.sales.sales.data.indexOf(order);

            if (index === -1) {
                return;
            }
            var payload = {
                data: data,
                index: index,
            }
            this.$store.dispatch("updateStatus", payload);

        });
        eventBus.$on("responseChooseEvent", data => {
            if (data == "Excel") {
                eventBus.$emit("openEditSale");
            } else {
                eventBus.$emit("openCreateSale");
            }
        });
    },

    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can["Order view"]) {
                next();
            } else {
                next("/");
            }
        });
    },

    beforeRouteLeave(to, from, next) {

        // destroy the vue listeners, etc
        // this.$destroy();

        // remove the element from the DOM
        // this.$el.parentNode.removeChild(this.$el);
        next();
    },

};
</script>


<style>
.el-input--prefix .el-input__inner {
    border-radius: 50px !important;
}

.v-toolbar__content,
.v-toolbar__extension {
    height: auto !important;
}

.v-avatar {
    height: 10px !important;
    width: 10px !important;
    margin-left: 40% !important;
}

.theme--light.v-data-table>.v-data-table__wrapper>table>tbody>tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {
    cursor: pointer !important;
}

#orders td {
    /* padding: 0 0 0 15px !important; */
    padding: 10px 4px !important;
}

#orders .v-data-table>.v-data-table__wrapper>table {
    width: 150% !important;
}

#address_tab span {
    font-style: inherit;
    font-weight: inherit;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 180px;
    overflow: hidden;
    display: block;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
</style>
