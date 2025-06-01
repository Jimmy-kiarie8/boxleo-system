<template>
<div>
    <v-chip-group mandatory>
        <v-chip v-if="product_transactions.sales">
            Total {{ product_transactions.sales.total }}
        </v-chip>
        <v-chip :color="(index == 'Delivered') ? 'success' : 'primary'" v-for="(data, index) in product_transactions.counts" :key="index">
            {{ index }} {{data}}
        </v-chip>
    </v-chip-group>

    <v-data-table :headers="product_transactions.columns" :items="product_transactions.sales.data" :search="search">
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

        </template>
        <template v-slot:item.paid="{ item }">
            <el-tooltip :content="(item.paid) ? 'Paid' : 'Not paid'" placement="top">
                <v-avatar style="cursor: pointer" :color="(item.paid) ? 'green' : 'red'" small></v-avatar>
            </el-tooltip>
        </template>
        <template v-slot:item.printed="{ item }">
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn v-on="on" icon class="mx-0" slot="activator">
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
                <v-icon :color="s_color" style="font-size: 16px;margin-right: 10px;" v-if="item.platform == 'Shopify'" small>fab fa-shopify</v-icon>
                <v-icon :color="w_color" style="font-size: 16px;margin-right: 10px;" v-else-if="item.platform == 'Woocommerce'" small>fab fa-wordpress-simple</v-icon>

                <v-icon :color="g_color" style="font-size: 16px;margin-right: 10px;" v-else-if="item.platform == 'Google Sheets'" small>mdi-google-spreadsheet</v-icon>

                <v-icon color="primary" style="font-size: 16px;margin-right: 10px;" v-else-if="item.platform == 'API'" small>mdi-api</v-icon>
                <v-icon color="primary" style="font-size: 16px;margin-right: 10px;" small v-else>fas fa-file-excel</v-icon>
                {{ item.order_no }}
            </v-btn>

            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on" @click="copyText(item.order_no)" color="primary" style="float: right;">
                        <v-icon small>mdi-content-copy</v-icon>
                    </v-btn>
                </template>
                <span>Copy order No.</span>
            </v-tooltip>
        </template>
    </v-data-table>
</div>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    props: ['user'],
    data() {
        return {
            search: '',
            s_color: '#95bf47',
            w_color: '#9b5c8f',
            g_color: '#34a853',
        }
    },
    computed: {
        ...mapState(['product_transactions'])
    },

    methods: {

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
        openOrder(data) {
            eventBus.$emit("drawerEvent", data.id);
        },
    }
}
</script>
