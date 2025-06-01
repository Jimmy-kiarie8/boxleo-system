<template>
<v-card style="width: 90%;margin: auto;padding: 30px 0 ">

    <v-hover v-slot="{ hover }" open-delay="200" close-delay="200" style="margin-top: 30px;">
        <v-card :elevation="hover ? 16 : 2" style="padding: 20px 0;border-radius: 30px;">
            <v-card-text>

                <v-layout row wrap>
                    <v-flex sm4>
                        <h4>Google-Sheet Orders</h4>
                        <v-list>
                            <v-list-item link>
                                <v-list-item-icon>
                                    <v-icon :color="s_color" style="font-size:90px;">fas fa-file-spreadsheet</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>
                                        <v-icon>mdi-arrow-right-bold-circle</v-icon>
                                    </v-list-item-title>
                                </v-list-item-content>
                                <v-list-tile-avatar>
                                    <img src="https://logixsaas.com/home/logo.jpg" height="100" />
                                </v-list-tile-avatar>
                            </v-list-item>
                        </v-list>
                    </v-flex>

                    <v-flex sm7>
                        <div style="margin-top: 40px;text-align: center;float: right">
                            <v-btn color="primary" text rounded outlined @click="syncOrders"> Sync <v-icon>mdi-sync</v-icon>
                            </v-btn>
                            <br>
                            <label for="" style="color: #999">Last Sync Time : 19 Aug 2021 11:09</label>
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
    components: {
    },
    data() {
        return {
            s_color: '#34a853',
        }
    },
    methods: {
        syncProducts() {
            var payload = {
                model: 'sync_products',
                data: {
                    id: this.$route.params.id
                },
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {});
        },
        syncOrders() {
            var payload = {
                model: 'sync_orders',
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
