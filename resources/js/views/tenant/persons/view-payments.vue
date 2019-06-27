<template>
    <div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Pagos</h3>
            </div>
            <div class="card-body">
                <data-table1 :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Detalle</th>
                        <th>Fecha</th>
                        <th>Cuenta</th>
                        <th>Método de Pago</th>
                        <th>Total</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody">
                        <td>{{ index }}</td>
                        <td>{{ row.number }}</td>
                        <td>{{ row.date_of_issue }}</td>
                        <td>{{ row.account }}</td>
                        <td>{{ row.payment_method }}</td>
                        <td>{{ row.total }}</td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                        </td>
                    </tr>
                </data-table1>
            </div>
        </div>
    </div>
</template>
<script>

    import DataTable1 from '../../../components/DataTable1.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        props: ['id'],
        mixins: [deletable],
        components: {DataTable1},
        data() {
            return {
                showDialog: false,                
                resource: 'persons/customers/view/'+this.id+'/payments',
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
