<template>
<div>
    <v-layout justify-center align-center wrap>
        <splitpanes>
            <pane min-size="30" :size="table_size">
                <myReturns />
            </pane>
            <pane :size="edit_size">
                <div style="width: 100%">
                    <myHeader :return_order="return_order" />
                    <myDetails :return_order="return_order" />
                </div>
            </pane>
        </splitpanes>
        <myreceive :return_order="return_order" />
    </v-layout>
</div>
</template>

<script>
import {
    Splitpanes,
    Pane
} from 'splitpanes'
import {
    mapState
} from 'vuex'
import myReturns from './returns'
import myEdit from './edit'
import myHeader from './Tab_header'
import myreceive from './receive'
import myDetails from './details'

export default {
    components: {
        myReturns,
        Splitpanes,
        Pane,
        myHeader, myreceive, myDetails
    },
    data() {
        return {
            search: '',
            edit_size: 0,
            table_size: 100,
            return_order: {},
        }
    },
    methods: {
        handleClick(value) {
            this.headers = [{
                text: 'Created On',
                align: 'start',
                value: 'created_at',
            }]

        },
        getReturns() {
            var payload = {
                model: '/returns',
                update: 'updateReturnsList'
            }
            this.$store.dispatch("getItems", payload);
        },
        editOrder(id) {
            console.log(id);

            this.$router.push({
                name: "edit_returns",
                params: {
                    id: id
                }
            });

        },
    },
    mounted() {
        this.getReturns();
    },
    created() {
        eventBus.$on('resizeEvent', data => {
            this.table_size = '40'
            this.edit_size = '80'
            this.return_order = data
        });

        eventBus.$on('closeReturnEvent', data => {
            this.table_size = '100'
            this.edit_size = '0'

        });
    },
    computed: {
        ...mapState(['returns'])
    },
}
</script>
