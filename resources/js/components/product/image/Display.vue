<template>
<v-card class="mx-auto" max-width="400">
    <v-img :src="avatar" :lazy-src="avatar" aspect-ratio="1" class="grey lighten-2 white--text align-end"  height="200px">
        <template v-slot:placeholder>
            <v-row class="fill-height ma-0" align="center" justify="center">
                <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
            </v-row>
        </template>
        <!-- <v-img class="white--text align-end" height="200px" :lazy-src="avatar"> -->
        <v-card-title>
            <v-btn color="red" darken-1 raised @click="onPickFile" style="color: #fff;">Update Image</v-btn>

        </v-card-title>
    </v-img>

    <v-card-actions>
        <input type="file" @change="Getimage" accept="image/*" style="display: none" ref="fileInput">
        <v-btn text color="primary" @click="upload" :disabled="loading" :loading="loading">Update</v-btn>

        <!-- <v-btn text color="primary" @click="cancle" v-show="imagePlaced">Cancle</v-btn> -->
    </v-card-actions>
</v-card>
</template>

<script>
// import VueUploadMultipleImage from 'vue-upload-multiple-image'
export default {
    components: {
        // LightBox,/
    },
    props: ['product_id', 'product', 'avatar'],
    data() {
        return {
            errors: {},
            loading: false,
            // dialog: false,
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
                this.avatar = fileReader.result;
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
                this.avatar = e.target.result;
            };
            this.imagePlaced = true;
            let form = new FormData();
            form.append("image", image);
            this.file = form;
            console.log(e.target.files);
        },

        cancle() {
            this.avatar = this.product.image;
            this.imagePlaced = false;
        },

        upload() {
            this.loading = true;
            var data = {
                'file': this.file,
                'id': this.product_id,
            }
            axios
                .post(`images/${this.product_id}`, this.file)
                .then(response => {
                    eventBus.$emit('productEvent')

                    this.loading = false;
                    // console.log(response);
                    eventBus.$emit("alertRequest", 'Successifully uploaded');
                    this.imagePlaced = false;

                    // this.close()
                })
                .catch(error => {
                    eventBus.$emit('productEvent')
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

        // eventBus.$on("openImageEvent", data => {
        //     this.product = data;
        //     this.avatar = data.image;
        // });
    }
};
</script>

<style scoped>
input[type="file"] {
    opacity: 0;
    width: 100%;
    height: 200px;
    position: absolute;
    cursor: pointer;
}

.filezone {
    outline: 2px dashed grey;
    outline-offset: -10px;
    background: #ccc;
    color: dimgray;
    padding: 10px 10px;
    min-height: 200px;
    position: relative;
    cursor: pointer;
}

.filezone:hover {
    background: #c0c0c0;
}

.filezone p {
    font-size: 1.2em;
    text-align: center;
    padding: 50px 50px 50px 50px;
}

div.file-listing img {
    max-width: 90%;
}

div.file-listing {
    margin: auto;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

div.file-listing img {
    height: 100px;
}

div.success-container {
    text-align: center;
    color: green;
}

div.remove-container {
    text-align: center;
}

div.remove-container a {
    color: red;
    cursor: pointer;
}

a.submit-button {
    display: block;
    margin: auto;
    text-align: center;
    width: 200px;
    padding: 10px;
    text-transform: uppercase;
    background-color: #ccc;
    color: white;
    font-weight: bold;
    margin-top: 20px;
}

#image {
    width: 90%;
    height: 150px;
}
</style>
