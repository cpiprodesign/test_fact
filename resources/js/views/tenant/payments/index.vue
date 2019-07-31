<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Pagos Recibidos</span></li>
            </ol>
            <!-- <div class="right-wrapper pull-right">
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div> -->
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Pagos</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Detalle</th>
                        <th>Fecha</th>
                        <th>Cuenta</th>
                        <th>Método de Pago</th>
                        <th>Total</th>
                        <th>Descripción</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody">
                        <td>{{ index }}</td>
                        <td>{{ row.customer }}</td>
                        <td>{{ row.number }}</td>
                        <td>{{ row.date_of_issue }}</td>
                        <td>{{ row.account }}</td>
                        <td>{{ row.payment_method }}</td>
                        <td>{{ row.total }}</td>
                        <td>{{ row.description }}</td>
                        <td class="text-right">
                            <el-tooltip class="item" effect="dark" content="Eliminar" placement="top-end">
                                <button v-show="hasPemissionTo('tenant.payments.destroy')" type="button" class="btn btn-xs" @click.prevent="clickDelete(row.id)" v-if="row.has_delete"><i class="fa fa-trash-alt i-icon text-danger"></i></button>
                                <button v-show="hasPemissionTo('tenant.payments.destroy')" type="button" class="btn btn-xs" v-else><i class="fa fa-trash-alt i-icon text-disabled"></i></button>
                            </el-tooltip>
                        </td>
                    </tr>
                    <div class="row col-md-12 justify-content-center" slot-scope="{ totals }" slot="totals">
                        <div class="col-md-4">
                            <h5><strong>Total de pagos recibidos soles ({{ totals.totalPEN.quantity}}) </strong>S/. {{ totals.totalPEN.total }}</h5>
                        </div>
                        <div class="col-md-4">
                            <h5><strong>Total de pagos recibidos dólares ({{ totals.totalUSD.quantity}}) </strong>$ {{ totals.totalUSD.total }}</h5>
                        </div>                        
                    </div>
                </data-table>
            </div>
        </div>
    </div>
</template>
<script>

    import ItemsForm from './form.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [deletable],
        components: {ItemsForm, DataTable},
        data() {
            return {
                showDialog: false,                
                resource: 'payments',
                recordId: null,
            }
        },
        created() {
        },
        methods: {
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
