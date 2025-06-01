import { Bar } from 'vue-chartjs'

export default {
    name: 'barGraph',
    extends: Bar,
    props: ['options', 'blueBarChart'],
    created() {
        this.renderChart(this.blueBarChart.chartdata, this.options)
    },

}
