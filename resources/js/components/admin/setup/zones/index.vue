<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
                <v-card>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex sm5 style="margin-left: 20px">
                                <label for="">Zone Code</label>
                                <el-input placeholder="" v-model="form.code"></el-input>
                            </v-flex>
                            <v-flex sm5 offset-sm1>
                                <label for="">Zone name</label>
                                <el-input placeholder="" v-model="form.name"></el-input>
                            </v-flex>
                            <v-flex sm12 style="padding: 30px 20px;">
                                <v-btn color="primary" outlined rounded @click="add_zone">
                                    <v-icon>mdi-plus</v-icon> Add
                                </v-btn>
                            </v-flex>
                        </v-layout>

                        <v-divider></v-divider>

                        <v-data-table :headers="headers" :items="zones" :search="search">
                            <template v-slot:item.actions="{ item, index }">
                                
                                <v-btn color="success" text v-if="index == zone_details.zone_index" small outlined>
                                    Default
                                </v-btn>
                                <v-btn text @click="default_zone(index)" v-else small outlined>
                                    Make Default
                                </v-btn>

                                
                                <v-btn text @click="deleteItem(item)" color="red" small outlined>
                                   Remove
                                </v-btn>

                                <!-- <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" icon class="mx-0" @click="default_zone(index)" slot="activator">
                                            <v-icon small color="success" v-if="index == zone_details.zone_index">mdi-check-circle</v-icon>
                                            <v-icon small v-else>mdi-check-circle</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Mark as default zone</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn icon v-on="on" class="mx-0" @click="deleteItem(item)" slot="activator">
                                            <v-icon small color="pink darken-2">mdi-delete</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Delete {{ item.name }}</span>
                                </v-tooltip> -->
                            </template>
                        </v-data-table>
                    </v-card-text>

                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</div>
</template>

<script>
export default {
    props: ['setup_details'],
    name: 'zone',
    data() {
        return {
            search: '',
            headers: [{
                    text: 'Zone Code',
                    value: 'code'
                },
                {
                    text: 'Zone Name',
                    value: 'name'
                },
                {
                    text: 'Actions',
                    value: 'actions',
                    sortable: false
                }
            ],
            form: {
                code: '',
                name: '',
            },
            zone_details: {
                ou: null,
                zone_index: 0
            },
            zones: [],
            loading: false
        }
    },
    methods: {
        add_zone() {
            var exists = false

            if (this.form.code == '' || this.form.name == '') {
                this.$message({
                    type: 'error',
                    message: 'Please fill all the fields'
                });
                return  
            }

            if (this.zones.length > 0) {
                this.zones.forEach(element => {
                    if (element.code == this.form.code) {
                        exists = true
                        this.$message({
                            type: 'error',
                            message: 'Zone already added'
                        });
                    }
                });

                if (!exists) {
                    this.zones.push(this.form)
                }
            } else {
                this.zones.push(this.form)
            }
            this.form = {
                code: '',
                name: '',
            }

        },
        save() {
            // this.zones.push(this.setup_details)
            var payload = {
                model: 'zone_setup',
                data: {
                    zones: this.zones,
                    zone_details: this.zone_details
                },
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    eventBus.$emit("finishEvent")
                });
        },
        default_zone(index) {
            this.zone_details.zone_index = index
        },
        deleteItem(item) {
            const index = this.zones.indexOf(item)
            this.zones.splice(index, 1)
        },
    },

    created() {
        eventBus.$on('zoneCreateEvent', data => {
            if (this.zones.length > 0) {
                this.save()
            } else {
                this.$message({
                    type: 'error',
                    message: 'You need to add zones'
                });
            }
        });
        eventBus.$on('ouEvent', data => {
            this.zone_details.ou = data
        });
    },
}
</script>
