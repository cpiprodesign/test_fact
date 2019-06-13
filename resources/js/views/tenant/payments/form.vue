<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" top="2vh" append-to-body>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.has_voucher}">
                            <div class="form-group">
                                <el-radio v-model="form.has_voucher" label="0">Sin comprobante</el-radio>
                                <el-radio v-model="form.has_voucher" label="1">Con comprobante</el-radio>
                            </div>
                            <small class="form-control-feedback" v-if="errors.has_voucher" v-text="errors.has_voucher[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                            <label class="control-label">Fecha de emisión <span class="text-danger">*</span></label>
                            <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción <span class="text-danger">*</span></label>
                            <el-input v-model="form.description" dusk="description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description"
                                   v-text="errors.description[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">                    
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.total}">
                            <label class="control-label">Monto <span class="text-danger">*</span></label>
                            <el-input v-model="form.total"></el-input>
                            <small class="form-control-feedback" v-if="errors.total"
                                   v-text="errors.total[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.detail}">
                            <label class="control-label">Detalle</label>
                            <el-input type="textarea" :rows="3" placeholder="Detalle..." v-model="form.detail" maxlength="500"></el-input>
                            <small class="form-control-feedback" v-if="errors.detail" v-text="errors.detail[0]"></small>
                        </div>
                    </div>
                </div>
                <div v-show="form.has_voucher == 1">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-">Comprobante</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" :class="{'has-danger': errors.company_number}">
                                <label class="control-label">Ruc</label>
                                <el-input v-model="form.detail_voucher.company_number"></el-input>
                                <small class="form-control-feedback" v-if="errors.company_number"
                                    v-text="errors.company_number[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nombre de la empresa</label>
                                <el-input v-model="form.detail_voucher.company_name"></el-input>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Tipo de comprobante</label>
                                <el-select v-model="form.detail_voucher.document_type">
                                    <el-option :key="1" :value="1" :label="'Boleta'"></el-option>
                                    <el-option :key="2" :value="2" :label="'Factura'"></el-option>
                                </el-select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">N° de documento</label>
                                <el-input v-model="form.detail_voucher.document_number"></el-input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'payments',
                errors: {},
                form: {},
                currency_types: []
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                        this.currency_types = response.data.currency_types
                    }
                )
        },
        methods: {
            initForm() {
                this.loading_submit = false,
                    this.errors = {}
                this.form = {
                    id: null,
                    has_voucher: '0',
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    currency_type_id: 'PEN',
                    detail_voucher: {
                        company_name: '',
                        document_type: 1,
                        company_number: '',
                        document_number: ''
                    }
                }
            },
            resetForm() {
                this.initForm()
            },
            create() {
                this.titleDialog = (this.recordId) ? 'Editar Gasto' : 'Nuevo Gasto'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                                this.form = response.data.data
                                this.form.has_voucher = ''+response.data.data.has_voucher
                                // this.form.detail_voucher_document_type = response.data.data.detail_voucher.document_type
                                // this.form.detail_voucher_company_name = response.data.data.detail_voucher.company_name
                                // this.form.detail_voucher_document_number = response.data.data.detail_voucher.document_number
                            }
                        )
                }
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            // if (this.external) {
                            //     this.$eventHub.$emit('reloadDataItems', response.data.id)
                            // } else {
                                this.$eventHub.$emit('reloadData')
                            // }
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.resetForm()
            }
        }
    }
</script>
