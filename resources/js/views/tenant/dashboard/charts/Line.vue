<script>
    import {Line, mixins} from "vue-chartjs";
    import {lineChartOptions} from "./config";

    export default {
        extends: Line,
        props: ["data"],
        data() {
            return {
                options: lineChartOptions
            };
        },
        mounted() {
            this.renderLineChart();
        },
        computed: {
            dataChartLine: function() {
                return this.data;
            }
        },
        methods: {
            renderLineChart: function() {
                this.renderChart(this.data, this.options);
            }
        },
        watch: {
            data: function() {
                this.destroy();
                //this.renderChart(this.data, this.options);
                this.renderLineChart();
            }
        }
    };
    
</script>
