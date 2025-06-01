<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" max-width="600px">
            <v-card v-if="dialog">
                <v-card-title>
                    <span class="headline text-center" style="margin: auto;">Proof Of delivery</span>
                    <VSpacer />

                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon class="mx-0" @click="close" slot="activator">
                                <v-icon small color="blue darken-2">close</v-icon>
                            </v-btn>
                        </template>
                        <span>close</span>
                    </v-tooltip>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex sm12>
                                <el-upload class="upload-demo" drag :action="`/pod-upload/${form.id}`"
                                    :auto-upload="true" :on-preview="handlePreview" :on-remove="handleRemove"
                                    :file-list="fileList" ref="upload" :headers="headers"
                                    :on-success="handleSuccess" :on-error="handleError">
                                    <i class="el-icon-upload"></i>
                                    <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                                    <div class="el-upload__tip" slot="tip">Upload you files here!</div>
                                </el-upload>
                            </v-flex>
                            <v-flex sm12>
                                <v-list dense>
                                    <v-subheader>{{ form.order_no }}</v-subheader>
                                    <v-list-item-group v-model="selectedItem" color="primary">
                                        <v-list-item>
                                            <v-list-item-icon>
                                                <v-icon>mdi-cash-100</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title v-text="form.total_price"></v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                        <v-list-item>
                                            <v-list-item-icon>
                                                <v-icon>mdi-update</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title v-text="form.delivery_status"></v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                            </v-flex>
                            <v-flex sm12 v-if="form.pod">
                                <el-card :body-style="{ padding: '0px' }">
                                    <img :src="form.pod.signature" class="image" style="width: 100%; height: 200px;">
                                    <div style="padding: 14px;">
                                        <span>{{ form.pod.notes }}</span>
                                        <div class="bottom clearfix">
                                            <time class="time">{{ form.pod.created_at }}</time>
                                        </div>
                                    </div>
                                </el-card>
                            </v-flex>

                            <v-flex sm12>
                              

                                <v-list dense two-line v-if="pods.length > 0">
                                    <v-subheader>{{ form.order_no }}</v-subheader>
                                    <v-list-item-group v-model="selectedItem" color="primary">
                                        <v-list-item  v-for="pod in pods" :href="pod.path" target="_blank" :key="pod.id">
                                            <v-list-item-icon>
                                                <v-icon>mdi-draw</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title v-text="form.notes"></v-list-item-title>
                                                <v-list-item-subtitle v-text="form.created_at"></v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
import {
    mapState 
} from 'vuex';
export default {
    data: () => ({
        dialog: false,
        loading: false,
        form: {},
        comments: [],
        selectedItem: 0,
        payload: {
            model: 'leaves',
        },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            fileList: []
    }),
    created() {
        eventBus.$on("openPod", data => {
            console.log(data);
            this.dialog = true;
            this.form = data;
            this.getPod()
        });
    },

    methods: {
        close() {
            this.dialog = false;
        },
        getPod() {

            var payload = {
                model: `pods/${this.form.id}`,
                update: 'updatePod'
            }
            this.$store.dispatch("getItems", payload);
        },

        handlePreview() { },
        handleRemove() { },
        handleError(err, file) {
            this.$store.dispatch('page_loader', false)
            this.$message.error("Something went wrong. Please check your file!");
        },

        handleSuccess(res, file) {
        },
        beforeUpload(file) {

        },
        handleFileUpload() {
            this.$refs.upload.submit();
        },
    },

    computed: {
        ...mapState(['pods'])
    }
};
</script>
