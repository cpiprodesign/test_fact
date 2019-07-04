<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Comprobantes</span> </li>
                <li><span class="text-muted">Facturas - Notas <small>(crédito y débito)</small> - Boletas - Anulaciones</small></span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="data-table-visible-columns">
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columns" :key="index">
                            <el-checkbox v-model="column.visible">{{ column.title }}</el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body ">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th class="text-center">#</th>
                        <th>Cliente</th>
                        <th>N° Documento</th>
                        <th class="text-center">Creación</th>
                        <th>Número</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Pagado</th>
                        <th class="text-right">Por pagar</th>
                        <th class="text-right">Estado SUNAT/OSE</th>
                        <th class="text-center">Estado</th>
                        <!--<th class="text-center">Anulación</th>-->
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody" :class="{
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
                        <td class="text-center">{{ index }}</td>
                        <td>{{ row.customer_name }}</td>
                        <td>{{row.customer_number}}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.number }}</td>
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td class="text-right">
                            <span>{{ row.total_paid }}</span>
                        </td>
                        <td class="text-right">
                            <span>{{ row.total_to_pay }}</span>
                        </td>
                        <td class="text-right"><span class="badge bg-secondary text-white" :class="{
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
                        <td class="text-right">
                            <el-tooltip class="item" effect="dark" content="Enviar a SUNAT/OSE" placement="top-end">
                                <button type="button" class="btn btn-xs" @click.prevent="clickResend(row.id)" v-if="row.btn_resend"><i class="fa fa-file-export i-icon text-danger"></i></button>
                                <button type="button" class="btn btn-xs" v-else=""><i class="fa fa-file-export i-icon text-disabled"></i></button>
                            </el-tooltip>
                            <el-tooltip class="item" effect="dark" content="Visualizar" placement="top-end">
                                <a :href="`/${resource}/view/${row.id}`" class="btn btn-xs"><i class="fa fa-eye i-icon text-info"></i></a>
                            </el-tooltip>
                            <el-tooltip class="item" effect="dark" content="Nota de Crédito/Debito" placement="top-end">
                                <a :href="`/${resource}/note/${row.id}`" class="btn btn-xs" v-if="row.btn_note"><i class="fa fa-file-signature i-icon text-danger"></i></a>
                                <a class="btn btn-xs" v-else=""><i class="fa fa-file-signature i-icon text-disabled"></i></a>
                            </el-tooltip>
                            <el-tooltip class="item" effect="dark" content="Guía de Remisión" placement="top-end">
                                <a :href="`/dispatches/create2/${row.id}`" class="btn btn-xs" v-if="row.btn_note"><i class="fa fa-clipboard-check i-icon text-success"></i></a>
                                <a class="btn btn-xs" v-else=""><i class="fa fa-clipboard-check i-icon text-disabled"></i></a>
                            </el-tooltip>
                            <el-tooltip class="item" effect="dark" content="Agregar pago" placement="top-end">
                                <button type="button" class="btn btn-xs" @click.prevent="clickPay(row.id)" v-if="row.total - row.total_paid > 0"><i class="fa fa-money-bill-wave i-icon text-warning"></i></button>
                                <button type="button" class="btn btn-xs" v-else="" disabled><i class="fa fa-money-bill-wave i-icon text-disabled"></i></button>
                            </el-tooltip>
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn waves-effect waves-light btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Descargar
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <button type="button" @click.prevent="clickDownload(row.download_xml)" v-if="row.has_xml" class="dropdown-item">XML</button>
                                        <a @click.prevent="clickDownload(row.download_pdf)" v-if="row.has_pdf" class="dropdown-item">PDF</a>
                                        <a @click.prevent="clickDownload(row.download_cdr)" v-if="row.has_cdr" class="dropdown-item">CDR</a>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info m-1__2" @click.prevent="clickOptions(row.id)">Opciones</button>
                        </td>
                    </tr>
                    <div class="row col-md-12 justify-content-center" slot-scope="{ totals }" slot="totals">
                        <div class="col-md-3">
                            <h5><strong>Total de ventas en soles </strong>S/. {{ totals.total.total }}</h5>
                            <h5><strong>Total pagado en soles </strong>S/. {{ totals.total.total_paid }}</h5>
                            <h5><strong>Total por cobrar en soles </strong>S/. {{ totals.total.total_to_pay }}</h5>
                        </div>
                        <div class="col-md-4">
                            <h5><strong>Total factura emitidas en soles ({{ totals.total01.quantity}}) </strong>S/. {{ totals.total01.total }}</h5>
                            <h5><strong>Total boletas emitidas en soles ({{ totals.total03.quantity}}) </strong>S/. {{ totals.total03.total }}</h5>
                        </div>
                        <div class="col-md-3">
                            <h5 v-for="row in totals.total_state_types"><strong>Total estado{{ row.description}} SUNAT/OSE </strong>{{ row.quantity }}</h5>
                        </div>
                    </div>
                </data-table>
            </div>
            
            <documents-voided :showDialog.sync="showDialogVoided"
                            :recordId="recordId"></documents-voided>

            <document-options :showDialog.sync="showDialogOptions"
                              :recordId="recordId"
                              :showClose="true"></document-options>
            <documents-pay :showDialog.sync="showDialogPay"
                            :recordId="recordId" :resource="resource"></documents-pay>
        </div>
    </div>
</template>

<script>

    import DocumentsVoided from './partials/voided.vue'
    import DocumentsPay from './partials/pay.vue'
    import DocumentOptions from './partials/options.vue'
    import DataTable from '../../../components/DataTable.vue'

    export default {
        components: {DocumentsVoided, DocumentOptions, DataTable, DocumentsPay},
        data() {
            return {
                showDialogVoided: false,
                showDialogPay: false,
                resource: 'documents',
                recordId: null,
                showDialogOptions: false,
                columns: {
                    total_exportation: {
                        title: 'T.Exportación',
                        visible: false
                    },
                    total_free: {
                        title: 'T.Gratuito',
                        visible: false
                    },
                    total_unaffected: {
                        title: 'T.Inafecto',
                        visible: false
                    },
                    total_exonerated: {
                        title: 'T.Exonerado',
                        visible: false
                    },
                }
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
//            clickTicket(voided_id, group_id) {
//                this.$http.get(`/voided/ticket/${voided_id}/${group_id}`)
//                    .then(response => {
//                        if (response.data.success) {
//                            this.$message.success(response.data.message)
//                            this.getData()
//                        } else {
//                            this.$message.error(response.data.message)
//                        }
//                    })
//                    .catch(error => {
//                        this.$message.error(error.response.data.message)
//                    })
//            },
            clickDownload(download) {
                window.open(download, '_blank');
            },
            clickResend(document_id) {
                this.$http.get(`/${this.resource}/send/${document_id}`)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        this.$message.error(error.response.data.message)
                    })
            },

            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
        }
    }
</script>
