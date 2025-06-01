<template>
<div>
    <v-container fluid fill-height style="background: #fff;">
        <v-layout justify-center align-center wrap>
            <v-flex sm4 style="background: #f8fafc;">
                <div class="hide-overflow" style="position: relative;">
                    <v-toolbar absolute :color="not_shipped" dark scroll-off-screen scroll-target="#scrolling-techniques">
                        <v-toolbar-title>
                            <el-button-group v-if="edit_notshipped">
                                <el-tooltip class="item" effect="dark" content="Mark as delivered" placement="top-start">
                                    <el-button icon="el-icon-circle-check"></el-button>
                                </el-tooltip>
                                <el-tooltip class="item" effect="dark" content="Print" placement="top-start">
                                    <el-button icon="el-icon-printer"></el-button>
                                </el-tooltip>
                                <el-tooltip class="item" effect="dark" content="Delete" placement="top-start">
                                    <el-button icon="el-icon-delete"></el-button>
                                </el-tooltip>
                            </el-button-group>
                            <h2 v-else>Packages, Not Shipped</h2>
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-side-icon></v-toolbar-side-icon>

                        <v-tooltip right>
                            <v-btn icon slot="activator" class="mx-0" @click="getPackages">
                                <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                            </v-btn>
                            <span>Refresh</span>
                        </v-tooltip>
                    </v-toolbar>
                    <div id="scrolling-techniques" class="scroll-y" style="max-height: 600px;">
                        <v-container style="height: 90vh;">
                            <v-hover style="margin-top: 40px;border-radius: 9px;" v-for="packaged in packages.data" :key="packaged.id" v-if="!packaged.shipped && packaged.order_status != 'Delivered'">
                                <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="344">
                                    <div id="ember1638" class="ember-view">
                                        <div id="ember1667" tabindex="-1" class="kanban-list-item   ember-view">
                                            <div style="width: 10%">
                                                <span id="ember1668" class="bulk-selection-cell ember-view">
                                                    <!-- <input id="ember1669" class="ember-checkbox ember-view" type="checkbox"> -->
                                                    <v-checkbox v-model="edit_notshipped" style="margin-top: -6px;"></v-checkbox>
                                                </span>
                                            </div>
                                            <div style="width: 90%">
                                                <div>
                                                    <span style="float: right;">14.00</span>
                                                    <div class="name over-flow" style="font-size: 12px;">{{ packaged.client.name }}</div>
                                                </div>
                                                <div style="font-size: 13px;">
                                                    <div>
                                                        <a class="over-flow" data-ember-action="" data-ember-action-1670="1670"> {{ packaged.package_no }} </a>
                                                        <span class="text-muted separationline over-flow">SO-00001</span>
                                                    </div>
                                                    <div>
                                                        <label class="text-muted over-flow"> SPEEDBALL COURIER SERVICES LTD
                                                            <span class="over-flow separationline">02 Jul 2019</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!---->
                                    </div>
                                    <v-card-actions>
                                        <VSpacer />
                                        <el-dropdown trigger="click">
                                            <span class="el-dropdown-link">
                                                <i class="el-icon-more el-icon--right"></i>
                                            </span>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item icon="el-icon-circle-check">
                                                    <v-btn text color="primary" @click="mark_delivered(packaged)">Mark as delivered</v-btn>
                                                </el-dropdown-item>
                                                <el-dropdown-item divided icon="el-icon-printer">
                                                    <a @click="shipLable(packaged)">Print/Download Package Label</a>
                                                </el-dropdown-item>
                                                <el-dropdown-item icon="el-icon-printer">
                                                    <a @click="delete_package(packaged)">Delete Packages</a>
                                                </el-dropdown-item>

                                            </el-dropdown-menu>
                                        </el-dropdown>
                                    </v-card-actions>
                                </v-card>
                            </v-hover>
                        </v-container>
                    </div>
                </div>
            </v-flex>

            <v-flex sm4 style="background: #f8fafc;">
                <div class="hide-overflow" style="position: relative;">
                    <v-toolbar absolute :color="shipped" dark scroll-off-screen scroll-target="#scrolling-techniques">
                        <v-toolbar-title>
                            <el-button-group v-if="edit_shipped">
                                <el-tooltip class="item" effect="dark" content="Mark as delivered" placement="top-start">
                                    <el-button icon="el-icon-circle-check"></el-button>
                                </el-tooltip>
                                <el-tooltip class="item" effect="dark" content="Print" placement="top-start">
                                    <el-button icon="el-icon-printer"></el-button>
                                </el-tooltip>
                                <el-tooltip class="item" effect="dark" content="Delete" placement="top-start">
                                    <el-button icon="el-icon-delete"></el-button>
                                </el-tooltip>
                            </el-button-group>
                            <h2 v-else>Shipped Packages</h2>
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-side-icon></v-toolbar-side-icon>
                    </v-toolbar>
                    <div id="scrolling-techniques" class="scroll-y" style="max-height: 600px;">
                        <v-container style="height: 90vh;">
                            <v-hover style="margin-top: 40px;border-radius: 9px;" v-for="packaged in packages.data" :key="packaged.id" v-if="packaged.shipped && packaged.order_status != 'Delivered'">
                                <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="344">
                                    <div id="ember1638" class="ember-view">
                                        <div id="ember1667" tabindex="-1" class="kanban-list-item   ember-view">
                                            <div style="width: 10%">
                                                <span id="ember1668" class="bulk-selection-cell ember-view">
                                                    <v-checkbox v-model="edit_shipped" style="margin-top: -6px;"></v-checkbox>
                                                </span>
                                            </div>
                                            <div style="width: 90%">
                                                <div>
                                                    <span style="float: right;">14.00</span>
                                                    <div class="name over-flow" style="font-size: 12px;">{{ packaged.client.name }}</div>
                                                </div>
                                                <div style="font-size: 13px;">
                                                    <div>
                                                        <a class="over-flow" data-ember-action="" data-ember-action-1670="1670"> {{ packaged.package_no }} </a>
                                                        <span class="text-muted separationline over-flow">SO-00001</span>
                                                    </div>
                                                    <div>
                                                        <label class="text-muted over-flow"> SPEEDBALL COURIER SERVICES LTD
                                                            <span class="over-flow separationline">02 Jul 2019</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!---->
                                    </div>
                                    <v-card-actions>
                                        <VSpacer />
                                        <el-dropdown trigger="click">
                                            <span class="el-dropdown-link">
                                                <i class="el-icon-more el-icon--right"></i>
                                            </span>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item icon="el-icon-circle-check">
                                                    <v-btn text color="primary" @click="mark_delivered(packaged)">Mark as delivered</v-btn>
                                                </el-dropdown-item>
                                                <el-dropdown-item divided icon="el-icon-printer">
                                                    <a @click="shipLable(packaged)">Print Shipment Label</a>
                                                </el-dropdown-item>
                                                <el-dropdown-item icon="el-icon-printer">Print Packages slip</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown>
                                    </v-card-actions>
                                </v-card>
                            </v-hover>
                        </v-container>
                    </div>
                </div>
            </v-flex>

            <v-flex sm4 style="background: #f8fafc;">
                <div class="hide-overflow" style="position: relative;">
                    <v-toolbar absolute :color="delivered" dark scroll-off-screen scroll-target="#scrolling-techniques">
                        <v-toolbar-title>
                            <el-button-group v-if="edit_delivered">
                                <el-tooltip class="item" effect="dark" content="Mark as delivered" placement="top-start">
                                    <el-button icon="el-icon-circle-check"></el-button>
                                </el-tooltip>
                                <el-tooltip class="item" effect="dark" content="Print" placement="top-start">
                                    <el-button icon="el-icon-printer"></el-button>
                                </el-tooltip>
                                <el-tooltip class="item" effect="dark" content="Delete" placement="top-start">
                                    <el-button icon="el-icon-delete"></el-button>
                                </el-tooltip>
                            </el-button-group>
                            <h2 v-else>Delivered Packages</h2>
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-side-icon></v-toolbar-side-icon>
                    </v-toolbar>
                    <div id="scrolling-techniques" class="scroll-y" style="max-height: 600px;">
                        <v-container style="height: 90vh;">
                            <v-hover style="margin-top: 40px;border-radius: 9px;" v-for="packaged in packages.data" :key="packaged.id" v-if="packaged.order_status == 'Delivered'">
                                <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto" width="344">
                                    <div id="ember1638" class="ember-view">
                                        <div id="ember1667" tabindex="-1" class="kanban-list-item   ember-view">
                                            <div style="width: 10%">
                                                <span id="ember1668" class="bulk-selection-cell ember-view">
                                                    <v-checkbox v-model="edit_delivered" style="margin-top: -6px;"></v-checkbox>
                                                </span>
                                            </div>
                                            <div style="width: 90%">
                                                <div>
                                                    <span style="float: right;">14.00</span>
                                                    <div class="name over-flow" style="font-size: 12px;">{{ packaged.client.name }}</div>
                                                </div>
                                                <div style="font-size: 13px;">
                                                    <div>
                                                        <a class="over-flow" data-ember-action="" data-ember-action-1670="1670"> {{ packaged.package_no }} </a>
                                                        <span class="text-muted separationline over-flow">SO-00001</span>
                                                    </div>
                                                    <div>
                                                        <label class="text-muted over-flow"> SPEEDBALL COURIER SERVICES LTD
                                                            <span class="over-flow separationline">02 Jul 2019</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!---->
                                    </div>
                                    <v-card-actions>
                                        <VSpacer />
                                        <el-dropdown trigger="click">
                                            <span class="el-dropdown-link">
                                                <i class="el-icon-more el-icon--right"></i>
                                            </span>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item icon="el-icon-circle-check">
                                                    <v-btn text color="primary" @click="mark_delivered(packaged)">Mark as delivered</v-btn>
                                                </el-dropdown-item>
                                                <el-dropdown-item divided icon="el-icon-printer">
                                                    <a @click="shipLable(packaged)">Print Shipment Label</a>
                                                </el-dropdown-item>
                                                <el-dropdown-item icon="el-icon-printer">Print Packages slip</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown>
                                    </v-card-actions>
                                </v-card>
                            </v-hover>
                        </v-container>
                    </div>
                </div>
            </v-flex>
        </v-layout>
    </v-container>
    <el-button type="text" @click="delete_package"></el-button>

    <form action="/lableDownload" method="post" ref="form_data" target="_blank">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="inventory" :value="serialize_data">
    </form>
    <myLable></myLable>
    <myPackage></myPackage>
</div>
</template>

<script>
import myLable from '../printing/Lable'
import myPackage from '../printing/Package'
export default {
    components: {
        myLable,
        myPackage
    },
    data() {
        return {
            not_shipped: '#D9F3F9',
            shipped: '#FAF8CA',
            delivered: '#D4F1B4',
            edit_notshipped: false,
            edit_shipped: false,
            edit_delivered: false,
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            serialize_data: null,
        }
    },
    computed: {
        packages() {
            return this.$store.getters.packages
        },

    },
    methods: {
        async serialize_d(packaged) {
            alert('test')
            return this.serialize_data = JSON.stringify(packaged)
            // return JSON.stringify(this.form);
            //     this.$refs.form.submit()
            // eventBus.$emit('printPackageEvent', packaged)
        },
        getPackages() {
            this.$store.dispatch('getPackages')
        },
        mark_delivered(data) {
            axios.post(`orderDelivered/${data.id}`)
                .then((response) => {
                    this.$store.dispatch('alertEvent', 'Order updated')
                        .then((res) => {
                            this.getPackages()
                        })
                })
        },
        shipLable(packaged) {
            eventBus.$emit('printPackageEvent', packaged)
        },
        submit() {
            this.$refs.form_data.submit()
        },
        delete_package(item) {
            this.$confirm('This will permanently delete the package. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                axios.delete(`/packages/${item.id}`)
                    .then((response) => {
                        this.$message({
                            type: 'success',
                            message: 'Delete completed'
                        });
                        this.getPackages()
                    })
                    .catch((error) => {

                    });
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: 'Delete canceled'
                });
            });
        }
    },
    mounted() {
        this.getPackages();
    },
}
</script>

<style scoped>
.v-toolbar__title {
    color: #000 !important;
}

.v-btn .v-btn__content .v-icon {
    color: inherit;
    color: #999;
}

.v-toolbar[data-booted=true] {
    border-bottom-right-radius: 90px;
}

.el-dropdown-link {
    cursor: pointer;
    color: #409EFF;
}

.el-icon-arrow-down {
    font-size: 12px;
}

.demonstration {
    display: block;
    color: #8492a6;
    font-size: 14px;
    margin-bottom: 20px;
}

#ember1638 .kanban-list-item {
    margin-top: 12px;
    border: 1px solid #EFEFEF;
    padding: 18px 18px 10px;
    line-height: 1.7;
    cursor: pointer;
    display: flex;
    background-color: #fff;
}

a:focus,
a:hover {
    outline: 0;
    text-decoration: none;
    color: #154983;
    text-decoration: underline;
}
</style>
