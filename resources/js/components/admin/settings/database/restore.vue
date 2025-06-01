<template>
<v-container fluid fill-height>
    <v-layout justify-center align-center wrap>
        <v-card>
            <v-card-title primary-title>

                <v-tooltip right>
                    <template v-slot:activator="{ on }">
                        <v-btn icon v-on="on" slot="activator" class="mx-0" @click="getBackups">
                            <v-icon color="blue darken-2" small>mdi-refresh</v-icon>
                        </v-btn>
                    </template>
                    <span>Refresh</span>
                </v-tooltip>
                <v-spacer></v-spacer>
                <v-btn small elevation="3" color="primary" @click="backup">Take a backup</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="backups" :items-per-page="5" class="elevation-1">
                <template v-slot:item.actions="{ item }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon class="mx-0" @click="confirmRestore(item.path)" slot="activator">
                                <v-icon small color="blue darken-2">mdi-history</v-icon>
                            </v-btn>
                        </template>
                        <span>Restore this version</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card>
    </v-layout>
</v-container>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    data() {
        return {
            headers: [{
                text: 'File name',
                value: 'filename'
            }, {
                text: 'Backup Date',
                value: 'Backup Date'
            }, {
                text: 'Action',
                value: 'actions'
            }]
        }
    },
    methods: {
        restore(item) {
            var data = {
                path: item
            }
            var payload = {
                model: 'dbrestore',
                data: data
            }
            this.$store.dispatch('postItems', payload).then((res) => {
                window.location.reload(); 
            })
        },
        backup() {
            var payload = {
                model: 'backup',
                data: {}
            }
            this.$store.dispatch('postItems', payload).then((response) => {
                this.getBackups()
            })
        },
        backupAndRestore(item) {
            console.log(item);

            var payload = {
                model: 'backup',
                data: {}
            }
            this.$store.dispatch('postItems', payload).then((response) => {
            // console.log(item);
                this.restore(item)

            })
        },
        getBackups() {
            var payload = {
                model: 'dbbackups',
                update: 'updateBackup'
            }
            this.$store.dispatch('getItems', payload)
        },

        
        confirmRestore(item) {
            console.log(item);
            this.$confirm('Are you sure you want to restore this version. A backup for the current data will be taken incase you want to revert back. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
            console.log(item);
                this.backupAndRestore(item);
            }).catch(() => {
                // this.$message({
                //     type: 'error',
                //     message: 'Delete canceled'
                // });
            });

        },
    },
    computed: {
        ...mapState(['backups'])
    },
    mounted() {
        this.getBackups();
    },
}
</script>
