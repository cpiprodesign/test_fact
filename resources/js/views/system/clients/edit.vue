<template>
    <el-dialog :title="titleDialog" :visible="showDialogEdit" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <!-- <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label class="control-label">Correo de Acceso</label>
                            <el-input v-model="form.email" dusk="email"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>
                </div> -->
                <div class="row">                    
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.plan_id}">
                            <label class="control-label">Plan</label>
                            <el-select v-model="form.plan_id" dusk="plan_id">
                                <el-option v-for="option in plans" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.plan_id" v-text="errors.plan_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.plan_id}">
                            <label class="control-label">SOAP Envio</label>
                            <el-select v-model="form.soap_send_id">
                                <el-option v-for="(option, index) in soap_sends" :key="index" :value="index" :label="option"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.plan_id" v-text="errors.plan_id[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit" dusk="submit">Editar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    import {serviceNumber} from '../../../mixins/functions'

    export default {
        mixins: [serviceNumber],
        props: ['showDialogEdit', 'recordId'],
        data() {
            return {
                loading_submit: false,
                loading_search: false,
                titleDialog: null,
                resource: 'clients',
                error: {},
                form: {},
                url_base: null,
                plans:[],
                soap_sends: []
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.url_base = response.data.url_base
                    this.plans = response.data.plans
                    this.soap_sends = response.data.soap_sends
                })
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    name: null,
                    email: null,
                    identity_document_type_id: '6',
                    number: '',
                    password:null,
                    plan_id:null
                }
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar Cliente':'Nuevo Cliente'
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
                this.$http.post(`${this.resource}/update/${this.recordId}`, this.form)
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
                            console.log(error.response)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialogEdit', false)
                this.initForm()
            },
            searchSunat() {
                this.searchServiceNumber()
            }
        }
    }
</script>