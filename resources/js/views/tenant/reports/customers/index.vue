<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Reportes: Ventas por Clientes</span></li>
            </ol>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Clientes</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Número</th>
                        <th class="text-center">N° Ventas</th>
                        <th>Total Venta</th>
                        <th>Total Pagado</th>
                        <th>Balance</th>
                        <th class="text-center">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody" :class="{'text-warning': (row.balance > 0)}">
                        <td>{{ index }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.number }}</td>
                        <td class="text-center">{{ row.quantity }}</td>
                        <td>{{ row.total }}</td>
                        <td>{{ row.total_paid }}</td>
                        <td>{{ row.balance}}</td>
                        <td class="text-center">
                            <a :href="`/persons/customers/view/${row.id}`" class="btn waves-effect waves-light btn-xs btn-info m-1__2">Ver Informe</a>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>
    </div>
</template>
<script>

    import DataTable from '../../../../components/DataTable.vue'

    export default {
        components: {DataTable},
        data() {
            return {
                showDialog: false,                
                resource: 'reports-customers',
                recordId: null,
            }
        },
        created() {
        },
        methods: {
            
        }
    }
</script>
