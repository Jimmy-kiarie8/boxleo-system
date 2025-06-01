<template>
<div>
    <v-card width="90%" style="margin: auto">
    <myStores />
    </v-card>
</div>
</template>

<script>
import myStores from './stores.vue'
export default {
    components: {
        myStores,
    },
    data() {
        return {
        }
    },
}
</script>
