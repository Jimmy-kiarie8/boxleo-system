<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <v-flex xs4 sm5 offset-sm1 style="padding: 7px 0;">
                <label for="" style="color: #52627d;">Customer Name*</label>
                <el-select v-model="form.vendor_id" multiple filterable remote reserve-keyword placeholder="type at least 3 characters" :remote-method="getSellers" :loading="loading" style="width: 100%;">
                    <el-option v-for="item in sellers.data" :key="item.id" :label="item.name" :value="item.id">
                    </el-option>
                </el-select>
            </v-flex>
        </v-layout>
    </v-container>
</div>
</template>

<script>
export default {

}
</script>
