//Importing Line class from the vue-chartjs wrapper
import { Line } from 'vue-chartjs'
//Exporting this so it can be used in other components
export default {
    props: ['form'],
    // extend: Line,
    extends: Line,
    data() {
        return {
            label: [],
            rows: []
        }
    },
    mounted() {
        this.getChartSummary()
        //   axios.get('/getChartData')
        //           .then((response) => {
        //               // console.log(response);
        //               this.label = response.data.data.lables
        //               this.rows = response.data.data.rows
        //               this.setGraph()
        //           })
        //           .catch((error) => {
        //               this.errors = error.response.data.errors
        //           })

    },
    methods: {

        getChartSummary() {
            // if (this.form.ou_id == null || this.form.ou_id == '') {
            //     this.form.ou_id = 0
            // }
            // if (this.form.year_f == null || this.form.year_f == '') {
            //     this.form.year_f = new Date().getFullYear()
            // }
            var payload = {
                model: 'sales_chart?ou=' + this.form.ou + '&year=' + this.form.year,
                update: 'updateSaleChartList'
            }

            this.$store.dispatch('getItems', payload).then((response) => {

                      this.label = response.data.data.lables
                      this.rows = response.data.data.rows
                      this.setGraph()
                // this.purpleLineChart.chartData.labels = this.sales_chart.data.lables
                // this.purpleLineChart.chartData.datasets[0].data = this.sales_chart.data.rows
            })
        },
        setGraph() {
            this.renderChart({
                labels: this.label,
                datasets: [
                    {
                        label: 'Orders',
                        backgroundColor: '#6b61dea1',
                        data: this.rows
                    }
                ]
            }, { responsive: true, maintainAspectRatio: false })
        },
        ref(data) {
            axios.post('/getChartData', data)
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
        eventBus.$on('SaleRefreshEvent', data => {
            this.getChartSummary()
        });

    },
}
