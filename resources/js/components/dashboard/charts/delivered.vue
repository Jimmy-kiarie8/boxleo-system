<template>
<div>
    <lineChart :height="height" :blueBarChart="blueBarChart" :chart-data="blueBarChart.chartData" :gradient-color="blueBarChart.gradientColors" :gradient-stops="blueBarChart.gradientStops" :extra-options="blueBarChart.extraOptions" />
</div>
</template>

<script>
import lineChart from './line'
import {
    mapState
} from 'vuex'
export default {
    name: 'deliverChart',
    components: {
        lineChart,
    },
    data() {
        return {
            height: 200,
            form: {
                year_f: ''
            },
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

                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(29,140,248,0.1)',
                                zeroLineColor: "transparent",
                            },
                            ticks: {
                                suggestedMin: 60,
                                suggestedMax: 120,
                                padding: 20,
                                fontColor: "#9e9e9e"
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(29,140,248,0.1)',
                                zeroLineColor: "transparent",
                            },
                            ticks: {
                                padding: 20,
                                fontColor: "#9e9e9e"
                            }
                        }]
                    }
                },
                chartData: {
                    labels: [],
                    datasets: [{
                        label: "Delivery",
                        fill: true,
                        borderColor: '#1f8ef1',
                        borderWidth: 2,
                        borderDash: [],
                        backgroundColor: '#43826954',
                        borderDashOffset: 0.0,
                        borderColor: '#61b983',
                        pointBackgroundColor: '#61b983',
                        pointHoverBackgroundColor: '#61b983',
                        data: [],
                    }]
                },
                gradientColors: ['rgba(29,140,248,0.2)', 'rgba(29,140,248,0.0)', 'rgba(29,140,248,0)'],
                gradientStops: [1, 0.4, 0],
            },
        }
    },
    computed: {
        ...mapState(['delivery_chart'])
    },
    mounted() {
        this.getDeliveryChart();
    },
    methods: {
        getDeliveryChart() {
            // if (this.form.ou_id == null || this.form.ou_id == '') {
            //     this.form.ou_id = 0
            // }
            if (this.form.year_f == null || this.form.year_f == '') {

                this.form.year_f = new Date().getFullYear()
            }
            var payload = {
                model: 'delivery_chart',
                update: 'updateDeliveryChart'
            }

            this.$store.dispatch('getItems', payload).then((res) => {
                // eventBus.$emit('saleChartEvent', res.data)
                this.blueBarChart.chartData.labels = this.delivery_chart.data.lables
                this.blueBarChart.chartData.datasets[0].data = this.delivery_chart.data.rows
            })
        },
    },
}
</script>
