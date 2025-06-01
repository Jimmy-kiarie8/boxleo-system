<template>
<v-card>
    <v-card-text>
        <v-row>
            <v-col sm="4" v-for="(option, index) in options" :key="index" style="text-align: center;">
                <!-- {{ option }} -->
                <v-hover v-slot:default="{ hover }" open-delay="200" close-delay="200">
                    <v-card :elevation="hover ? 16 : 2" class="mx-auto" style="border-radius: 50%;height: 150px;width: 150px;" @click="openApi(option)">
                        <v-btn text icon color="primary" style="margin-top: 50px;">
                            <v-icon>{{ option.icon }}</v-icon>
                        </v-btn>
                        <v-card-text>
                            <div>
                                <small>{{ option.site }}</small>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-hover>
            </v-col>
            <myApi />
        </v-row>
    </v-card-text>
</v-card>
</template>

<script>
import myApi from './inc/api'
export default {
    components: {
        myApi,
    },
    data() {
        return {

            options: [{
                site: 'M-pesa Integration',
                value: 'm_pesa',
                url: '#',
                icon: 'mdi-card',
            }, {
                site: 'Twilio Api',
                value: 'twilio',
                url: 'google_sheets',
                icon: 'mdi-message-processing',
            }, {
                site: 'Africas Talking Api',
                value: 'africas_talking',
                url: 'google_sheets',
                icon: 'mdi-message-alert',
            }, {
                site: 'Celcom Africa',
                value: 'celcomafrica',
                url: 'google_sheets',
                icon: 'mdi-message',
            }, 
            {
                site: 'Woocommerce',
                value: 'woocommerce',
                url: 'google_sheets',
                icon: 'mdi-wordpress',
            }, {
                site: 'Shopify',
                value: 'shopify',
                url: 'google_sheets',
                icon: 'mdi-shopping',
            }]
        }
    },
    methods: {
        openApi(data) {
            // console.log(data);

            eventBus.$emit('ApiConnectEvent', data)
        },
    },
}
</script>

<style>

</style>
