<template>
<v-card style="overflow-x: hidden;">
    <v-card-title>
        Upload Excel Shipments
    </v-card-title>
    <v-container grid-list-md>
        <v-card-text>
            <v-layout wrap>
                <el-upload class="upload-demo" drag action="/get_sellers" :auto-upload="false" :data="form" ref="upload" :headers="headers" :on-success="handleSuccess" :on-error="handleError" :before-upload="beforeUpload">
                    <i class="el-icon-upload"></i>
                    <div class="el-upload__text">
                        Drop file here or <em>click to upload</em>
                    </div>
                    <div class="el-upload__tip" slot="tip">
                        xlsx/csv files with a size less than 500kb
                    </div>
                </el-upload>

                <!-- <v-btn color="red" style="text-transform: none;color: #fff;" rounded @click="onPickFile">
                    <v-icon>mdi-upload</v-icon>
                    <span>Choose file</span>
                </v-btn> -->
                <v-tooltip bottom style="margin-left: 30px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on">
                            <v-icon color="primary lighten-1">
                                mdi-download
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Download Template</span>
                </v-tooltip>

                <v-spacer></v-spacer>

                <!-- <input type="file" @change="GetSellers" style="display: none" ref="fileInput"> -->
                <!-- <v-btn text color="primary" @click="upload" :disabled="loading" :loading="loading">Upload</v-btn> -->
            </v-layout>
        </v-card-text>
    </v-container>
</v-card>
</template>

<script>
import {
    mapState
} from "vuex";
export default {
    props: ["user", "form"],
    data() {
        return {
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            filePlaced: false,
            dialog: false,
            loading: false,
            sellers_upload: {}
        };
    },
    methods: {
        close() {
            this.dialog = false;
        },
        previewFiles() {},

        handleSuccess(res, file) {
            this.$store.dispatch('page_loader', false)
            eventBus.$emit('sellerResponseEvent', res)
            this.sellers_upload = res
        },
        handleError(res, file) {
            this.$store.dispatch('page_loader', false)
            this.$message.error('Error!');

            // eventBus.$emit('sellerResponseEvent', res)
            // this.sellers_upload = res
        },

        beforeUpload(file) {
            this.$store.dispatch("page_loader", true);
            console.log(file);
            const isJPG = file.type === "image/jpeg";
        },

        upload() {
            this.$refs.upload.submit();
        }
    },
    created() {
        eventBus.$on("fileUploadEvent", data => {
            this.upload();
        });
    },
};
</script>
