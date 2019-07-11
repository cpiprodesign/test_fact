<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Notas de Venta</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body ">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Cliente</th>
                        <th>N° Documento</th>
                        <th class="text-center">Creación</th>
                        <th>Número</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Pagado</th>
                        <th class="text-right">Por pagar</th>
                        <th class="text-right">Estado Pago</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody">
                        <td>{{ index }}</td>
                        <td>{{ row.customer_name }}</td>
                        <td>{{row.customer_number}}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.number }}</td>
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td class="text-right">{{ row.total_paid }}</td>
                        <td class="text-right">{{ row.total_to_pay }}</td>
                        <td class="text-right">
                            <span class="badge bg-secondary text-white bg-warning" v-if="row.total_to_pay > 0">Pendiente</span>
                            <span class="badge bg-secondary text-white bg-success" v-else>Pagado</span>
                        </td>
                        <td class="text-right">
                            <el-tooltip class="item" effect="dark" content="Agregar pago" placement="top-end">
                                <button type="button" class="btn btn-xs" @click.prevent="clickPay(row.id)" v-if="row.total_to_pay > 0"><i class="fa fa-money-bill-wave i-icon text-warning"></i></button>
                                <button type="button" class="btn btn-xs" v-else="" disabled><i class="fa fa-money-bill-wave i-icon text-disabled"></i></button>
                            </el-tooltip>
                            <el-tooltip class="item" effect="dark" content="Descargar Pdf" placement="top-end">
                                <a :href="`/download/salenote/pdf/`+row.id" class="btn btn-xs"><i class="fa fa-file-pdf i-icon text-info"></i></a>
                            </el-tooltip>
                            <el-tooltip class="item" effect="dark" content="Eliminar" placement="top-end">
                                <button type="button" class="btn btn-xs" @click.prevent="clickDelete(row.id)" v-if="row.has_delete"><i class="fa fa-trash-alt i-icon text-danger"></i></button>
                                <button type="button" class="btn btn-xs" v-else><i class="fa fa-trash-alt i-icon text-disabled"></i></button>
                            </el-tooltip>
                        </td>
                    </tr>
                    <div class="row col-md-12 justify-content-center" slot-scope="{ totals }" slot="totals">
                        <div class="col-md-3">
                            <h5><strong>Total de nota de ventas en soles </strong>S/. {{ totals.total.total }}</h5>
                        </div>
                        <div class="col-md-3">
                            <h5><strong>Total pagado en soles </strong>S/. {{ totals.total.total_paid }}</h5>
                        </div>
                        <div class="col-md-3">
                            <h5><strong>Total por cobrar en soles </strong>S/. {{ totals.total.total_to_pay }}</h5>
                        </div>
                    </div>
                </data-table>
            </div>
            <documents-pay :showDialog.sync="showDialogPay"
                            :recordId="recordId" :resource="resource"></documents-pay>
        </div>
    </div>
</template>

<script>

    import DataTable from '../../../components/DataTable.vue'
    import DocumentsPay from '../documents/partials/pay.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [deletable],
        components: {DataTable, DocumentsPay},
        data() {
            return {
                showDialogPay: false,
                resource: 'sale-notes',
                recordId: null
            }
        },
        created() {
        },
        methods: {
            clickPay(recordId = null) {
                this.recordId = recordId
                this.showDialogPay = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
