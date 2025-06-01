<template>
<v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="700px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Permissions</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-layout wrap>
                    <v-flex xs6 sm3 v-for="perm in sortPerm" :key="perm.id">
                        <v-checkbox v-model="selected" :label="perm.name" :value="perm.name"></v-checkbox>
                    </v-flex>
                </v-layout>
            </v-card-text>

            <v-card-actions>
                <!-- <v-btn text @click="resetForm">reset</v-btn> -->
                <v-btn text @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn :disabled="loading" text color="primary" @click="save" :loading="loading">Submit</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import { mapState } from 'vuex'
export default {
    // props: ['form'],
    data() {
        return {
            e1: true,
            loader: false,
            loading: false,
            selected: [],
            // permissions: [],
            dialog: false,
            form: {}
        }
    },
    methods: {
        save() {
            this.loading = true
            axios.post(`/userpermisions/${this.form.id}`, {
                selected: this.selected
            }).
            then((response) => {
                    // console.log(response);
                    this.loading = false
                    this.$emit('alertRequest');
                    this.close();
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        resetForm() {
            // this.form = Object.assign({}, this.defaultForm)
            // this.$refs.form.reset()
        },
        close() {
            this.dialog = false
        },
        getPermissions() {
            var payload = {
                model: 'permissions/web',
                update: 'updatePermissionList'
            }
            this.$store.dispatch("getItems", payload);

        }
    },
    created() {
        eventBus.$on('permEvent', data => {
            this.dialog = true
            this.selected = data.data;

            this.form.id = data.id
            this.getPermissions()
        });
    },
    computed: {
        ...mapState(['permissions']),
        sortPerm() {
            return _.orderBy(this.permissions, 'name', 'asc')
        }
    },
    mounted() {

    }
}
</script>
