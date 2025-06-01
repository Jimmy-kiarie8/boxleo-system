<template>
<div v-if="warningZone">Are you still there</div>
</template>

<script>
export default {
    data() {
        return {
            events: ['click', 'mousemove', 'mousedown', 'scroll', 'keypress', 'load'],
            warningTimer: null,
            logoutTimer: null,
            warningZone: true,
        }
    },
    mounted() {
        this.events.forEach(function (event) {
            console.log(event);
            window.addEventListener(event, this.resetTimer())
        }, this);
        this.setTimers()
    },
    // destroyed() {
    //     this.events.forEach(function (event) {
    //         window.removeEventListener(event, this.resetTimer())
    //     }, this);
    //     this.resetTimer()
    // },
    methods: {
        setTimers() {
            this.warningTimer = setTimeout(this.warningMessage, 10000);
            this.logoutTimer = setTimeout(this.logouUser, 15000);

            this.warningZone = false
            /*  this.warningTimer = setTimeout(() => {
                            this.logouUser()
                        }, 15000); */
        },
        resetTimer() {
            clearTimeout(this.warningTimer)
            clearTimeout(this.logoutTimer)

            this.setTimers()
        },
        warningMessage() {
            // alert('Warning')
            this.warningZone = true
        },
        logouUser() {
            alert('logout')
        }
    },
}
</script>

<style>

</style>
