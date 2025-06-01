<template>
    <v-row>
        <template v-for="item in form_data">
            <v-col :cols="item.col" :md="item.col" v-if="item.display">
                <div>
                    <v-text-field clearable :label="item.label" variant="outlined" v-model="item.value"
                        v-if="item.type == 'text'" type="text"></v-text-field>
                    <v-text-field clearable :label="item.label" variant="outlined" v-model="item.value"
                        v-if="item.type == 'number'" type="number"></v-text-field>
                    <v-text-field clearable :label="item.label" variant="outlined" v-model="item.value"
                        v-if="item.type == 'date'" type="date"></v-text-field>
                    <v-text-field clearable :label="item.label" variant="outlined" v-model="item.value"
                        v-if="item.type == 'time'" type="time"></v-text-field>
                    <v-textarea clearable :label="item.label" variant="outlined" v-model="item.value"
                        v-if="item.type == 'long_text'"></v-textarea>
                    <v-radio-group v-model="item.value" inline v-if="item.type == 'radio'" color="success">
                        <template v-slot:label>
                            <div>{{ item.label }}</div>
                        </template>
                        <v-radio v-for="(option, index) in item.items" :key="index" :label="option"
                            :value="option"></v-radio>
                    </v-radio-group>
                    <v-autocomplete v-model="item.value" v-if="item.type == 'select'" :items="item.items"
                        item-text="label" item-value="value" :label="item.label" return-object :multiple="item.multiple"
                        single-line @change="emitUpdate(item)"></v-autocomplete>
                    <v-combobox clearable chips :multiple="item.multiple" :label="item.label" :items="item.items"
                        variant="outlined" v-if="item.type == 'combobox'" :return-object="false"
                        v-model="item.value"></v-combobox>
                </div>
            </v-col>
        </template>
    </v-row>
</template>

<script>
export default {
    props: {
        form_data: Array
    },
    watch: {
        form_data: {
            handler: 'emitUpdate',
            deep: true
        }
    },
    methods: {
        emitUpdate(item) {
            console.log("🚀 ~ emitUpdate ~ item:", item)
            eventBus.$emit('update', item);
        }
    }
}
</script>
