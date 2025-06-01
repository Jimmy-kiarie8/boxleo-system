<template>
    <div class="dispatch-container">
        <!-- List View -->
        <v-card class="mb-4">
            <v-card-title>
                Dispatches
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                    hide-details></v-text-field>
                <v-btn color="primary" class="ml-4" @click="openCreateModal">
                    Create New Dispatch
                </v-btn>
            </v-card-title>

            <div v-if="dispatches">

                <v-data-table :headers="dispatchHeaders" :items="dispatches.data" :search="search" :loading="loading">
                    <template v-slot:item.status="{ item }">
                        <v-chip :color="getStatusColor(item.status)">
                            {{ item.status }}
                        </v-chip>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-btn small text color="primary" @click="viewDispatch(item)">
                            View
                        </v-btn>
                        <v-btn small text color="success" :href="'/dispatch-print/'+item.id" target="_blank">
                            Print
                        </v-btn>
                    </template>
                </v-data-table>
            </div>
        </v-card>

        <!-- Create Dispatch Modal -->
        <v-dialog v-model="showCreateModal" max-width="900px">
            <v-card>
                <v-card-title>
                    Create New Dispatch
                    <v-spacer></v-spacer>
                    <v-btn icon @click="closeCreateModal">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text>
                    <v-stepper v-model="e6" vertical>
                        <v-stepper-step :complete="e6 > 1" step="1" :editable="e6 > 1">
                            Vendor Details
                        </v-stepper-step>

                        <v-stepper-content step="1">
                            <v-card class="mb-12" elevation="3">
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12">
                                            <label>Dispatch date</label>
                                            <el-date-picker v-model="form.dispatch_date" type="date"
                                                placeholder="Pick a Date" format="yyyy/MM/dd" value-format="yyyy-MM-dd"
                                                style="width:100%"></el-date-picker>
                                        </v-col>

                                        <v-col cols="12">
                                            <label>Vendor</label>
                                            <el-select v-model="form.vendor_id" placeholder="Select"
                                                @change="getProducts" style="width:100%" clearable filterable>
                                                <el-option v-for="item in sellers.data" :key="item.id"
                                                    :label="item.name" :value="item.id"></el-option>
                                            </el-select>
                                        </v-col>

                                        <v-col cols="12" v-if="form.vendor_id">
                                            <label>Product</label>
                                            <el-select v-model="form.products" placeholder="Select" multiple clearable
                                                filterable style="width:100%" value-key="id">
                                                <el-option v-for="item in products" :key="item.id"
                                                    :label="item.product_name" :value="item"></el-option>
                                            </el-select>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>
                            <v-btn color="primary" @click="goTo2">Continue</v-btn>
                        </v-stepper-content>

                        <v-stepper-step :complete="e6 > 2" step="2">
                            Product Details
                        </v-stepper-step>

                        <v-stepper-content step="2">
                            <v-card class="mb-12" elevation="3">
                                <v-card-text>
                                    <v-data-table :headers="headers" :items="form.products" :items-per-page="5"
                                        class="elevation-1">
                                        <template v-slot:item.quantity="{ item }">
                                            <el-input placeholder="Please input" v-model="item.quantity"></el-input>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                            <v-btn color="primary" @click="createDispatch">Create Dispatch</v-btn>
                            <v-btn text @click="e6 = 1">Back</v-btn>
                        </v-stepper-content>
                    </v-stepper>
                </v-card-text>
            </v-card>
        </v-dialog>

        <!-- View/Approve Dispatch Modal -->
        <v-dialog v-model="showViewModal" max-width="900px">
            <v-card>
                <v-card-title>
                    View Dispatch #{{ selectedDispatch?.id }}
                    <v-spacer></v-spacer>
                    <v-btn icon @click="closeViewModal">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text>
                    <v-stepper v-model="approvalStep" vertical>
                        <div v-for="(step, index) in approvalSteps" :key="index">
                            <v-stepper-step :step="index + 1" :complete="isStepComplete(index + 1)">
                                {{ step.title }} {{ approvalStep }} 999999
                                <small>{{ step.description }}</small>
                            </v-stepper-step>

                            <v-stepper-content :step="index + 1">
                                <v-card class="mb-4" elevation="3">
                                    <v-card-text>
                                        <div v-if="step.type === 'warehouse'">
                                            <v-checkbox v-model="step.checks.itemsVerified"
                                                label="All items verified"></v-checkbox>
                                            <v-checkbox v-model="step.checks.quantityChecked"
                                                label="Quantities checked"></v-checkbox>
                                        </div>
                                        <div v-if="step.type === 'quality'">
                                            <v-checkbox v-model="step.checks.qualityChecked"
                                                label="Quality standards met"></v-checkbox>
                                            <v-checkbox v-model="step.checks.packagingVerified"
                                                label="Packaging verified"></v-checkbox>
                                        </div>
                                        <div v-if="step.type === 'manager'">
                                            <v-checkbox v-model="step.checks.documentationComplete"
                                                label="Documentation complete"></v-checkbox>
                                            <v-checkbox v-model="step.checks.finalApproval"
                                                label="Final approval"></v-checkbox>
                                        </div>
                                        <v-textarea v-model="step.notes" label="Notes" rows="3"></v-textarea>
                                    </v-card-text>
                                </v-card>
                                <v-btn color="primary" @click="approveStep(index + 1)"
                                    :disabled="!canApproveStep(index + 1)">
                                    Approve Step
                                </v-btn>
                            </v-stepper-content>
                        </div>
                    </v-stepper>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    data() {
        return {
            e6: 1,
            search: '',
            loading: false,
            showCreateModal: false,
            showViewModal: false,
            form: {
                dispatch_date: null,
                vendor_id: null,
                products: []
            },
            selectedDispatch: null,
            approvalStep: 1,
            dispatchHeaders: [
                { text: 'Waybill No', value: 'waybill_no' },
                { text: 'Date', value: 'dispatch_date' },
                { text: 'Vendor', value: 'vendor_name' },
                { text: 'Status', value: 'status' },
                { text: 'Actions', value: 'actions' }
            ],
            headers: [
                { text: 'Product name', value: 'product_name' },
                { text: 'Quantity', value: 'quantity' },
                { text: 'Actions', value: 'actions' }
            ],
            approvalSteps: [
                {
                    title: 'Warehouse Verification',
                    description: 'Verify items and quantities',
                    type: 'warehouse',
                    checks: {
                        itemsVerified: false,
                        quantityChecked: false
                    },
                    notes: ''
                },
                {
                    title: 'Quality Check',
                    description: 'Verify quality standards',
                    type: 'quality',
                    checks: {
                        qualityChecked: false,
                        packagingVerified: false
                    },
                    notes: ''
                },
                {
                    title: 'Manager Approval',
                    description: 'Final approval and documentation',
                    type: 'manager',
                    checks: {
                        documentationComplete: false,
                        finalApproval: false
                    },
                    notes: ''
                }
            ],
            dispatches: []
        };
    },

    methods: {
        openCreateModal() {
            this.showCreateModal = true;
            this.e6 = 1;
            this.form = {
                dispatch_date: null,
                vendor_id: null,
                products: []
            };
        },

        approveDispatch(dispatch) {
            console.log(dispatch);
        },

        closeCreateModal() {
            this.showCreateModal = false;
        },

        closeViewModal() {
            this.showViewModal = false;
            this.selectedDispatch = null;
        },

        getVendors() {
            const payload = {
                model: 'sellers',
                update: 'updateSellerList'
            };
            this.$store.dispatch('getItems', payload);
        },

        getProducts(id) {
            const payload = {
                model: 'vendor_product',
                update: 'updateProductsList',
                id: id
            };
            this.$store.dispatch('showItem', payload);
        },

        goTo2() {
            if (!this.form.vendor_id || !this.form.dispatch_date) {
                this.$store.dispatch('showError', 'Please fill all required fields');
                return;
            }
            this.e6 = 2;
        },

        async createDispatch() {
            if (!this.validateProducts()) {
                this.$store.dispatch('showError', 'Please enter quantities for all products');
                return;
            }

            try {
                const payload = {
                    model: 'dispatches',
                    data: this.form
                };
                await this.$store.dispatch('postItems', payload);
                this.closeCreateModal();
                this.loadDispatches();
                this.$store.dispatch('showSuccess', 'Dispatch created successfully');
            } catch (error) {
                this.$store.dispatch('showError', 'Error creating dispatch');
            }
        },

        validateProducts() {
            return this.form.products.every(product =>
                product.quantity && Number(product.quantity) > 0
            );
        },

        async loadDispatches() {
            this.loading = true;
            try {
                const payload = {
                    model: 'dispatches',
                    update: 'updateDispatches'
                };
                await this.$store.dispatch('getItems', payload).then(res => {
                    console.log(res);
                    this.dispatches = res.data;
                });
            } finally {
                this.loading = false;
            }
        },

        viewDispatch(dispatch) {
            this.selectedDispatch = dispatch;
            this.showViewModal = true;
            this.approvalStep = this.getCurrentApprovalStep(dispatch);
            console.log(this.approvalStep);
        },

        getCurrentApprovalStep(dispatch) {
            console.log(dispatch);
            // Logic to determine current approval step based on dispatch status
            return dispatch.approval_level || 1;
        },

        getStatusColor(status) {
            const colors = {
                'pending': 'warning',
                'warehouse_approved': 'info',
                'quality_approved': 'success',
                'manager_approved': 'purple',
                'completed': 'green',
                'rejected': 'error'
            };
            return colors[status] || 'grey';
        },

        canApprove(dispatch) {
            // Logic to determine if current user can approve the dispatch
            return true; // Implement your authorization logic here
        },

        isStepComplete(step) {
            if (!this.selectedDispatch) return false;
            // Implement logic to check if step is complete based on dispatch status
            return this.selectedDispatch.approval_level > step;
        },

        canApproveStep(step) {
            const currentStep = this.approvalSteps[step - 1];
            if (!currentStep) return false;

            return Object.values(currentStep.checks).every(check => check === true);
        },

        async approveStep(step) {
            try {
                const payload = {
                    model: 'dispatch_approval',
                    data: {
                        dispatch_id: this.selectedDispatch.id,
                        step: step,
                        notes: this.approvalSteps[step - 1].notes,
                        checks: this.approvalSteps[step - 1].checks
                    }
                };
                await this.$store.dispatch('postItems', payload);
                this.loadDispatches();
                if (step === 3) {
                    this.closeViewModal();
                } else {
                    this.approvalStep++;
                }
                this.$store.dispatch('showSuccess', 'Step approved successfully');
            } catch (error) {
                this.$store.dispatch('showError', 'Error approving step');
            }
        }
    },

    computed: {
        ...mapState(['sellers', 'products'])
    },

    mounted() {
        this.getVendors();
        this.loadDispatches();
    }
};
</script>

<style scoped>
.dispatch-container {
    padding: 20px;
}
</style>
