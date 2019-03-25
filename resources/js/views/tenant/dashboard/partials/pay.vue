<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">¿Seguro que desea pagar?</label>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="danger" native-type="submit" dusk="annulment-voided" :loading="loading_submit">Pagar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                titleDialog: null,
                loading_submit: false,
                resource: null,
                errors: {},
                form: {},
                group_id: null,
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            initForm() {
                this.loading_submit = false,
                this.group_id = null,
                this.errors = {},
                this.form = {
                    date_of_reference: null,
                    summary_status_type_id: '3',
                    documents: [
                        {
                            document_id: null,
                            description: null,
                        }
                    ]
                }
            },
            create() {
                this.$http.get(`/documents/record/${this.recordId}`)
                    .then(response => {
                        let document = response.data.data
                        this.group_id = document.group_id
                        this.form.date_of_reference = document.date_of_issue
                        this.form.documents[0].document_id = document.id
                        this.titleDialog = 'Comprobante: '+document.number
                    })
            },
            submit() {
                this.loading_submit = true
                this.$http.get(`/documents/cambiar_estado_pago/${this.recordId}`)
                    .then(response => {
                        if (response) {
                            this.$eventHub.$emit('reloadData')
                            this.$message.success("Proceso exitoso")
                            this.close()
                        } else {
                            this.$message.error("Ocurrió un error")
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors
                        } else {
                            this.$message.error(error.response.data.message)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                location.href = '/dashboard'
            },
        }
    }
</script>