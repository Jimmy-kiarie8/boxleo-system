<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="400px">
        <v-card>
            <v-card-title>
                <span class="headline text-center" style="margin: auto;">Create Subcategory</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex xs12 style="padding: 7px 10px 20px 10px;">
                            <label for="" style="color: #52627d;">Product types</label>
                            <el-select v-model="form.category_id" filterable clearable placeholder="Select Category" style="width:100%;" value-key="id">
                                <el-option v-for="item in categories.data" :key="item.id" :label="item.category" :value="item">
                                </el-option>
                            </el-select>
                        </v-flex>
                        <v-flex sm12>
                            <v-card-text>
                                <div>
                                    <label for="">Subcategory</label>
                                    <el-input placeholder="Tops..." v-model="form.subcategory"></el-input>
                                </div>
                            </v-card-text>
                        </v-flex>
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
import { mapState } from 'vuex';
export default {
    data: () => ({
        dialog: false,
        form: {},
    }),
    created() {
        eventBus.$on("openEditSubcategory", data => {
            this.form = data
            this.dialog = true;
            this.getCategories()
        });
    },

    methods: {
        save() {
            var payload = {
                model: 'subsubcategories',
                data: this.form,
                id: this.form.id,
            }
            this.$store.dispatch('patchItems', payload)
                .then(response => {
                    eventBus.$emit("subcategoryEvent")
                });
        },

        getCategories() {
            var payload = {
                model: 'categories',
                update: 'updateCategoryList'
            }
            this.$store.dispatch("getItems", payload);
        },
        close() {
            this.dialog = false;
        }
    },
    computed: {
        ...mapState(['categories', 'loading']),
        // loading() {
        //     return this.$store.getters.loading;
        // },
        // subcategories() {
        //     return this.$store.getters.subcategories;
        // },
    },
};
</script>
