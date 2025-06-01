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
            map: null,
            marker: null,
            center: {
                lat: -1.292066,
                lng: 36.821945
            },
            linecoordinates: [],
            data: null,
        }
    },
    methods: {
        mapInit() {
            this.map = new google.maps.Map(document.getElementById('realtimemap'), {
                center: {
                    lat: -1.292066,
                    lng: 36.821945
                },
                zoom: 10
            })

            this.marker = new google.maps.Marker({
                map: this.map,
                position: this.center,
                animation: 'bounce'
            })
        },
        updateMap() {
            let newPosition = {
                lat: parseFloat(this.data.lat),
                lng: parseFloat(this.data.long)
            }
            // console.log(newPosition);

            this.map.setCenter(newPosition);
            this.marker.setPosition(newPosition);
            this.linecoordinates.push(new google.maps.LatLng(newPosition.lat, newPosition.lng));

            var linecoordinatesPath = new google.maps.Polyline({
                path: this.linecoordinates,
                geodesic: true,
                map: this.map,
                strokeColor: '#f00',
                strokeOpacity: 1.0,
                strokeWeight: 2
            })
        }
    },
    created() {

        Echo.channel('location') //Should be Channel Name
            .listen('SendPosition', (e) => {
                console.log(e);
                this.data = e.location
                this.updateMap()
                // eventBus.$emit('orderUploadEvent', e)
                eventBus.$emit('playSoundEvent')
            });
        // this.mapInit();
    },
    mounted() {

        this.mapInit();
    },
}
</script>

<style scoped>
#realtimemap {
    height: 500px;
    width: 100%;
}
</style>
