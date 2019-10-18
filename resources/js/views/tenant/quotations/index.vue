<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Cotizaciones</span></li>
            </ol>
            <div v-show="hasPermissionTo('tenant.quotations.store')" class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body ">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Número</th>
                        <th>Estado</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right">Total</th>
                        <!-- <th class="text-center">Descargas</th> -->
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
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td>{{ row.number }}</td>
                        <td>
                            <span class="badge bg-secondary text-white" :class="{
                            'bg-danger': (row.state_type_id === '11'),
                            'bg-warning': (row.state_type_id === '13'),
                            'bg-secondary': (row.state_type_id === '01'),
                            'bg-info': (row.state_type_id === '03'),
                            'bg-success': (row.state_type_id === '05'),
                            'bg-secondary': (row.state_type_id === '07'),
                            'bg-dark': (row.state_type_id === '09')
                            }">{{ row.state_type_description }}</span>
                        </td>
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td class="text-right">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn waves-effect waves-light btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Opciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <a v-show="hasPermissionTo('tenant.documents.store')" v-if="row.state_type_id==1" class="dropdown-item" :href="`/documents/create2/`+row.id">Crear venta</a>
                                        <a v-show="hasPermissionTo('tenant.quotations.update')" v-if="row.state_type_id==1" class="dropdown-item" :href="`/quotations/edit/`+row.id">Editar</a>
                                        <a v-show="hasPermissionTo('tenant.quotations.report')" class="dropdown-item" :href="`/download/Quotation/pdf/`+row.id">Descargar PDF</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <div class="row col-md-12 justify-content-center" slot-scope="{ totals }" slot="totals">
                        <div class="col-md-3">
                            <h5><strong>Total cotizado en soles ({{ totals.totalPEN.quantity}}) </strong>S/. {{ totals.totalPEN.total }}</h5>
                        </div>
                        <div class="col-md-3">
                            <h5><strong>Total cotizado en dólares ({{ totals.totalUSD.quantity}}) </strong>$ {{ totals.totalUSD.total }}</h5>
                        </div>
                    </div>
                </data-table>
            </div>

            <quotations-voided :showDialog.sync="showDialogVoided"
                            :recordId="recordId"></quotations-voided>

            <quotation-options :showDialog.sync="showDialogOptions"
                              :recordId="recordId"
                              :showClose="true"></quotation-options>
        </div>
    </div>
</template>

<script>

    import QuotationsVoided from './partials/voided.vue'
    import QuotationOptions from './partials/options.vue'
    import DataTable from '../../../components/DataTable.vue'

    export default {
        components: {QuotationsVoided, QuotationOptions, DataTable},
        data() {
            return {
                showDialogVoided: false,
                resource: 'quotations',
                recordId: null,
                showDialogOptions: false
            }
        },
        created() {
        },
        methods: {
            clickVoided(recordId = null) {
                this.recordId = recordId
                this.showDialogVoided = true
            },
            clickDownload(download) {
                window.open(download);
            },
            clickCreateSale(download) {
                window.open(download, '_blank');
            },
            clickResend(quotation_id) {
                this.$http.get(`/${this.resource}/send/${quotation_id}`)
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
