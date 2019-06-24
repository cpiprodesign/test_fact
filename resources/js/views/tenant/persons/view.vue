<template>
    <div>
        <div class="card mb-0">
            <div class="card-body ">
                <data-table1 :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Cliente</th>
                        <th class="text-center">Creación</th>
                        <th>Número</th>
                        <th class="text-right">Moneda</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Pagado</th>
                        <th class="text-right">Por pagar</th>
                        <th class="text-right">Estado SUNAT</th>
                        <th class="text-right">Estado</th>
                        <th class="text-center">Descargas</th>
                        <!--<th class="text-center">Anulación</th>-->
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
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.number }}<br/>
                            <small v-text="row.document_type_description"></small><br/>
                            <small v-if="row.affected_document" v-text="row.affected_document"></small>
                        </td>
                        <td class="text-right">{{ row.currency_type_id }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td class="text-right">{{ row.total_paid }}</td>
                        <td class="text-right">{{ row.total - row.total_paid }}</td>
                        <td><span class="badge bg-secondary text-white" :class="{
                            'bg-danger': (row.state_type_id === '11'),
                            'bg-warning': (row.state_type_id === '13'),
                            'bg-secondary': (row.state_type_id === '01'),
                            'bg-info': (row.state_type_id === '03'),
                            'bg-success': (row.state_type_id === '05'),
                            'bg-secondary': (row.state_type_id === '07'),
                            'bg-dark': (row.state_type_id === '09')
                        }">{{ row.state_type_description }}</span></td>
                        <td>
                            <span class="badge bg-secondary text-white bg-success" v-if="row.total - row.total_paid == 0">Pagado</span>
                            <span class="badge bg-secondary text-white bg-warning" v-if="row.total - row.total_paid > 0">Pendiente</span>                            
                        </td>
                        <td class="text-center">
                            <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickDownload(row.download_pdf)"
                                    v-if="row.has_pdf">PDF</button>
                           
                        </td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-warning m-1__2" @click.prevent="clickPay(row.id)" v-if="row.total - row.total_paid > 0">Agregar Pago</button>                            
                        </td>
                    </tr>
                </data-table1>
            </div>

            <documents-pay :showDialog.sync="showDialogPay"
                            :recordId="recordId" :resource="resource"></documents-pay>
        </div>
    </div>
</template>

<script>    
    import DocumentsPay from '../documents/partials/pay.vue'
    import DataTable1 from '../../../components/DataTable.vue'

    export default {
        props: ['id'],
        components: {DataTable1, DocumentsPay},
        data() {
            return {
                showDialogPay: false,
                resource: 'documents',
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
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
        }
    }
</script>
