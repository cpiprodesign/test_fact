<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Notas de Venta</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body ">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Número</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td>{{ row.number }}</td>
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td class="text-right">
                            <a :href="`/download/salenote/pdf/`+row.id" class="btn waves-effect waves-light btn-xs btn-info">Pdf</a>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>
    </div>
</template>

<script>

    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [deletable],
        components: {DataTable},
        data() {
            return {
                //showDialogVoided: false,
                resource: 'sale-notes',
                recordId: null,
                //showDialogOptions: false
            }
        },
        created() {
        },
        methods: {
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
