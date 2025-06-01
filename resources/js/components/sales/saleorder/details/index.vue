<template>
<el-tabs v-model="activeName" @tab-click="handleClick" style="width: 98%;margin: auto;">
    <el-tab-pane label="Status" name="Status">
        <myOrder :saleorder="saleorder" />
    </el-tab-pane>
    <el-tab-pane label="Units" name="Units">
        <myUnits :products="saleorder.products" />
    </el-tab-pane>
    <el-tab-pane label="what's Next" name="next">
        <v-layout row wrap>
            <v-flex sm6>

                <div style="padding-bottom: 30px;margin-left: 30px">
                    <v-layout row wrap>
                        <v-flex sm2>

                            <svg style="width: 50px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 499.54 499.54" class="icon icon-xxxlg">
                                <g id="Layer_2" data-name="Layer 2">
                                    <g id="Layer_1-2" data-name="Layer 1">
                                        <circle cx="249.77" cy="249.77" r="249.77" fill="#ffefdd"></circle>
                                        <path fill="#f9a13c" d="M299.85 371.97l-.54.48-.5.43-2.94 2.61-2.33 2.06-.14.12-.75.67-2.15 1.9-1.3 1.15-.57.5-2.66 2.36-.54.47-.69.61-2.21 1.96-.38.33-1.98 1.75-.13.11-.02.02-.72.64-.63.55-2.82 2.49-2.74 2.43-.09.07-.4.35-.57.51-2.87 2.54-.07.07-1.63 1.43-.94.83-.58.52-1.48 1.31-1.97 1.74-3.23 2.85-.61.55-1.24 1.1-.83.72-.77.68-.1.09-1.51 1.34-1.18 1.03-.43.38-.87.78-.84.74-.66.58-.06.05-.2-.18-7.31-6.19-1.14-.98-1.36-1.15-4.3-3.65-1.37-1.17-2.07-1.76-2.36-2-.98-.84-3.88-3.29-.75-.64-5.42-4.62-1.29-1.08-1.98-1.69-9.46-8.04-4.12-3.5-3.52-3h29.69v-32.98l.01-3.42v-32.71l1.32.63 2.27 1.07.89.41 1.68.79 2.36 1.11 23.93 11.25 2.4 1.13 6.88 3.24v49.36h29.75z"></path>
                                        <path d="M134.73 127.66l26.38 26.38m203.71-28.61l-28.61 28.61m-87.2-36.86V83.67" fill="none" stroke="#f9ac3e" stroke-linecap="round" stroke-linejoin="round" stroke-width="8"></path>
                                        <path d="M310.85 175.88a58.46 58.46 0 00-14-19.5 63 63 0 00-16.8-11l-1.62-.71h-.06c-1-.42-2-.82-3-1.2a79 79 0 00-27.66-4.67h-1.82a72.87 72.87 0 00-13.89 1.58 58.52 58.52 0 00-9.76 3c-1.19.47-2.34 1-3.47 1.48a69.28 69.28 0 00-8.57 4.62 62.6 62.6 0 00-7.61 5.66c-.4.34-.79.7-1.18 1a70.75 70.75 0 00-6 6.14 68.52 68.52 0 00-6 8.11l-.6 1c-1.43 2.34-2.61 4.34-3.6 6.24a49.19 49.19 0 00-1.67 3.55 45.19 45.19 0 00-2 5.94l35.45 17.8a40.2 40.2 0 013.92-10 36.21 36.21 0 016.76-8.72 31.13 31.13 0 019.43-6.11 30.33 30.33 0 0111.94-2.27 31.19 31.19 0 019.52 1.36 22.78 22.78 0 019.82 6.06 24.86 24.86 0 016.36 11.19 24.36 24.36 0 01.71 5.89 31.75 31.75 0 01-.16 3.28 25.11 25.11 0 01-3.13 10.3 20.93 20.93 0 01-1.43 2.22c-1 1.33-2.06 2.7-3.27 4.11q-3.19 3.72-7.51 7.83c-.78.74-1.58 1.49-2.42 2.25a193.68 193.68 0 00-3.78 3.36q-4.33 3.94-7.78 7.58c-.74.76-1.44 1.52-2.11 2.27-1.17 1.3-2.25 2.59-3.27 3.89a58.33 58.33 0 00-5.53 8.37l-.14.27a50.33 50.33 0 00-3.24 7.4 49 49 0 00-1.49 5.06c-.41 1.71-.73 3.46-1 5.28a67 67 0 00-.58 8.8v.26L264 302.44l5.72 2.84v-15a51.33 51.33 0 01.94-10.31 34.32 34.32 0 013-8.55 41.21 41.21 0 015.65-8.22q3.62-4.17 9-9.35 5.65-5.46 10.69-11.12a75.65 75.65 0 008.8-12.08 63.25 63.25 0 006-13.86 62.26 62.26 0 00-2.84-41z" fill="#fbb124"></path>
                                        <path d="M270.1 322.61v8.83l-41.73-19.19v-9.18l4.48 2.02 1.68.79 2.36 1.11 23.93 11.25 2.4 1.13 6.88 3.24zm0 20.27v9.52l-41.73-19.18v-9.19l41.73 18.85zm25.77 32.61l-2.33 2.06-.14.12-.75.67-2.15 1.9-1.3 1.15-.57.5-60.27-27.71V345l41.74 18.85v8.12h17.98l7.79 3.52zm-15.7 13.88l-.13.11-.02.02-.72.64-.63.55-2.82 2.49-2.74 2.43-.09.07-51.29-23.59h6.63v-6.13l51.81 23.41zm-15.69 13.87l-1.97 1.74-3.23 2.85-.61.55-1.24 1.1-32.22-14.82-.75-.64-5.42-4.62-1.29-1.08-1.98-1.69-9.46-8.04-4.12-3.5 62.29 28.15z" fill="#f2f3f9"></path>
                                    </g>
                                </g>
                            </svg>
                        </v-flex>
                        <v-flex sm10>

                            <span>
                                <h2>Send the Sales Order</h2>
                                <small v-if="saleorder.status == 'open'">Sales order has been created. You can email the Sales Order to your customer or confirmed the order for delivery.</small>
                                <small v-else-if="saleorder.status == 'Inprogress'">Sales order has been created. You can email the Sales Order to your customer or confirmed the order for delivery.</small>
                                <small v-else-if="saleorder.status == 'Confirmed'">Sales order has been confirmed for delivery. You can create a shipment for the order to be dispatched.</small>
                                <small v-else-if="saleorder.status == 'Shipped'">Sales order has been shipped. You can update the order as delivered or returned</small>
                                <small v-else-if="saleorder.status == 'Closed'">Sales order has been closed. You can't perform any more actions to this order.</small>
                            </span>
                        </v-flex>
                    </v-layout>
                </div>
            </v-flex>
            <v-flex sm5 offset-sm1 v-if="!saleorder.confirmed">
                <v-btn color="primary" small text @click="confirm">Mark as Confirmed</v-btn>
                <v-btn color="primary" small text>Send Sale Order</v-btn>
            </v-flex>

        </v-layout>
    </el-tab-pane>
    <el-tab-pane label="Comment&History" name="comments">
        <el-collapse accordion>
            <el-collapse-item name="1">
                <template slot="title">
                    Comments <i class="header-icon el-icon-info"></i>
                </template>
                <div>
                    <el-timeline>
                        <el-timeline-item v-for="(item, index) in saleorder.order_history" :key="index" icon="el-icon-comment" :type="item.type" :color="item.color" :size="item.size" :timestamp="item.timestamp">
                            <p v-html="item.show_comment + ' | ' + item.created_at"></p>
                            <small v-if="item.comment">Comment: <b>{{item.comment}}</b></small>

                            <el-collapse accordion v-if="item.updated_fields['original']">
                                <el-collapse-item name="1">
                                    <template slot="title">
                                        Updated fields<i class="header-icon el-icon-s-operation"></i>
                                    </template>
                                    <v-layout row wrap style="padding-top: 20px;">
                                        <v-flex sm6 style="1px solid rgb(215 216 216)">
                                            <b>Original</b>
                                            <v-divider></v-divider>
                                            <v-list-item two-line v-for="(update, o_key) in item.updated_fields['original']" :key="o_key">
                                                <v-list-item-content>
                                                    <v-list-item-subtitle> {{ o_key }}: <b>{{ update }}</b></v-list-item-subtitle>
                                                    <!-- <v-list-item-subtitle>Secondary text</v-list-item-subtitle> -->
                                                </v-list-item-content>
                                            </v-list-item>

                                        </v-flex>
                                        <v-flex sm6>
                                            <b>Updated to</b>
                                            <v-divider></v-divider>
                                            <v-list-item two-line v-for="(update, u_key) in item.updated_fields['updated']" :key="u_key">
                                                <v-list-item-content>
                                                    <v-list-item-subtitle> {{ u_key }}: <b>{{ update }}</b></v-list-item-subtitle>
                                                    <!-- <v-list-item-subtitle>Secondary text</v-list-item-subtitle> -->
                                                </v-list-item-content>
                                            </v-list-item>

                                        </v-flex>
                                    </v-layout>
                                </el-collapse-item>
                            </el-collapse>

                        </el-timeline-item>
                    </el-timeline>
                </div>
            </el-collapse-item>
        </el-collapse>



        <el-collapse accordion>
            <el-collapse-item name="1">
                <template slot="title">
                    Calls <i class="header-icon el-icon-info"></i>
                </template>
                <div>
                    <el-timeline>
                        <el-timeline-item v-for="(item, index) in saleorder.calls" :key="index" icon="el-icon-comment" :type="item.type" :color="item.color" :size="item.size" :timestamp="item.timestamp">
                            <p v-html="item.call_status + ' | ' + item.created_at"></p>
                            <small>Duration: <b>{{item.durationInSeconds}} secs</b></small>

                        </el-timeline-item>
                    </el-timeline>
                </div>
            </el-collapse-item>
        </el-collapse>

        <el-dialog title="Comment" :visible.sync="dialog" width="30%">
            <el-input placeholder="Please input" v-model="form.comment"></el-input>

            <div slot="footer" class="dialog-footer">
                <v-btn color="primary" style="text-transform: none;" text @click="addComment">
                    <v-icon>mdi-comment</v-icon>
                    <span>Comment</span>
                </v-btn>
            </div>
        </el-dialog>

        <v-btn small color="primary" style="text-transform: none;" rounded @click="dialog = true">
            <v-icon>mdi-comment</v-icon>
            <span>Comment</span>
        </v-btn>

        <myInvoice />
    </el-tab-pane>
    <el-tab-pane label="Charges" name="charges">
        <myCharges />
    </el-tab-pane>
</el-tabs>
</template>

<script>
import {
    mapState
} from 'vuex';
// import myPackage from './package'
import myOrder from './order.vue'
import myUnits from './units.vue'
import myCharges from '../charges'
export default {
    name: 'Orderdetails',
    components: {
        // myPackage,
        myCharges,
        myOrder, myUnits
    },
    data() {
        return {
            form: {},
            dialog: false,
            activeName: 'Status',
            show_all: false,
        }
    },
    methods: {
        addComment() {
            this.form.sale_id = this.$route.params.id
            var payload = {
                model: 'comments',
                data: this.form,
            }
            this.$store.dispatch("postItems", payload).then((res) => {
                eventBus.$emit('routerChangeEvent')
            })
        },
        handleClick(tab, event) {
            console.log(tab, event);
        },
        getInvoice() {
            var payload = {
                model: 'invoices',
                update: 'updateInvoiceList',
                id: this.$route.params.id
            }
            this.$store.dispatch('showItem', payload)
        },
        confirm() {
            var payload = {
                model: 'confirm',
                data: '',
                id: this.$route.params.id
            }
            this.$store.dispatch('patchItems', payload).then((res) => {
                eventBus.$emit('routerChangeEvent')
            })
        }
    },
    created() {
        eventBus.$on('confirmEvent', data => {
            this.confirm()
        })
    },
    computed: {
        ...mapState(['invoices', 'packages', 'saleorder'])
    },

};
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

</style>
