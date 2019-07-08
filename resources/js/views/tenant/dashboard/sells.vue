<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Dashboard</span> </li>
                <li><span class="text-muted">Ventas</span></li> 
            </ol>
            <div class="right-wrapper pull-right">
                <div class="row">
                    <div class="col-md-4">
                        <el-select v-model="range_id" @change="changeRange" class="mt-2 mr-2">
                            <el-option v-for="option in range" :key="option.id" :value="option.id" :label="option.id"></el-option>
                        </el-select>
                    </div>
                    <div class="col-md-6">
                        <el-select v-model="establishment_id" @change="changeEstablishment" class="mt-2 mr-2">
                    <el-option :key="0" :value="0" :label="'Todos los establecimientos'"></el-option>
                    <el-option v-for="option in establishments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                </el-select>
                    </div>
                </div> 
                
               
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <div class="row" v-if="loaded">
                    <div class="col-md-12">
                        <div class="row">
                        <div class="col-md-4">
                            <section class="card card-horizontal card-tenant-dashboard">
                                <header class="card-header bg-info">
                                    <div class="card-header-icon"><i class="fas fa-shopping-cart"></i></div>
                                </header>
                                <div class="card-body p-4 text-center">
                                    <p class="font-weight-semibold mb-0 mx-4">Total Vendido</p>
                                    <h5 class="font-weight-semibold mt-0">S/. {{ total }}</h5>                                   
                                </div>
                            </section>
                        </div>
                        <div class="col-md-4">
                            <section class="card card-horizontal card-tenant-dashboard">
                                <header class="card-header bg-success">
                                    <div class="card-header-icon"><i class="fas fa-donate"></i></div>
                                </header>
                                <div class="card-body p-4 text-center">
                                    <p class="font-weight-semibold mb-0 mx-4">Total Pagados</p>
                                    <h5 class="font-weight-semibold mt-0">S/. {{ total_paid }}</h5>
                                </div>
                            </section>
                        </div>
                        <div class="col-md-4">
                            <section class="card card-horizontal card-tenant-dashboard">
                                <header class="card-header bg-warning">
                                    <div class="card-header-icon"><i class="fas fa-address-book"></i></div>
                                </header>
                                <div class="card-body p-4 text-center">
                                    <p class="font-weight-semibold mb-0 mx-4">Total Pendientes</p>
                                    <h5 class="font-weight-semibold mt-0">S/. {{ total_charges }}</h5>
                                </div>
                            </section>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h5> Ventas</h5>                                
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha Emisión</th>
                                    <th>Tipo</th>
                                    <th>Documento Cliente</th>
                                    <th>Cliente</th>
                                    <th>N° Documento</th>
                                    <th class="text-center">Moneda</th>
                                    <th>Total</th>
                                    <th>Total Pagado</th>
                                    <th>Pendiente</th>
                                </tr>
                            </thead>
                            <tbody v-for="(row,index) in sells">
                                <tr>
                                    <td>{{ row.created_at }}</td>
                                    <td>{{ row.type }}</td>
                                    <td>{{ row.customer_number }}</td>
                                    <td>{{ row.name }}</td>
                                    <td>{{ row.series }}-{{ row.number }}</td>
                                    <td  class="text-center">{{ row.currency_type_id }}</td>
                                    <td>{{ row.total }}</td>
                                    <td>{{ row.total_paid }}</td>
                                    <td>{{ row.total - row.total_paid }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>   
    export default {
        data() {
            return {
                resource: 'dashboard',
                loaded: false,
                showDialogPay: false,
                recordId: null,
                documents: [],
                range_id: "Diario",
                range: [
                    {"id": "Diario"}, 
                    {"id": "Mensual"},
                    {"id": "Anual"}
                ], 
                sells: [],
                establishments: [],
                establishment_id: 0,
                total_paid: 0,
                total_charges: 0,
                total: 0
            }
        },
        created() {
            this.loaded = false

            this.$http.get(`/${this.resource}/establishments/`)
                .then(response => {
                    this.establishments = response.data.establishments
            })

            this.load()
            
            this.loaded = true
        },
        methods: {
            load() {
                this.$http.get(`/${this.resource}/load_sells/${this.establishment_id}/${this.range_id}`)
                    .then(response => {
                        this.sells = response.data.sells
                        this.total = response.data.total
                        this.total_paid = response.data.total_paid
                        this.total_charges = response.data.total - response.data.total_paid
                })
            },
            changeEstablishment() {
                this.loaded = false
                this.load()
                this.loaded = true
            },
            changeRange() {
                this.loaded = false
                this.load()
                this.loaded = true
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
