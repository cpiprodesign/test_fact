<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Dashboard</span> </li>
                <!-- <li><span class="text-muted">Facturas - Notas <small>(crédito y débito)</small> - Boletas - Anulaciones</small></span></li> -->
            </ol>
            <div class="right-wrapper pull-right">
                <el-select v-model="establishment_id" @change="changeEstablishment" class="pull-right mt-2 mr-2">
                    <el-option :key="0" :value="0" :label="'Todos los establecimientos'"></el-option>
                    <el-option v-for="option in establishments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                </el-select>
                <!-- <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a> -->
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <div class="row" v-if="loaded">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="el-dialog__title">Flujo de caja de la última semana (Todos los establecimientos)</h5>
                                <chart-line :data="dataChartLine" v-if="loaded_line" ></chart-line>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12" style="max-height: 300px; overflow-y: scroll">
                                <h5 class="el-dialog__title">Productos por agotarse</h5>                                
                                <table class="table table-sm">
                                    <thead class="table-active">
                                        <tr>
                                            <td>Producto</td>
                                            <td>Cantidad</td>
                                            <td>Stock Mínimo</td>
                                            <td>Almacén</td>
                                            <td>Estado</td>
                                            <td>Opción</td>
                                        </tr>
                                    </thead>
                                    <tbody v-for="(row,index) in items" :key="row.id">
                                        <tr>
                                            <td>{{ row.description }}</td>
                                            <td>{{ row.stock }}</td>
                                            <td>{{ row.stock_min }}</td>
                                            <td>{{ row.warehouse }}</td>
                                            <td>
                                                <span class="badge bg-secondary text-white bg-danger" v-if="row.stock < 1 ">Agotado</span>
                                                <span class="badge bg-secondary text-white bg-warning" v-if="row.stock > 0">Pocas unidades</span> 
                                            </td>
                                            <td>
                                                <a :href="`purchases/create`" class="btn waves-effect waves-light btn-xs btn-info m-1__2">Abastacer</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <h5 class="el-dialog__title">Cuentas por cobrar</h5>
                                <table class="table table-sm">
                                    <thead class="table-active">
                                        <tr>
                                            <td>Cliente</td>
                                            <td>Documento</td>
                                            <td>Deuda</td>
                                            <td>Opción</td>
                                        </tr>
                                    </thead>
                                    <tbody v-for="(row,index) in customers" :key="row.id">
                                        <tr>
                                            <td>{{ row.name }}</td>
                                            <td>
                                                {{ row.series }}-{{ row.number }} <br>
                                                <small v-if="row.document_type_id == '01'">Factura Electrónica</small>
                                                <small v-if="row.document_type_id == '03'">Boleta de Venta Electrónica</small>
                                            </td>
                                            <td>{{ row.total }}</td>
                                            <td><button type="button" class="btn waves-effect waves-light btn-xs btn-danger m-1__2" @click.prevent="clickPay(row.id)">Pagar</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <section class="card card-horizontal card-tenant-dashboard">
                            <header class="card-header bg-info">
                                <div class="card-header-icon"><i class="fas fa-shopping-cart"></i></div>
                            </header>
                            <div class="card-body p-4 text-center">
                                <p class="font-weight-semibold mb-0 mx-4">Venta en el día</p>
                                <h5 class="font-weight-semibold mt-0">S/. {{ total_sell }}</h5>
                                <div class="summary-footer"><a :href="`dashboard/sells`" class="text-muted text-uppercase">Ver detalle</a></div>               
                            </div>
                        </section>
                        <section class="card card-horizontal card-tenant-dashboard">
                            <header class="card-header bg-success">
                                <div class="card-header-icon"><i class="fas fa-donate"></i></div>
                            </header>
                            <div class="card-body p-4 text-center">
                                <p class="font-weight-semibold mb-0 mx-4">Total Facturado</p>
                                <h5 class="font-weight-semibold mt-0">S/. {{ total_invoices }}</h5>
                            </div>
                        </section>
                        <section class="card card-horizontal card-tenant-dashboard">
                            <header class="card-header bg-warning">
                                <div class="card-header-icon"><i class="fas fa-address-book"></i></div>
                            </header>
                            <div class="card-body p-4 text-center">
                                <p class="font-weight-semibold mb-0 mx-4">Cuentas por Cobrar</p>
                                <h5 class="font-weight-semibold mt-0">S/. {{ total_charge }}</h5>
                            </div>
                        </section>                        
                        <section class="card card-horizontal card-tenant-dashboard">
                            <h5>Todos los establecimientos (% Pagos)</h5>
                            <chart-pie :data="dataChartPie" v-if="loaded_pie"></chart-pie>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <documents-pay :showDialog.sync="showDialogPay"
                            :recordId="recordId"></documents-pay>
    </div>
</template>

<script>   
    import ChartLine from './charts/Line'
    import ChartPie from './charts/Pie'
    import DocumentsPay from './partials/pay.vue'

    export default {
        components: {ChartLine, ChartPie, DocumentsPay},
        data() {
            return {
                resource: 'dashboard',
                loaded: false,
                loaded_line: false,
                loaded_pie: false,
                showDialogPay: false,
                recordId: null,
                establishments: [],
                establishment_id: 0,
                total_invoices: 0,
                total_charge: 0,
                total_sell: 0,
                items: [],
                customers: [], 
                dataChartLine : null,
                dataChartPie : null
            }
        },
        async mounted() {
            await this.load_grafic()
            await this.load_grafic_pie()
        },
        async created() {
            this.loaded = false

            await this.$http.get(`/${this.resource}/establishments/`)
                .then(response => {
                    this.establishments = response.data.establishments
            })
            
           
            await this.load()
            this.loaded = true
        },
        methods: {
            load() {
                this.loaded = false
                this.$http.get(`/${this.resource}/load/${this.establishment_id}`)
                    .then(response => {

                        this.items = response.data.items
                        this.customers = response.data.customers

                        this.total_invoices = response.data.total_invoices
                        this.total_charge = response.data.total_charge
                        this.total_sell = response.data.total_sell
                        
                    })
                this.loaded = true
            },
            load_grafic_pie(){
                this.loaded_pie = false

                this.dataChartPie = null;
                this.dataChartPie =  {
                    labels: null,
                    datasets: [{
                        label: "",
                        backgroundColor: ["#28a745", "#ffcd56"],
                        data: null
                    }]
                }

                this.$http.get(`/${this.resource}/chart_pie_total/${this.establishment_id}`)
                .then(response => {
                    let pie = response.data.pie
                    this.dataChartPie.labels = pie.labels
                    this.dataChartPie.datasets[0].data = pie.data     
                })
                
                this.loaded_pie = true
            },
            load_grafic(){

                this.loaded_line = true
                
                this.dataChartLine = null;
                this.dataChartLine = {
                    labels: null,
                    datasets: [{
                        data: null,
                        label: "Total Facturado",
                        backgroundColor: "#28a745",
                        borderColor: "#28a745",
                        fill: false
                    }, {
                        data: null,
                        label: "Pagos pendientes",
                        backgroundColor: "#ffcd56",
                        borderColor: "#ffcd56",
                        fill: false
                    }
                ]}
                
                this.$http.get(`/${this.resource}/chart_cash_flow/${this.establishment_id}`)
                .then(response => {
                    let line = response.data.line
                    this.dataChartLine.labels = line.labels
                    this.dataChartLine.datasets[0].data = line.data
                    this.dataChartLine.datasets[1].data = line.data2
                })

                this.loaded_line = true
            },
            changeEstablishment() {
                this.load_grafic()
                this.load()
                this.load_grafic_pie()                
            },
            clickPay(recordId = null) {
                this.recordId = recordId
                this.showDialogPay = true
            }
        }
    }
</script>
<style>
    .div-lista{
        border: 1px solid #ccc;
        padding: 5px
    }
</style>
