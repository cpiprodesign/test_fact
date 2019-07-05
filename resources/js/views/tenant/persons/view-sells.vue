<template>
    <div>
        <div class="card mb-0">
            <div class="card-body ">
                <data-table1 :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Creación</th>
                        <th>Número</th>
                        <th class="text-right">Moneda</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Pagado</th>
                        <th class="text-right">Por pagar</th>
                        <th class="text-right">Estado SUNAT</th>
                        <th class="text-right">Estado</th>
                        <th class="text-center">Descargas</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" :class="{
                                                    'text-danger': (row.state_type_id === '11'),
                                                    'text-warning': (row.state_type_id === '13'),
                                                    'border-light': (row.state_type_id === '01'),
                                                    'border-left border-info': (row.state_type_id === '03'),
                                                    'border-left border-success': (row.state_type_id === '05'),
                                                    'border-left border-secondary': (row.state_type_id === '07'),
                                                    'border-left border-dark': (row.state_type_id === '09'),
                                                    'border-left border-danger': (row.state_type_id === '11'),
                                                    'border-left border-warning': (row.state_type_id === '13')
                    }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.number }}<br/>
                            <small v-text="row.document_type_description"></small><br/>
                            <small v-if="row.affected_document" v-text="row.affected_document"></small>
                        </td>
                        <td class="text-right">{{ row.currency_type_id }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td class="text-right">{{ row.total_paid }}</td>
                        <td class="text-right">{{ row.total_to_pay }}</td>
                        <td><span class="badge bg-secondary text-white" :class="{
                            'bg-danger': (row.state_type_id === '11'),
                            'bg-warning': (row.state_type_id === '13'),
                            'bg-secondary': (row.state_type_id === '01'),
                            'bg-info': (row.state_type_id === '03'),
                            'bg-success': (row.state_type_id === '05'),
                            'bg-secondary': (row.state_type_id === '07'),
                            'bg-dark': (row.state_type_id === '09')
                        }">{{ row.state_type_description }}</span></td>
                        <td class="text-right">
                            <span class="badge bg-secondary text-white bg-success" v-if="row.total_to_pay == 0">Pagado</span>
                            <span class="badge bg-secondary text-white bg-warning" v-if="row.total_to_pay > 0">Pendiente</span>
                        </td>
                        <td class="text-center">
                            <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickDownload(row.download_pdf)"
                                    v-if="row.has_pdf">PDF</button>
                           
                        </td>
                        <td class="text-right">
                            <el-tooltip class="item" effect="dark" content="Agregar pago" placement="top-end">
                                <button type="button" class="btn btn-xs" @click.prevent="clickPay(row.id)" v-if="row.total - row.total_paid > 0"><i class="fa fa-money-bill-wave i-icon text-warning"></i></button>
                                <button type="button" class="btn btn-xs" v-else="" disabled><i class="fa fa-money-bill-wave i-icon text-disabled"></i></button>
                            </el-tooltip>
                        </td>
                    </tr>
                </data-table1>
            </div>

            <documents-pay :showDialog.sync="showDialogPay"
                            :recordId="recordId" :resource="'documents'"></documents-pay>
        </div>
    </div>
</template>

<script>
    import DocumentsPay from '../documents/partials/pay.vue'
    import DataTable1 from '../../../components/DataTable1.vue'

    export default {
        props: ['id'],
        components: {DataTable1, DocumentsPay},
        data() {
            return {
                showDialogPay: false,
                resource: 'persons/customers/view/'+this.id+'/sells',
                recordId: null
            }
        },
        created() {
        },
        methods: {
            clickVoided(recordId = null) {
                this.recordId = recordId
                this.showDialogVoided = true
            },
            clickPay(recordId = null) {
                this.recordId = recordId
                this.showDialogPay = true
            },
            clickDownload(download) {
                window.open(download, '_blank');
            },
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
        }
    }
</script>
