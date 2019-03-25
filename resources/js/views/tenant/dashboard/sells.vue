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
                                    <h5 class="font-weight-semibold mt-0">S/. {{ total_invoices }}</h5>
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
                                    <th class="text-center">Fecha Emisión</th>
                                    <th>Cliente</th>
                                    <th>Número</th>
                                    <th>Estado</th>
                                    <th>Estado de Pago</th>
                                    <th class="text-center">Moneda</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody v-for="(row,index) in sells" :key="row.id">
                                <tr>
                                    <td>{{ row.created_at }}</td>
                                    <td>{{ row.customer.name }}</td>
                                    <td>
                                        {{ row.series }}-{{ row.number }}
                                        <small>{{row.document_type_description}}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary text-white" :class="{
                                            'bg-danger': (row.state_type.id === '11'),
                                            'bg-warning': (row.state_type.id === '13'),
                                            'bg-secondary': (row.state_type.id === '01'),
                                            'bg-info': (row.state_type.id === '03'),
                                            'bg-success': (row.state_type.id === '05'),
                                            'bg-secondary': (row.state_type.id === '07'),
                                            'bg-dark': (row.state_type.id === '09')
                                        }">{{ row.state_type.description }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary text-white bg-success" v-if="row.status_paid == 1">Pagado</span>
                                        <span class="badge bg-secondary text-white bg-dark" v-if="row.status_paid == 0">Pendiente</span>
                                    </td>
                                    <td class="text-center">{{ row.currency_type_id }}</td>
                                    <td class="text-right">{{ row.total }}</td>
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
                establishments: [],
                establishment_id: 0,
                total_invoices: 0,
                total_charges: 0,
                total: 0
            }
        },
        async mounted() {
        //     this.loaded = false
        //   await this.load_grafic() 
        //    this.loaded = true
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
                this.$http.get(`/${this.resource}/load_sells/${this.establishment_id}/${this.range_id}`)
                    .then(response => {
                        this.sells = response.data.sells
                        this.total_invoices = response.data.total_invoices
                        this.total_charges = response.data.total_charges
                        this.total = this.total_invoices + this.total_charges
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
