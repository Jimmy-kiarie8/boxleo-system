<template>
<v-card color="" dark style="height: 54px;width: 400px;" id="search">
    <v-autocomplete v-model="model" :items="items" :loading="isLoading" :search-input.sync="search" color="white" hide-no-data hide-selected item-text="order_no" item-value="id" label="Search..." placeholder="Search" prepend-inner-icon="mdi-magnify" return-object></v-autocomplete>
    <!-- <v-divider></v-divider> -->
    <v-expand-transition v-if="model" style="width: 400px">
        <v-list class="" dense style="position: absolute;" two-line>
            <v-list-item @click="openOrder(model.id)">
                <!-- {{ order }} -->
                <v-list-item-content>
                    <!-- <v-list-item-title>{{ order.order_no }}</v-list-item-title> -->
                    <v-list-item-subtitle>{{ model.order_no }}</v-list-item-subtitle>
                    <v-list-item-subtitle>{{ model.client.phone }}</v-list-item-subtitle>
                    <v-list-item-action-text>{{ model.client.address }}</v-list-item-action-text>
                </v-list-item-content>
                <v-list-item-action>
                    <v-list-item-subtitle>{{ model.delivery_status }}</v-list-item-subtitle>
                    <v-list-item-subtitle>{{ model.total_price }}</v-list-item-subtitle>

                    <v-btn icon @click="model = null" small>
                        <v-icon>mdi-close-circle</v-icon>
                    </v-btn>
                </v-list-item-action>
            </v-list-item>
        </v-list>
    </v-expand-transition>
    <!-- <v-card-actions v-if="model">
        <v-spacer></v-spacer>
        <v-btn :disabled="!model" color="grey darken-3" @click="model = null">
            Clear
            <v-icon right>
                mdi-close-circle
            </v-icon>
        </v-btn>
    </v-card-actions> -->
</v-card>
</template>

<script>
export default {
    data: () => ({
        descriptionLimit: 60,
        entries: [],
        isLoading: false,
        model: null,
        search: null,
        order_search: []
    }),
    computed: {
        fields() {
            if (!this.model) return []

            return Object.keys(this.model).map(key => {
                return {
                    key,
                    value: this.model[key] || 'n/a',
                }
            })
        },

        items() {
            return this.entries.map(entry => {
                const order_no = entry.order_no.length > this.descriptionLimit ?
                    entry.order_no.slice(0, this.descriptionLimit) + '...' :
                    entry.order_no

                return Object.assign({}, entry, {
                    order_no
                })
            })
        },
    },

    methods: {
        
        openOrder(data) {
            eventBus.$emit("drawerEvent", data);
        },
       /*  openOrder(data) {

            this.$router.push({
                name: "saleorder",
                params: {
                    id: data
                }
            });
            // this.getWarehouses()

        }, */
    },

    watch: {
        search(val) {
            // Items have already been loaded
            if (val < 4) return

            // Items have already been requested
            if (this.isLoading) return

            this.isLoading = true

            axios.get('order_search' + '/' + val + '?search_top=search').then((response) => {
                this.isLoading = false
                this.entries = response.data
                // this.model = true
            }).catch((error) => {

            })

            /*   var payload = {
                model: 'order_search',
                update: 'updateOrderSearch',
                search: val
            }
            this.$store.dispatch('searchItems', payload).then(() => {
                this.isLoading = false
            })
 */
        },
    },
}
</script>

<style scoped>
.v-autocomplete__content.v-menu__content {
    left: 56% !important;
}
.topbar .v-input__control {
        background: #1e1e1e !important;
}
.topbar .v-input__control {
        margin-top: 5px !important;
}

</style>
