<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" top="2vh" append-to-body>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.account_type_id}">
                            <label class="control-label">Tipo de la cuenta <span class="text-danger">*</span></label>
                            <el-select v-model="form.account_type_id">
                                <el-option v-for="option in account_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.account_type_id" v-text="errors.account_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre de la cuenta<span class="text-danger">*</span></label>
                            <el-input v-model="form.name" dusk="name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.number}">
                            <label class="control-label">Número de la cuenta</label>
                            <el-input v-model="form.number" dusk="number"></el-input>
                            <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
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
                        <div class="form-group" :class="{'has-danger': errors.beginning_balance}">
                            <label class="control-label">Saldo Inicial <span class="text-danger">*</span></label>
                            <el-input v-model="form.beginning_balance"></el-input>
                            <small class="form-control-feedback" v-if="errors.beginning_balance" v-text="errors.beginning_balance[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                            <label class="control-label">Fecha <span class="text-danger">*</span></label>
                            <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción</label>
                            <el-input v-model="form.description" dusk="description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
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
                resource: 'accounts',
                errors: {},
                form: {},
                currency_types: [],
                account_types: []
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                        this.currency_types = response.data.currency_types
                        this.account_types = response.data.account_types
                    }
                )
        },
        methods: {
            initForm() {
                this.loading_submit = false,
                    this.errors = {}
                this.form = {
                    id: null,
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    currency_type_id: 'PEN',
                    beginning_balance: 0
                }
            },
            resetForm() {
                this.initForm()
            },
            create() {
                this.titleDialog = (this.recordId) ? 'Editar Banco' : 'Nuevo Banco'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                                this.form = response.data.data
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
