<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre</label>
                            <el-input v-model="form.name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción</label>
                            <el-input v-model="form.description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                        </div>
                    </div>
                   
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.slug}">
                            <label class="control-label">Identificador</label>
                            <el-input v-model="form.slug"></el-input>
                            <small class="form-control-feedback" v-if="errors.slug" v-text="errors.slug[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.special}">
                            <!-- <label class="control-label">Especial</label> -->
                            <el-radio @change="onChange_radioSpecial" v-model="form.special" label="all-access">Acceso total</el-radio>
                            <el-radio @change="onChange_radioSpecial" v-model="form.special" label="no-access">Sin acceso</el-radio>
                            <el-radio @change="onChange_radioSpecial" v-model="form.special" label="custom">Personalizado</el-radio>
                            
                            <small class="form-control-feedback" v-if="errors.special" v-text="errors.special[0]"></small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12" >
                        <div class="form-group ">
                            <label class="control-label">Permisos</label>
                            <div class="row">
                                <div class="col-4" v-for="permiso in form.permisos">
                                    <el-checkbox v-model="permiso.checked">{{ permiso.name }}</el-checkbox>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>

</template>

<script>

    import {EventBus} from '../../../helpers/bus'

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'roles',
                errors: {},
                form: {},
                permisos: []
            }
        },
        async created() {
            await this.$http.get(`/permisos/records`)
                .then(response => {
                    this.permisos = response.data
                    // this.establishments = response.data.establishments
                })
            await this.initForm()
        },
        methods: {
            onChange_radioSpecial (e) {
              
            },
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    name: null,
                    description: null,
                    slug: null,
                    special: null,
                    permisos: []
                }

                this.permisos.forEach(permiso => {
                    this.form.permisos.push({
                        id: permiso.id,
                        name: permiso.name,
                        description: permiso.description,
                        slug: permiso.slug,
                        checked: false
                    })
                })
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar Rol':'Nuevo Rol'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form.permisos.forEach(permiso => {
                                permiso.checked = false;
                                for (let i = 0; i < response.data.permissions.length; i++) {
                                    if (response.data.permissions[i].slug == permiso.slug) {
                                        response.data.permissions.splice(i, 1);
                                        console.log(response.data.permissions)
                                        permiso.checked = true;
                                        break;
                                    }
                                } 
                                permiso
                            })
                            this.form.id = response.data.id
                            this.form.name = response.data.name
                            this.form.description = response.data.description
                            this.form.slug = response.data.slug
                            if (response.data.special == null) 
                                this.form.special = 'custom'
                            else this.form.special = response.data.special
                            
                            // this.permissions = []
                            // this.form.admin = response.data.data.admin == 1 ? true : false
                        })
                }
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form).then(response => {
                        if (response.data.success) {
                            this.$message.success(this.recordId ? 'Rol Actualizado' : 'Rol creado')
                            this.$eventHub.$emit('reloadData', this.resource)
                            this.close()
                        } else {
                            this.$message.error('Rol no puedo crearse')
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
                this.initForm()
            },
        }
    }
</script>
