<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="1000px">


        <v-card class="overflow-hidden">
            <v-app-bar absolute color="primary" dark shrink-on-scroll prominent scroll-target="#scrolling-techniques">
                <!-- <label for="">Phone number</label> -->
                <span class="headline text-center" style="margin: auto; text-align: left !important" v-if="!agent_call">
                 <!--  <el-select v-model="form.phone" filterable remote allow-create reserve-keyword placeholder="Phone number" :remote-method="searchLeads" :loading="loading" style="width: 100%" @change="lead_details" value-key="phone" >
                        <el-option v-for="item in lead_search" :key="item.id" :label="item.phone" :value="item"></el-option>
                    </el-select>--> 
                    <el-input placeholder="Please input" v-model="form.phone" @change="searchLeads" v-if="new_call"></el-input>

                    <v-chip class="ma-2" color="teal" v-else> {{ phoneNumber }}</v-chip>
                </span>
                <!-- <v-spacer></v-spacer>
                <el-tooltip content="Comment" placement="top">
                    <v-btn color="white" dark icon @click="comment">
                        <v-icon dark small>mdi-comment</v-icon>
                    </v-btn>
                </el-tooltip> -->
                <el-tooltip content="Edit contact" placement="top">
                    <v-btn color="white" dark icon @click="editDetails">
                        <v-icon dark small>mdi-pen</v-icon>
                    </v-btn>
                </el-tooltip>
                <v-btn color="whte" dark icon @click="close">
                    <v-icon dark small>mdi-close</v-icon>
                </v-btn>
            </v-app-bar>
            <v-sheet id="scrolling-techniques" class="overflow-y-auto" max-height="600">
                <v-container style="height: 300px; margin-top: 110px">
                    <v-list dense v-if="!agent_call">
                        <v-list-item-group color="error">
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon color="error">mdi-account-circle</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>{{ client.name }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                        <v-list-item-group color="error">
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon color="success">mdi-email</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>{{ client.email}}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                        <v-list-item-group color="error">
                            <v-list-item @click="swithPhone(client.phone)">
                                <v-list-item-icon>
                                    <v-icon color="primary">mdi-phone</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>{{ client.phone}}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                        <v-list-item-group color="error">
                            <v-list-item @click="swithPhone(client.alt_phone)">
                                <v-list-item-icon>
                                    <v-icon color="primary">mdi-phone-plus</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>{{ client.alt_phone}}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                        <v-list-item-group color="error">
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon color="warning">mdi-map-marker</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>{{ client.address}}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                    <v-data-table :headers="headers" :items="order" :loading="loading" v-if="!agent_call">

                        <template v-slot:item.customer_notes="props">
                            <v-edit-dialog :return-value.sync="props.item.customer_notes" large persistent @save="update_data(props.item, 'customer_notes')" >
                                
                                <span id="address_tab">
                                    <el-tooltip class="item" effect="dark" :content="props.item.customer_notes" placement="top-start"  @click="updateStatus()">
                                        <span>
                                    {{ props.item.customer_notes  }}
                                        </span>
                                    </el-tooltip>
                                </span>
                                <template v-slot:input>
                                    <div class="mt-4 title" style="color: #333">
                                        Update Notes
                                    </div>
                                    <v-text-field v-model="props.item.customer_notes" label="Edit" single-line counter autofocus></v-text-field>
                                </template>

                            </v-edit-dialog>
                        </template> 
                        <template v-slot:item.total_price="props">
                            <v-edit-dialog :return-value.sync="props.item.total_price" large persistent @save="update_data(props.item, 'total_price')" >
                                <div>{{ props.item.total_price }}</div>
                                <template v-slot:input>
                                    <div class="mt-4 title" style="color: #333">
                                        Update Price
                                    </div>
                                    <v-text-field v-model="props.item.total_price" label="Edit" single-line counter autofocus></v-text-field>
                                </template>
                            </v-edit-dialog>
                        </template> 
                        <template v-slot:item.delivery_status="props">
                                <v-btn text color="primary" label @click="updateStatus()">
                                    <v-icon left>
                                        mdi-check-circle-outline
                                    </v-icon>
                                    {{ props.item.delivery_status  }}
                                </v-btn>
                        </template>


                        <template v-slot:item.product_name="props">
                                <v-btn text color="primary" label @click="updateProduct(props.item)">
                                    <v-icon left>
                                        mdi-check-circle-outline
                                    </v-icon>
                                    {{ props.item.product_name  }}
                                </v-btn>
                        </template>
                        
                        <template v-slot:item.delivery_date="props">
                            <v-edit-dialog :return-value.sync="props.item.delivery_date" large persistent @save="update_data(props.item, 'delivery_date')">
                                <el-tag type="info">{{ props.item.delivery_date }}</el-tag>
                                <template v-slot:input>
                                    <div class="mt-4 title" style="color: #333">
                                        Update name
                                    </div>
                                    <el-date-picker v-model="props.item.delivery_date" type="date" placeholder="Pick a day" value-format="yyyy-MM-dd"></el-date-picker>
                                </template>
                            </v-edit-dialog>
                        </template> 
                    </v-data-table>
                   <!--<v-data-table :headers="agent_header" :items="agents.data" :search="search" :loading="loading" v-else> <template v-slot:item.actions="{ item }">
                            <v-tooltip bottom> <template v-slot:activator="{ on }">
                                    <v-btn icon v-on="on" class="mx-0" @click="call(item.agent_sip)" slot="activator">
                                        <v-icon small color="success">mdi-phone-outgoing</v-icon>
                                    </v-btn>
                                </template> <span>Call</span> </v-tooltip>
                        </template></v-data-table>--> 


                    <v-data-table :headers="headers" :items="call_orders.sales.data" :search="search" :loading="loading" v-if="call_logs">
                    </v-data-table>
                </v-container>
            </v-sheet>

            <v-card-actions>
                <v-btn color="red" text @click="hangup" v-if="call_inprogress">
                    <v-icon color="red">mdi-phone-hangup</v-icon> Hangup
                </v-btn>

                <v-btn color="red" text @click="unhold" v-if="onhold">
                    <v-icon color="red">mdi-phone</v-icon> Resume
                </v-btn>
                <div  v-else>
                 <v-btn color="red" text @click="hold" v-if="call_inprogress">
                    <v-icon color="red">mdi-pause</v-icon> Hold
                </v-btn> 
            </div>
                <v-btn color="primary" text @click="transfer" v-if="call_inprogress"><v-icon color="primary">mdi-phone-forward</v-icon> Transfer
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn color="success darken-1" text @click="call" :loading="Cloading" :disabled="Cloading" v-if="!call_inprogress">
                    <v-icon color="error">mdi-phone-outgoing-outline</v-icon> Call
                </v-btn>
            </v-card-actions>
        </v-card>
        <myComment />

        <v-card>
        <v-card-text>
<!--
            <el-collapse accordion>
                <el-collapse-item name="1" style="padding: 20px">
                    <template slot="title">
                        Comments<i class="header-icon el-icon-info"></i>
                    </template>
                    <el-timeline>
                        <el-timeline-item v-for="(comment, index) in lead.comments" :key="index" icon="el-icon-more" type="error" color="#0bbd87" size="large" :timestamp="comment.created_at"> {{comment.comment}} by  <el-tag type="danger">{{ comment.user.name }}</el-tag> </el-timeline-item>
                    </el-timeline>
                </el-collapse-item>
            </el-collapse>
                -->
        </v-card-text>
        </v-card>

        <div class="text-center">
            <v-dialog v-model="transfer_dialog" width="500">
                <v-card>
                    <v-card-title class="text-h5 primary">
                        Transfer to
                    </v-card-title>

                    <v-card-text>
                        <v-list dense>
                           <v-list-item-group v-model="selectedItem" color="primary">
                                <v-list-item v-for="(item, i) in agents" :key="i" @click="trnasfer_call(item.agent_sip)">
                                    <v-list-item-icon>
                                        <v-icon>mdi-account-circle</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title v-text="item.name"></v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" text @click="transfer_dialog = false">
                            Cancel
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>


    </v-dialog>

    <myProduct />
</v-layout>
</template>

<script>
import {
    mapState
} from "vuex";
import myComment from './inc/comment.vue';
import myProduct from '../product/update.vue'
export default {
    components: {
        myComment, myProduct
    },
    data: () => ({
        dialog: false,
        call_logs: false,
        form: {
            phone: {},
            delivery_status: ''
        },
        statuses: ['Cancelled', 'Out of stock', 'Pending', 'Scheduled'],
        selectedItem: 0,
        lead_detail: {},
        lead_id: null,
        Cloading: false,
        client: {},
        search: "",
        call_inprogress: false,
        new_call: true,
        agent_call: true,
        new_no: false,
        order: {},
        transfer_dialog: false,
        onhold: false,
        phoneNumber: null,
        agent_header: [{
                text: "Name",
                value: "name",
            },
            {
                text: "SIP",
                value: "agent_sip",
            },
            {
                text: "Actions",
                value: "actions",
            },
        ],
        headers: [{
                text: "Order no",
                value: "order_no",
            },
            {
                text: "Product Name",
                value: "product_name",
            },
            {
                text: "Total price",
                value: "total_price",
            },
            {
                text: "Delivery status",
                value: "delivery_status",
            },
            {
                text: "Delivery Status",
                value: "delivery_date",
            },
            {
                text: "Instructions",
                value: "customer_notes",
            },
            {
                text: "Created On",
                value: "created_at",
            },
        ],
    }),
    created() {
        eventBus.$on("callEvent", (data) => {
            this.order = [data.order];
            if (data.call == "new") {
                this.form = data;
                this.phoneNumber = data.phoneNumber;
                this.agent_call = false;
                this.new_call = true;
            } else if (data.call == "agent") {
                this.new_call = false;
                this.agent_call = true;
            }  else if (data.call == "api") {
                this.phoneNumber = data.phoneNumber;
                this.lead_id = data.data.id;
                this.client = data.data;
                this.new_call = false;
                this.form.phone = data.data;
                this.agent_call = false;
            } else {
                this.searchLeads(data.phoneNumber);
                this.new_call = false;
                // this.form.phone = data;
                this.agent_call = false;
                this.phoneNumber = data.phoneNumber;
            }
            this.dialog = true;

            setTimeout(() => {
                this.getOrders(this.phoneNumber);
            }, 300);
        });
        eventBus.$on("callHangedupEvent", (data) => {
            this.call_inprogress = false;
            this.updateStatus();
        });


    },

    methods: {
        getOrders(phone) {            
            var payload = {
                update: 'orderCallList',
                model: 'order_search/' + phone,
                page_loader: false
            }
            this.$store.dispatch('getItems', payload)
                .then(response => {
                    this.call_logs = true
                    // this.goToSales()
                });

        },
        swithPhone(phone) {
            this.phoneNumber = phone;
        },
        transfer() {
            this.transfer_dialog = true

        // if(this.agents.length < 1) {
            this.getAgents();
        // }
        },
        cancel() {},

        open_dialog() {
            // this.snack = true
            // this.snackColor = 'info'
            // this.snackText = 'Dialog opened'
        },
        update_data(item, update) {
            // console.log(data);
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
        updateStatus() {
            if(this.order) {
                eventBus.$emit("orderStatusEvent", this.order[0]);
            }
        },
        updateProduct(item) {
            console.log("🚀 ~ file: call.vue:387 ~ updateProduct ~ item:", item)
            eventBus.$emit("productEvent", item);
        },
        comment() {
            eventBus.$emit("commentEvent", this.form.phone);
        },
        getAgents() {
            var payload = {
                model: 'agents',
                update: 'updateAgentList',
            }
            this.$store.dispatch("getItems", payload);
        },
        editDetails() {
            eventBus.$emit("editEvent", this.client);
        },
        lead_details(data) {
            // console.log(data);
            // console.log(typeof data);

            if (typeof data == "string") {
                // alert('ttttt')
                this.lead_detail = {
                    phone: data,
                };
                this.new_no = true;
                this.getLead(data);
            } else {
                // alert('2121')
                this.new_no = false;
                this.lead_detail = data;
                this.getLead(data.phone);
            }
        },
        getLead(id) {
            var payload = {
                update: "updateLead",
                model: "lead_phone",
                load: "no",
                id: id,
            };
            this.$store.dispatch("showItem", payload);
        },

        getLeadItem(id) {
            var payload = {
                update: "updateLead",
                model: "leads",
                load: "no",
                id: id,
            };
            this.$store.dispatch("showItem", payload);
        },
        call(data) {
            this.onCall(1);
            // console.log(data);
            this.call_inprogress = true;
            var phone = "";

            if (this.agent_call) {
                phone = data;
            } else {
                if (this.new_call) {
                    phone = this.form.phone;
                } else {
                    
                    phone = this.phoneNumber;
                }
            }
            let phoneNoSpaces = phone.replace(/\s/g, "");

            eventBus.$emit("makeCallEvent", phoneNoSpaces);
            // eventBus.$emit("currentCallEvent", phone);

            return;
        },

        async onCall(id) {
            this.Cloading = true;
            var payload = {
                data: {},
                model: 'on_call/' + id,
                page_loader: false
            }
            await this.$store.dispatch('postItems', payload)
            this.Cloading = false;

        
        },
        update_missed() {
            var payload = {
                data: this.form.phone,
                model: "/missed_update",
                load: "no",
                notify: "no",
            };
            this.$store.dispatch("postItems", payload);
        },
        create_lead() {
            var payload = {
                data: this.form,
                model: "/leads",
                load: "no",
                notify: "no",
            };
            this.$store.dispatch("postItems", payload);
        },
        hangup() {
            eventBus.$emit("hangupEvent", this.phoneNumber);
        },
        close() {
            this.dialog = false;
        },

        trnasfer_call(agent) {
            console.log(agent);
            var form = {
                sessionId: '',
                phoneNumber: this.caller,
                callLeg: '',
                transfer_to: agent,
                holdMusicUrl: '',
            }
            var payload = {
                'data': form,
                'model': 'transfer'
            }
            this.$store.dispatch("postItems", payload);

        },
        hold() {
            eventBus.$emit('holdEvent')
        },
        unhold() {
            eventBus.$emit('unholdEvent')
        },
        searchLeads(search) {
            if (search.length > 8) {
                var payload = {
                    update: "searchLead",
                    model: "searchLeads",
                    load: "no",
                    search: search,
                };
                this.$store.dispatch("searchItems", payload).then((res) => {
                    this.client = res.data;
                    this.order = res.data.sales;
                });
            }
        },
        searchSale(id) {
            // if (search.length > 2) {
                var payload = {
                    update: "searchLead",
                    model: "search_order",
                    load: "no",
                    search: id,
                };
                this.$store.dispatch("searchItems", payload);
            // }
        },
    },
    computed: {
        ...mapState(["errors", "loading", "lead_search", "callHistory", "agents", "call_orders"]),
    },
};
</script>

<style scoped>

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
