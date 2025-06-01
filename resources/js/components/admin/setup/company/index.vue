<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-card style="width: 80%">
                <v-card-title primary-title>

                    <!-- <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon class="mx-0" @click="getCompany" slot="activator">
                                <v-icon small color="blue darken-2">mdi-refresh</v-icon>
                            </v-btn>
                        </template>
                        <span>Refresh</span>
                    </v-tooltip> -->
                    <v-spacer></v-spacer>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon class="mx-0" @click="openEdit" slot="activator">
                                <v-icon small color="blue darken-2">mdi-pencil</v-icon>
                            </v-btn>
                        </template>
                        <span>Edit profile</span>
                    </v-tooltip>
                </v-card-title>
                <el-tabs :tab-position="tabPosition" style="width: 80%;">
                    <el-tab-pane label="Overview">
                        <div style="text-align: center">
                            <!-- <el-avatar :size="80" src="https://empty" @error="errorHandler">
                                <img src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png" />
                            </el-avatar> <br> -->
                            <v-hover v-slot:default="{ hover }">
                                <v-card class="mx-auto" color="grey lighten-4" max-width="200" style="border-radius: 50%;">
                                    <v-img :aspect-ratio="16/9" :src="company_logo" style="height: 200px" v-on:error="onImgError">
                                        <v-expand-transition>
                                            <div v-if="hover" class="d-flex transition-fast-in-fast-out black darken-2 v-card--reveal display-3 white--text" style="height: 100%;margin-left: -100px">
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn v-on="on" icon class="mx-0" slot="activator" color="white" @click="openLogo">
                                                            <v-icon>mdi-image-edit</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Edit Logo</span>
                                                </v-tooltip>
                                            </div>
                                        </v-expand-transition>
                                    </v-img>
                                </v-card>
                            </v-hover>
                            <small>
                                <v-icon>mdi-email</v-icon> Email: {{ company.email }}
                            </small><br>
                            <small>
                                <v-icon>mdi-phone</v-icon> phone: {{ company.phone }}
                            </small>
                            <address></address>
                            <p>
                                <v-icon>mdi-map-marker</v-icon> {{ company.address }}, {{ company.ou }}
                            </p>
                            <p> {{ company.about }}</p>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane label="Company">
                        <div>
                            <h6>Terms&Conditions</h6>
                            <small> {{ company.terms }}</small>
                        </div>
                    </el-tab-pane>
                </el-tabs>
            </v-card>
        </v-layout>
    </v-container>
    <myEdit />
    <myLogo />
    <!-- <myApi /> -->
</div>
</template>

<script>
import myEdit from './edit'
import myLogo from './logo'
// import myApi from './inc/api'
import {
    mapState
} from 'vuex';
export default {
    // props: ['company_details'],
    components: {
        myEdit,
        myLogo,
        //  myApi
    },
    data() {
        return {
            tabPosition: 'left',
            failed_image: false,
            company: {}
        };
    },
    methods: {
        errorHandler() {
            return true
        },
        openEdit() {
            eventBus.$emit('editCompanyEvent')

        },
        openLogo() {
            eventBus.$emit('openLogoEvent')
        },

        onImgError(event) {
            // alert('dwdwdw');

            this.failed_image = true;

        },

        openApi(data) {
            console.log(data);

            eventBus.$emit('ApiConnectEvent', data)
        },
    },
    computed: {
        ...mapState(['loading']),

        company_logo() {
            console.log(this.failed_image);

            return this.company.logo ? this.company.logo : "https://picsum.photos/500/300?image=4";
        },

    },
    mounted() {
        // this.getCompany();
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
