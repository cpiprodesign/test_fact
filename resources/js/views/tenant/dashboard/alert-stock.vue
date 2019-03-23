<template>
    <div class="card">
        <div class="card-header">
           Productos por agotarse
        </div>
        <div class="card-body">
           <table class="table table-sm">
                <thead>
                    <tr>
                        <td>Producto</td>
                        <td>Cantidad</td>
                        <td>Stock Mínimo</td>
                        <td>Estado</td>
                    </tr>
                </thead>
                <tbody v-for="(row,index) in items" :key="row.id">
                    <tr>
                        <td>{{ row.description }}</td>
                        <td>{{ row.quantity }}</td>
                        <td>{{ row.stock_min }}</td>
                        <td>
                            <span class="badge bg-secondary text-white bg-danger" v-if="row.difference < 1">Agotado</span>
                            <span class="badge bg-secondary text-white bg-warning" v-if="row.difference > 0">Pocas unidades</span> 
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>    
    import {functions, exchangeRate} from '../../../mixins/functions'   

    export default {
        mixins: [functions, exchangeRate],
        data() {
            return {
                resource: 'dashboard',
                loaded: false,               
                items: [],                
                establishments: [],
                establishment_id: 1
            }
        },
        async mounted() {
            this.loaded = false
            await this.$http.get(`/${this.resource}/alert_stock/${this.establishment_id}`)
                .then(response => {
                    this.items = response.data.items
                })
            this.loaded = true
        },
        methods: {
                        
        }
    }
</script>
