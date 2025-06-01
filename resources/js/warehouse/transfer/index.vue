<template>


        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <v-data-table :headers="headers" :items="table_data.data" :sort-by="[{ key: 'name', order: 'asc' }]"
                class="elevation-2" :search="search" :loading="loading">
                <template v-slot:top>
                    <v-toolbar flat>
                        <v-toolbar-title>{{ title }} Management</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                  
                        <v-btn prepend-icon="mdi-plus-circle" variant="outlined" @click="openCreate()"
                            color="info">
                            Add {{ title }}
                        </v-btn>

                    </v-toolbar>
                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                        hide-details></v-text-field>
                </template>
                <template v-slot:item.actions="{ item }">
                    <div class="actions">
                        <v-tooltip location="bottom" v-for="(button, index) in actions" :key="index">
                            <template v-slot:activator="{ props }">
                                <v-btn icon v-bind="props" @click="runAction(item, button)" variant="text"
                                    :color="button.color">
                                    <v-icon>
                                        {{ button.icon }}
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>{{ button.action_name }}</span>
                        </v-tooltip>
                    </div>
                </template>

                <template v-slot:item.availability_status="{ value }">
                    <v-icon color="success" size="x-small" v-if="value">mdi-circle</v-icon>
                    <v-icon color="red" size="x-small" v-else>mdi-circle</v-icon>
                </template>
                <template v-slot:item.active="{ value }">
                    <v-icon color="success" size="x-small" v-if="value">mdi-circle</v-icon>
                    <v-icon color="red" size="x-small" v-else>mdi-circle</v-icon>
                </template>
            </v-data-table>
        <myCreate :title="title" :modelRoute="'transfer'" :form_data="form_data" />
        <!-- <myEdit ref="editModal" :modelRoute="modelRoute" :title="title" />
        <myUpload ref="uploadModal" :modelRoute="modelRoute" :title="title" />
        <myImage ref="imageModal" :modelRoute="modelRoute" :title="title" /> -->


        <v-dialog v-model="dialogDelete" max-width="400px">
            <v-card>
                <v-card-title class="text-h5">Warning!</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    Are you sure you want to delete this item?
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn color="red-darken-1" variant="tonal" @click="close">
                        <v-icon>mdi-close-box</v-icon>Cancel
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="info" variant="tonal" @click="deleteItemConfirm">
                        <v-icon>mdi-checkbox-marked</v-icon>OK
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>

</template>

<script>

import myCreate from './create.vue'
// import myEdit from './edit.vue'
import axios from 'axios';
export default {
    props: {
    },
    components: {
        myCreate
    },
    data() {
        return {
            data: {},
            form_data: [],
            company: {},
            headers: [],
            actions: {},
            modelRoute: '',
            title: 'Transfer',
            upload: false,
            search: '',
            page: 1,
            itemsPerPage: 5,
            table_data: {},
            loading: false,
            dialogDelete: false
        }
    },
    methods: {
        async loadJsonData() {
            try {
                const response = await axios.get('transfer-data');
                console.log("🚀 ~ loadJsonData ~ response:", response)
                this.form_data = response.data;
            } catch (error) {
                console.error('Error loading JSON data:', error);
            }
        },
        goTo(route) {
            router.visit(route)
        },
        refresh() {
            this.loading = true
            axios.get(`${this.modelRoute}?axios=${true}`).then((res) => {
                this.loading = false
                this.table_data = res.data

                console.log("🚀 ~ axios.get ~ res:", res)
            }).catch((error) => {
                this.loading = false
                console.log("🚀 ~ axios.get ~ error:", error)

            })
        },
        runAction(data, action) {
            console.log("🚀 ~ runAction ~ action:", action.action_name)
            if (action.action_name == 'Edit') {
                this.openEdit(data)
            } else if (action.action_name == 'Delete') {
                this.deleteItem(data)
            } else if (action.action_name == 'Logo') {
                this.uploadImage(data)
            } else {
                this.action_run(data, action.route)
            }

        },

        async action_run(data, route) {
            this.loading = true;

            try {
                const response = await axios.post(`/${route}`, data);
                console.log("🚀 ~ dispatch ~ response:", response)
            } catch (error) {
                console.log("🚀 ~ axios.get ~ error:", error);
            } finally {
                this.loading = false;
            }
        },
        uploadImage(data) {
            this.$refs.imageModal.show(data)
        },
        openEdit(data) {
            console.log("🚀 ~ openEdit ~ data:", data)
            this.$refs.editModal.show(data.id)
            // this.$emit('CallEvent', data)
        },
        openCreate() {
            // this.$refs.createModal.show()
            eventBus.$emit('transferEvent')
            // this.$emit('CallEvent', data)
        },
        uploadItem() {
            this.$refs.uploadModal.show()
        },
        close() {
            this.dialogDelete = false
        },
        deleteItem(item) {
            console.log("🚀 ~ deleteItem ~ item:", item)
            this.editedIndex = this.data.data.indexOf(item)
            this.deleteId = item.id
            this.dialogDelete = true

        },
        deleteItemConfirm() {
            axios.delete(`${this.modelRoute}/${this.deleteId}`).then((res) => {
                console.log("🚀 ~ axios.delete ~ res:", res)
                this.dialogDelete = true
                this.data.data.splice(this.editedIndex, 1)
                this.close()
            }).catch((error) => {
                console.log("🚀 ~ axios.delete ~ error:", error)

            })


        },
    },

    computed: {
        pageCount() {
            return Math.ceil(this.data.data.length / this.itemsPerPage)
        },
    },

    mounted() {
        this.loadJsonData()
        this.table_data = this.data
    }
}
</script>

<style>
.v-btn--icon .v-icon {
    font-size: 18px !important;
}

.actions {
    width: 200px;
}
</style>
