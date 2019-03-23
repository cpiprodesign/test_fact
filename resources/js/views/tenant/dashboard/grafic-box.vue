<template>
    <div class="card">
        <div class="card-header">
            Flujo de caja del año actual
        </div>
        <div class="card-body">
           <chart-line :data="dataChartLine" v-if="loaded"></chart-line>
        </div>
    </div>
</template>

<script>    
    import {functions, exchangeRate} from '../../../mixins/functions'
    import ChartLine from './charts/Line'

    export default {
        mixins: [functions, exchangeRate],
        components: {ChartLine},
        data() {
            return {
                resource: 'dashboard',
                loading_submit: false,
                loading_form: false,
                loaded: false,
                total_invoices: 0,
                total_charge: 0,
                form: {},
                customers: [],
                company: null,
                operation_types: [],
                establishments: [],
                establishment: null,
                dataChartLine : {
                    title: {
                        display: true,
                        text: ""
                    },
                    labels: null,
                    datasets: [{
                        data: null,
                        label: "Total Facturado",
                        backgroundColor: "#4caf50",
                        borderColor: "#4caf50",
                        fill: false
                    }, {
                        data: null,
                        label: "Pagos pendientes",
                        backgroundColor: "#1e32a2",
                        borderColor: "#1e32a2",
                        fill: false
                    }
                ]}
            }
        },
        async mounted() {
            this.loaded = false
            await this.$http.get(`/${this.resource}/chart_cash_flow`)
                .then(response => {
                    let line = response.data.line
                    this.dataChartLine.labels = line.labels
                    this.dataChartLine.datasets[0].data = line.data
                    this.dataChartLine.datasets[1].data = line.data2
                    //this.total_documents = response.data.total_documents
                    // console.log(response.data)
                    // this.records = response.data.data
                })
            this.loaded = true
        },
        async created() {
            //await this.initForm()
            await this.$http.get(`/${this.resource}/counts_bank/0`)
                .then(response => {
                    this.total_invoices = response.data.total_invoices.total
                    this.total_charge = response.data.total_charge.total
                })
            this.loading_form = true
           
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    establishment_id: null,
                    document_type_id: null,
                    series_id: null,
                    number: '#',
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    customer_id: null,
                    currency_type_id: null,
                    purchase_order: null,
                    exchange_rate_sale: 0,
                    total_prepayment: 0,
                    total_charge: 0,
                    total_discount: 0,
                    total_exportation: 0,
                    total_free: 0,
                    total_taxed: 0,
                    total_unaffected: 0,
                    total_exonerated: 0,
                    total_igv: 0,
                    total_base_isc: 0,
                    total_isc: 0,
                    total_base_other_taxes: 0,
                    total_other_taxes: 0,
                    total_taxes: 0,
                    total_value: 0,
                    total: 0,
                    additional_information:null,
                    operation_type_id: null,
                    date_of_due: moment().format('YYYY-MM-DD'),
                    items: [],
                    charges: [],
                    discounts: [],
                    attributes: [],
                    guides: [],
                    actions: {
                        format_pdf:'a4',
                    }
                }
            }
            
        }
    }
</script>
