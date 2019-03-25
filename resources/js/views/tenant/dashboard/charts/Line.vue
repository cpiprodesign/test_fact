<script>
    import {Line, mixins} from "vue-chartjs";
    import {lineChartOptions} from "./config";
    const { reactiveProp } = mixins

    export default {
        extends: Line,
        props: ["data"],
        mixins: [reactiveProp],
        data() {
            return {
                options: lineChartOptions
            };
        },
        mounted() {
            this.renderChart(this.data, this.options);
        }
    };
    
</script>
