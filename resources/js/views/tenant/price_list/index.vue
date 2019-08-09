<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Lista de Precios</span></li>
            </ol>
            <div v-show="hasPermissionTo('tenant.price-list.store')" class="right-wrapper pull-right">
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Precios</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Principal</th>
                        <th>Tipo</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody">
                        <td>{{ index }}</td>
                        <td>{{ row.name }}</td>
                        <td class="text-right">{{ (row.principal) ? "Si" : "No" }}</td>
                        <td class="text-right">{{ (row.type == 1) ? "Porcentaje ("+row.value+"%)" : "Valor" }}</td>
                        <td class="text-right">
                            <button v-show="hasPermissionTo('tenant.price-list.update')" type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                            <button v-show="hasPermissionTo('tenant.price-list.destroy')" type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <items-form :showDialog.sync="showDialog"
                        :recordId="recordId"></items-form>
        </div>
    </div>
</template>
<script>

    import ItemsForm from './form.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [deletable],
        components: {ItemsForm, DataTable},
        data() {
            return {
                showDialog: false,                
                resource: 'price-list',
                recordId: null,
            }
        },
        created() {
        },
        methods: {
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
