<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>

            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Warehouse mapping</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-progress-linear indeterminate color="primary" class="mb-0" :active="proloading"></v-progress-linear>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <myArea :warehouse="form" v-if="dialog_dispaly == 'area'" />
                        <myRow :form_row="form_row" v-else-if="dialog_dispaly == 'row'" />
                        <myBay :form_bay="form_bay" v-else-if="dialog_dispaly == 'bay'" />
                        <!-- <myLevels :form_level="form_bay" v-else-if="dialog_dispaly == 'level'" /> -->
                    </v-layout>
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
import myRow from './row'
import myArea from './area'
import myBay from './bay'
// import myLevels from './level'
import {
    mapState
} from 'vuex';
export default {
    components: {
        myRow,
        myArea,
        myBay,
        // myLevels
    },
    data: () => ({
        dialog_dispaly: 'area',
        dialog: false,
        form: {},
        form_row: {},
        form_bay: {},
        form_level: {},
        proloading: false,

    }),
    created() {
        eventBus.$on("openShowWarehouse", data => {
            this.dialog = true;
            this.form = data
        });
        eventBus.$on("openShowRow", data => {
            this.dialog = true;
            this.form_row.area_id = data
        });
        eventBus.$on("openShowBay", data => {
            this.dialog = true;
            this.form_bay.row_id = data
        });
        eventBus.$on("openShowLevel", data => {
            this.dialog = true;
            this.form_level.bay_id = data
        });
        eventBus.$on("displayChange", data => {
            this.proloading = true

            setTimeout(() => (
                this.proloading = false, this.dialog_dispaly = data

            ), 1000)
        });
    },

    // watch: {
    //     proloading(val) {
    //         if (!val) return

    //         setTimeout(() => (this.proloading = false), 1000)
    //     },
    // },
    methods: {
        save() {

            var payload = {
                model: 'warehouses',
                data: this.form,
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("warehouseEvent")
                });
        },
        close() {
            this.dialog = false;
            this.dialog_dispaly = 'area'
        },
    },

    computed: {
        ...mapState(['warehouses', 'errors', 'loading'])
    },
};
</script>
