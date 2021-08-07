<script>
    //Importing Bar class from the vue-chartjs wrapper
    import { Bar } from 'vue-chartjs'
    //Exporting this so it can be used in other components
    export default {
        extends: Bar,
        props: ['cd'],
        data () {
            return {
                datacollection: null,
                //Chart.js options that controls the appearance of the chart
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            gridLines: {
                                display: true
                            }
                        }],
                        xAxes: [ {
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                    legend: {
                        display: true
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            }
        },
        created () {
            this.datacollection = this.cd;
        },
        mounted() {
            this.renderChart(this.datacollection, this.options)
        }
    }
</script>