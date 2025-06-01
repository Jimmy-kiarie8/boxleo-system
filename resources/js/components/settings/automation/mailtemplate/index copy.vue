<template>
<div class="container">
    <el-select v-model="form.option" placeholder="Select" @change="get_rows">
        <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
        </el-option>
    </el-select>

    <el-select v-model="opt" placeholder="Select" @change="add_message">
        <el-option v-for="item in table_rows" :key="item.Field" :label="item.Field" :value="item.Field">
        </el-option>
    </el-select>

    <tinymce id="d1" :other_options="tinyOptions" v-model="form.template" ref="tm" :plugins="plugins" :init="initObj" toolbar="mybutton"></tinymce>
    <v-app>
        <VBtn color="primary" @click="save">Save</VBtn>
    </v-app>
    <VDivider />

    <div v-html="form.template"></div>
</div>
</template>

<script>
import tinymce from 'vue-tinymce-editor'
import {
    mapState
} from 'vuex'

export default {
    components: {
        tinymce,
    },
    data() {
        return {

            initObj: {
                setup: function (editor) {
                    editor.addButton('mybutton', {
                        text: 'My button',
                        icon: false,
                        onclick: function () {
                            editor.insertContent('&nbsp;<b>It\'s my button!</b>&nbsp;');
                        }
                    });
                }
            },

            plugins: ['advlist autolink lists link image charmap print preview hr anchor pagebreak', 'searchreplace wordcount visualblocks visualchars code fullscreen', 'insertdatetime media nonbreaking save table contextmenu directionality', 'template paste textcolor colorpicker textpattern imagetools toc emoticons hr codesample'],
            other_options: ['test 123'],
            editorContent: '<h2 style="color: #339966;">Hi there from TinyMCE for Vue.js.</h2> <p>&nbsp;</p> <p><span>Hey y`all.</span></p>',
            tinyOptions: {
                'height': 500
            },
            form: {
                template: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio iusto dignissimos beatae veniam nam! Recusandae, obcaecati temporibus culpa, accusamus natus distinctio voluptates quos porro sunt neque cupiditate non at sequi.'
            },
            opt: '',
            mailable: {},

            options: [{
                value: 'users',
                label: 'Users'
            }, {
                value: 'sales',
                label: 'Orders'
            }],
            // txtContent: ''
        }
    },
    methods: {
        save() {
            // this.form.message = this.message
            var payload = {
                data: this.form,
                model: 'mailable',
            }
            this.$store.dispatch('postItems', payload)
                .then(response => {
                    // this.goToSales()
                });
        },
        get_rows(table) {
            var payload = {
                model: 'table_rows',
                search: table,
                update: 'updateTableRows'
            }

            this.$store.dispatch('searchItems', payload)
        },

        add_message(text) {

            console.log(this.$refs.tm.editor)
            return

            let out = this.form.template
            let cursorIndex = document.getElementById('inputTextField').selectionEnd;
            out = out.substring(0, cursorIndex) + tagToInsert + out.substring(cursorIndex);

            console.log(out);

            return

            const self = this;
            var tArea = this.$refs.tiny;
            // filter:
            if (0 == insert) {
                return;
            }
            // if (0 == cursorPos) {
            //     return;
            // }

            // get cursor's position:
            var startPos = tArea.selectionStart,
                endPos = tArea.selectionEnd,
                cursorPos = startPos,
                tmpStr = tArea.value;

            // insert:
            self.form.template = tmpStr.substring(0, startPos) + insert + tmpStr.substring(endPos, tmpStr.length);

            // move cursor:
            setTimeout(() => {
                cursorPos += insert.length;
                tArea.selectionStart = tArea.selectionEnd = cursorPos;
            }, 3);

            console.log(cursorPos);
            console.log(tArea);
            console.log(self.form.template);

            // this.form.template = this.form.template + ' {{' + option + ' }} '
            // this.opt = ''
        }
    },
    computed: {
        ...mapState(['table_rows'])
    },
}
</script>
