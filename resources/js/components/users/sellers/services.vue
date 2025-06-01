<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="700px">
        <v-card>
            <v-card-title>
                    <h3 class="text-center">Services</h3>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
        <v-container grid-list-md>
          <v-divider></v-divider>
          <v-layout row wrap>
            <v-flex xs12 sm12>
              <v-checkbox v-model="selectAll" label="Select All" @change="toggleSelectAll"></v-checkbox>
            </v-flex>
            <v-flex v-for="(service, index) in services" :key="index" xs6 sm4>
              <v-checkbox v-model="form.selected" :label="service.name" :value="service.id"></v-checkbox>
              <el-input placeholder="Please input" v-model="service.charges"></el-input>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>

            <v-card-actions>
                <v-btn color="blue darken-1" text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="save" :loading="loading" :disabled="loading">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import { mapState } from 'vuex';
export default {
    props: ['selectedVendors'],
    data: () => ({
        dialog: false,
        selectAll: false,
        form: {selected:[]},
    }),
    created() {
        eventBus.$on("openServiceUpdate", data => {
            this.form.vendors = data
            this.form.selected = []
            this.dialog = true;
            this.getServices()
        });
    },

    methods: {
        toggleSelectAll() {
            this.$nextTick(() => {
            if (this.selectAll) {
                this.form.selected = this.services.map(service => service.id);
            } else {
                this.form.selected = [];
            }
            });
        },
        save() {
            var payload = {
                data: this.form,
                model: '/vendor_services',
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("sellerEvent")
                });
        },
        getServices() {
            var payload = {
                model: 'services',
                update: 'updateService',
            }
            this.$store.dispatch('getItems', payload).then((response) => {
            })
        },
        close() {
            this.dialog = false;
        },
    },
    computed: {
        ...mapState(['errors', 'loading', 'services']),
        selectAll: {
            get() {
            return this.form.selected.length === this.services.length;
            },
            set(value) {
            this.selectAll = value;
            },
        },
    },
};
</script>
