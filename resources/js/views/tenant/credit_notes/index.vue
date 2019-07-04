<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Notas de Crédito/Débito</span></li>
            </ol>            
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Notas de Crédito/Débitoto</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th class="text-center">#</th>
                        <th>Cliente</th>
                        <th>N° Documento</th>
                        <th class="text-center">Creación</th>
                        <th>Número</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Estado SUNAT/OSE</th>
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
                            <el-tooltip class="item" effect="dark" content="Enviar a Sunat/OSE" placement="top-end">
                                <button type="button" class="btn btn-xs" @click.prevent="clickResend(row.id)" v-if="row.btn_resend"><i class="fa fa-file-export i-icon text-danger"></i></button>
                                <button type="button" class="btn btn-xs" v-else=""><i class="fa fa-file-export i-icon text-disabled"></i></button>
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
                        <div class="col-md-4">
                            <h5><strong>Total notas de créditos emitidas en soles ({{ totals.total07.quantity}}) </strong>S/. {{ totals.total07.total }}</h5>
                            <h5><strong>Total notas de débito en soles ({{ totals.total08.quantity}}) </strong>S/. {{ totals.total08.total }}</h5>
                        </div>
                        <div class="col-md-3">
                            <h5 v-for="row in totals.total_state_types"><strong>Total estado {{ row.description}} SUNAT/OSE </strong>{{ row.quantity }}</h5>
                        </div>
                    </div>                  
                </data-table>
            </div>
            <document-options :showDialog.sync="showDialogOptions"
                              :recordId="recordId"
                              :showClose="true"></document-options>
        </div>
    </div>
</template>
<script>

    import DataTable from '../../../components/DataTable.vue'
    import DocumentOptions from '../documents/partials/options.vue'
    
    export default {
        components: {DataTable, DocumentOptions},
        data() {
            return {
                showDialog: false,                
                resource: 'credit-notes',
                recordId: null,
                showDialogOptions: false
            }
        },
        created() {
        },
        methods: {
            clickDownload(download) {
                window.open(download, '_blank');
            },
            clickResend(document_id) {
                this.$http.get(`/documents/send/${document_id}`)
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
            }
        }
    }
</script>
