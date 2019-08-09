<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button v-show="hasPermissionTo(['tenant.suppliers.import', 'tenant.customers.import'])" type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button>
                <button v-show="hasPermissionTo(['tenant.suppliers.store', 'tenant.customers.store'])" type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de {{ title }}</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource+`/${this.type}`">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Nombre</th>
                        <th class="text-right">Número</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody">
                        <td>{{ index }}</td>
                        <td>{{ row.name }}</td>
                        <td class="text-right">{{ row.number }}</td>
                        <td class="text-right">
                            <a v-show="hasPermissionTo(['tenant.customers.detalle'])" :href="`/persons/customers/view/${row.id}`" class="btn waves-effect waves-light btn-xs btn-primary m-1__2">Detalle</a>
                            <button v-show="hasPermissionTo(['tenant.suppliers.update', 'tenant.customers.update'])" type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                            <button v-show="hasPermissionTo(['tenant.suppliers.destroy', 'tenant.customers.destroy'])" type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <persons-form :showDialog.sync="showDialog"
                          :type="type"
                          :recordId="recordId"></persons-form>

            <persons-import :showDialog.sync="showImportDialog"
                            :type="type"></persons-import>
        </div>
    </div>
</template>

<script>

    import PersonsForm from './form.vue'
    import PersonsImport from './import.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [deletable],
        props: ['type'],
        components: {PersonsForm, PersonsImport, DataTable},
        data() {
            return {
                title: null,
                showDialog: false,
                showImportDialog: false,
                resource: 'persons',
                recordId: null,
            }
        },
        created() {
            this.title = (this.type === 'customers')?'Clientes':'Proveedores'
        },
        methods: {
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickImport() {
                this.showImportDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
