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
                model: 'agent_chart/Scheduled',
                update: 'updateAgentChart'
            }

            this.$store.dispatch('getItems', payload).then((response) => {
                console.log("🚀 ~ file: cancelled.js:22 ~ this.$store.dispatch ~ response:", response)

                this.label = response.data.data.lables
                this.rows = response.data.data.rows
                this.setGraph()
            })
        },
        setGraph() {
          // Define an array of colors for each bar
          const color = '#1976d2';
        //   const colors = ['#6b61dea1','#ff0000',  '#ff00ff', '#28a165'];
        
          this.renderChart({
            labels: this.label,
            datasets: [
              {
                label: 'Scheduled',
                backgroundColor: color,
                // backgroundColor: colors.slice(0, this.rows.length),
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
