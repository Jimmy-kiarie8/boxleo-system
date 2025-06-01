<template>
<div>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="500px">
            <v-container fluid fill-height>
                <v-layout justify-center align-center>
                    <template>
                        <v-card class="mx-auto" width="100%">
                            <v-card-title class="title font-weight-regular justify-space-between">
                                <span> {{ product.name }} </span>
                                <!-- <v-avatar color="primary lighten-2" class="subheading white--text" size="24" v-text="step"></v-avatar> -->
                                <v-spacer></v-spacer>
                                <v-btn icon dark @click="close">
                                    <v-icon color="black">close</v-icon>
                                </v-btn>
                            </v-card-title>

                            <v-card-text>
                                <img :src="avatar" style="height: 150px" /> 
                                <v-divider></v-divider>

                                <el-upload class="upload-demo" drag :action="'images/' + product_id" :auto-upload="false" :data="form" ref="upload" :headers="headers" :on-success="handleSuccess" on-error="handleError" :before-upload="beforeUpload">
                                    <i class="el-icon-upload"></i>
                                    <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                                    <div class="el-upload__tip" slot="tip">jpg/jpeg/png files with a size less than 500kb</div>
                                </el-upload>
                            </v-card-text>

                            <v-divider></v-divider>

                            <v-card-actions>
                                <v-btn text @click="close" color="primary">
                                    Close
                                </v-btn>
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" depressed @click="upload">
                                    Upload
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                </v-layout>
            </v-container>
        </v-dialog>
    </v-layout>
</div>
</template>

<script>
import Display from './Display';
import Others from './Others';

export default {
    name: 'images',
    props: ["user"],
    components: {
        Display,
        Others,
    },
    data: () => ({
        // length: 3,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        avatar: '',
        dialog: false,
        step: 1,
        form: {},
        files: [],
        upload_files: [],
        product: [],
        images: [],
        product_id: null,
    }),
    methods: {
        close() {
            this.dialog = false;
        },
        handleSuccess(res, file) {
            this.$store.dispatch('page_loader', false)
            // this.orders_upload = res
        },
        handleError(res, file) {
            this.$store.dispatch('page_loader', false)
            this.$message({
                type: 'error',
                message: 'Something wen wrong'
            });
        },
        beforeUpload(file) {
            this.$store.dispatch('page_loader', true)
            console.log(file);
            const isJPG = file.type === 'image/jpeg';

        },

        upload() {
            this.$refs.upload.submit();
        }
    },
    created() {
        eventBus.$on("openImageEvent", data => {
            // alert('dialog')
            console.log(data);

            this.product = data;
            this.avatar = data.image;
            this.dialog = true;
            this.product_id = data.id

            this.images = data.images

            // this.getImages()
        });
    }
}
</script>
