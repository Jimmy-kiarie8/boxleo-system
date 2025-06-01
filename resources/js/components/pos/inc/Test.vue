<template>
<div>
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <v-btn color="primary" @click="print">Print</v-btn>
            <div id="print">
                <myReceipt></myReceipt>
            </div>

        </v-layout>
    </v-container>
</div>
</template>

<script>
import myReceipt from './Receipt'
export default {
    components: {
        myReceipt,
    },
  data () {
    return {
      output: null
    }
  },
  methods: {
    print () {
      // Pass the element id here
      this.$htmlToPaper('print');
    }
  }
}
</script>
