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
                            
                            <div class="" v-for="mod in modules">
                                <el-checkbox-group class="row"  v-model="form.target_permisos" >
                                <div class="col-sm-12"><label style="font-size:14px;font-weight: 700;" :title="mod.value">{{ mod.description }}</label></div>
                                <div class="col-4" v-for="permiso in mod.permisos">
                                    <el-checkbox :label="permiso.slug" :title="permiso.name+'|'+permiso.slug" >{{ permiso.description }}</el-checkbox>
                                </div>
                                
                                </el-checkbox-group>
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
                modules: [],
                errors: {},
                form: {},
                // target_permisos: [],
                permisos: []
            }
        },
        async created() {
            await this.$http.get(`/permisos/records`).then(response => {
                this.permisos = response.data
                // this.establishments = response.data.establishments
            })
            await this.$http.get('/roles/tables').then(res => {
                console.log(res.data.modules)
                  res.data.modules.push({
                        name: 'Otros', value: 'other', description: 'Otros', permisos: []
                    })
                  this.permisos.forEach(permiso => {
                    let cath_module = res.data.modules.find(m => permiso.slug.indexOf(`tenant.${m.value}.`) != -1)
                    //pertenece
                    if (cath_module) {
                        let mod = res.data.modules.find(m => m.value == cath_module.value)
                        let per = {
                            id: permiso.id,
                            name: permiso.name,
                            description: permiso.description,
                            slug: permiso.slug,
                            checked: false        
                        }
                        if (mod.permisos) {
                            mod.permisos.push(per)
                        } else {
                            cath_module.permisos = [per]
                        }
                        // res.data.modules.push(cath_module)
                    } else {
                        let mod = res.data.modules.find(m => m.value == 'other')
                        console.log(mod)
                        if (mod) {
                            mod.permisos.push({
                                id: permiso.id,
                                name: permiso.name,
                                description: permiso.description,
                                slug: permiso.slug,
                                checked: false        
                            })
                        }
                    }
                    //agregar a otros

                    
                })
                this.modules = res.data.modules
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
                    permisos: [],
                    target_permisos: [],
                    modules: [{
                        name: 'Otros', value: 'other', description: 'Otros', permisos: []
                    }]
                }
                // this.permisos.forEach(permiso => {
                //     let cath_module = this.modules.find(m => permiso.slug.indexOf(`.${m.value}.`) != -1)
                //     //pertenece
                //     if (cath_module) {
                //         let mod = this.form.modules.find(m => m.value == cath_module.value)
                //         if (mod) {
                //             mod.permisos.push({
                //                 id: permiso.id,
                //                 name: permiso.name,
                //                 description: permiso.description,
                //                 slug: permiso.slug,
                //                 checked: false        
                //             })
                //         } else {
                //             cath_module.permisos = [{
                //                 id: permiso.id,
                //                 name: permiso.name,
                //                 description: permiso.description,
                //                 slug: permiso.slug,
                //                 checked: false        
                //             }]
                //             this.form.modules.push(cath_module)
                //         }
                //     } else {
                //         let mod = this.form.modules.find(m => m.value == 'other')
                //         if (mod) {
                //             mod.permisos.push({
                //                 id: permiso.id,
                //                 name: permiso.name,
                //                 description: permiso.description,
                //                 slug: permiso.slug,
                //                 checked: false        
                //             })
                //         }
                //     }
                //     //agregar a otros

                    
                // })
                // this.modules.forEach(mod => {
                //     mod.permisos = [];
                //     let per = this.permisos.find(p => p.slug.indexOf(`.${mod.value}.`))
                //     if (per != undefined) {
                //         mod.permisos.push({
                //             id: per.id,
                //             name: per.name,
                //             description: per.description,
                //             slug: per.slug,
                //             checked: false
                //         })
                //     } else {
                        
                //     }
                    
                // })
                // this.permisos.forEach(permiso => {
                //     this.form.permisos.push({
                //         id: permiso.id,
                //         name: permiso.name,
                //         description: permiso.description,
                //         slug: permiso.slug,
                //         checked: false
                //     })
                // })
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar Rol':'Nuevo Rol'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            
                            response.data.permissions.forEach(permiso => {
                                this.form.target_permisos.push(permiso.slug)    
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
