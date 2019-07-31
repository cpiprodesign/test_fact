<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Productos</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button v-show="hasPermissionTo('tenant.items.import')" type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button>
                <button v-show="hasPermissionTo('tenant.items.store')" type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de productos</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Código Interno</th>
                        <th>Unidad</th>
                        <th>Descripción</th>
                        <th>Código SUNAT</th>
                        <th>Stock</th>
                        <th class="text-right">P.Unitario (Venta)</th>
                        <th>IGV Incluido</th>                        
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody">
                        <td>{{ index }}</td>
                        <td>{{ row.internal_id }}</td>
                        <td>{{ row.unit_type_id }}</td>
                        <td>{{ row.description }}</td>
                        <td>{{ row.item_code }}</td>
                        <td><button class="btn btn-custom btn-sm" @click.prevent="clickStock(row.id)"><i class="fa fa-search"></i> </button></td>
                        <td class="text-right">{{ row.sale_unit_price }}</td>
                        <td class="text-right">{{ (row.included_igv) ? "SI" : "NO" }}</td>
                        <td class="text-right">
                            <button v-show="hasPermissionTo('tenant.items.update')" type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                            <button v-show="hasPermissionTo('tenant.items.destroy')" type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <items-form :showDialog.sync="showDialog"
                        :recordId="recordId"></items-form>

            <items-import :showDialog.sync="showImportDialog"></items-import>

            <items-stock :showStockDialog.sync="showStockDialog"
                        :recordId="recordId"></items-stock>
        </div>
    </div>
</template>
<script>

    import ItemsForm from './form.vue'
    import ItemsImport from './import.vue'
    import ItemsStock from './stock.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [deletable],
        components: {ItemsForm, ItemsImport, ItemsStock, DataTable},
        data() {
            return {
                showDialog: false,
                showImportDialog: false,
                showStockDialog: false,
                resource: 'items',
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
            clickStock(recordId = null) {
                this.recordId = recordId
                this.showStockDialog = true
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
