<template>
<div>

    <v-btn color="red" style="text-transform: none; color: #fff" rounded @click="goToStep2(2)">
        <v-icon>mdi-chevron-right</v-icon>
        <span>Next</span>
    </v-btn>

    <v-btn text @click="close">Cancel</v-btn>
</div>
</template>

<script>
export default {
    methods: {
        goToStep2() {
            eventBus.$emit("excellUploadEvent");
        },
    },
}
</script>

<style>

</style>
