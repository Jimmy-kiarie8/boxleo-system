<template>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <div id="realtimemap"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    data() {
        return {
            map: null
        }
    },
    methods: {
        mapInit() {
            // console.log('******************************');

            this.map = new google.maps.Map(document.getElementById('realtimemap'), {
                center: {lat: -34.397, lng: 150.644},
                zoom: 8
            })
            // console.log(this.map)
        }
    },
    created () {

        Echo.channel('location') //Should be Channel Name
            .listen('SendPosition', (e) => {
                console.log(e);
                // eventBus.$emit('orderUploadEvent', e)
                eventBus.$emit('playSoundEvent')
            });
        // this.mapInit();
    },
    mounted () {

        this.mapInit();
    },
}
</script>
