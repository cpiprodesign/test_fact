<script>
    import {Pie, mixins} from "vue-chartjs";
    import {pieChartOptions} from "./configPie";

    export default {
        extends: Pie,
        props: ["data"],
        data() {
            return {
                options: pieChartOptions
            };
        },
        mounted() {
            this.renderPieChart();
        },
        computed: {
            dataChartPie: function() {
                return this.data;
            }
        },
        methods: {
            renderPieChart: function() {
                this.renderChart(this.data, this.options);
            }
        },
        watch: {
            data: function() {
                this.destroy();
                //this.renderChart(this.data, this.options);
                this.renderPieChart();
            }
        }
    };
    
</script>
