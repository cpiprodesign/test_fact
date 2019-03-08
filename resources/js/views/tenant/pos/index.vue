<template>
    <div>
        <div class="page-header pr-0">
            <button class="btn btn-sm btn-primary pull-right mt-2 mr-2 " @click.prevent="getPos()">
                <i class="fas fa-desktop mr-3"></i>
                <span v-if="activePos"> Abrir Punto de Venta </span>
                <span v-else> Nuevo Punto de Venta </span>

            </button>
            <h2><a href="/dashboard">
                <i class="fas fa-cash-register"></i>
            </a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Puntos de Venta</span></li>
            </ol>
        </div>

        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Puntos de Ventas</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>IP</th>
                        <th>Ubicación</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.ip }}</td>
                        <td>{{ row.establishment.description}}</td>
                        <td>{{ row.user.name }}</td>
                        <td>{{ row.status }}</td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickCreate(row.id)">Editar
                            </button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                    @click.prevent="clickDelete(row.id)">Eliminar
                            </button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <items-form :showDialog.sync="showDialog"
                        :recordId="recordId"></items-form>

            <items-import :showDialog.sync="showImportDialog"></items-import>
        </div>

    </div>
</template>
<script>

    // import ItemsForm from './form.vue'
    //import ItemsImport from './import.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {

        // mixins: [deletable],
        components: {
            // ItemsForm,
            // ItemsImport,
            DataTable
        },
        data() {
            return {
                // showDialog: false,
                //showImportDialog: false,
                resource: 'pos',
                recordId: null,
                activePos: false,
            }
        },
        created() {
        },
        methods: {
            getPos() {
                this.activePos = true;
            }
        },
        beforeCreate() {
            getPost()
        }
    }
</script>
