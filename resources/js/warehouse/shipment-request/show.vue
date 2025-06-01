<template>
    <div class="text-center">
        <v-dialog v-model="dialog" width="500">
            <v-card>
                <v-card-title primary-title>
                    Documents
                </v-card-title>
                <v-card-text>
                    <v-list dense>
                        <v-subheader>REPORTS</v-subheader>
                        <v-list-item-group v-model="selectedItem" color="primary">
                            <v-list-item v-for="(item, i) in documents" :key="i">
                                <v-list-item-icon>
                                    <v-icon>mdi-file</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>{{ item.file_name }}</v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                <v-btn icon :href="item.path" target="_blank">
                                    <v-icon color="success">mdi-download</v-icon>
                                </v-btn>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card-text>
                <v-card-actions>
                    <v-btn flat color="primary" @click="dialog=false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            selectedItem: 0,
            documents: {},
            dialog: false,
        }
    },

    methods: {
    },

    created() {
        eventBus.$on('showShipmentEvent', data => {
            this.documents = data;
            this.dialog = true;
        });
    },
}
</script>