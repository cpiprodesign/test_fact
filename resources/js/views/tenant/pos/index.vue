<template>
    <div>
        <div class="page-header pr-0">
            <button v-show="hasPermissionTo(['tenant.pos.store', 'tenant.documents.pos'])" class="btn btn-sm btn-primary pull-right mt-2 mr-2 "
                    v-if="!posActive"
                    @click.prevent="showNewRegisterDialog = !showNewRegisterDialog">
                <i class="fas fa-desktop mr-1"></i> Aperturar Caja
            </button>
            <a v-show="hasPermissionTo(['tenant.pos.store', 'tenant.documents.pos'])" v-else href="./pos/register" target="_blank"
               class="btn btn-sm btn-success pull-right mt-2 mr-2 "
            >
                <i class="fas fa-desktop mr-3"></i>
                Abrir Punto de Venta
            </a>
            <h2><a href="/dashboard">
                <i class="fas fa-cash-register"></i>
            </a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Control de Cajas</span></li>
            </ol>
        </div>

        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Cajas</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Ubicación</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th>Saldo</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody">
                        <td>{{ index }}</td>
                        <td>{{ row.establishment.description}}</td>
                        <td>{{ row.user.name }}</td>
                        <td>{{ row.status }}</td>
                        <td>{{ row.created_at }}</td>
                        <td>{{ row.deleted_at }}</td>
                        <td>S/. {{ row.balance }}</td>
                        <td class="text-right">
                            <a v-show="hasPermissionTo(['tenant.pos.report', 'tenant.documents.pos'])" class="btn waves-effect waves-light btn-xs btn-danger" :href="`pos/report/pdf/${row.id}`"
                               title="Descargar Reporte"
                            >
                                <i class="far fa-file-pdf"></i> Reporte
                            </a>
                            <button v-show="hasPermissionTo(['tenant.pos.detalles', 'tenant.documents.pos'])" type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="posDetails(row.id)">Detalles
                            </button>
                            <button v-show="hasPermissionTo(['tenant.pos.destroy', 'tenant.documents.pos'])" type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                    title="Cerrar Caja"
                                    v-if="row.id==posId"
                                    @click.prevent="posClose(row.id)">Cerrar
                            </button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <new-pos-register :show-dialog.sync="showNewRegisterDialog" @OpenPos="OpenPos"></new-pos-register>
            <pos-details :show-dialog.sync="showPosDetailsDialog"
                              :registers.sync="posDetailsData"></pos-details>
        </div>

    </div>
</template>
<script>

    import NewPosRegister from './partials/newRegister'
    import PosDetails from './partials/posDetails'
    import DataTable from '../../../components/DataTable.vue'

    export default {

        components: {
            DataTable,
            PosDetails,
            NewPosRegister
        },
        data() {
            return {

                showDialog: false,
                showNewRegisterDialog: false,
                showPosDetailsDialog: false,
                posDetailsData: {},
                resource: 'pos',
                posActive: false,
                posId: false,
            }
        },
        created() {
            this.init();

        },
        methods: {
            async init() {
                await this.$http.get(`/${this.resource}/tables`)
                    .then(response => {
                        this.posActive = response.data.pos > 0 ? true : false;
                        this.posId = response.data.pos > 0 ? response.data.pos : false;
                    })
            },

            OpenPos(form) {
                this.$http.post(`/${this.resource}`, form)
                    .then(response => {
                        // console.info(response);
                        // this.init()
                        document.location.href = `./${this.resource}`;
                    });

            },
            /**
             * carga las operaciones de el
             *
             * @param id
             * @returns {Promise<void>}
             */
            posDetails(id) {
                this.$http.get(`/${this.resource}/${id}/details`)
                    .then(response => {
                        this.posDetailsData = response.data;
                        this.showPosDetailsDialog = true;
                    })
            },

            posClose() {
                this.$confirm('Desea Realizar el Cierre de Caja?', {
                    confirmButtonText: 'Cerrar',
                    cancelButtonText: 'Cancelar',
                    type: 'danger'
                }).then(() => {
                    this.$http.post(`/${this.resource}/destroy`)
                    this.$message({
                        type: 'success',
                        message: 'Se realizo el cierre correctamente'
                    });
                    document.location.href = `./box`;

                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Cierre Cancelado'
                    });
                });
            }
        }
    }
</script>
