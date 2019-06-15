<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" top="2vh" append-to-body>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row justify-content-center mt-0">
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.type}">
                            <div class="form-group">                                
                                <el-radio v-model="form.type" label="1">Porcentaje</el-radio>
                                <el-radio v-model="form.type" label="2">Valor</el-radio>
                            </div>
                            <small class="form-control-feedback" v-if="errors.type" v-text="errors.type[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre <span class="text-danger">*</span></label>
                            <el-input v-model="form.name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4" v-show="form.type == 1">
                        <div class="form-group" :class="{'has-danger': errors.value}">
                            <label class="control-label">Valor <span class="text-danger">*</span></label>
                            <el-input v-model="form.value"></el-input>
                            <small class="form-control-feedback" v-if="errors.value" v-text="errors.value[0]"></small>
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
                resource: 'price-list',
                errors: {},
                form: {}
            }
        },
        created() {
            this.initForm()
            
        },
        methods: {
            initForm() {
                this.loading_submit = false,
                    this.errors = {}
                this.form = {
                    id: null,
                    name: '',
                    type: '2'
                }
            },
            resetForm() {
                this.initForm()
            },
            create() {
                this.titleDialog = (this.recordId) ? 'Editar Lista de Precio' : 'Nueva Lista de Precio'
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

                let submit = true;

                if(this.form.type == 1){
                    if(this.form.value == '' || this.form.value == null){
                        submit = false;
                        this.$message.error("Debe ingresar el valor");
                        this.loading_submit = false                        
                    }
                }

                if(submit)
                {
                    this.$http.post(`/${this.resource}`, this.form)
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
            }
        }
    }
</script>
