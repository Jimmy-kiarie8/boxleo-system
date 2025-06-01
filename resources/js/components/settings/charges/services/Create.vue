<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="700px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Service</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex sm12>
                            <label for="">Service</label>
                            <el-input placeholder="" v-model="form.name"></el-input>
                        </v-flex>
                        <!-- <v-layout row wrap> -->
                        <v-flex sm12>
                            <label for="">From</label>
                            <el-select v-model="form.service_from" clearable placeholder="Select" style="width:100%">
                                <el-option v-for="(item, index) in options" :key="index" :label="item.value" :value="item.value">
                                </el-option>
                            </el-select>
                        </v-flex>

                        <v-flex sm12 v-if="form.service_from == 'Zone'">
                            <label for="">Zone</label>
                            <el-select v-model="form.zone_from" clearable placeholder="Select a zone" style="width:100%">
                                <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-flex>

                        <v-flex sm12 v-if="form.service_from == 'City'">
                            <label for="">City</label>
                            <el-select v-model="form.city_from" clearable placeholder="Select a city" style="width:100%">
                                <el-option v-for="item in cities" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-flex>
                    </v-layout>
                    <v-divider></v-divider>

                    <v-layout row wrap>
                        <v-flex sm12>
                            <label for="">To</label>
                            <el-select v-model="form.service_to" clearable placeholder="Select" style="width:100%">
                                <el-option v-for="(item, index) in options" :key="index" :label="item.value" :value="item.value">
                                </el-option>
                            </el-select>
                        </v-flex>

                       <!--<v-flex sm12 v-if="form.service_to == 'Zone'">
                            <label for="">Zone</label>
                            <el-select v-model="form.zone_to" clearable placeholder="Select a zone" style="width:100%" multiple>
                                <el-option v-for="item in zones" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-flex>-->

                        <v-select v-model="form.zone_to" :items="zones" label="Zones" multiple  v-if="form.service_to == 'Zone'" item-text="name" item-value="id" chips> 
                            <template v-slot:prepend-item>
                                <v-list-item ripple @mousedown.prevent @click="toggle">
                                    <v-list-item-action>
                                    <v-icon :color="selectedZones.length > 0 ? 'indigo darken-4' : ''">
                                        {{ icon }}
                                    </v-icon>
                                </v-list-item-action>
                                <v-list-item-content>
                                    <v-list-item-title>
                                    Select All
                                    </v-list-item-title>
                                </v-list-item-content>
                                </v-list-item>
                                <v-divider class="mt-2"></v-divider>
                            </template>
                        </v-select>

                        <v-flex sm12 v-if="form.service_to == 'City'">
                            <label for="">Zone</label>
                            <el-select v-model="form.city_to" clearable placeholder="Select a city" style="width:100%">
                                <el-option v-for="item in cities" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </v-flex>
                    </v-layout>

                    <v-divider></v-divider>

                    <v-layout row wrap>
                        <v-flex sm6>
                            <label for="">Charged on</label>
                            <el-select v-model="form.charged_on" clearable placeholder="Select" style="width:100%">
                                <el-option v-for="(item, index) in charge_on" :key="index" :label="item.value" :value="item.value">
                                </el-option>
                            </el-select>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Charged</label>
                            <el-select v-model="form.charge_frequency" clearable placeholder="Select" style="width:100%">
                                <el-option v-for="(item, index) in charge_frequency" :key="index" :label="item.value" :value="item.value">
                                </el-option>
                            </el-select>
                        </v-flex>
                        <h4>Apply on this condition</h4>
                        <v-btn color="primary" outlined rounded @click="open_condition">Apply when</v-btn>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm6>
                            <label for="">Charge</label>
                            <el-select v-model="form.charges_type" clearable placeholder="Select" style="width:100%">
                                <el-option v-for="(item, index) in charges" :key="index" :label="item.label" :value="item.value">
                                </el-option>
                            </el-select>
                        </v-flex>
                        <v-flex sm6>
                            <label for="">Amount</label>
                            <el-input placeholder="" v-model="form.charges"></el-input>
                        </v-flex>
                    </v-layout>
                    <!-- </v-layout> -->
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="save" :loading="loading" :disabled="loading">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import {
    mapState
} from 'vuex';
export default {
    props: ['form'],
    data: () => ({
        dialog: false,
        loading: false,
        options: [{
            value: 'Anywhere'
        }, {
            value: 'Zone'
        }],
        charge_on: [{
            value: 'COD'
        }, {
            value: 'Per Order'
        }],
        charge_frequency: [{
            value: 'Once'
        }, {
            value: 'Every time'
        }],
        charges: [{
            label: 'Percentage on total order price',
            value: 'Percentage'
        }, {
            label: 'Fixed per order',
            value: 'Fixed'
        }],
        selectedZones: [],
    }),
    created() {
        eventBus.$on("openCreateService", data => {
            this.dialog = true;
        });
    },

    methods: {
      toggle () {
        this.$nextTick(() => {
          if (this.selectedAll) {
            this.form.zone_to = []
          } else {
            this.form.zone_to = this.zones.slice()
          }
        })
      },
        save() {

            var payload = {
                model: 'services',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("serviceEvent")
                });
        },
        close() {
            this.dialog = false;
        },
        open_condition() {
            eventBus.$emit('OpenConditionEvent')
        }
    },
    computed: {
        ...mapState(['zones', 'cities']),

        selectedSome () {
            return this.form.zone_to.length > 0 && !this.selectedAll
        },
        icon () {
            if (this.selectedAll) return 'mdi-close-box'
            if (this.selectedSome) return 'mdi-minus-box'
            return 'mdi-checkbox-blank-outline'
        },
      selectedAll () {
        return this.form.zone_to.length === this.zones.length
      },
    },
};
</script>
