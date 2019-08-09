<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <!--<button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>-->
            </div>
        </div>
        <div class="row">
          <div class="col-md-6">
              <div class="card mb-0">
                  <div class="card-header bg-info">
                      <h3 class="my-0">{{ title_roles }}</h3>
                  </div>
                  <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre</th>
                          <!-- <th>Descripción</th> -->
                          <th>Especial</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(role, index) in list_roles">
                          <td scope="row">{{ index + 1}}</td>
                          <td><span :title="role.description">{{ role.name }}</span></td>
                          <!-- <td>{{ role.description }}</td> -->
                          <td><span class="label" :class="{'label-success': role.special == 'all-access', 'label-danger': role.special == 'no-access', 'label-info': role.special == null}"> {{ role.special == null ? 'personalizado' : role.special }}</span></td>
                          <td class="text-right">
                            <!-- <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="onClick_btVerPermisos(role.id)">Ver Permisos</button> -->
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="onClick_btnCreateRole(role.id)">Editar</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="onClick_btnEliminar(role.id, resource_roles)">Eliminar</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="row">
                      <div class="col">
                          <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="onClick_btnCreateRole()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                      </div>
                    </div>

                  </div>
              </div>
          </div>
          <div class="col-md-6" v-show="false">
              <div class="card mb-0">
                  <div class="card-header bg-info">
                      <h3 class="my-0">{{ !showPermisosRole ? title_permisos : title_permisos + '-' + titleNameRole}}</h3>
                  </div>
                  <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre</th>
                          <th>Descripción</th>
                          <!-- <th>Acciones</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(permiso, index) in list_permisos">
                          <td scope="row">{{ index + 1}}</td>
                          <td><span :title="permiso.slug">{{ permiso.name }}</span></td>
                          <td>{{ permiso.description }}</td>
                          <!-- <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="onClick_btnCreatePermiso(permiso.id)">Editar</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="onClick_btnEliminar(permiso.id, resource_permisos)">Eliminar</button>
                          </td> -->
                        </tr>
                      </tbody>
                    </table>
                    <!-- <div class="row">
                      <div class="col">
                          <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="onClick_btnCreatePermiso()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                      </div>
                    </div> -->
                  </div>
              </div>
          </div>
        </div>
        <role-form :showDialog.sync="showDialogRole" :recordId="recordIdRole"></role-form>
        <permiso-form :showDialog.sync="showDialogPermiso" :recordId="recordIdPermiso"></permiso-form>
    </div>
</template>

<script>
    import PermisoForm from './permiso_form.vue'
    import RoleForm from './role_form.vue'
    import {deletable} from '../../../mixins/deletable'
    import DataTable from '../../../components/DataTable.vue'
    export default {
        mixins: [deletable],
        components: {DataTable, PermisoForm, RoleForm},
        data() {
            return {
                title: 'Roles y permisos',
                title_roles: 'Roles',
                title_permisos: 'Permisos',
                resource_permisos: 'permisos',
                resource_roles: 'roles',
                list_roles: [],
                list_permisos: [],
                showDialogRole: false,
                recordIdRole: null,
                showDialogPermiso: false,
                recordIdPermiso: null,
                showPermisosRole: false,
                titleNameRole: ''
            }
        },
        created () {
          this.$eventHub.$on('reloadData', (resource = 'all') => {
              this.getData(resource)
          })
          this.getData();
        },
        methods: {
          getData(resource = 'all') {
              if (resource == this.resource_roles || resource == 'all') {
                this.$http.get('/roles/datatable').then(res => {
                  this.list_roles = res.data
                })
              }

              if (resource == this.resource_permisos || resource == 'all') {
                this.$http.get('/permisos/datatable').then(res => {
                  this.list_permisos = res.data
                })
              }
          },
          onClick_btVerPermisos (item_id, resource) {
            this.$http.get(`/${this.resource_roles}/record/${item_id}`).then(res => {
              this.list_permisos = res.data.permissions
            })
          },
          onClick_btnEliminar (item_id, resource) {
            this.destroy(`/${resource}/${item_id}`).then(() =>this.getData(resource))
          },
          onClick_btnCreateRole (recordId = null) {
            this.recordIdRole = recordId
            this.showDialogRole = true
          },
          onClick_btnCreatePermiso (recordId = null) {
            this.recordIdPermiso = recordId
            this.showDialogPermiso = true
          }
        }
    }
</script>