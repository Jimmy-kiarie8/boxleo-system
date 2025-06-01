<template>
<div class="text-center">
    <v-dialog v-model="dialog" width="500">
        <v-card>
            <v-card-title class="headline">
                <p>Create warehouse bins</p>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-layout row wrap style="margin-top: 10px">
                    <v-flex sm12>
                        <label for="">Bin Position</label>
                        <el-input placeholder="A" v-model="form.position"></el-input>
                    </v-flex>

                    <v-flex sm12>
                        <label for="">Warehouse</label>
                        <el-select v-model="form.warehouse_id" placeholder="Select" @change="getAreas" style="width: 100%" value-key="id">
                            <el-option v-for="item in warehouses" :key="item.id" :label="item.name" :value="item">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm12>
                        <label for="">Area</label>
                        <el-select v-model="form.area_id" placeholder="Select" @change="getRows" style="width: 100%" value-key="id">
                            <el-option v-for="item in areas" :key="item.id" :label="item.name" :value="item">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm12>
                        <label for="">Row</label>
                        <el-select v-model="form.row_id" placeholder="Select" @change="getBays" style="width: 100%" value-key="id">
                            <el-option v-for="item in rows" :key="item.id" :label="item.name" :value="item">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm12>
                        <label for="">Bay</label>
                        <el-select v-model="form.bay_id" placeholder="Select" style="width: 100%" value-key="id">
                            <el-option v-for="item in bays" :key="item.id" :label="item.name" :value="item">
                            </el-option>
                        </el-select>
                    </v-flex>
                    <v-flex sm12>
                        <label for="">Level</label>
                        <el-select v-model="form.level_id" placeholder="Select" style="width: 100%" value-key="id">
                            <el-option v-for="item in levels" :key="item.id" :label="item.name" :value="item">
                            </el-option>
                        </el-select>
                    </v-flex>
                </v-layout>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-btn color="primary" text @click="dialog = false">
                    Close
                </v-btn>
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
    // props: ['form'],
    data() {
        return {
            dialog: false,
            form: {},
            warehouse: {}
        }
    },
    methods: {
        submit() {
            var payload = {
                model: 'bins',
                data: this.form
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // eventBus.$emit("warehouseEvent")
                });
        },
        close() {
            this.dialog = false
        },
        getWarehouses() {
            var payload = {
                model: 'warehouses',
                update: 'updateWarehouseList',
            }
            this.$store.dispatch('getItems', payload);
        },
        getAreas(data) {
            var payload = {
                model: 'areas',
                update: 'updateAreas',
                id: data.id
            }
            this.$store.dispatch('showItem', payload);
        },
        getRows(data) {
            var payload = {
                model: 'rows',
                update: 'updateRows',
                id: data.id
            }
            this.$store.dispatch('showItem', payload);
        },
        getBays(data) {
            var payload = {
                model: 'bays',
                update: 'updateBays',
                id: data.id
            }
            this.$store.dispatch('showItem', payload);
        },
        getLevels() {
            var payload = {
                model: 'levels',
                update: 'updateLevels',
            }
            this.$store.dispatch('getItems', payload);
        },
    },
    created() {
        eventBus.$on('CreateBinEvent', data => {
            // console.log(data);
            // this.form.area_id = data.area_id
            this.dialog = true
        });
    },
    computed: {
        ...mapState(['warehouses', 'areas', 'rows', 'bays', 'levels'])
    },
    mounted () {
        this.getWarehouses();
        this.getLevels();
    },
}
</script>
