<template>
<v-layout row wrap>
    <v-flex sm12>
        <v-card-text>
            <v-layout row wrap>
                <VFlex sm6>
                    <label for="">Offer Name</label>
                    <el-input placeholder="Discount name" v-model="form.offer_name"></el-input>
                </VFlex>
                <VFlex sm6>
                    <label for="">Offer display name</label>
                    <el-input placeholder="Discount name" v-model="form.offer_display_name"></el-input>
                </VFlex>

                <VFlex sm6>
                    <label for="">Offer Start date</label>
                    <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="width: 100%" v-model="form.start_date" type="date" placeholder="Pick a day">
                    </el-date-picker>
                </VFlex>
                <VFlex sm6>
                    <label for="">Offer End date</label>
                    <el-date-picker format="yyyy/MM/dd" value-format="yyyy-MM-dd" style="width: 100%" v-model="form.end_date" type="date" placeholder="Pick a day">
                    </el-date-picker>
                </VFlex>

                <VFlex sm6>
                    <label for="">Priority</label>
                    <el-input placeholder="0" v-model="form.priority" type="number"></el-input>
                </VFlex>
                <VFlex sm6>
                    <br>
                    <br>
                  <el-checkbox v-model="form.is_active">Is active</el-checkbox>
                </VFlex>

                <!-- <v-checkbox label="Is active" v-model="form.is_active" value="is_active"></v-checkbox> -->
                <VFlex sm12>
                    <label for="">Description</label>
                    <vue-editor v-model="form.description"></vue-editor>
                </VFlex>
            </v-layout>
            <v-divider></v-divider>
        </v-card-text>
    </v-flex>
</v-layout>
</template>

<script>
import {
    VueEditor
} from "vue2-editor";
export default {
    components: {
        VueEditor,
    },
    props: ['form'],
    data() {
        return {}
    },
    methods: {},
}
</script>

<style scoped>
label {
    color: #5d5252 !important;
}
</style>
