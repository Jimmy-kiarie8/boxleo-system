<template>
<myAdminDashboard />
</template>

<script>
import myAdminDashboard from './admin'
export default {
    props: ['user'],
    components: {
        myAdminDashboard
    },

}
</script>

<style scoped>
</style>
