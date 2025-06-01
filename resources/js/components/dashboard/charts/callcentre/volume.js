import { Bar } from 'vue-chartjs'
export default {
    extends: Bar,
    data() {
        return {
            label: [],
            rows: []
        }
    },
    mounted() {
        this.getData()

    },
    methods: {
        getData() {
            var payload = {
                model: 'callcentre',
                update: 'updateStatusChart'
            }

            this.$store.dispatch('getItems', payload).then((response) => {

                this.label = response.data.data.lables
                this.rows = response.data.data.rows
                this.setGraph()
            })
        },
        setGraph() {
          // Define an array of colors for each bar
          const colors = ['#28a165', '#ff0000', '#6b61dea1', '#00ff00', '#ff00ff'];
        
          this.renderChart({
            labels: this.label,
            datasets: [
              {
                label: 'Count',
                backgroundColor: colors.slice(0, this.rows.length),
                data: this.rows
              }
            ]
          }, { responsive: true, maintainAspectRatio: false });
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
