<template>
<v-card style="width: 90%;margin: auto;padding: 30px 0 ">

    <el-breadcrumb separator-class="el-icon-arrow-right">
        <el-breadcrumb-item :to="{ path: '/marketplace' }">Marketplace</el-breadcrumb-item>
        <el-breadcrumb-item>Sync</el-breadcrumb-item>
    </el-breadcrumb>
    <br>
    <v-hover v-slot="{ hover }" open-delay="200" close-delay="200" style="margin-top: 30px;">
        <v-card :elevation="hover ? 16 : 2" style="padding: 20px 0;border-radius: 30px;">
            <v-card-text>

                <v-layout row wrap>
                    <v-flex sm4>
                        <h4>Google-sheets Orders</h4>
                        <v-list>
                            <v-list-item link>
                                <v-list-item-icon>
                                    <v-icon :color="s_color" style="font-size:90px;">mdi-google-spreadsheet</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>
                                        <v-icon>mdi-arrow-right-bold-circle</v-icon>
                                    </v-list-item-title>
                                </v-list-item-content>
                                <v-list-tile-avatar>
                                    <img :src="user.company.logo" height="100" />
                                </v-list-tile-avatar>
                            </v-list-item>
                        </v-list>
                    </v-flex>

                    <v-flex sm7>
                        <div style="margin-top: 40px;text-align: center;float: right">
                            <v-btn color="primary" text rounded outlined @click="syncOrders"> Get Sheet Orders <v-icon>mdi-sync</v-icon>
                            </v-btn>
                            <br>
                            <label for="" style="color: #999">Last Sync on : {{ $route.params.last_sync }}</label>
                        </div>
                        <div style="margin-top: 40px;text-align: center;float: right">
                            <v-btn color="primary" text rounded outlined @click="syncSheet"> Update Sheet Orders <v-icon>mdi-upload-network-outline</v-icon>
                            </v-btn>
                        </div>
                    </v-flex>
                </v-layout>
            </v-card-text>
        </v-card>
    </v-hover>
</v-card>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            s_color: '#34a853',
        }
    },
    methods: {
        syncOrders() {
            var payload = {
                model: 'google_sync/' + this.$route.params.id,
                data: {
                    id: this.$route.params.id
                },
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {});
        },

        syncSheet() {
            var payload = {
                model: 'sheet_sync/' + this.$route.params.id,
                data: {
                    id: this.$route.params.id
                },
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {});
        }
    },
}
</script>
