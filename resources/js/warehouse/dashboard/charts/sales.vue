<template>
<div>
    <lineChart  :height="height" :chart-data="purpleLineChart.chartData" :gradient-color="purpleLineChart.gradientColors" :gradient-stops="purpleLineChart.gradientStops" :extra-options="purpleLineChart.extraOptions" />
</div>
</template>

<script>
import lineChart from './line'
import {
    mapState
} from 'vuex'
export default {
    name: 'saleschart',
    components: {
        lineChart,
    },
    data() {
        return {
            height: 100,
            form: {},
            purpleLineChart: {
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
                        label: "Sales",
                        fill: true,
                        borderColor: '#1c8cf8',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        pointBackgroundColor: '#1c8cf8',
                        pointBorderColor: 'rgba(255,255,255,0)',
                        pointHoverBackgroundColor: '#1c8cf8',
                        backgroundColor: '#1c8cf875',
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
        ...mapState(['sales_chart'])
    },
    methods: {
        getChartSummary() {
            if (this.form.ou_id == null || this.form.ou_id == '') {
                this.form.ou_id = 0
            }
            if (this.form.year_f == null || this.form.year_f == '') {
                this.form.year_f = new Date().getFullYear()
            }
            var payload = {
                model: 'sales_chart',
                update: 'updateSaleChartList'
            }

            this.$store.dispatch('getItems', payload).then((res) => {
                // eventBus.$emit('saleChartEvent', res.data)
                this.purpleLineChart.chartData.labels = this.sales_chart.data.lables
                this.purpleLineChart.chartData.datasets[0].data = this.sales_chart.data.rows
            })
        },
    },
    created() {
        // eventBus.$on('saleChartEvent', data => {
        //     this.purpleLineChart.chartData.labels = data.data.lables
        //     this.purpleLineChart.chartData.datasets[0].data = data.data.rows
        // });
    },

    mounted () {
        this.getChartSummary();
    },
}
</script>
