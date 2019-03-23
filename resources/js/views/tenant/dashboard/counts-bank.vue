<template>
    <div class="card">
        <div class="card-header">
            Cuentas de Banco
        </div>
        <div class="card-body">
            <section class="card card-horizontal card-tenant-dashboard">
                <header class="card-header bg-info">
                    <div class="card-header-icon"><i class="fas fa-shopping-cart"></i></div>
                </header>
                <div class="card-body p-4 text-center">
                    <p class="font-weight-semibold mb-0 mx-4">Venta en el día</p>
                    <h5 class="font-weight-semibold mt-0">S/. {{ total_sell }}</h5>
                    <div class="summary-footer"><a href="#client-list" class="text-muted text-uppercase">Ver detalle</a></div>               
                </div>
            </section>
            <section class="card card-horizontal card-tenant-dashboard">
                <header class="card-header bg-success">
                    <div class="card-header-icon"><i class="fas fa-users"></i></div>
                </header>
                <div class="card-body p-4 text-center">
                    <p class="font-weight-semibold mb-0 mx-4">Total Facturado</p>
                    <h5 class="font-weight-semibold mt-0">S/. {{ total_invoices }}</h5>
                </div>
            </section>
            <section class="card card-horizontal card-tenant-dashboard">
                <header class="card-header bg-warning">
                    <div class="card-header-icon"><i class="fas fa-users"></i></div>
                </header>
                <div class="card-body p-4 text-center">
                    <p class="font-weight-semibold mb-0 mx-4">Cuentas por Cobrar</p>
                    <h5 class="font-weight-semibold mt-0">S/. {{ total_charge }}</h5>
                </div>
            </section>
        </div>
    </div>
</template>

<script>    
    import {functions, exchangeRate} from '../../../mixins/functions'
    
    export default {
        mixins: [functions, exchangeRate],
        data() {
            return {
                resource: 'dashboard',
                loading_submit: false,
                loading_form: false,
                total_invoices: 0,
                total_charge: 0,
                total_sell: 0,
                form: {},
                customers: [],
                company: null,
                document_type_03_filter: null,
                operation_types: [],
                establishments: [],
                establishment: null,
                all_series: [],
                series: [],
                currency_type: {},
                documentNewId: null
            }
        },
        async created() {
            //await this.initForm()
            await this.$http.get(`/${this.resource}/counts_bank/0`)
                .then(response => {
                    this.total_invoices = response.data.total_invoices
                    this.total_charge = response.data.total_charge
                    this.total_sell = response.data.total_sell
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
            },
            changeCurrencyType() {
                this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
                let items = []
                this.form.items.forEach((row) => {
                    items.push(calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
                });
                this.form.items = items
                this.calculateTotal()
            },
            calculateTotal() {
                let total_discount = 0
                let total_charge = 0
                let total_exportation = 0
                let total_taxed = 0
                let total_exonerated = 0
                let total_unaffected = 0
                let total_free = 0
                let total_igv = 0
                let total_value = 0
                let total = 0
                this.form.items.forEach((row) => {
                    total_discount += parseFloat(row.total_discount)
                    total_charge += parseFloat(row.total_charge)

                    if (row.affectation_igv_type_id === '10') {
                        total_taxed += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '20') {
                        total_exonerated += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '30') {
                        total_unaffected += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '40') {
                        total_exportation += parseFloat(row.total_value)
                    }
                    if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) < 0) {
                        total_free += parseFloat(row.total_value)
                    }
                    if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) > -1) {
                        total_igv += parseFloat(row.total_igv)
                        total += parseFloat(row.total)
                    }
                    total_value += parseFloat(row.total_value)
                });

                this.form.total_exportation = _.round(total_exportation, 2)
                this.form.total_taxed = _.round(total_taxed, 2)
                this.form.total_exonerated = _.round(total_exonerated, 2)
                this.form.total_unaffected = _.round(total_unaffected, 2)
                this.form.total_free = _.round(total_free, 2)
                this.form.total_igv = _.round(total_igv, 2)
                this.form.total_value = _.round(total_value, 2)
                this.form.total_taxes = _.round(total_igv, 2)
                this.form.total = _.round(total, 2)
            }
           
        }
    }
</script>

<style>
    .card-tenant-dashboard
    {
        /* margin: 0px !important; */
    }
</style>