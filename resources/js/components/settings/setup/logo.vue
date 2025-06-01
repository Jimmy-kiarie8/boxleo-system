<template>
<v-dialog v-model="dialog" persistent max-width="500px">
    <v-card>
        <v-card-title primary-title>
            <v-spacer></v-spacer>
            <v-btn text icon color="primary" @click="dialog = false">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-card-title>

        <v-hover v-slot:default="{ hover }">
            <v-card class="mx-auto" color="grey lighten-4" max-width="400">
                <v-img :aspect-ratio="16/9" :src="company.logo">
                    <v-expand-transition>
                        <div v-if="hover" class="d-flex transition-fast-in-fast-out black darken-2 v-card--reveal display-3 white--text" style="height: 100%;margin-left: -100px">
                            <!-- <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" icon class="mx-0" slot="activator" color="white" @click="openLogo">
                                    <v-icon>mdi-image-edit</v-icon>
                                </v-btn>
                            </template>
                            <span>Edit Logo</span>
                        </v-tooltip> -->
                            <v-btn color="red" darken-1 raised @click="onPickFile" style="color: #fff;">Update Image</v-btn>

                        </div>
                    </v-expand-transition>
                </v-img>

            </v-card>
        </v-hover>
        <v-card-actions>
            <input type="file" @change="Getimage" accept="image/*" style="display: none" ref="fileInput">
            <v-btn text color="primary" @click="upload" :disabled="loading" :loading="loading">Update</v-btn>
        </v-card-actions>
    </v-card>
</v-dialog>
</template>

<script>
import {
    mapState
} from 'vuex';
// import VueUploadMultipleImage from 'vue-upload-multiple-image'
export default {
    components: {
        // LightBox,/
    },
    data() {
        return {
            errors: {},
            loading: false,
            dialog: false,
            disabled: true,
            // avatar: "",
            actualImage: false,
            imagePlaced: false,
            files: [],
            upload_files: [],
            product: [],
            images: [],
        };
    },
    methods: {
        close() {
            this.actualImage = false;
            // this.dialog = false;
        },

        onPickFile() {
            this.$refs.fileInput.click();
        },
        onFilePicked(event) {
            this.imagePlaced = true;
            const files = event.target.files;
            let filename = files[0].name;
            if (filename.lastIndexOf(".") <= 0) {
                return alert("please upload a valid image");
            }
            const fileReader = new FileReader();
            fileReader.addEventListener("load", () => {
                this.company.logo = fileReader.result;
            });
            fileReader.readAsDataURL(files[0]);
            this.image = files[0];
        },
        Getimage(e) {
            let image = e.target.files[0];
            // this.read(image);
            let reader = new FileReader();
            reader.readAsDataURL(image);
            reader.onload = e => {
                this.company.logo = e.target.result;
            };
            this.imagePlaced = true;
            let form = new FormData();
            form.append("image", image);
            this.file = form;
            console.log(e.target.files);
        },

        cancle() {
            this.company.logo = this.product.image;
            this.imagePlaced = false;
        },

        upload() {
            this.loading = true;
            var data = {
                'file': this.file,
                'id': this.product_id,
            }
            axios
                .post(`/company_logo`, this.file)
                .then(response => {
                    eventBus.$emit('companyEvent')

                    this.loading = false;
                    // console.log(response);
                    eventBus.$emit("alertRequest", 'Successifully uploaded');
                    this.imagePlaced = false;

                    // this.close()
                })
                .catch(error => {
                    eventBus.$emit('companyEvent')
                    this.loading = false
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    }
                    this.errors = error.response.data.errors;
                });
        },
    },
    created() {
        eventBus.$on("openLogoEvent", data => {
            // this.avatar = data.image;
            this.dialog = true
            if (!this.company.logo) {
                this.company.logo = 'https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png'
            }
        });
    },

    computed: {
        ...mapState(['company'])
    },
};
</script>

<style scoped>
.v-card--reveal {
    align-items: center;
    bottom: 0;
    justify-content: center;
    opacity: .5;
    position: absolute;
    width: 100%;
}
</style>
