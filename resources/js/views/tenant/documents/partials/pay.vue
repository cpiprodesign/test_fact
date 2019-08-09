<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" append-to-body>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.account_id}">
                            <label class="control-label">Cuenta Bancaria <span class="text-danger">*</span></label>
                            <el-select v-model="form.account_id" filterable>
                                <el-option v-for="option in accounts" :key="option.id" :value="option.id" :label="option.name+' | '+option.account_type.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.account_id" v-text="errors.account_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.payment_method_id}">
                            <label class="control-label">Método de Pago</label>
                            <el-select v-model="form.payment_method_id">
                                <el-option v-for="option in payment_methods" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.payment_method_id" v-text="errors.payment_method_id[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                            <label class="control-label">Fecha <span class="text-danger">*</span></label>
                            <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                            <label class="control-label">Moneda <span class="text-danger">*</span></label>
                            <el-select v-model="form.currency_type_id">
                                <el-option v-for="option in currency_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Total</label>
                            <el-input v-model="form.total0" readonly=""></el-input>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Deuda</label>
                            <el-input v-model="form.total_debt" readonly=""></el-input>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.total}">
                            <label class="control-label">Valor recibido<span class="text-danger">*</span></label>
                            <el-input v-model="form.total"></el-input>
                            <small class="form-control-feedback" v-if="errors.total" v-text="errors.total[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" :class="{'has-danger': errors.description}">
                        <label class="control-label">Descripción</label>
                        <el-input v-model="form.description"></el-input>
                        <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="danger" native-type="submit" :loading="loading_submit">Pagar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'recordId', 'resource'],
        data() {
            return {
                titleDialog: null,
                loading_submit: false,
                //resource: 'documents',
                errors: {},
                form: {},
                currency_types: [],
                payment_methods: [],
                accounts: []
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/payments/tables`)
                .then(response => {
                        this.currency_types = response.data.currency_types
                        this.payment_methods = response.data.payment_methods
                        this.accounts = response.data.accounts
                    }
                )
        },
        methods: {
            initForm() {
                this.loading_submit = false,
                this.errors = {},
                this.form = {
                    payment_method_id: 1,
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    currency_type_id: 'PEN'
                }
            },
            resetForm() {
                this.initForm()
            },
            create() {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        let document = response.data.data
                        if(this.resource == 'documents'){
                            this.form.document_id = document.id
                        }
                        else{
                            this.form.sale_note_id = document.id                            
                        }
                        
                        this.form.total0 = document.total
                        this.form.total_debt = document.total - document.total_paid
                        this.titleDialog = 'Comprobante: '+document.number
                    })
            },
            submit() {
                
                if (this.hasPermissionTo('tenant.documents.agregar-pago')) {     
                    this.loading_submit = true
                    this.$http.post(`/payments`, this.form)
                        .then(response => {
                            if (response.data.success) {
                                this.$message.success(response.data.message)
                                this.$eventHub.$emit('reloadData')
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
                }
            },
            close() {
                this.$emit('update:showDialog', false)
                this.resetForm()
            },
        }
    }
</script>