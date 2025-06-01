<template>
    <v-row justify="center">
        <v-dialog persistent v-model="dialog" width="500">
            <v-card>
                <v-card-title primary-title>
                    Upload Document
                </v-card-title>
                <hr>
                <v-card-text>
                    <!-- <v-file-input label="Document" chips show-size small-chips truncate-length="21" outline ref="fileUpload"></v-file-input> -->

                    <el-upload class="upload-demo" drag :action="`/shipment-document/${form.id}`" :auto-upload="false"
                        :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" multiple
                        ref="upload" :headers="headers" :on-success="handleSuccess" :on-error="handleError">
                        <i class="el-icon-upload"></i>
                        <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                        <div class="el-upload__tip" slot="tip">Upload you files here!</div>
                    </el-upload>
                </v-card-text>
                <v-card-actions>
                    <v-btn prepend-icon="mdi-close" variant="tonal" @click="close" color="error">
                        Close
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn prepend-icon="mdi-upload" variant="tonal" :loading="loading" @click="handleFileUpload"
                        color="info">
                        Upload
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar">
            {{ text }}
        </v-snackbar>
    </v-row>
</template>

<script>
export default {
    props: {
        modelRoute: String,
        title: String,
    },
    data() {
        return {
            dialog: false,
            file: null,
            form: {},
            loading: false,
            snackbar: false,
            text: 'Document uploaded',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            fileList: []
        }
    },
    methods: {
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
        handleFileUpload_() {
            if (!this.$refs.fileUpload || !this.$refs.fileUpload.files) {
                console.error('File input element not found');
                return;
            }
            this.file = this.$refs.fileUpload.files[0];
            if (!this.file) {
                console.error('No file selected');
                return;
            }
            this.loading = true;
            const formData = new FormData();
            formData.append('file', this.file);
            axios.post(`shipment-document${this.form.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    this.loading = false;
                    console.log('File uploaded successfully', response);
                })
                .catch(error => {
                    this.loading = false;
                    console.error('Error uploading file', error);
                });
        },
        close() {
            this.dialog = false;
        },
        show() {
            this.dialog = true;
        }
    },
    created() {
        eventBus.$on('uploadShipmentEvent', (data) => {
            this.dialog = true;
            this.form = data;
        });
    },
}
</script>
