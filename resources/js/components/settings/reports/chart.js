import { Bar } from 'vue-chartjs'
export default {
    extends: Bar,
   props: ['chartData', 'title', 'color'],

    mounted() {
    },
    methods: {
        setGraph() {
            // Define an array of colors for each bar
            //   const colors = ['#28a165', '#ff0000', '#6b61dea1', '#00ff00', '#ff00ff'];
            // const color = '#6b61dea1';

            this.renderChart({
                labels: this.chartData.labels,
                datasets: [
                    {
                        label: this.title,
                        backgroundColor: this.color,
                        data: this.chartData.rows
                    }
                ]
            }, { responsive: true, maintainAspectRatio: false });
        }

    },
    created() {
        eventBus.$on('reportEvent', () => {
            setTimeout(() => {
            this.setGraph()
            }, 400);
        });
    },
}
