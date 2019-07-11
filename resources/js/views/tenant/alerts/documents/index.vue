<template>
    <div class="table-responsive">
        <small>** Se listan las boletas, facturas, notas de crédito y notas de débito</small>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>N° Cliente</th>
                <th>Fecha</th>
                <th>N° Documento</th>
                <th>Total</th>
                <th class="text-center">Días para vencer</th>
                <th>Enviar</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(row, index) in records">
                <td>{{ index + 1 }}</td>
                <td>{{ row.name }}</td>
                <td>{{ row.customer_number }}</td>
                <td>{{ row.date_of_issue }}</td>
                <td>{{ row.series }} - {{ row.number }}</td>
                <td>{{ row.total }}</td>
                <td class="text-center">
                    <span v-if="row.diferent < 0">Su comprobante ya venció</span>
                    <span v-else>{{ row.diferent }}</span>
                </td>
                <td class="">
                    <el-tooltip class="item" effect="dark" v-if="row.document_type_id == '03'" content="Boletas se envian como Resúmenes" placement="top-end">
                        <button type="button" class="btn btn-xs"><i class="fa fa-file-export i-icon text-disabled"></i></button>
                    </el-tooltip>
                    <el-tooltip class="item" effect="dark" v-else="" content="Enviar a SUNAT/OSE" placement="top-end">
                        <button type="button" class="btn btn-xs" @click.prevent="clickResend(row.id)"><i class="fa fa-file-export i-icon text-danger"></i></button>                                
                    </el-tooltip>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                showDialog: false,
                resource: 'alerts/documents',
                recordId: null,
                records: [],
            }
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
            this.getData()
        },
        methods: {
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.records
                    })
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
            }
        }
    }
</script>