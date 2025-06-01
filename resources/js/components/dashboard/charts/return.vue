<template>
<div>
    <lineChart :height="height" :chart-data="blueBarChart.chartData" :gradient-color="blueBarChart.gradientColors" :gradient-stops="blueBarChart.gradientStops" :extra-options="blueBarChart.extraOptions" />
</div>
</template>

<script>
import lineChart from './line'
import {
    mapState
} from 'vuex'
export default {
    name: 'returnchart',
    components: {
        lineChart,
    },
    data() {
        return {
            height: 200,
            form: {},
            blueBarChart: {
                extraOptions: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    responsive: true,
                    tooltips: {
                        backgroundColor: '#f5f5f5',
                        titleFontColor: '#333',
                        bodyFontColor: '#666',
                        bodySpacing: 4,
                        xPadding: 12,
                        mode: "nearest",
                        intersect: 0,
                        position: "nearest"
                    },
                    scales: {
                        yAxes: [{
                            barPercentage: 1.6,
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(29,140,248,0.0)',
                                zeroLineColor: "transparent",
                            },
                            ticks: {
                                suggestedMin: 50,
                                suggestedMax: 110,
                                padding: 20,
                                fontColor: "#ff8a76"
                            }
                        }],

                        xAxes: [{
                            barPercentage: 1.6,
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(220,53,69,0.1)',
                                zeroLineColor: "transparent",
                            },
                            ticks: {
                                padding: 20,
                                fontColor: "#ff8a76"
                            }
                        }]
                    }

                },
                chartData: {
                    labels: [],
                    datasets: [{
                        label: "Returns",
                        fill: true,
                        borderColor: '#f00',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        pointBackgroundColor: '#f00',
                        pointBorderColor: 'rgba(255,255,255,0)',
                        pointHoverBackgroundColor: '#f00',
                        backgroundColor: '#ff00007a',
                        pointBorderWidth: 20,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 15,
                        pointRadius: 4,
                        data: [],
                    }]
                }
            }
        }
    },
    computed: {
        ...mapState(['return_chart'])
    },

    mounted() {
        this.getReturnChart();
    },
    methods: {
        getReturnChart() {
            // if (this.form.ou_id == null || this.form.ou_id == '') {
            //     this.form.ou_id = 0
            // }
            if (this.form.year_f == null || this.form.year_f == '') {
                this.form.year_f = new Date().getFullYear()
            }
            var payload = {
                model: 'return_chart',
                update: 'updateReturnChart'
            }

            this.$store.dispatch('getItems', payload).then((res) => {
                // eventBus.$emit('saleChartEvent', res.data)
                this.blueBarChart.chartData.labels = this.return_chart.data.lables
                this.blueBarChart.chartData.datasets[0].data = this.return_chart.data.rows
            })
        },
    },
    created() {
        eventBus.$on('saleChartEvent', data => {

            // this.blueBarChart.chartData.labels = data.data.lables
            // this.blueBarChart.chartData.datasets[0].data = data.data.rows
        });
    },
}
</script>
