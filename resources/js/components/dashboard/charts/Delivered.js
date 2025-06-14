//Importing Line class from the vue-chartjs wrapper
import { Line } from 'vue-chartjs'
//Exporting this so it can be used in other components
export default {
    // extend: Line,
    extends: Line,
    data() {
        return {
            label: [],
            rows: []
        }
    },
    mounted() {
        this.getDeliveryChart()

    },
    methods: {
        getDeliveryChart() {
            var payload = {
                model: 'delivery_chart',
                update: 'updateDeliveryChart'
            }


            this.$store.dispatch('getItems', payload).then((response) => {

                this.label = response.data.data.lables
                this.rows = response.data.data.rows
                this.setGraph()
            })
        },
        setGraph() {
            this.renderChart({
                labels: this.label,
                datasets: [
                    {
                        label: 'Delivered',
                        backgroundColor: '#28a165',
                        data: this.rows
                    }
                ]
            }, { responsive: true, maintainAspectRatio: false })
        },
        ref(data) {
            axios.post('/getChartDelivered', data)
                .then((response) => {
                    // console.log(response);
                    this.label = response.data.data.lables
                    this.rows = response.data.data.rows
                    this.setGraph()
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        }
    },
    created() {
        eventBus.$on('chartEvent', data => {
            this.label = data.lables
            this.data = data.rows
        });
        eventBus.$on('DashchartEvent', data => {
            this.ref(data)
        });
    },
}
